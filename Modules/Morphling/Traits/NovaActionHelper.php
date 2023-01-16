<?php

namespace Modules\Morphling\Traits;

use Modules\Morphling\Utils\BulkActionFluent;

trait NovaActionHelper
{
    public function bulkAction(): BulkActionFluent
    {
        return app(BulkActionFluent::class);
    }
}
