<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskCategory;

class TaskCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Event',
                'description' => 'This is for Event type of Task',
            ],
            [
                'name' => 'Todo',
                'description' => 'This is for todo type of Task',
            ],
        ];

        foreach ($categories as $category) {
            TaskCategory::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }
}
