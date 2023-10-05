<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Media;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createReport();
    }

    private function createReport(int $eachUserPostMin=1, int $eachUserPostMax=6){
        // Lấy các user có role là người dùng
        $users = Role::find(RoleEnum::USER)->users()->get();
        foreach ($users as $user) {
            $reports = Report::factory(rand($eachUserPostMin, $eachUserPostMax))->create([
                'users_id' => $user->id
            ]);

            $numOfImages = rand(1, 6);
            foreach ($reports as $report) {
                $mediaa = Media::factory($numOfImages)->create();
                $report->medias()->attach($mediaa);
            }
        }
    }
}
