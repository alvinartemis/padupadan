<?php

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $decimals = 2) {
        if ($bytes === 0) return '0 Bytes';

        $k = 1024;
        $dm = ($decimals < 0) ? 0 : $decimals;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

        $i = floor(log($bytes, $k));

        return number_format($bytes / pow($k, $i), $dm) . ' ' . $sizes[$i];
    }
}
