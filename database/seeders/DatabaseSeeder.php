<?php

namespace Database\Seeders;

use App\Models\Waiver;
use App\Models\WaiverText;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed the default waiver text (v1.0)
        $waiverText = WaiverText::create([
            'version' => '1.0',
            'content' => view('waiver.text-v1')->render(),
            'is_active' => true,
        ]);

        $participants = [
            ['first_name' => 'John', 'last_name' => 'Smith', 'email' => 'john.smith@example.com', 'phone' => '+27 82 123 4567', 'id_passport_number' => '8501015800083', 'date_of_birth' => '1985-01-01', 'emergency_contact_name' => 'Jane Smith', 'emergency_contact_relationship' => 'Spouse'],
            ['first_name' => 'Sarah', 'last_name' => 'Johnson', 'email' => 'sarah.j@example.com', 'phone' => '+27 83 234 5678', 'id_passport_number' => 'GB123456789', 'date_of_birth' => '1990-06-15', 'emergency_contact_name' => 'Tom Johnson', 'emergency_contact_relationship' => 'Parent'],
            ['first_name' => 'Michael', 'last_name' => 'van der Berg', 'email' => 'michael.vdb@example.com', 'phone' => '+27 84 345 6789', 'id_passport_number' => '9203025800085', 'date_of_birth' => '1992-03-02', 'emergency_contact_name' => 'Lisa van der Berg', 'emergency_contact_relationship' => 'Sibling'],
            ['first_name' => 'Emma', 'last_name' => 'Wilson', 'email' => 'emma.wilson@example.com', 'phone' => '+27 71 456 7890', 'id_passport_number' => 'US987654321', 'date_of_birth' => '1988-11-20', 'emergency_contact_name' => 'David Wilson', 'emergency_contact_relationship' => 'Spouse'],
            ['first_name' => 'Thabo', 'last_name' => 'Molefe', 'email' => 'thabo.m@example.com', 'phone' => '+27 63 567 8901', 'id_passport_number' => '9507125800081', 'date_of_birth' => '1995-07-12', 'emergency_contact_name' => 'Grace Molefe', 'emergency_contact_relationship' => 'Parent'],
            ['first_name' => 'Annika', 'last_name' => 'Müller', 'email' => 'annika.m@example.com', 'phone' => '+49 170 1234567', 'id_passport_number' => 'DE1234567890', 'date_of_birth' => '1993-04-08', 'emergency_contact_name' => 'Hans Müller', 'emergency_contact_relationship' => 'Parent'],
            ['first_name' => 'James', 'last_name' => 'O\'Brien', 'email' => 'james.ob@example.com', 'phone' => '+27 82 678 9012', 'id_passport_number' => 'IE987654321', 'date_of_birth' => '1987-09-25', 'emergency_contact_name' => 'Siobhan O\'Brien', 'emergency_contact_relationship' => 'Spouse'],
            ['first_name' => 'Priya', 'last_name' => 'Naidoo', 'email' => 'priya.n@example.com', 'phone' => '+27 73 789 0123', 'id_passport_number' => '9108305800086', 'date_of_birth' => '1991-08-30', 'emergency_contact_name' => 'Raj Naidoo', 'emergency_contact_relationship' => 'Sibling'],
            ['first_name' => 'Lucas', 'last_name' => 'Ferreira', 'email' => 'lucas.f@example.com', 'phone' => '+55 11 98765 4321', 'id_passport_number' => 'BR1234567890', 'date_of_birth' => '1996-02-14', 'emergency_contact_name' => 'Maria Ferreira', 'emergency_contact_relationship' => 'Parent'],
            ['first_name' => 'Sophie', 'last_name' => 'de Villiers', 'email' => 'sophie.dv@example.com', 'phone' => '+27 82 890 1234', 'id_passport_number' => '8812105800084', 'date_of_birth' => '1988-12-10', 'emergency_contact_name' => 'Pierre de Villiers', 'emergency_contact_relationship' => 'Spouse'],
        ];

        // Stagger created_at dates over the past 2 weeks
        foreach ($participants as $i => $p) {
            Waiver::create([
                ...$p,
                'emergency_contact_phone' => '+27 82 000 '.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'waiver_version' => '1.0',
                'waiver_text_id' => $waiverText->id,
                'signature_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
                'ip_address' => '127.0.0.'.($i + 1),
                'user_agent' => 'Mozilla/5.0 (Seeder)',
                'download_token' => Str::random(64),
                'email_sent_at' => now()->subDays(13 - $i),
                'created_at' => now()->subDays(13 - $i),
                'updated_at' => now()->subDays(13 - $i),
            ]);
        }
    }
}
