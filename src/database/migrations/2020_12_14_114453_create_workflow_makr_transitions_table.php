<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowMakrTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_makr_transitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_status_id')->nullable(true);
            $table->unsignedBigInteger('new_status_id');
            $table->unsignedBigInteger('scenario_id');
            $table->unsignedBigInteger('action_id');
            $table->unsignedBigInteger('predecessor_id')->nullable(true);
            $table->foreign('old_status_id')->references('id')->on('workflow_makr_statuses');
            $table->foreign('new_status_id')->references('id')->on('workflow_makr_statuses');
            $table->foreign('scenario_id')->references('id')->on('workflow_makr_scenarios');
            $table->foreign('action_id')->references('id')->on('workflow_makr_actions');
            $table->foreign('predecessor_id')->references('id')->on('workflow_makr_transitions');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workflow_makr_transitions');
    }
}
