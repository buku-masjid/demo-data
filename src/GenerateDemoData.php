<?php

namespace BukuMasjid\DemoData;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateDemoData extends Command
{
    protected $signature = 'buku-masjid:generate-demo-data
                            {--reset-all : Reset seluruh isi database}
                            ';

    protected $description = 'Generate data demo untuk simulasi.';

    public function handle()
    {
        $confirm = $this->confirm('Anda yakin ini generate data demo?');
        if ($confirm == false) {
            return;
        }

        if ($this->option('reset-all')) {
            $confirm = $this->confirm('Kosongkan seluruh isi database?');
            if ($confirm) {
                $this->call('migrate:fresh', ['--seed' => true]);
            }
        }

        $this->generateBooks();
        $this->generateBankAccounts();
        $this->generateBankAccountBalances();
        $this->generateLecturingSchedulesWithTransactions();
        // $this->generateTransactions();

        $this->info('Demo data sudah digenerate!');
    }

    public function generateBooks()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Books...');
        DB::table('books')->insert([
            ['name' => 'Ramadhan 2022', 'description' => 'Buku catatan keuangan Ramadhan 2022', 'creator_id' => 1],
            ['name' => 'Qurban 2022', 'description' => 'Buku catatan keuangan Qurban 2022', 'creator_id' => 1],
            ['name' => 'Ramadhan 2023', 'description' => 'Buku catatan keuangan Ramadhan 2023', 'creator_id' => 1],
            ['name' => 'Qurban 2023', 'description' => 'Buku catatan keuangan Qurban 2023', 'creator_id' => 1],
        ]);
        $this->comment(date('Y-m-d H:i:s').' Finish generate Books');
    }

    public function generateBankAccounts()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Bank Accounts...');
        $bankAccountId = DB::table('bank_accounts')->insertGetId([
            'name' => 'BSI Operasional Masjid',
            'number' => '0123456789',
            'account_name' => 'Masjid As-Salam',
            'creator_id' => 1,
        ]);
        DB::table('books')->where('id', 1)->update(['bank_account_id' => $bankAccountId]);
        $this->comment(date('Y-m-d H:i:s').' Finish generate Bank Accounts');
    }

    public function generateBankAccountBalances()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Bank Account Balances...');
        $firstBankAccount = DB::table('bank_accounts')->latest('id')->first();
        DB::table('bank_account_balances')->insert([
            ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(4)->format('Y-m-t'), 'amount' => 34568400, 'description' => 'Saldo akhir '.now()->subMonths(4)->isoFormat('MMMM Y'), 'creator_id' => 1],
            ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(3)->format('Y-m-t'), 'amount' => 39268400, 'description' => 'Saldo akhir '.now()->subMonths(3)->isoFormat('MMMM Y'), 'creator_id' => 1],
            ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(2)->format('Y-m-t'), 'amount' => 49568400, 'description' => 'Saldo akhir '.now()->subMonths(2)->isoFormat('MMMM Y'), 'creator_id' => 1],
            ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(1)->format('Y-m-t'), 'amount' => 53297160, 'description' => 'Saldo akhir '.now()->subMonths(1)->isoFormat('MMMM Y'), 'creator_id' => 1],
        ]);
        $this->comment(date('Y-m-d H:i:s').' Finish generate Bank Account Balances');
    }

    public function generateLecturingSchedulesWithTransactions()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Lecturing Schedules...');
        $montRange = range(0, 2);
        rsort($montRange);
        foreach ($montRange as $number) {
            $monthDate = Carbon::parse(now()->format('Y-m-').'10')->subMonths($number);
            foreach (range(1, $monthDate->format('t')) as $date) {
                $date = str_pad($date, 2, 0, STR_PAD_LEFT);
                $loopDate = Carbon::parse($monthDate->format('Y-m-').$date);
                if ($loopDate->isTuesday()) {
                    $this->generateTuesdayLecturing($loopDate);
                    if ($loopDate->lessThanOrEqualTo(today())) {
                        $this->generateTuesdayLecturingSchedule($loopDate);
                    }
                }
                if ($loopDate->isThursday()) {
                    $this->generateThursdayLecturing($loopDate);
                    if ($loopDate->lessThanOrEqualTo(today())) {
                        $this->generateThursdayLecturingSchedule($loopDate);
                    }
                }
                if ($loopDate->isFriday()) {
                    $this->generateFridayLecturing($loopDate);
                    if ($loopDate->lessThanOrEqualTo(today())) {
                        $this->generateFridayLecturingTransactions($loopDate);
                    }
                }
                if ($loopDate->isSaturday()) {
                    $this->generateSaturdayLecturing($loopDate);
                    if ($loopDate->lessThanOrEqualTo(today())) {
                        $this->generateSaturdayLecturingTransations($loopDate);
                    }
                }
                if ($loopDate->isSunday()) {
                    $this->generateSundayLecturing($loopDate);
                    if ($loopDate->lessThanOrEqualTo(today())) {
                        $this->generateSundayLecturingTransactions($loopDate);
                    }
                }
            }
        }
        $this->comment(date('Y-m-d H:i:s').' Finish generate Lecturing Schedules');
    }

    private function generateTuesdayLecturing(Carbon $date): void
    {
        DB::table('lecturing_schedules')->insert([
            'audience_code' => 'public',
            'date' => $date->format('Y-m-d'),
            'start_time' => '05:50',
            'time_text' => 'Ba\'da Subuh',
            'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
            'creator_id' => 1,
        ]);
    }

    private function generateTuesdayLecturingSchedule(Carbon $date): void
    {
        $incentiveCategory = DB::table('categories')->where('name', 'Insentif Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $incentiveCategory->id,
            'amount' => 400000,
            'description' => 'Insentif kajian subuh Selasa',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $snackCategory = DB::table('categories')->where('name', 'Konsumsi Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $snackCategory->id,
            'amount' => 250000,
            'description' => 'Konsumsi kajian subuh Selasa',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $lecturingInfaqCategory = DB::table('categories')->where('name', 'Kotak Infaq Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $lecturingInfaqCategory->id,
            'amount' => 500000,
            'description' => 'Kotak infaq kajian subuh Selasa',
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

    private function generateThursdayLecturing(Carbon $date): void
    {
        DB::table('lecturing_schedules')->insert([
            'audience_code' => 'public',
            'date' => $date->format('Y-m-d'),
            'start_time' => '05:50',
            'time_text' => 'Ba\'da Subuh',
            'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
            'creator_id' => 1,
        ]);
    }

    private function generateThursdayLecturingSchedule(Carbon $date): void
    {
        $incentiveCategory = DB::table('categories')->where('name', 'Insentif Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $incentiveCategory->id,
            'amount' => 400000,
            'description' => 'Insentif kajian subuh Kamis',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $lecturingInfaqCategory = DB::table('categories')->where('name', 'Kotak Infaq Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $lecturingInfaqCategory->id,
            'amount' => 500000,
            'description' => 'Kotak infaq kajian subuh Kamis',
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

    private function generateFridayLecturing(Carbon $date): void
    {
        DB::table('lecturing_schedules')->insert([
            'audience_code' => 'friday',
            'date' => $date->format('Y-m-d'),
            'start_time' => '12:20',
            'time_text' => null,
            'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
            'creator_id' => 1,
        ]);
    }

    private function generateFridayLecturingTransactions(Carbon $date): void
    {
        $incentiveCategory = DB::table('categories')->where('name', 'Insentif Hari Jumat')->first();
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
        $lecturingInfaqCategory = DB::table('categories')->where('name', 'Kotak Infaq Hari Jumat')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $lecturingInfaqCategory->id,
            'amount' => 500000,
            'description' => 'Kotak infaq hari Jumat',
            'in_out' => 1,
            'book_id' => 1,
            'creator_id' => 1,
        ]);

    }

    private function generateSaturdayLecturing(Carbon $date): void
    {
        DB::table('lecturing_schedules')->insert([
            'audience_code' => 'muslimah',
            'date' => $date->format('Y-m-d'),
            'start_time' => '16:10',
            'time_text' => 'Ba\'da Ashar',
            'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
            'creator_id' => 1,
        ]);
    }

    private function generateSaturdayLecturingTransations(Carbon $date): void
    {

        $incentiveCategory = DB::table('categories')->where('name', 'Insentif Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $incentiveCategory->id,
            'amount' => 400000,
            'description' => 'Insentif kajian muslimah Sabtu',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $snackCategory = DB::table('categories')->where('name', 'Konsumsi Kajian')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $snackCategory->id,
            'amount' => 250000,
            'description' => 'Konsumsi kajian muslimah Sabtu',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $lecturingInfaqCategory = DB::table('categories')->where('name', 'Kotak Infaq Kajian')->first();
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

    private function generateSundayLecturing(Carbon $date): void
    {
        DB::table('lecturing_schedules')->insert([
            'audience_code' => 'public',
            'date' => $date->format('Y-m-d'),
            'start_time' => '05:50',
            'time_text' => 'Ba\'da Subuh',
            'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
            'creator_id' => 1,
        ]);
    }

    private function generateSundayLecturingTransactions(Carbon $date): void
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
            'amount' => 500000,
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

    public function generateTransactions()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Transactions...');
        $this->comment(date('Y-m-d H:i:s').' Finish generate Transactions');
    }
}
