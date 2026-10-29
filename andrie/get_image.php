<?php
/**
 * Image Delivery & Auto-Sync Script
 * Handles profile and project image previews for Sean Andrie Cubio's portfolio
 */

$assets_dir = __DIR__ . '/assets/images';
if (!is_dir($assets_dir)) {
    @mkdir($assets_dir, 0777, true);
}

$image_registry = [
    'profile' => [
        'source' => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/b24e7995-1cee-479e-95d2-2e3c6f14f9a9/.user_uploaded/media_1792758513313.png',
        'target' => $assets_dir . '/profile.png',
        'mime'   => 'image/png'
    ],
    'tapseafo' => [
        'source' => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/b24e7995-1cee-479e-95d2-2e3c6f14f9a9/tapseafo_preview_1792758023433.jpg',
        'target' => $assets_dir . '/tapseafo.jpg',
        'mime'   => 'image/jpeg'
    ],
    'hotel_booking' => [
        'source' => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/b24e7995-1cee-479e-95d2-2e3c6f14f9a9/hotel_booking_preview_1792758047169.jpg',
        'target' => $assets_dir . '/hotel_booking.jpg',
        'mime'   => 'image/jpeg'
    ],
    'arduino_dispenser' => [
        'source' => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/b24e7995-1cee-479e-95d2-2e3c6f14f9a9/arduino_dispenser_preview_1792758144860.jpg',
        'target' => $assets_dir . '/arduino_dispenser.jpg',
        'mime'   => 'image/jpeg'
    ],
    'resume' => [
        'source' => file_exists('C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793197474146.png')
                    ? 'C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793197474146.png'
                    : (file_exists('C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793197228911.png')
                        ? 'C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793197228911.png'
                        : 'C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793195555550.png'),
        'target' => $assets_dir . '/resume.png',
        'mime'   => 'image/png'
    ],
    'interview_poster' => [
        'source' => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/interview_poster_1793198684346.jpg',
        'target' => $assets_dir . '/interview_poster.jpg',
        'mime'   => 'image/jpeg'
    ]
];

// Synchronize all images to assets/images if modified or missing
foreach ($image_registry as $key => $item) {
    if (file_exists($item['source'])) {
        if (!file_exists($item['target']) || filesize($item['target']) !== filesize($item['source'])) {
            @copy($item['source'], $item['target']);
        }
    }
}

// Request dispatcher
$req = isset($_GET['img']) ? strtolower(trim($_GET['img'])) : (isset($_GET['name']) ? strtolower(trim($_GET['name'])) : 'profile');

if (isset($image_registry[$req])) {
    $info = $image_registry[$req];
    $serve = (file_exists($info['target']) && filesize($info['target']) === filesize($info['source'])) 
             ? $info['target'] 
             : (file_exists($info['source']) ? $info['source'] : (file_exists($info['target']) ? $info['target'] : null));
    
    if ($serve && file_exists($serve)) {
        header('Content-Type: ' . $info['mime']);
        header('Content-Length: ' . filesize($serve));
        header('Cache-Control: no-cache, must-revalidate');
        readfile($serve);
        exit;
    }
}

// Transparent fallback
header('Content-Type: image/png');
echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
exit;
