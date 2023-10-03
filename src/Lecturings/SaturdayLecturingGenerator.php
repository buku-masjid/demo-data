<?php

namespace BukuMasjid\DemoData\Lecturings;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaturdayLecturingGenerator
{
    use RandomLecturerNames;

    public function generate(Carbon $date)
    {
        DB::table('lecturing_schedules')->insert([
            'audience_code' => 'muslimah',
            'date' => $date->format('Y-m-d'),
            'start_time' => '16:10',
            'time_text' => 'Ba\'da Ashar',
            'lecturer_name' => $this->randomLecturer(),
            'creator_id' => 1,
        ]);
    }
}
