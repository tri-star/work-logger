<?php

namespace WorkLogger\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use WorkLogger\Jobs\SampleJob;

class RunJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:run-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SampleJob::dispatch(1);
        SampleJob::dispatch(2);
        return 0;
    }
}
