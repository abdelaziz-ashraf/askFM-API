<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;

class SeedLargeDataFactoryHelper {
    public static function seedLargeData($model, $totalRecords, $chunkSize) {
        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            $model::factory()->count($chunkSize)->create();
        }
    }
}
