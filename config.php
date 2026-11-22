<?php

declare(strict_types=1);

/**
 * Завантажує налаштування з локального .env.
 *
 * @return array<string, string>
 */
function loadAppConfig(): array
{
    $envPath = __DIR__ . DIRECTORY_SEPARATOR . '.env';
    $requiredKeys = ['GEOAPIFY_API_KEY'];

    if (!is_file($envPath) || !is_readable($envPath)) {
        throw new RuntimeException(
            'Файл конфігурації .env недоступний. Створіть .env на основі .env.example та заповніть потрібні значення локально.'
        );
    }

    $config = parse_ini_file($envPath, false, INI_SCANNER_RAW);

    if ($config === false || !is_array($config)) {
        throw new RuntimeException(
            'Не вдалося прочитати конфігурацію .env. Перевірте формат файлу локально.'
        );
    }

    $settings = [];

    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $config)) {
            throw new RuntimeException(
                'Відсутнє обов\'язкове налаштування: ' . $key . '. Перевірте .env на основі .env.example.'
            );
        }

        $value = $config[$key];

        if (!is_string($value)) {
            throw new RuntimeException(
                'Некоректне значення налаштування: ' . $key . '.'
            );
        }

        $value = trim($value);

        if ($value === '' || $value === 'your_geoapify_api_key_here') {
            throw new RuntimeException(
                'Налаштування ' . $key . ' не заповнене. Додайте локальне значення в .env.'
            );
        }

        $settings[$key] = $value;
    }

    return $settings;
}

return loadAppConfig();