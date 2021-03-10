<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Holidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:holidays {country} {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Holidays from abstractapi.com';

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
     * @return int
     */
    public function handle()
    {
        \App\Http\Services\Settings\Holidays::getHolidays();
        return 0;
    }
}
