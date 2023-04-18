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
        Schema::create('pending_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class);
            $table->string('type');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        /*Schema::create('pending_action_has_model', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\PendingAction::class);
            $table->morphs('awaitable');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_actions');
    }
};
