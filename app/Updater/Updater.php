<?php

namespace App\Updater;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class Updater
{
    protected ?Command $command = null;

    public function __construct(protected readonly string $latestVersion = '0.0.0', protected array $updates = [])
    {
    }

    public function withCommand(Command $command): static
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @throws FileNotFoundException
     * @throws \Throwable
     */
    public function update(): int
    {
        if (! $this->shouldUpdate()) {
            $this->comment('Application is up to date.');

            return true;
        }

        if ($this->runUpdates()) {
            $this->comment('App updated to version '.$this->getVersion());

            return true;
        }

        $this->comment('Something went wrong during the update. Version: '.$this->getVersion());

        return false;
    }

    /**
     * Safely print out comments.
     */
    protected function comment(string $command): void
    {
        if ($this->command) {
            $this->command->comment($command);
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function runUpdates(): bool
    {
        $currentVersion = $this->getVersion();

        foreach ($this->updates as $edition => $action) {
            if (version_compare($currentVersion, $edition, '<')) {
                $updateAction = app($action)
                    ->setUpdater($this)
                    ->setCommand($this->command);

                if ($updateAction->update()) {
                    $this->writeVersion($edition);
                    $currentVersion = $edition;
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @throws FileNotFoundException
     */
    public function shouldUpdate(): bool
    {
        if (empty($this->updates)) {
            return false;
        }

        $version = $this->getVersion();

        if ($this->hasVersion() && version_compare($version, $this->latestVersion, '>=')) {
            return false;
        }

        foreach ($this->updates as $edition => $action) {
            if (version_compare($version, $edition, '<')) {
                return true;
            }
        }

        return false;
    }

    public function latestVersion(): string
    {
        return $this->latestVersion;
    }

    protected function writeVersion(string $version): bool|int
    {
        return \File::put($this->getFilePath(), $version);
    }

    protected function hasVersion(): bool
    {
        return \File::exists($this->getFilePath());
    }

    /**
     * @throws FileNotFoundException
     */
    public function getVersion(): string
    {
        return trim(\File::get($this->getFilePath())) ?? $this->latestVersion;
    }

    protected function getFilePath(): string
    {
        return storage_path('app/version');
    }
}
