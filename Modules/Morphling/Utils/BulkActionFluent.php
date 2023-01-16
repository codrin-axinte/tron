<?php

namespace Modules\Morphling\Utils;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Notifications\NovaNotification;

class BulkActionFluent
{
    const DEFAULT_STRATEGY = 0;

    const BAIL_STRATEGY = 1;

    const TRANSACTION_STRATEGY = 2;

    protected string $resourceName = 'resources';

    protected string $actionName = 'updated';

    protected int $strategy = self::DEFAULT_STRATEGY;

    private ?Closure $successCallback = null;

    protected ?Closure $failCallback = null;

    private ?Closure $actionCallback = null;

    private bool $withNotifications = true;

    /**
     * Perform the action on the given models.
     */
    public function run(Collection $models, Closure $action): bool
    {
        $this->actionCallback = $action;

        $report = $this->initReport();
        $report->total = $models->count();

        switch ($this->strategy) {
            default:
            case self::DEFAULT_STRATEGY:
                $success = $this->useDefault($models, $report);
                break;
            case self::BAIL_STRATEGY:
                $success = $this->useBail($models, $report);
                break;
            case self::TRANSACTION_STRATEGY:
                $success = $this->useTransaction($models, $report);
                break;
        }

        if ($success) {
            $this->runSuccess($report);
        } else {
            $this->runFail($report);
        }

        return $success;
    }

    public function disableNotifications(): static
    {
        $this->withNotifications = false;

        return $this;
    }

    private function initReport(): \stdClass
    {
        $report = new \stdClass();
        $report->total = 0;
        $report->affected = 0;
        $report->errored = 0;

        return $report;
    }

    private function useDefault(Collection $models, object $report): bool
    {
        foreach ($models as $model) {
            try {
                $this->executeAction($model);
                $report->affected++;
            } catch (\Exception $exception) {
                $report->errored++;
                $this->logError($exception);
            }
        }

        return true;
    }

    private function useBail(Collection $models, object $report): bool
    {
        try {
            foreach ($models as $model) {
                $this->executeAction($model);
                $report->affected++;
            }

            return true;
        } catch (\Exception $exception) {
            $this->logError($exception);

            return false;
        }
    }

    private function executeAction($model): void
    {
        call_user_func_array($this->actionCallback, [$model]);
    }

    /**
     * @param  int  $strategy
     * @return BulkActionFluent
     */
    public function setStrategy(int $strategy): BulkActionFluent
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function setResourceName(string $plural): static
    {
        $this->resourceName = $plural;

        return $this;
    }

    public function setActionName(string $action): static
    {
        $this->actionName = $action;

        return $this;
    }

    private function useTransaction(Collection $models, object $report): bool
    {
        return DB::transaction(function () use ($models, $report) {
            foreach ($models as $model) {
                $this->executeAction($model);
                $report->affected++;
            }
        });
    }

    private function logError(\Exception $exception): void
    {
        Log::error($exception->getMessage(), [
            'stacktrace' => $exception->getTraceAsString(),
        ]);
    }

    private function runSuccess($report): void
    {
        if ($this->successCallback) {
            call_user_func_array($this->successCallback, [$report]);

            return;
        }

        $message = "{$report->affected} {$this->resourceName} {$this->actionName} out of {$report->total}";

        $this->notify($message, $report->errored > 0 ? NovaNotification::WARNING_TYPE : NovaNotification::SUCCESS_TYPE);
    }

    private function runFail($report): void
    {
        if ($this->failCallback) {
            call_user_func_array($this->failCallback, [$report]);

            return;
        }

        $this->notify(__('Something went wrong.'), NovaNotification::ERROR_TYPE);
    }

    private function notify($message, $type): void
    {
        // FIXME: Not the best approach.
        if ($this->withNotifications) {
            auth()
                ->user()
                ->notify(NovaNotification::make()
                    ->message($message)
                    ->type($type)
                );
        }
    }

    /**
     * @param  Closure  $successCallback
     * @return BulkActionFluent
     */
    public function onSuccess(Closure $successCallback): BulkActionFluent
    {
        $this->successCallback = $successCallback;

        return $this;
    }

    /**
     * @param  Closure  $failCallback
     * @return BulkActionFluent
     */
    public function onFail(Closure $failCallback): BulkActionFluent
    {
        $this->failCallback = $failCallback;

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceName(): string
    {
        return $this->resourceName;
    }
}
