<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array('Privilege Leave','Casual Leave', 'Sick Leave');
        foreach ($types as $value) {
            \App\Models\LeaveType::create([
                 'type' => $value,
                 'total_leave' => mt_rand(1,10),
            ]);  
        }
    }
}
