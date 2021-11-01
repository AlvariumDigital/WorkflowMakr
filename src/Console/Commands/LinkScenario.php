<?php

namespace AlvariumDigital\WorkflowMakr\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LinkScenario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflowmakr:link-scenario
                                {model : The namespace of the model to link}
                                {scenario : The scenario ID}
                                {--conn=0 : The connection name if non default used} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add model namespace to a scenario';

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
        app($this->argument('model'));
        $conn = $this->option('conn');
        $scenario = $this->argument('scenario');
        if ($conn) {
            $scenario = DB::connection($conn)->table('workflow_makr_scenarios')->where('id', $scenario);
        } else {
            $scenario = DB::table('workflow_makr_scenarios')->where('id', $scenario);
        }
        $scenario->update(['entity' => $this->argument('model')]);
        $this->info('Scenario configured with given model');
        return 0;
    }
}
