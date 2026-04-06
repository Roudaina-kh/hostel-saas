<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$out = "=== OWNERS ===\n";
foreach (\App\Models\Owner::all() as $owner) {
    $out .= $owner->email . " (status: " . $owner->status . ")\n";
}

$out .= "\n=== USERS ===\n";
foreach (\App\Models\User::all() as $user) {
    $out .= $user->email . " (status: " . $user->status . ")\n";
}

$out .= "\n=== SUPER ADMINS ===\n";
foreach (\App\Models\SuperAdmin::all() as $sa) {
    $out .= $sa->email . " (is_active: " . $sa->is_active . ")\n";
}

file_put_contents('c:/Users/khrij/hostel-saas/dump.txt', $out);
