<?php

function load_env(string $path = __DIR__ . '/.env'): void
{
  if (!file_exists($path)) return;

  $linii = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  foreach ($linii as $linie) {
    $linie = trim($linie);
    if ($linie === '' || str_starts_with($linie, '#') || !str_contains($linie, '=')) continue;

    [$cheie, $valoare] = explode('=', $linie, 2);
    $cheie = trim($cheie);
    $valoare = trim($valoare, " \t\n\r\0\x0B\"'");

    if (!preg_match('/^[A-Z0-9_]+$/', $cheie)) continue;
    if (!array_key_exists($cheie, $_ENV)) {
      $_ENV[$cheie] = $valoare;
    }
  }

  $localPath = dirname($path) . '/.env.local';
  if (file_exists($localPath)) {
    load_env($localPath);
  }
}
?>