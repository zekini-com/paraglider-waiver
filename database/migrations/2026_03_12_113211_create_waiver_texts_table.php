<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waiver_texts', function (Blueprint $table) {
            $table->id();
            $table->string('version')->unique();
            $table->text('content');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Add waiver_text_id foreign key to waivers table
        Schema::table('waivers', function (Blueprint $table) {
            $table->foreignId('waiver_text_id')->nullable()->after('waiver_version')->constrained('waiver_texts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('waivers', function (Blueprint $table) {
            $table->dropForeign(['waiver_text_id']);
            $table->dropColumn('waiver_text_id');
        });

        Schema::dropIfExists('waiver_texts');
    }
};
