<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Department::create([
            'name' => 'Computer Science',
            'abbreviation' => 'CSCI'
        ]);

        \App\Department::create([
            'name' => 'Economics',
            'abbreviation' => 'ECON'
        ]);

        \App\Department::create([
            'name' => 'Biology',
            'abbreviation'=> 'BIOL'
        ]);

        \App\User::create([
            'name' => 'Assanali Sailau',
            'email' => 'assan@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'department_id' => 3
        ]);

        $nargiz = \App\User::create([
            'name' => 'Nargiz Zhuanysheva',
            'email' => 'nargiz@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'instructor',
            'department_id' => 2
        ]);

        $marat = \App\User::create([
            'name' => 'Marat Turlubayev',
            'email' => 'sktlmarat@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'department_id' => 1,
            'instructor_id' => 2
        ]);

        $marat->courses()->create([
           'title' =>  'Programming Languages',
            'abbreviation' => 'CSCI 245',
            'department_id' => 1,
            'capacity' => 60
        ]);


        $nargiz->courses()->create([
            'title' =>  'Microecon',
            'abbreviation' => 'ECON 101',
            'department_id' => 2,
            'capacity' => 30
        ]);

        $marat->courses()->create([
            'title' =>  'Computer Systems',
            'abbreviation' => 'CSCI 202',
            'department_id' => 1,
            'capacity' => 15
        ]);


        \App\Course::find(1)->blocks()->create([
            'title' => 'Week 1'
        ]);

        \App\Course::find(1)->blocks()->create([
            'title' => 'Week 2'
        ]);

        \App\Course::find(2)->blocks()->create([
            'title' => 'Week 1'
        ]);

        \App\Block::find(1)->assignments()->create([
           'title' => 'Assignment 1',
            'description' => 'This is assignment 1',
            'file' => 'default_file',
        ]);

        \App\Block::find(3)->assignments()->create([
            'title' => 'Assignment 2',
            'description' => 'This is assignment 2',
            'file' => 'default_file',
        ]);

        \App\Block::find(1)->materials()->create([
            'title' => 'Lecture 1',
            'file' => 'default_file',
        ]);

    }
}
