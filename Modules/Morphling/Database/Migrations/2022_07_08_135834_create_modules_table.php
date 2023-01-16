<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Morphling\Utils\Table;

return new class extends Migration
{
    public function up()
    {
        Schema::create(Table::modules(), function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name')->unique();
            $table->string('alias')->nullable();
            $table->boolean('enabled')->default(false);
            $table->string('description', 500)->nullable();
            $table->unsignedInteger('priority')->default(0);
            $table->string('version')->nullable();
            $table->string('minimumCoreVersion')->nullable();
            $table->string('author')->nullable();
            $table->json('requirements')->nullable();
            $table->json('keywords')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(Table::modules());
    }
};
