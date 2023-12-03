<?php

use App\Enums\TransactionBlockchainStatus;
use App\Enums\TransactionCurrency;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tron_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedTinyInteger('currency')
                ->default(TransactionCurrency::TRX->value)
                ->after('amount');
            $table->unsignedTinyInteger('blockchain_status')
                ->default(TransactionBlockchainStatus::Pending->value)
                ->after('blockchain_reference_id');
            $table->boolean('confirmed')->default(false);
            $table->string('reject_reason', 500)
                ->nullable()
                ->after('status');
            $table->text('fail_reason')
                ->nullable()
                ->after('blockchain_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tron_transactions', function (Blueprint $table) {
            $table->string('type')->default(\App\Enums\TransactionType::Deposit->value)
                ->after('blockchain_reference_id');
        });
    }
};
