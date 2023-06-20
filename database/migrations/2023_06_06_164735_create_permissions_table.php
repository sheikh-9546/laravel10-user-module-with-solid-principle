<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id()->startingValue(2001);
            $table->string('name')->index();
            $table->string('slug');
            $table->string('content_type')->nullable();
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->index();
            $table->bigInteger('updated_by')->nullable()->index();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};


