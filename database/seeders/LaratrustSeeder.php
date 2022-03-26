<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions_json = [];
        $this->truncateLaratrustTables();

        $config = Config::get('laratrust_seeder.roles_structure');

        if ($config === null) {
            $this->command->error("The configuration has not been published. Did you run `php artisan vendor:publish --tag=\"laratrust-seeder\"`");
            $this->command->line('');
            return false;
        }

        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        $models = Config::get('roles_models.models');


        foreach ($models as $module => $value) {

            foreach (explode(',', $value) as $p => $perm) {

                $permissionValue = $mapPermission->get($perm);

                $permission = Permission::firstOrCreate([
                    'name' => $module . '_' . $permissionValue,
                    'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                ]);
                $permissions[] = $permission->id;
                $permissions_json[] = $permission->name;
            }
        }

        $user_super_admin = User::create([
            'company_id' => 1,
            'code' => 1,
            'first_name' => 'مدلول',
            'last_name' => 'الشمري',
            'email' => 'mdloul@gmail.com',
            'role' => 'super_admin',
            'password' => bcrypt('123456'),
            'password_visible' => '123456',
            'permissions_json' => json_encode($permissions_json),
        ]);
        $user_super_admin->attachPermissions($permissions);

        /*$user_admin = User::create([
            'company_id' => 1,
            'code' => 2,
            'first_name' => 'المشرف',
            'last_name' => '',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('123456'),
            'password_visible' => '123456',
            'permissions_json' => json_encode($permissions_json),
        ]);
        $user_admin->attachPermissions($permissions);
    */




        /*
        foreach ($config as $key => $modules) {

            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $p => $perm) {

                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = Permission::firstOrCreate([
                        'name' => $module . '_' . $permissionValue,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ])->id;
                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                }
            }

            if (Config::get('laratrust_seeder.create_users')) {

                $this->command->info("Creating '{$key}' user");

                $user = \App\Models\User::create([
                    'code' => 1,
                    'first_name' => 'مدلول',
                    'last_name' => 'الشمري',
                    'email' => 'mdloul@gmail.com',
                    'password' => bcrypt('123456'),
                    'password_visible' => '123456',
                    'permissions_json' => '["create_users", "read_users", "update_users", "delete_users", "create_companies", "read_companies", "update_companies", "delete_companies", "create_customers", "read_customers", "update_customers", "delete_customers"]',
                ]);
                //$user->attachRole($role);
                $user->attachPermissions($permissions);

            }

        }
        */
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return  void
     */
    public function truncateLaratrustTables()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        Schema::disableForeignKeyConstraints();

        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();

        if (Config::get('laratrust_seeder.truncate_tables')) {
            DB::table('roles')->truncate();
            DB::table('permissions')->truncate();

            if (Config::get('laratrust_seeder.create_users')) {
                $usersTable = (new User)->getTable();
                DB::table($usersTable)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
