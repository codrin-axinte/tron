<?php

namespace App\Actions\Tron;

use App\Exceptions\TronServiceUnavailable;
use App\Http\Integrations\Tron\Requests\HeartbeatRequest;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Throwable;

class GetStatusAction
{
    private const CACHE_KEY = 'wallet-status';

    private int $ttlInMinutes = 5;

    public function __invoke(): array
    {
        return Cache::remember(
            self::CACHE_KEY,
            now()->addMinutes($this->ttlInMinutes),
            fn() => $this->getStatus()
        );
    }


    private function getStatus(): array
    {
        $request = new HeartbeatRequest();

        try {
            $response = $request->send();

            if ($response->status() === 200) {
                return $this->success();
            }

            return $this->fail('Service is not available', $response->json());
        } catch (Throwable $exception) {
            return $this->fail('Something went wrong.', $exception->getMessage());
        }
    }

    private function fail(string $message, string $context): array
    {
        // TODO: Should notify admins

        return [
            'status' => false,
            'message' => $message,
            'context' => $context
        ];
    }

    private function success(): array
    {
        return [
            'status' => true,
            'message' => 'Service is up and running.'
        ];
    }
}
