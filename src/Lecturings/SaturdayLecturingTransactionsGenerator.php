<?php

namespace BukuMasjid\DemoData\Lecturings;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaturdayLecturingTransactionsGenerator
{
    public function generate(Carbon $date)
    {
        $incentiveCategory = DB::table('categories')->where('name', 'Insentif Kajian')->first();
        if ($incentiveCategory) {
            DB::table('transactions')->insert([
                'date' => $date->format('Y-m-d'),
                'category_id' => $incentiveCategory->id,
                'amount' => 400000,
                'description' => 'Insentif kajian muslimah Sabtu',
                'in_out' => 0,
                'book_id' => 1,
                'creator_id' => 1,
            ]);
        }
        $snackCategory = DB::table('categories')->where('name', 'Konsumsi Kajian')->first();
        if ($snackCategory) {
            DB::table('transactions')->insert([
                'date' => $date->format('Y-m-d'),
                'category_id' => $snackCategory->id,
                'amount' => 250000,
                'description' => 'Konsumsi kajian muslimah Sabtu',
                'in_out' => 0,
                'book_id' => 1,
                'creator_id' => 1,
            ]);
        }
        $lecturingInfaqCategory = DB::table('categories')->where('name', 'Kotak Infaq Kajian')->first();
        if ($lecturingInfaqCategory) {
            DB::table('transactions')->insert([
                'date' => $date->format('Y-m-d'),
                'category_id' => $lecturingInfaqCategory->id,
                'amount' => 500000,
                'description' => 'Kotak infaq kajian muslimah Sabtu',
                'in_out' => 1,
                'book_id' => 1,
                'creator_id' => 1,
            ]);
        }
    }
}
