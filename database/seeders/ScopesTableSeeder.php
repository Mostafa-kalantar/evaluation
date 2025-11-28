<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScopesTableSeeder extends Seeder
{
    public function run(): void {
        $rows = json_decode(file_get_contents(resource_path('json/database_seeds/scopes.json')), true);
        DB::table('scopes')->upsert($rows, ['id']);
    }
}
