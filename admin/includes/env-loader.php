<?php
/**
 * Minimal .env loader — no Composer/vlucas dependency required.
 *
 * Loads KEY=VALUE pairs from a .env file into getenv()/$_ENV, skipping
 * blank lines and lines starting with '#'. Values can optionally be
 * wrapped in double quotes if they contain spaces or special characters.
 *
 * Example .env line:
 *   DB_PASSWORD="Some Value With Spaces"
 */
function loadEnv(string $path): void
{
    if (!is_readable($path)) {
        throw new RuntimeException(".env file not found or not readable at: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $i => $line) {
        // Strip a UTF-8 BOM if present on the very first line.
        if ($i === 0) {
            $line = preg_replace('/^\xEF\xBB\xBF/', '', $line);
        }

        // Strip any stray \r left over from Windows-style CRLF line endings
        // (FILE_IGNORE_NEW_LINES only strips \n, not \r).
        $line = rtrim($line, "\r\n \t");
        $line = trim($line);

        // Skip comments
        if ($line === '' || substr($line, 0, 1) === '#') {
            continue;
        }

        if (strpos($line, '=') === false) {
            continue;
        }

        $parts = explode('=', $line, 2);
        $name  = trim($parts[0]);
        $value = trim($parts[1]);

        // Strip surrounding quotes if present
        if (strlen($value) >= 2) {
            $first = $value[0];
            $last  = $value[strlen($value) - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }

        // Don't overwrite variables already set at the OS/webserver level
        if (getenv($name) === false) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}