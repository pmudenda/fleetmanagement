<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('WFL_WORKFLOW_PROCESSES')->insert([
            'process_code' => '2000',
            'name' => 'Fuel Requisition',
            'description' => 'Approval flow for fuel requisition',
            'created_by' => 5900,
            'modified_by' => 5900
        ]);

        DB::table('WFL_WORKFLOW_STEP')->insert([
            'process_id' => '2000',
            'step_id' => '01',
            'name' => 'submission',
            'is_initial_step' => 1,
            'is_final_step' => 0,
            'next_step' => '02',
            'action_page' => 'task.show',
            'created_by' => 5900,
            'privilege' => 'create_fuel_requisition'
        ]);

        DB::table('WFL_WORKFLOW_STEP')->insert([
            'process_id' => '2000',
            'step_id' => '02',
            'name' => 'review',
            'is_initial_step' => 0,
            'is_final_step' => 0,
            'previous_step' => '02',
            'next_step' => '03',
            'action_page' => 'task.review',
            'created_by' => 5900,
            'privilege' => 'approve_fuel_requisition'
        ]);

        DB::table('WFL_WORKFLOW_STEP')->insert([
            'process_id' => '2000',
            'step_id' => '03',
            'name' => 'approve',
            'is_initial_step' => 0,
            'is_final_step' => 1,
            'previous_step' => '02',
            //'next_step' => '03',
            'action_page' => 'task.approve',
            'created_by' => 5900,
            'privilege' => 'approve_fuel_requisition'
        ]);

    }
}
