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
        Schema::create('service_processes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('location')->nullable();
            $table->string('process_time')->nullable();
            $table->string('responsible');
            $table->decimal('fees_to_paid', 10, 2)->defalt(0);
            $table->string('action')->nullable();
            $table->string('description')->nullable();
            $table->integer('index');
            $table->foreignId('responsible_user')->nullable()->references('id')->on('users');
            $table->foreignId('manager_id');
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
        Schema::dropIfExists('service_processes');
    }
};
