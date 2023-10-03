<?php

namespace BukuMasjid\DemoData\Lecturings;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SundayLecturingTransactionsGenerator
{
    public function generate(Carbon $date)
    {
        $incentiveCategory = DB::table('categories')->where('name', 'Insentif Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $incentiveCategory->id,
            'amount' => 400000,
            'description' => 'Insentif kajian subuh Ahad',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $snackCategory = DB::table('categories')->where('name', 'Konsumsi Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $snackCategory->id,
            'amount' => 250000,
            'description' => 'Konsumsi kajian subuh Ahad',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $lecturingInfaqCategory = DB::table('categories')->where('name', 'Kotak Infaq Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $lecturingInfaqCategory->id,
            'amount' => 1000000,
            'description' => 'Kotak infaq kajian subuh Ahad',
            'in_out' => 1,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $dailyInfaqCategory = DB::table('categories')->where('name', 'Kotak Infaq Harian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $dailyInfaqCategory->id,
            'amount' => 350000,
            'description' => 'Kotak infaq harian',
            'in_out' => 1,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
    }
}
