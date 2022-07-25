<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // user Permissions
       Permission::create([
           'name' => 'view users'
       ]);
       Permission::create([
           'name' => 'create user'
       ]);
       Permission::create([
           'name' => 'edit user'
       ]);
       Permission::create([
           'name' => 'delete user'
       ]);

        // Role Permissions
        Permission::create([
           'name' => 'view roles'
        ]);
        Permission::create([
           'name' => 'create role'
        ]);
        Permission::create([
           'name' => 'edit role'
        ]);
        Permission::create([
           'name' => 'delete role'
        ]);


        // Category Permissions
       Permission::create([
            'name' => 'view categories'
        ]);
       Permission::create([
            'name' => 'create category'
       ]);
       Permission::create([
            'name' => 'edit category'
       ]);
       Permission::create([
            'name' => 'delete category'
       ]);

        // post Permissions
       Permission::create([
            'name' => 'view posts'
        ]);
       Permission::create([
            'name' => 'create post'
       ]);
       Permission::create([
            'name' => 'published post'
       ]);
       Permission::create([
            'name' => 'edit post'
       ]);
       Permission::create([
            'name' => "edit others post"
       ]);
       Permission::create([
            'name' => 'delete post'
       ]);
       Permission::create([
            'name' => 'delete published post'
       ]);
       Permission::create([
            'name' => "delete others post"
       ]);
    }
}
