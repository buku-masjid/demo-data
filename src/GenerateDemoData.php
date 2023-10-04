<?php

namespace BukuMasjid\DemoData;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\View\Components\Info;
use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\DB;

class GenerateDemoData extends Command
{
    protected $signature = 'buku-masjid:generate-demo-data
                            {--reset-all : Reset database data}
                            {--start_date= : Demo data start date}
                            {--end_date= : Demo data end date}
                            ';

    protected $description = 'Generate demo data for simulation';

    public function handle()
    {
        $confirm = $this->confirm('Are you sure to generate demo data?');
        if ($confirm == false) {
            return;
        }

        if ($this->option('reset-all')) {
            $confirm = $this->confirm('Are you sure to reset all database?');
            if ($confirm) {
                $this->call('migrate:fresh', ['--seed' => true]);
            }
        }

        $this->write(Info::class, 'Generating data');
        $this->generateBooks();
        $this->generateBankAccounts();
        $this->generateBankAccountBalances();
        $this->generateLecturingSchedulesWithTransactions();
        $this->generateTransactions();

        $this->newLine();
        $this->write(Info::class, 'Demo date has been generated.');
    }

    public function generateBooks()
    {
        $this->write(Task::class, 'Generate books', function () {
            DB::table('books')->insert([
                ['name' => 'Ramadhan 2022', 'description' => 'Buku catatan keuangan Ramadhan 2022', 'creator_id' => 1],
                ['name' => 'Qurban 2022', 'description' => 'Buku catatan keuangan Qurban 2022', 'creator_id' => 1],
                ['name' => 'Ramadhan 2023', 'description' => 'Buku catatan keuangan Ramadhan 2023', 'creator_id' => 1],
                ['name' => 'Qurban 2023', 'description' => 'Buku catatan keuangan Qurban 2023', 'creator_id' => 1],
            ]);
        });
    }

    public function generateBankAccounts()
    {
        $this->write(Task::class, 'Generate bank accounts', function () {
            $bankAccountId = DB::table('bank_accounts')->insertGetId([
                'name' => 'BSI Operasional Masjid',
                'number' => '0123456789',
                'account_name' => 'Masjid As-Salam',
                'creator_id' => 1,
            ]);
            DB::table('books')->where('id', 1)->update(['bank_account_id' => $bankAccountId]);
        });
    }

