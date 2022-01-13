<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Ели мясо мужики',
            'Пивом запивали',
            'О чём конюх говорил',
            'Они не понимали',
        ];
        $now = new \DateTime();

        foreach ($names as $name) {
            DB::table('task')->insert([
                'name'       => $name,
                'created_at' => $now,
            ]);
        }
    }
}
