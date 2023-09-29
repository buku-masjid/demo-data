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
        $this->generateLecturingSchedules();
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
        DB::table('bank_accounts')->insert([
            'name' => 'BSI Operasional Masjid',
            'number' => '0123456789',
            'account_name' => 'Masjid As-Salam',
            'creator_id' => 1,
        ]);
        $this->comment(date('Y-m-d H:i:s').' Finish generate Bank Accounts');
    }

    public function generateBankAccountBalances()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Bank Account Balances...');
        $firstBankAccount = DB::table('bank_accounts')->first();
        DB::table('bank_account_balances')->insert([
            ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(4)->format('Y-m-t'), 'amount' => 34568400, 'description' => 'Saldo akhir '.now()->subMonths(4)->isoFormat('MMMM Y'), 'creator_id' => 1],
            ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(3)->format('Y-m-t'), 'amount' => 39268400, 'description' => 'Saldo akhir '.now()->subMonths(3)->isoFormat('MMMM Y'), 'creator_id' => 1],
            ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(2)->format('Y-m-t'), 'amount' => 49568400, 'description' => 'Saldo akhir '.now()->subMonths(2)->isoFormat('MMMM Y'), 'creator_id' => 1],
            ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(1)->format('Y-m-t'), 'amount' => 53297160, 'description' => 'Saldo akhir '.now()->subMonths(1)->isoFormat('MMMM Y'), 'creator_id' => 1],
        ]);
        $this->comment(date('Y-m-d H:i:s').' Finish generate Bank Account Balances');
    }

    public function generateLecturingSchedules()
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
                    DB::table('lecturing_schedules')->insert([
                        'audience_code' => 'public',
                        'date' => $loopDate->format('Y-m-d'),
                        'start_time' => '05:50',
                        'time_text' => 'Ba\'da Subuh',
                        'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
                        'creator_id' => 1,
                    ]);
                }
                if ($loopDate->isSunday()) {
                    DB::table('lecturing_schedules')->insert([
                        'audience_code' => 'public',
                        'date' => $loopDate->format('Y-m-d'),
                        'start_time' => '05:50',
                        'time_text' => 'Ba\'da Subuh',
                        'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
                        'creator_id' => 1,
                    ]);
                }
                if ($loopDate->isThursday()) {
                    DB::table('lecturing_schedules')->insert([
                        'audience_code' => 'public',
                        'date' => $loopDate->format('Y-m-d'),
                        'start_time' => '05:50',
                        'time_text' => 'Ba\'da Subuh',
                        'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
                        'creator_id' => 1,
                    ]);
                }
                if ($loopDate->isFriday()) {
                    DB::table('lecturing_schedules')->insert([
                        'audience_code' => 'friday',
                        'date' => $loopDate->format('Y-m-d'),
                        'start_time' => '12:20',
                        'time_text' => null,
                        'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
                        'creator_id' => 1,
                    ]);
                }
                if ($loopDate->isSaturday()) {
                    DB::table('lecturing_schedules')->insert([
                        'audience_code' => 'muslimah',
                        'date' => $loopDate->format('Y-m-d'),
                        'start_time' => '16:10',
                        'time_text' => 'Ba\'da Ashar',
                        'lecturer_name' => 'Ustadz Fulan, S. Ag, M. Ag.',
                        'creator_id' => 1,
                    ]);
                }
            }
        }
        $this->comment(date('Y-m-d H:i:s').' Finish generate Lecturing Schedules');
    }

    public function generateTransactions()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Transactions...');
        $this->comment(date('Y-m-d H:i:s').' Finish generate Transactions');
    }
}
