<?php

require_once __DIR__ . '/smartbot/load_env.php';
load_env();

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$utilizator = $_ENV['MONITOR_USER'] ?? 'admin';
$parola_corecta = $_ENV['MONITOR_PASS'] ?? 'parola_schimba_urgenta';

if (
    !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $utilizator ||
    $_SERVER['PHP_AUTH_PW'] !== $parola_corecta
) {
    header('WWW-Authenticate: Basic realm="Monitor SmartBot"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Acces restricționat.';
    exit;
}

$loguri = [
  'Leaduri completate'      => 'smartbot/data/formulare/completate/',
  'IP blocați (formular)'   => 'smartbot/data/formulare/ip_blocati_formular.txt',
  'Log ratelimit formular'  => 'smartbot/data/formulare/ratelimit_*.log',
  'Conversații salvate'     => 'smartbot/data/conversatii/',
  'IP blocați GPT'          => 'smartbot/data/gpt_logs/ip_blocati.txt',
  'Erori API GPT'           => 'smartbot/data/gpt_logs/gpt_api_errors.log',
  'Evenimente Chatbot'      => 'smartbot/data/logs/chatbot_events.log',
  'Backupuri zip (Exemplu)' => 'smartbot/backups/backup_logs_*.zip' 
];

function countLines($filePath) {
    if (!file_exists($filePath) || !is_readable($filePath)) return 0;
    $linecount = 0;
    $handle = fopen($filePath, "r");
    if ($handle) {
        while(!feof($handle)){
          $line = fgets($handle);
          if ($line !== false) {
            $linecount++;
          }
        }
        fclose($handle);
    }
    return $linecount;
}

function safePath($base, $userPath) {
    $realBase = realpath($base);
    $realUserPath = realpath($userPath);
    if ($realUserPath === false || $realBase === false) {
        return false; 
    }
    if (strpos($realUserPath, $realBase) === 0) {
        return $realUserPath;
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Monitor SmartBot</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f9fc; font-size: 0.9rem; }
    .card-header { background: #4a90e2; color: #fff; }
    .btn-sm { font-size: 0.8rem; }
    .table th, .table td { vertical-align: middle; }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">📊 Monitorizare SmartBot</h2>
    <span class="badge bg-secondary">Actualizat: <?php echo date('d.m.Y H:i:s'); ?></span>
  </div>

  <?php foreach ($loguri as $titlu => $relPath): ?>
    <div class="card mb-4">
      <div class="card-header">
        <?= htmlspecialchars($titlu) ?>
      </div>
      <div class="card-body">
        <?php
        $absPathPattern = __DIR__ . '/' . $relPath;
        $listaFisiere = [];

        if (substr($relPath, -1) === '/') {
            if (is_dir(rtrim($absPathPattern, '*'))) {
                 $filesInDir = glob(rtrim($absPathPattern, '/') . '/*');
                 if ($filesInDir) {
                     $listaFisiere = array_merge($listaFisiere, $filesInDir);
                 }
            }
        } elseif (strpos($relPath, '*') !== false) {
            $foundFiles = glob($absPathPattern);
            if ($foundFiles) {
                $listaFisiere = array_merge($listaFisiere, $foundFiles);
            }
        } else {
            if (file_exists($absPathPattern)) {
                $listaFisiere[] = $absPathPattern;
            }
        }
        
        if (substr($relPath, -1) === '/' && !empty($listaFisiere)) {
            usort($listaFisiere, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });
        }


        if (empty($listaFisiere)) {
            echo '<p class="text-muted mb-0">Niciun fișier găsit.</p>';
        } else {
            echo '<div class="table-responsive">';
            echo '<table class="table table-striped table-hover table-sm mb-0">';
            echo '<thead class="table-light"><tr><th>Fișier / Director</th><th>Linii / Elemente</th><th>Ultima Modificare</th><th>Dimensiune</th><th>Acțiune</th></tr></thead><tbody>';

            foreach ($listaFisiere as $caleCompletaFisier) {
                $fisier_safe = safePath(__DIR__, $caleCompletaFisier);
                if (!$fisier_safe) continue;

                $basename = basename($fisier_safe);
                $statistici = '-';
                $dimensiune = '-';

                if (is_dir($fisier_safe)) {
                    $elemente = glob($fisier_safe . '/*');
                    $statistici = count($elemente) . ' elemente';
                    $dimensiune = '-';
                } elseif (is_file($fisier_safe)) {
                    $statistici = countLines($fisier_safe) . ' linii';
                    $dimensiune = round(filesize($fisier_safe) / 1024, 2) . ' KB';
                }
                
                $ultimaModificare = date("d.m.Y H:i:s", filemtime($fisier_safe));
                $href = str_replace(__DIR__ . '/', '', $fisier_safe);

                echo "<tr>
                        <td>" . htmlspecialchars($basename) . "</td>
                        <td>" . htmlspecialchars($statistici) . "</td>
                        <td>" . htmlspecialchars($ultimaModificare) . "</td>
                        <td>" . htmlspecialchars($dimensiune) . "</td>
                        <td>";
                if (is_file($fisier_safe)) {
                     echo "<a class='btn btn-sm btn-outline-primary' href='" . htmlspecialchars($href) . "' target='_blank'>Vizualizează</a>";
                } else {
                     echo "<span class='text-muted'>Director</span>";
                }
                echo "</td></tr>";
            }
            echo '</tbody></table>';
            echo '</div>';
        }
        ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>