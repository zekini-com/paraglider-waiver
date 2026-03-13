<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AdminAuthService
{
    public function attempt(string $email, string $password): bool
    {
        if (
            $email === config('waiver.admin_email') &&
            $password === config('waiver.admin_password')
        ) {
            Log::info('Admin login successful', ['email' => $email]);

            return true;
        }

        Log::warning('Admin login failed', ['email' => $email]);

        return false;
    }
}
