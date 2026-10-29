<?php
$file = 'app/Http/Controllers/Admin/EventController.php';
$content = file_get_contents($file);

// 1. Fix Image::make($bgUrl) deadlock and A4 resize
$search = <<<'SEARCH'
        // Use asset() for background image as requested, to support ASSET_URL in .env
        $bgUrl = asset('images/events/' . $event->certificate_bg_image);
        
        // Since asset() might be an external URL, file_exists won't work reliably without context or allow_url_fopen.
        // We will just let Intervention Image attempt to fetch it.
        $participants = \App\Models\EventBookingParticipant::with('booking', 'booking.cooperative')->whereIn('id', $participantIds)->get();

        $allSettings = $event->certificate_settings ?? [];
        if (empty($allSettings['with_designation'])) {
                // Fallback for old single-array structure
                $withDesignationSettings = $allSettings;
                $withoutDesignationSettings = array_filter($allSettings, function($f) { return $f['key'] !== 'participant_designation'; });
            } else {
                $withDesignationSettings = $allSettings['with_designation'] ?? [];
                $withoutDesignationSettings = $allSettings['without_designation'] ?? [];
            }

            foreach ($participants as $p) {
                $img = \Image::make($bgUrl);
                $width = $img->width();
                $height = $img->height();
SEARCH;

$replace = <<<'REPLACE'
        $localPath = public_path('images/events/' . $event->certificate_bg_image);
        $bgSource = file_exists($localPath) ? $localPath : asset('images/events/' . $event->certificate_bg_image);
        
        $participants = \App\Models\EventBookingParticipant::with('booking', 'booking.cooperative')->whereIn('id', $participantIds)->get();

        $allSettings = $event->certificate_settings ?? [];
        if (empty($allSettings['with_designation'])) {
                $withDesignationSettings = $allSettings;
                $withoutDesignationSettings = array_filter($allSettings, function($f) { return $f['key'] !== 'participant_designation'; });
        } else {
                $withDesignationSettings = $allSettings['with_designation'] ?? [];
                $withoutDesignationSettings = $allSettings['without_designation'] ?? [];
        }

        foreach ($participants as $p) {
            $savePath = $this->generateCertificateImage($event, $p, $bgSource, $withDesignationSettings, $withoutDesignationSettings, $allSettings);
REPLACE;
$content = str_replace($search, $replace, $content);

// 2. Remove the rest of the foreach loop block inside sendCertificates
$search2 = <<<'SEARCH'
                $eventDateFormatted = 'N/A';
                if ($event->start_date) {
                    if ($event->end_date && $event->start_date->format('Y-m-d') !== $event->end_date->format('Y-m-d')) {
                        $eventDateFormatted = $event->start_date->format('Y-m-d') . ' to ' . $event->end_date->format('Y-m-d');
                    } else {
                        $eventDateFormatted = $event->start_date->format('Y-m-d');
                    }
                }

                $dataMap = [
                    'participant_name' => $p->name,
                    'participant_designation' => $p->designation ?: '',
                    'cooperative_name' => $p->booking->is_member && $p->booking->cooperative ? $p->booking->cooperative->cooperative_name : ($p->booking->cooperative_name ?: 'N/A'),
                    'cooperative_address' => $p->booking->is_member && $p->booking->cooperative ? $p->booking->cooperative->address : 'N/A',
                    'event_name' => $event->title,
                    'event_location' => $event->google_map ? strip_tags($event->google_map) : 'N/A',
                    'event_start_date' => $eventDateFormatted,
                ];

                $isWithDesignation = !empty($p->designation);
                $settingsToUse = $isWithDesignation ? $withDesignationSettings : $withoutDesignationSettings;

                foreach ($settingsToUse as $setting) {
                if (isset($dataMap[$setting['key']])) {
                    $text = $dataMap[$setting['key']];
                    $x = ($setting['x'] / 100) * $width;
                    $y = ($setting['y'] / 100) * $height;
                    
                    // The UI editor font size is based on CSS pixels (e.g. 24px) for a 800px wide container
                    // We need to scale it relative to the actual image width.
                    $scaleFactor = $width / 800; // assuming the UI editor is scaled from an 800px base
                    $cssPixels = (isset($setting['size']) ? $setting['size'] : 24) * $scaleFactor;
                    
                    // Intervention Image with GD expects font size in POINTS, not Pixels. (1 Pixel = 0.75 Points)
                    $size = $cssPixels * 0.75;

                    $color = $setting['color'] ?? '#000000';
                    $globalFont = $allSettings['global_font'] ?? 'Roboto';
                    $fontFamily = $setting['font'] ?? 'global';
                    if ($fontFamily === 'global') {
                        $fontFamily = $globalFont;
                    }
                    $isBold = $setting['bold'] ?? false;
                    
                    $textTransform = $setting['text_transform'] ?? 'none';
                    if ($textTransform === 'uppercase') {
                        $text = mb_strtoupper($text, 'UTF-8');
                    } elseif ($textTransform === 'capitalize') {
                        $text = mb_convert_case($text, MB_CASE_TITLE, 'UTF-8');
                    } elseif ($textTransform === 'lowercase') {
                        $text = mb_strtolower($text, 'UTF-8');
                    }
                    $iconPref = $setting['icon'] ?? 'none';
                    
                    // First draw icon if any
                    if ($iconPref === 'calendar' || $iconPref === 'location') {
                        $iconChar = $iconPref === 'calendar' ? "\uf133" : "\uf3c5";
                        $faFontPath = realpath(public_path('backend/assets/plugins/fontawesome-free/webfonts/fa-solid-900.ttf'));
                        if ($faFontPath && file_exists($faFontPath)) {
                            $img->text($iconChar, $x, $y, function($font) use ($faFontPath, $size, $color) {
                                $font->file($faFontPath);
                                $font->size($size);
                                $font->color($color);
                                $font->align('left');
                                $font->valign('top');
                            });
                            // offset X for the main text. FontAwesome icons are roughly square.
                            // Convert back to pixels for layout offset calculation
                            $pixelSize = $size / 0.75;
                            $x += $pixelSize * 1.3; 
                        }
                    }

                    $fontFile = 'Roboto-Regular.ttf';
                    if ($fontFamily === 'Roboto' && $isBold) {
                        $fontFile = 'Roboto-Bold.ttf';
                    } elseif ($fontFamily === 'PlayfairDisplay') {
                        $fontFile = 'PlayfairDisplay-Regular.ttf';
                    } elseif ($fontFamily === 'GreatVibes') {
                        $fontFile = 'GreatVibes-Regular.ttf';
                    } elseif ($fontFamily === 'Montserrat') {
                        $fontFile = 'Montserrat-Regular.ttf';
                    } elseif ($fontFamily === 'OpenSans') {
                        $fontFile = 'OpenSans-Regular.ttf';
                    } elseif ($fontFamily === 'Oswald') {
                        $fontFile = 'Oswald-Regular.ttf';
                    } elseif ($fontFamily === 'DancingScript') {
                        $fontFile = 'DancingScript-Regular.ttf';
                    }
                    
                    $currentFontPath = realpath(public_path('fonts/' . $fontFile));
                    if (!$currentFontPath || !file_exists($currentFontPath)) {
                        $currentFontPath = realpath(public_path('fonts/Roboto-Regular.ttf'));
                    }

                    if (trim((string)$text) !== '') {
                        $img->text($text, $x, $y, function($font) use ($currentFontPath, $size, $color) {
                            $font->file($currentFontPath);
                            $font->size($size);
                            $font->color($color);
                            $font->align('left');
                            $font->valign('top');
                        });
                    }
                }
            }

            // Save temporarily at 100% HD Quality
            $fileName = 'certificate_' . $p->id . '_' . time() . '.jpg';
            $savePath = storage_path('app/public/' . $fileName);
            $img->save($savePath, 100);
SEARCH;

$content = str_replace($search2, "", $content);

// 3. Inject new methods at the end of class
$newMethods = <<<'METHODS'
    public function downloadCertificate($eventId, $participantId)
    {
        $event = $this->model->findOrFail($eventId);
        if (!$event->certificate_bg_image || empty($event->certificate_settings)) {
            return redirect()->back()->with('error_message', 'Certificate template is not configured properly.');
        }

        $localPath = public_path('images/events/' . $event->certificate_bg_image);
        $bgSource = file_exists($localPath) ? $localPath : asset('images/events/' . $event->certificate_bg_image);
        
        $p = \App\Models\EventBookingParticipant::with('booking', 'booking.cooperative')->findOrFail($participantId);

        $allSettings = $event->certificate_settings ?? [];
        if (empty($allSettings['with_designation'])) {
            $withDesignationSettings = $allSettings;
            $withoutDesignationSettings = array_filter($allSettings, function($f) { return $f['key'] !== 'participant_designation'; });
        } else {
            $withDesignationSettings = $allSettings['with_designation'] ?? [];
            $withoutDesignationSettings = $allSettings['without_designation'] ?? [];
        }

        $savePath = $this->generateCertificateImage($event, $p, $bgSource, $withDesignationSettings, $withoutDesignationSettings, $allSettings);
        
        return response()->download($savePath)->deleteFileAfterSend(true);
    }

    private function generateCertificateImage($event, $p, $bgSource, $withDesignationSettings, $withoutDesignationSettings, $allSettings)
    {
        $img = \Image::make($bgSource);
        
        $orientation = $event->certificate_orientation ?? 'horizontal';
        $targetWidth = $orientation === 'horizontal' ? 3508 : 2480;
        $targetHeight = $orientation === 'horizontal' ? 2480 : 3508;
        
        $img->resize($targetWidth, $targetHeight);
        
        $width = $img->width();
        $height = $img->height();

        $eventDateFormatted = 'N/A';
        if ($event->start_date) {
            if ($event->end_date && $event->start_date->format('Y-m-d') !== $event->end_date->format('Y-m-d')) {
                $eventDateFormatted = $event->start_date->format('Y-m-d') . ' to ' . $event->end_date->format('Y-m-d');
            } else {
                $eventDateFormatted = $event->start_date->format('Y-m-d');
            }
        }

        $dataMap = [
            'participant_name' => $p->name,
            'participant_designation' => $p->designation ?: '',
            'cooperative_name' => $p->booking->is_member && $p->booking->cooperative ? $p->booking->cooperative->cooperative_name : ($p->booking->cooperative_name ?: 'N/A'),
            'cooperative_address' => $p->booking->is_member && $p->booking->cooperative ? $p->booking->cooperative->address : 'N/A',
            'event_name' => $event->title,
            'event_location' => $event->google_map ? strip_tags($event->google_map) : 'N/A',
            'event_start_date' => $eventDateFormatted,
        ];

        $isWithDesignation = !empty($p->designation);
        $settingsToUse = $isWithDesignation ? $withDesignationSettings : $withoutDesignationSettings;

        foreach ($settingsToUse as $setting) {
            if (isset($dataMap[$setting['key']])) {
                $text = $dataMap[$setting['key']];
                $x = ($setting['x'] / 100) * $width;
                $y = ($setting['y'] / 100) * $height;
                
                $scaleFactor = $width / 800;
                $cssPixels = (isset($setting['size']) ? $setting['size'] : 24) * $scaleFactor;
                $size = $cssPixels * 0.75;

                $color = $setting['color'] ?? '#000000';
                $globalFont = $allSettings['global_font'] ?? 'Roboto';
                $fontFamily = $setting['font'] ?? 'global';
                if ($fontFamily === 'global') {
                    $fontFamily = $globalFont;
                }
                $isBold = $setting['bold'] ?? false;
                
                $textTransform = $setting['text_transform'] ?? 'none';
                if ($textTransform === 'uppercase') {
                    $text = mb_strtoupper($text, 'UTF-8');
                } elseif ($textTransform === 'capitalize') {
                    $text = mb_convert_case($text, MB_CASE_TITLE, 'UTF-8');
                } elseif ($textTransform === 'lowercase') {
                    $text = mb_strtolower($text, 'UTF-8');
                }
                $iconPref = $setting['icon'] ?? 'none';
                
                if ($iconPref === 'calendar' || $iconPref === 'location') {
                    $iconChar = $iconPref === 'calendar' ? "\uf133" : "\uf3c5";
                    $faFontPath = realpath(public_path('backend/assets/plugins/fontawesome-free/webfonts/fa-solid-900.ttf'));
                    if ($faFontPath && file_exists($faFontPath)) {
                        $iconY = $y + ($size * 0.05); 
                        $img->text($iconChar, $x, $iconY, function($font) use ($faFontPath, $size, $color) {
                            $font->file($faFontPath);
                            $font->size($size);
                            $font->color($color);
                            $font->align('left');
                            $font->valign('top');
                        });
                        $pixelSize = $size / 0.75;
                        $iconWidthMultiplier = $iconPref === 'calendar' ? 1.05 : 0.85;
                        $x += $pixelSize * $iconWidthMultiplier; 
                    }
                }

                $fontFile = 'Roboto-Regular.ttf';
                if ($fontFamily === 'Roboto' && $isBold) {
                    $fontFile = 'Roboto-Bold.ttf';
                } elseif ($fontFamily === 'PlayfairDisplay') {
                    $fontFile = 'PlayfairDisplay-Regular.ttf';
                } elseif ($fontFamily === 'GreatVibes') {
                    $fontFile = 'GreatVibes-Regular.ttf';
                } elseif ($fontFamily === 'Montserrat') {
                    $fontFile = 'Montserrat-Regular.ttf';
                } elseif ($fontFamily === 'OpenSans') {
                    $fontFile = 'OpenSans-Regular.ttf';
                } elseif ($fontFamily === 'Oswald') {
                    $fontFile = 'Oswald-Regular.ttf';
                } elseif ($fontFamily === 'DancingScript') {
                    $fontFile = 'DancingScript-Regular.ttf';
                }
                
                $currentFontPath = realpath(public_path('fonts/' . $fontFile));
                if (!$currentFontPath || !file_exists($currentFontPath)) {
                    $currentFontPath = realpath(public_path('fonts/Roboto-Regular.ttf'));
                }

                if (trim((string)$text) !== '') {
                    $img->text($text, $x, $y, function($font) use ($currentFontPath, $size, $color) {
                        $font->file($currentFontPath);
                        $font->size($size);
                        $font->color($color);
                        $font->align('left');
                        $font->valign('top');
                    });
                }
            }
        }

        $fileName = 'certificate_' . $p->id . '_' . time() . '.jpg';
        $savePath = storage_path('app/public/' . $fileName);
        $img->save($savePath, 100);
        
        return $savePath;
    }
}
METHODS;

$content = str_replace("}\n", "}\n\n" . $newMethods, $content);
// We replace the last closing bracket of the class with the new methods + closing bracket
$content = preg_replace('/}([\s]*)$/', "\n" . $newMethods . "$1", $content);

file_put_contents($file, $content);
?>
