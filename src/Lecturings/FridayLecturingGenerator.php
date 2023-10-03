<?php

namespace BukuMasjid\DemoData\Lecturings;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FridayLecturingGenerator
{
    use RandomLecturerNames;

    public function generate(Carbon $date)
    {
        DB::table('lecturing_schedules')->insert([
            'audience_code' => 'friday',
            'date' => $date->format('Y-m-d'),
            'start_time' => '12:20',
            'time_text' => null,
            'lecturer_name' => $this->randomLecturer(),
            'creator_id' => 1,
        ]);
    }
}
