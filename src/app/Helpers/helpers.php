<?php

if (!function_exists('embedImageAsBase64')) {
    function embedImageAsBase64(string $path): string
    {
        if (!file_exists($path)) {
            return ''; // ou talvez uma imagem padrão
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = base64_encode($data);

        return "data:image/{$type};base64,{$base64}";
    }
}
