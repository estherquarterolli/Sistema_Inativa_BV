<?php

namespace App\Core;

final class Storage
{
    public static function isVercel(): bool
    {
        return getenv('VERCEL') !== false;
    }

    public static function uploadPath(string $prefix, string $originalName): string
    {
        $safeName = preg_replace('/[^\p{L}\p{N}._-]+/u', '_', basename($originalName)) ?: 'arquivo';

        return self::directory('uploads')
            . DIRECTORY_SEPARATOR
            . $prefix . bin2hex(random_bytes(8)) . '_' . $safeName;
    }

    public static function outputPath(string $token): string
    {
        return self::directory('output') . DIRECTORY_SEPARATOR . $token . '.xlsx';
    }

    private static function directory(string $name): string
    {
        $base = self::isVercel()
            ? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sistema-inativa-bv'
            : dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage';

        $directory = $base . DIRECTORY_SEPARATOR . $name;

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new \RuntimeException('Não foi possível preparar o armazenamento temporário.');
        }

        return $directory;
    }
}
