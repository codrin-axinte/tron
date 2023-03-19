<?php

namespace Modules\Morphling\Traits;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;

trait InteractsWithCSV
{
    protected function mapFromCSV($module, ToModel $modelImporter, string $csvFile): static
    {
        $filePath = module_path($module, $csvFile);

        Excel::import($modelImporter, $filePath);

        return $this;
    }
}
