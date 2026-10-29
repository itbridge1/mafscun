<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    putenv('GDFONTPATH=' . realpath('public/fonts'));
    $img = \Image::canvas(800, 600, '#ffffff');
    // For GDFONTPATH to work, we must just pass the font name without the .ttf extension sometimes, or just the basename.
    // But let's see if setting it fixes the absolute path issue first.
    $fontPath = realpath(public_path('fonts/Roboto-Regular.ttf'));
    
    echo "Font path: " . $fontPath . "\n";
    $img->text('Test', 100, 100, function($font) use ($fontPath) {
        $font->file($fontPath);
        $font->size(24);
        $font->color('#000000');
    });
    echo "Absolute path test: Success\n";
    
} catch (\Exception $e) {
    echo "Absolute path Exception: " . $e->getMessage() . "\n";
    
    try {
        $img = \Image::canvas(800, 600, '#ffffff');
        $img->text('Test', 100, 100, function($font) {
            $font->file('Roboto-Regular.ttf');
            $font->size(24);
            $font->color('#000000');
        });
        echo "Relative path test: Success\n";
    } catch (\Exception $e2) {
        echo "Relative path Exception: " . $e2->getMessage() . "\n";
    }
}
