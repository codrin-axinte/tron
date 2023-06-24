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
        Schema::create('tron_transactions', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('from');
            $table->string('to');
            $table->float('amount');
            $table->string('contract')->nullable();
            $table->string('blockchain_reference_id')->nullable();
            $table->string('type')->default(\App\Enums\TransactionType::Deposit->value);
            $table->string('status')->default(\App\Enums\TransactionStatus::Approved->value);
            $table->nullableUuidMorphs('sender');
            $table->nullableUuidMorphs('receiver');
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
        Schema::dropIfExists('tron_transactions');
    }
};
