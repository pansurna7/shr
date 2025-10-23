<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $role=  Role::create(['name' => 'Super Admin','guard_name' => 'web',]);
      $permissionAll = [
            [
                'group_name' =>'Roles',
                'permissions' => [
                    'role.permission',
                    'role.list',
                    'role.create',
                    'role.edit',
                    'role.delete',
                    'role.export',
                    'role.import',
                ]
            ],
            [
                'group_name' =>'Category',
                'permissions' => [
                    'category.permission',
                    'category.list',
                    'category.create',
                    'category.edit',
                    'category.delete',
                    'category.export',
                    'category.import',
                ]
            ],
            [
                'group_name' =>'Product',
                'permissions' => [
                    'product.permission',
                    'product.list',
                    'product.create',
                    'product.edit',
                    'product.delete',
                    'product.export',
                    'product.import',
                ]
            ],
            [
                'group_name' =>'Customer',
                'permissions' => [
                    'customer.permission',
                    'customer.list',
                    'customer.create',
                    'customer.edit',
                    'customer.delete',
                    'customer.export',
                    'customer.import',
                    'customer.approval',
                ]
            ]
        ];

        foreach($permissionAll as $permGroup){
            $permissionGroup = $permGroup['group_name'];
            foreach($permGroup['permissions'] as $permissionName){
              $permission =   Permission::create([
                    'name' => $permissionName,
                    'group_name' => $permissionGroup,
                    'guard_name' => 'web'
                ]);

                $role->givePermissionTo($permission);
                $permission->assignRole($role);
            }
        }

        $user = User::find(1);
        if($user){
            $user->assignRole($role);
        }
    }
}
