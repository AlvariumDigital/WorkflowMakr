<?php

namespace AlvariumDigital\WorkflowMakr\Console\Commands;

use AlvariumDigital\WorkflowMakr\Models\Status;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LinkModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflowmakr:link {model} {--default=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add "status_id" column to a model, to link it to workflowmakr';

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
        $model = app($this->argument('model'));
        $table = $model->getTable();
        $default_status = $this->option('default');
        Schema::table($table, function (Blueprint $table) use ($default_status) {
            if ($default_status != 0) {
                $table->unsignedBigInteger('status_id')->default($default_status);
            } else {
                $table->unsignedBigInteger('status_id')->nullable();
            }
        });
        $this->info('Column "status_id" added to ' . $this->argument('model') . ' (' . $table . ')');
        return 0;
    }
}
