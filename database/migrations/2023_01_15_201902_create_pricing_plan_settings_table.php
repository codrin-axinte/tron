<?php

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
        Schema::create('pricing_plan_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\Modules\Wallet\Models\PricingPlan::class);
            $table->string('commission_strategy')->default('default');
            $table->json('commissions')->nullable();
            $table->unsignedDouble('interest_percentage')->default(0);
            $table->string('interest_frequency')->default('daily');
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('pricing_plan_settings');
    }
};
