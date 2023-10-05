<?php

namespace Database\Seeders;

use App\Enums\ReportStatus;
use App\Enums\RoleEnum;
use App\Models\Assignment;
use App\Models\Feedback;
use App\Models\Media;
use App\Models\Report;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAssignment();
    }

    private function createAssignment(){
        $manager = Role::find(RoleEnum::MANAGER)->users()->get()[0];
        $workers = Role::find(RoleEnum::WORKER)->users()->get();
        $arrStatus = [ReportStatus::COMPLETE, ReportStatus::SENT, ReportStatus::PROCESS, ReportStatus::IGNORE];
        $reports = Report::all();
        foreach ($reports as $report){
            $worker = $workers->random();
            $status = $arrStatus[array_rand($arrStatus)];
            Assignment::factory()->create([
                'worker_id' => $worker->id,
                'manager_id' => $manager->id,
                'reports_id' => $report->id
            ]);
            $report->status = $status;
            $report->save();
            if (strcmp($status, ReportStatus::COMPLETE) == 0){
                $feedback = Feedback::factory()->create([
                    'reports_id' => $report->id,
                    'users_id' => $worker->id,
                ]);
                $mediaa = Media::factory(rand(1, 6))->create();
                $feedback->medias()->attach($mediaa);
            }
            else if (strcmp($status, ReportStatus::IGNORE) == 0){
                // Feedback từ admin
                $feedback = Feedback::factory()->create([
                    'reports_id' => $report->id,
                    'users_id' => $manager->id,
                ]);
            }
        }
    }
}
