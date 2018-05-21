<?php

namespace App\Console\Commands;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class totalByDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:total {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = ($this->argument('date')) ? Carbon::parse($this->argument('date')) : Carbon::now()->subDays(1);

        $sum =  Transaction::whereDay('date','=', $date->day)
           ->whereMonth('date','=',$date->month)
           ->whereYear('date','=',$date->year)
           ->sum('amount');

        var_dump($date);

        $this->line('Save all transaction for date:');
        $this->line($date->format('d.m.Y'));
        $this->line('Sum = '.$sum);
        \DB::table('sum_day_by_day')->insert([
            'sum'=>$sum,
            'created_at' => Carbon::now(),
            'sum_for_date' => $date
        ]);
    }
}
