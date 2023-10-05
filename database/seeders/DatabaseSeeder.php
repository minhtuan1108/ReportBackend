<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                UserRoleSeeder::class,
                UserReportSeeder::class,
                ReportAssignmentSeeder::class
            ]
        );
        $this->defaultAccount();
    }

    protected function defaultAccount()
    {
        $user = User::factory()->create([
           'student_code' => '312',
            'name' => 'Trang Thanh Phúc',
            'username' => 'phuc',
            'password' => Hash::make('312')
        ]);
        $user->roles()->attach(Role::find(RoleEnum::USER));
    }
}
