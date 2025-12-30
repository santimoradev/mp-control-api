<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IdeaDaily;
use Carbon\Carbon;

class IdeaDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'idea:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily report';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $dayBefore  = Carbon::yesterday();
        if ($dayBefore->isWeekend()) {
            $dayBefore = $dayBefore->parse('last friday');
        }
        $date = $dayBefore->format('Y-m-d');

        $ideaDaily = new IdeaDaily();
        $ideaDaily->getIdeasReport($date);

    }
}
