<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowMakrModelStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_makr_model_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('model', 500);
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('workflow_makr_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workflow_makr_model_statuses');
    }
}
