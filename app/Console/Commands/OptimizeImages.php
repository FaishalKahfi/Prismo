<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--quality=80} {--path=public/images}';
    protected $description = 'Optimize images for web performance';

    public function handle()
    {
        $quality = $this->option('quality');
        $path = $this->option('path');

        if (!File::exists($path)) {
            $this->error("Path {$path} does not exist!");
            return 1;
        }

        $this->info("Optimizing images in {$path} with quality {$quality}%...");

        $extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $count = 0;
        $totalSaved = 0;

        foreach ($extensions as $ext) {
            $files = File::glob("{$path}/*.{$ext}");

            foreach ($files as $file) {
                $originalSize = File::size($file);

                try {
                    // Skip if already optimized
                    if (strpos($file, '-optimized') !== false) {
                        continue;
                    }

                    // Get image info
                    $imageInfo = @getimagesize($file);
                    if (!$imageInfo) {
                        continue;
                    }

                    $mimeType = $imageInfo['mime'];

                    // Load image based on type
                    switch ($mimeType) {
                        case 'image/jpeg':
                            $img = imagecreatefromjpeg($file);
                            break;
                        case 'image/png':
                            $img = imagecreatefrompng($file);
                            break;
                        case 'image/gif':
                            $img = imagecreatefromgif($file);
                            break;
                        default:
                            continue 2;
                    }

                    if (!$img) {
                        continue;
                    }

                    $width = imagesx($img);
                    $height = imagesy($img);

                    // Resize if too large (max 1920px width)
                    if ($width > 1920) {
                        $newWidth = 1920;
                        $newHeight = (int) ($height * ($newWidth / $width));

                        $newImg = imagecreatetruecolor($newWidth, $newHeight);

                        // Preserve transparency for PNG
                        if ($mimeType === 'image/png') {
                            imagealphablending($newImg, false);
                            imagesavealpha($newImg, true);
                        }

                        imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagedestroy($img);
                        $img = $newImg;
                    }

                    // Save optimized version
                    switch ($mimeType) {
                        case 'image/jpeg':
                            imagejpeg($img, $file, (int)$quality);
                            break;
                        case 'image/png':
                            // PNG quality is 0-9, convert from percentage
                            $pngQuality = (int) (9 - (($quality / 100) * 9));
                            imagepng($img, $file, $pngQuality);
                            break;
                        case 'image/gif':
                            imagegif($img, $file);
                            break;
                    }

                    imagedestroy($img);

                    $newSize = File::size($file);
                    $saved = $originalSize - $newSize;
                    $totalSaved += $saved;

                    $this->line("✓ " . basename($file) . " - Saved " . $this->formatBytes($saved));
                    $count++;

                } catch (\Exception $e) {
                    $this->error("✗ Failed to optimize " . basename($file) . ": " . $e->getMessage());
                }
            }
        }

        $this->info("\nOptimization complete!");
        $this->info("Files optimized: {$count}");
        $this->info("Total space saved: " . $this->formatBytes($totalSaved));

        return 0;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
