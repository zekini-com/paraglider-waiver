<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waivers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('id_passport_number');
            $table->date('date_of_birth');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_relationship');
            $table->string('waiver_version')->default('1.0');
            $table->longText('signature_data');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->string('download_token', 64)->unique();
            $table->timestamps();

            $table->index('email');
            $table->index('last_name');
            $table->index('id_passport_number');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waivers');
    }
};
