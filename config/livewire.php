<?php
// Hanya mengubah batas upload sementara (default Livewire 12 MB) agar admin bisa
// mengunggah video besar yang kemudian dipotong otomatis ke 50 MB.
return [
    'temporary_file_upload' => [
        'disk' => null,
        'rules' => ['required', 'file', 'max:'.((int) env('WARMAD_VIDEO_UPLOAD_MAX_MB', 500) * 1024)],
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 30,
        'cleanup' => true,
    ],
];
