<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AttendanceTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        \App\AttendanceType::insert([
            [
                'acronym' => 'P',
                'description' => 'Present',
                'grade' => 2,
            ],[
                'acronym' => 'L',
                'description' => 'Late',
                'grade' => 1,
            ],[
                'acronym' => 'A',
                'description' => 'Absent',
                'grade' => 0,
            ]
        ]);
        Model::reguard();
    }
}
