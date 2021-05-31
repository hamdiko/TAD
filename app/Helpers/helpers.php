<?php

if (!function_exists('extractColumnToKey')) {
    function extractColumnToKey(array $data, string $columnToExtract): array
    {
        if (empty($data) || !is_array($data)) {
            return $data;
        }

        return array_combine(array_column($data, $columnToExtract), $data);
    }
}

if (!function_exists('generateClientUrl')) {
    function generateClientUrl(string $target) : string
    {
        return config('app.client_url') . config("client-app.routes.{$target}");
    }
}
