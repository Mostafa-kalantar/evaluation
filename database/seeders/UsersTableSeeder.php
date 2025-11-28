<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode(file_get_contents(resource_path('json/database_seeds/users.json')), true);
        $roles = json_decode(file_get_contents(resource_path('json/database_seeds/roles.json')), true);
        $role_users = json_decode(file_get_contents(resource_path('json/database_seeds/role_user.json')), true);
        DB::table('users')->upsert($rows, ['id']);
        DB::table('roles')->upsert($roles, ['id']);
        DB::table('role_user')->upsert($role_users, ['user_id']);
    }
}
