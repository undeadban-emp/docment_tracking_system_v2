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
        Schema::create('user_service', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number');
            $table->integer('user_id');
            $table->integer('service_id');
            $table->integer('service_index');
            $table->foreignId('received_by')->nullable()->references('id')->on('users');
            $table->string('forwarded_by')->nullable()->references('id')->on('users');
            $table->string('forward_to')->nullable()->references('id')->on('users');
            $table->foreignId('returned_by')->nullable()->references('id')->on('users');
            $table->foreignId('returned_to')->nullable()->references('id')->on('users');
            $table->foreignId('manager_id')->nullable();
            $table->text('remarks')->nullable();
            $table->text('reasons')->nullable();
            $table->enum('status', ['pending', 'forwarded', 'received', 'disapproved', 'last']);
            $table->enum('stage', ['passed', 'current', 'incoming'])->default('incoming');
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
        Schema::dropIfExists('user_service');
    }
};
