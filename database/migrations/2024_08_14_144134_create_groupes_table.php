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
        Schema::create('groupes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->foreignIdFor(\App\Models\Foyer::class)->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Groupe::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupes');
    }
};
