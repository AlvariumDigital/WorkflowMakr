<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowMakrHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_makr_histories', function (Blueprint $table) {
            $table->id();
            $table->string('model', 500);
            $table->unsignedBigInteger('model_id');
            $table->string('performed_by_model', 500);
            $table->unsignedBigInteger('performed_by');
            $table->timestamp('performed_at');
            $table->unsignedBigInteger('transition_id');
            $table->foreign('transition_id')->references('id')->on('workflow_makr_transitions');
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
        Schema::dropIfExists('workflow_makr_histories');
    }
}
