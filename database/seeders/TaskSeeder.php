<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'name' => 'test1',
            'point' => 20,
        ]);

        Task::create([
            'name' => 'test1',
            'point' => 1,
        ]);

        Task::create([
            'name' => 'test2',
            'point' => 39,
        ]);
    }
}
