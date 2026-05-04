<?php

namespace App\Services\Security;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordVerificationService
{
    public function verify(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }
}