<?php

namespace BukuMasjid\DemoData;

use Illuminate\Console\Command;
use Illuminate\Console\View\Components\Info;
use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\DB;

class RemoveDemoData extends Command
{
    protected $signature = 'buku-masjid:remove-demo-data';

    protected $description = 'Remove buku masjid demo data';

    public function handle()
    {
        $confirm = $this->confirm('Are you sure to remove demo data?');
        if ($confirm == false) {
            return;
        }

        $this->write(Info::class, 'Removing data');
        $this->removeTransactions();
        $this->removeLecturingSchedules();
        $this->removeBankAccountBalances();
        $this->removeBankAccounts();
        $this->removeBooks();

        $this->newLine();
        $this->write(Info::class, 'Demo data has been deleted.');
    }

    public function removeBooks()
    {
        $this->write(Task::class, 'Remove demo books', function () {
            DB::table('books')->whereNull('created_at')->delete();
        });
    }

    public function removeBankAccounts()
    {
        $this->write(Task::class, 'Remove demo bank accounts', function () {
            $demoBankAccountIds = DB::table('bank_accounts')->whereNull('created_at')->get()->pluck('id');
            DB::table('books')->whereIn('bank_account_id', $demoBankAccountIds)->update(['bank_account_id' => null]);
            DB::table('bank_accounts')->whereIn('id', $demoBankAccountIds)->delete();
        });
    }

    public function removeBankAccountBalances()
    {
        $this->write(Task::class, 'Remove demo bank account balances', function () {
            DB::table('bank_account_balances')->whereNull('created_at')->delete();
        });
    }

    public function removeLecturingSchedules()
    {
        $this->write(Task::class, 'Remove demo lecturing schedules', function () {
            DB::table('lecturings')->whereNull('created_at')->delete();
        });
    }

    public function removeTransactions()
    {
        $this->write(Task::class, 'Remove demo transactions', function () {
            DB::table('transactions')->whereNull('created_at')->delete();
        });
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
