<?php

namespace BukuMasjid\DemoData\Lecturings;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FridayLecturingTransactionsGenerator
{
    public function generate(Carbon $date)
    {
        $incentiveCategory = DB::table('categories')->where('name', 'Insentif Hari Jumat')->first();
        if ($incentiveCategory) {
            DB::table('transactions')->insert([
                'date' => $date->format('Y-m-d'),
                'category_id' => $incentiveCategory->id,
                'amount' => 400000,
                'description' => 'Insentif khatib Jumat',
                'in_out' => 0,
                'book_id' => 1,
                'creator_id' => 1,
            ]);
            DB::table('transactions')->insert([
                'date' => $date->format('Y-m-d'),
                'category_id' => $incentiveCategory->id,
                'amount' => 100000,
                'description' => 'Insentif imam Jumat',
                'in_out' => 0,
                'book_id' => 1,
                'creator_id' => 1,
            ]);
            DB::table('transactions')->insert([
                'date' => $date->format('Y-m-d'),
                'category_id' => $incentiveCategory->id,
                'amount' => 100000,
                'description' => 'Insentif muadzin Jumat',
                'in_out' => 0,
                'book_id' => 1,
                'creator_id' => 1,
            ]);
            DB::table('transactions')->insert([
                'date' => $date->format('Y-m-d'),
                'category_id' => $incentiveCategory->id,
                'amount' => 100000,
                'description' => 'Insentif keamanan Jumat',
                'in_out' => 0,
                'book_id' => 1,
                'creator_id' => 1,
            ]);
        }
        $lecturingInfaqCategory = DB::table('categories')->where('name', 'Kotak Infaq Hari Jumat')->first();
        if ($lecturingInfaqCategory) {
            DB::table('transactions')->insert([
                'date' => $date->format('Y-m-d'),
                'category_id' => $lecturingInfaqCategory->id,
                'amount' => 1500000,
                'description' => 'Kotak infaq hari Jumat',
                'in_out' => 1,
                'book_id' => 1,
                'creator_id' => 1,
            ]);
        }
    }
}
