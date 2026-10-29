<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Admin;

$admin = Admin::where('email', 'dev@bagmati.com')->first();
echo "Admin found: " . ($admin ? 'yes' : 'no') . "\n";

$user = User::where('email', 'sesemi0129@gmail.com')->first();
echo "User found: " . ($user ? 'yes' : 'no') . "\n";
