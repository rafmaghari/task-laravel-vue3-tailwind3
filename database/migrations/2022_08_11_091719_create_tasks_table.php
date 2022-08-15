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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title');
            $table->string('description');
            $table->date('due_date')->nullable();
            $table->unsignedTinyInteger('priority')
                ->comment('0=low,1=normal,2=High,3=urgent')
                ->nullable();
            $table->integer('order')->default(0);
            $table->unsignedTinyInteger('status')
                ->comment('1=incomplete,2=complete')
                ->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('archived_at')->nullable();
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
        Schema::dropIfExists('tasks');
    }
};
