<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::create([
            'name' => 'Вантажник',
        ]);
        Position::create([
            'name' => 'Водій',
        ]);
        Position::create([
            'name' => 'Асистент',
        ]);
        Position::create([
            'name' => 'Менеджер',
        ]);
        Position::create([
            'name' => 'Бухгалтер',
        ]);
        Position::create([
            'name' => 'Касир',
        ]);
        Position::create([
            'name' => 'Охоронець',
        ]);
        Position::create([
            'name' => 'Прибиральник',
        ]);
        Position::create([
            'name' => 'Керівник відділу',
        ]);
    }
}
