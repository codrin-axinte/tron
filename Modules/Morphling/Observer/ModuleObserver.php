<?php

namespace Modules\Morphling\Observer;

use Modules\Morphling\Models\Module as ModuleEntity;
use Nwidart\Modules\Facades\Module;

class ModuleObserver
{
    public function created()
    {
        // TODO: Fire event that module created.
    }

    public function updated(ModuleEntity $entity)
    {
        // TODO: Fire event that module updated.
        if (! $entity->isDirty(['enabled'])) {
            return;
        }

        // TODO: Refactor this to a job to improve performance.
        if ($entity->enabled) {
            Module::enable($entity->name);
        } else {
            Module::disable($entity->name);
        }
    }

    public function deleting(ModuleEntity $entity)
    {
        // TODO: Fire event that module deleted.
        Module::delete($entity->name);
    }
}
