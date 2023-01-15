<?php

namespace App\Updater;

use Illuminate\Console\Command;

abstract class UpdateAction
{
    protected ?Updater $updater;

    protected ?Command $command;

    public function setUpdater(Updater $updater): static
    {
        $this->updater = $updater;

        return $this;
    }

    public function setCommand(Command $command): static
    {
        $this->command = $command;

        return $this;
    }

    protected function comment(string $comment): void
    {
        if ($this->command) {
            $this->command->comment($comment);
        }
    }

    abstract public function update(): bool;
}
