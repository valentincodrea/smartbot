<?php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmartBot – Asistent Auto în Rate</title>

  <meta name="description" content="SmartBot te ajută să cumperi mașina dorită în rate, rapid și fără bătăi de cap. Obține rapid informații despre credite, avans, rate și documente necesare.">
  <meta name="keywords" content="chatbot auto, credit auto, leasing, rate auto, SmartBot">

  <meta property="og:title" content="SmartBot – Asistent Auto în Rate">
  <meta property="og:description" content="SmartBot îți oferă rapid informații clare despre cum poți achiziționa o mașină în rate.">
  <meta property="og:type" content="website">
  <meta property="og:image" content="/smartbot/core/assets/img/avatar_bot.svg">
  <meta property="og:url" content="https://ADRESA_TA_WEB_AICI.ro">

  <link rel="icon" href="/smartbot/core/assets/img/avatar_bot.svg" type="image/svg+xml">

<?php
    $customCssFilePath = __DIR__ . '/smartbot/core/assets/css/custom.css';
    $customCssWebPath = '/smartbot/core/assets/css/custom.css';
    if (file_exists($customCssFilePath)) {
        echo '  <link rel="stylesheet" href="' . htmlspecialchars($customCssWebPath) . '?v=' . filemtime($customCssFilePath) . '">' . "\n";
    }
?>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #f0f4f8, #dce6f2);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-10 text-center">

  <main class="w-full max-w-2xl space-y-6 animate-fadeIn">
    <img src="/smartbot/core/assets/img/avatar_bot.svg" alt="SmartBot" class="w-16 h-16 mx-auto drop-shadow" />

    <h1 class="text-3xl sm:text-4xl font-bold text-blue-700 leading-tight">
      SmartBot – Asistentul tău pentru Mașini în Rate
    </h1>

    <p class="text-gray-700 text-base sm:text-lg">
      Obține rapid informații despre <strong>rate</strong>, <strong>avans</strong>, <strong>documente necesare</strong> și <strong>finanțare auto</strong> fără stres.
    </p>

    <div class="text-sm text-gray-500">
      Apasă pe bulă în colțul din dreapta jos pentru a începe conversația cu SmartBot.
    </div>

    <button onclick="document.getElementById('chatbot-bubble')?.click()"
            class="bg-blue-600 text-white px-6 py-3 rounded-full shadow hover:bg-blue-700 transition">
      Deschide SmartBot
    </button>
  </main>

  <?php include __DIR__ . '/smartbot/init.php'; ?>
</body>
</html>