    public function generateBankAccountBalances()
    {
        $this->write(Task::class, 'Generate bank account balances', function () {
            $firstBankAccount = DB::table('bank_accounts')->latest('id')->first();
            DB::table('bank_account_balances')->insert([
                ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(4)->format('Y-m-t'), 'amount' => 34568400, 'description' => 'Saldo akhir '.now()->subMonths(4)->isoFormat('MMMM Y'), 'creator_id' => 1],
                ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(3)->format('Y-m-t'), 'amount' => 39268400, 'description' => 'Saldo akhir '.now()->subMonths(3)->isoFormat('MMMM Y'), 'creator_id' => 1],
                ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(2)->format('Y-m-t'), 'amount' => 49568400, 'description' => 'Saldo akhir '.now()->subMonths(2)->isoFormat('MMMM Y'), 'creator_id' => 1],
                ['bank_account_id' => $firstBankAccount->id, 'date' => now()->subMonths(1)->format('Y-m-t'), 'amount' => 53297160, 'description' => 'Saldo akhir '.now()->subMonths(1)->isoFormat('MMMM Y'), 'creator_id' => 1],
            ]);
        });
    }

    public function generateLecturingSchedulesWithTransactions()
    {
        $this->write(Task::class, 'Generate lecturing schedules with transactions', function () {
            $dateRange = $this->getDateRange();
            foreach ($dateRange as $date) {
                $this->generateLecturingScheduleWithTransactions($date);
            }
        });
    }

    private function getDateRange(): array
    {
        // Ref: https://stackoverflow.com/a/4312630
        $dateRange = [];
        $givenStartDate = $this->option('start_date');
        $givenEndDate = $this->option('end_date');
        $startDate = $givenStartDate ?: now()->subMonths(2)->format('Y-m').'-01';
        $endDate = $givenEndDate ?: now()->addMonth()->format('Y-m-t');
        $period = new \DatePeriod(
            Carbon::parse($startDate),
            new \DateInterval('P1D'),
            Carbon::parse($endDate)
        );
        foreach ($period as $date) {
            $dateRange[] = $date;
        }
        return $dateRange;
    }

    private function generateLecturingScheduleWithTransactions(Carbon $date): void
    {
        $dayName = $date->locale('en_EN')->dayName;
        $generatorClassNamespace = 'BukuMasjid\DemoData\Lecturings\\';
        $generatorClassName = $generatorClassNamespace.$dayName.'LecturingGenerator';
        if (class_exists($generatorClassName)) {
            (new $generatorClassName)->generate($date);
        }
        if ($date->lessThanOrEqualTo(today())) {
            $generatorClassName = $generatorClassNamespace.$dayName.'LecturingTransactionsGenerator';
            if (class_exists($generatorClassName)) {
                (new $generatorClassName)->generate($date);
            }
        }
    }

    public function generateTransactions()
    {
        $this->write(Task::class, 'Generate bill and salary payment transactions', function () {
            foreach ($this->getDateRange() as $date) {
                if ($date->greaterThan(today())) {
                    break;
                }
                $this->generateBillPaymentTransactions($date);
                $this->generateSalaryTransactions($date);
            }
        });
    }

    private function generateBillPaymentTransactions(Carbon $date)
    {
        $firstTuesdayOfTheMonth = Carbon::parse('first tuesday of '.$date->format('F Y'));
        if (!$date->equalTo($firstTuesdayOfTheMonth)) {
            return;
        }

        $electricBillCategory = DB::table('categories')->where('name', 'Tagihan Listrik')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $electricBillCategory->id,
            'amount' => 5496000,
            'description' => 'Bayar tagihan listrik '.$date->isoFormat('MMMM Y'),
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $waterBillCategory = DB::table('categories')->where('name', 'Tagihan Air')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $waterBillCategory->id,
            'amount' => 757200,
            'description' => 'Bayar tagihan PDAM '.$date->isoFormat('MMMM Y'),
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        $internetBillCategory = DB::table('categories')->where('name', 'Tagihan Internet')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $internetBillCategory->id,
            'amount' => 431000,
            'description' => 'Bayar tagihan Internet '.$date->isoFormat('MMMM Y'),
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
    }

    private function generateSalaryTransactions(Carbon $date)
    {
        $lastDayOfTheMonthDate = Carbon::parse($date->format('Y-m-t'));
        if (!$date->equalTo($lastDayOfTheMonthDate)) {
            return;
        }

        $salaryCategory = DB::table('categories')->where('name', 'Gaji Karyawan')->first();
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $salaryCategory->id,
            'amount' => 2125000,
            'description' => 'Insentif Fulan (Satpam)',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $salaryCategory->id,
            'amount' => 1000000,
            'description' => 'Gaji Fulan',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $salaryCategory->id,
            'amount' => 750000,
            'description' => 'Insentif Admin Fulan',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
        DB::table('transactions')->insert([
            'date' => $date->format('Y-m-d'),
            'category_id' => $salaryCategory->id,
            'amount' => 275000,
            'description' => 'Insentif Fulan',
            'in_out' => 0,
            'book_id' => 1,
            'creator_id' => 1,
        ]);
    }

    protected function write($component, ...$arguments)
    {
        if ($this->output && class_exists($component)) {
            (new $component($this->output))->render(...$arguments);
        } else {
            foreach ($arguments as $argument) {
                if (is_callable($argument)) {
                    $argument();
                }
            }
        }
    }
}
