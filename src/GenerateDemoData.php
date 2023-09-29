<?php

namespace BukuMasjid\DemoData;

use Illuminate\Console\Command;

class GenerateDemoData extends Command
{
    protected $signature = 'buku-masjid:generate-demo-data';

    protected $description = 'Generate data demo untuk simulasi.';

    public function handle()
    {
        $this->generateBankAccounts();
        $this->generateBankAccountBalances();
        $this->generateBooks();
        $this->generateTransactions();
        $this->generateLecturingSchedules();

        $this->info('Demo data sudah digenerate!');
    }

    public function generateBankAccounts()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Bank Accounts...');
        $this->comment(date('Y-m-d H:i:s').' Finish generate Bank Accounts');
    }

    public function generateBankAccountBalances()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Bank Account Balances...');
        $this->comment(date('Y-m-d H:i:s').' Finish generate Bank Account Balances');
    }

    public function generateBooks()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Books...');
        $this->comment(date('Y-m-d H:i:s').' Finish generate Books');
    }

    public function generateTransactions()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Transactions...');
        $this->comment(date('Y-m-d H:i:s').' Finish generate Transactions');
    }

    public function generateLecturingSchedules()
    {
        $this->comment(date('Y-m-d H:i:s').' Start generate Lecturing Schedules...');
        $this->comment(date('Y-m-d H:i:s').' Finish generate Lecturing Schedules');
    }
}
