<?php
return [
    // Nomor WhatsApp admin (format 62..., tanpa +). Bisa diganti lewat .env: WARMAD_ADMIN_WA=62xxxxxxxxxx
    'admin_wa' => env('WARMAD_ADMIN_WA', '62895630447306'),

    // Video: batas yang TAMPIL/DISIMPAN (MB). Video lebih besar dari ini otomatis dipotong dari awal.
    'video_max_mb' => (int) env('WARMAD_VIDEO_MAX_MB', 50),
    // Batas file yang boleh DIUNGGAH admin sebelum dipotong (MB).
    'video_upload_max_mb' => (int) env('WARMAD_VIDEO_UPLOAD_MAX_MB', 500),
    // Lokasi ffmpeg/ffprobe (cukup "ffmpeg" jika sudah ada di PATH).
    'ffmpeg' => env('FFMPEG_BINARY', 'ffmpeg'),
    'ffprobe' => env('FFPROBE_BINARY', 'ffprobe'),
];
