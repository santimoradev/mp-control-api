<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Conglomerate;
use Carbon\Carbon;

class ConglomerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'idea:conglomerate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Conglomerate report';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ideaDaily = new Conglomerate();
        $ideaDaily->sendData();
    }
}
