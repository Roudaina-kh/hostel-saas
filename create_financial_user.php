<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

use App\Models\User;
use App\Models\Hostel;
use Illuminate\Support\Facades\Hash;

$hostel = Hostel::first();
if (!$hostel) {
    echo "No hostel found. Please create one first.";
    exit;
}

$email = 'finance@hostel-flow.com';
$password = 'FinanceFlow2026';

$user = User::updateOrCreate(
    ['email' => $email],
    [
        'name' => 'Responsable Financier',
        'password' => Hash::make($password),
        'status' => 'active'
    ]
);

// Attacher au hostel via pivot
$user->hostels()->syncWithoutDetaching([
    $hostel->id => [
        'role' => 'financial',
        'status' => 'active'
    ]
]);

echo "SUCCESS: Financial Manager created for hostel: " . $hostel->name . "\n";
echo "Email: " . $email . "\n";
echo "Password: " . $password . "\n";
