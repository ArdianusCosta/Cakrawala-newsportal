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

    foreach ($lines as $line) {
        $line = trim($line);

        // Skip comments
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);
        $name  = trim($name);
        $value = trim($value);

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