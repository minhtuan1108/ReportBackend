<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = $this->createRoles();
        $users = $this->createUsers($roles);
    }

    public function createUsers($roles){
        $users = User::factory(10)->create([

            ]
        );
        foreach ($users as $user){
            $user->roles()->attach($roles[array_rand($roles)]);
        }
        return $users;
    }

    public function createRoles(){
        $user = Role::create([
                'id' => RoleEnum::USER,
                'name' => 'user',
                'description' => 'Người dùng (sinh viên, giảng viên) có thể báo cáo các trang thiết bị hỏng hóc'
            ]
        );

        $worker = Role::create([
                'id' => RoleEnum::WORKER,
                'name' => 'worker',
                'description' => 'Người thực hiện sửa chữa các trang thiết bị hỏng hóc'
            ]
        );

        $manager = Role::create([
                'id' => RoleEnum::MANAGER,
                'name' => 'manager',
                'description' => 'Người thực hiện quản lý, điều phối các công việc từ người dùng cho nhân viên sửa chữa'
            ]
        );

        return compact("user", "worker", "manager");
    }
}
