<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('sender_account_number');
            $table->string('destination_account_number');
            $table->string('destination_account_type');
            $table->string('sender_account_type');
            $table->decimal('amount', 10, 2);
            $table->decimal('charge', 10, 2);
            $table->enum('status',['pending','successful','failed']);
            $table->string('currency')->default('XAF');
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
        Schema::dropIfExists('transfers');
    }
}
