<?php

namespace BukuMasjid\DemoData\Lecturings;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ThursdayLecturingGenerator
{
    use RandomLecturerNames;

    public function generate(Carbon $date)
    {
        DB::table('lecturing_schedules')->insert([
            'audience_code' => 'public',
            'date' => $date->format('Y-m-d'),
            'start_time' => '05:50',
            'time_text' => 'Ba\'da Subuh',
            'lecturer_name' => $this->randomLecturer(),
            'creator_id' => 1,
        ]);
    }
}
