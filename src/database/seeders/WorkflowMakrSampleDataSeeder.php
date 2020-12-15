<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use AlvariumDigital\WorkflowMakr\Models\Action;
use AlvariumDigital\WorkflowMakr\Models\Status;
use AlvariumDigital\WorkflowMakr\Models\Scenario;
use AlvariumDigital\WorkflowMakr\Models\Transition;

class WorkflowMakrSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Actions
        Action::insert([
            ['id' => 1, 'code' => 'CREATE', 'designation' => 'Create', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'code' => 'UPDATE', 'designation' => 'Update', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'code' => 'SUBMIT', 'designation' => 'Submit', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'code' => 'RECALL', 'designation' => 'Recall', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'code' => 'REJECT', 'designation' => 'Reject', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'code' => 'VALIDATE', 'designation' => 'Validate', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Statuses
        Status::insert([
            ['id' => 1, 'code' => 'CREATED', 'designation' => 'Created', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'code' => 'UPDATED', 'designation' => 'Updated', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'code' => 'SUBMITTED', 'designation' => 'Submitted', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'code' => 'RECALLED', 'designation' => 'Recalled', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'code' => 'REJECTED', 'designation' => 'Rejected', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'code' => 'VALIDATED', 'designation' => 'Validated', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Scenarios
        Scenario::insert([
            ['id' => 1, 'code' => 'SAMPLE_SCENARIO', 'designation' => 'Sample scenario', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);

        // Transitions
        Transition::insert([
            ['id' => 1, 'old_status_id' => null, 'new_status_id' => 1, 'scenario_id' => 1, 'action_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'old_status_id' => 1, 'new_status_id' => 2, 'scenario_id' => 1, 'action_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'old_status_id' => 1, 'new_status_id' => 3, 'scenario_id' => 1, 'action_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'old_status_id' => 2, 'new_status_id' => 3, 'scenario_id' => 1, 'action_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'old_status_id' => 3, 'new_status_id' => 4, 'scenario_id' => 1, 'action_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'old_status_id' => 3, 'new_status_id' => 5, 'scenario_id' => 1, 'action_id' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'old_status_id' => 3, 'new_status_id' => 6, 'scenario_id' => 1, 'action_id' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 8, 'old_status_id' => 4, 'new_status_id' => 2, 'scenario_id' => 1, 'action_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 9, 'old_status_id' => 4, 'new_status_id' => 3, 'scenario_id' => 1, 'action_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 10, 'old_status_id' => 5, 'new_status_id' => 2, 'scenario_id' => 1, 'action_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 11, 'old_status_id' => 5, 'new_status_id' => 3, 'scenario_id' => 1, 'action_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
    }
}
