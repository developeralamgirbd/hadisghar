<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_lists', function (Blueprint $table) {
            $table->id();
            $table->string('cron')->nullable();
            $table->string('command_name')->nullable();
            $table->string('last_run')->nullable();
            $table->string('next_run')->nullable();
            $table->integer('frequency_id')->nullable();
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
        Schema::dropIfExists('schedule_lists');
    }
};
