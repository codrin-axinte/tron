<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Wallet\Enums\FrequencyType;
use Modules\Wallet\Utils\Table;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Table::creditsPlans(), function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->unsignedBigInteger('price')->default(0);
            $table->json('description')->nullable();
            $table->boolean('is_best')->default(false);
            $table->string('frequency_type')->default(FrequencyType::Once->value);
            $table->json('features')->nullable();
            $table->boolean('enabled')->default(false);
            $table->dateTime('expires_at')->nullable();
            $table->integer('sort_order')->nullable();
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
        Schema::dropIfExists(Table::creditsPlans());
    }
};
