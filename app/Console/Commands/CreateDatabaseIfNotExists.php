<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateDatabaseIfNotExists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:prepare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database if not exists, then run migrations';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $database = env('DB_DATABASE');
        config(['database.connections.mysql.database' => null]);
        DB::purge('mysql');
        DB::reconnect('mysql');
        DB::statement("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $this->info("Database `$database` created or existed.");
        config(['database.connections.mysql.database' => $database]);
        DB::purge('mysql');
        DB::reconnect('mysql');
        $this->info("Reconnected to database `$database`. Running migrations...");
        $this->call('migrate', ['--seed' => true]);
        return CommandAlias::SUCCESS;
    }
}
