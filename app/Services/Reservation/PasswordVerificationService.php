<?php

namespace App\Services\Security;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordVerificationService
{
    /**
     * Vérifie le mot de passe d'un utilisateur.
     * Utilisé pour AJAX (UX) ET backend (sécurité réelle).
     */
    public function verify(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }
}