<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>" />
  <title><?= htmlspecialchars($page_title ?? 'Cafeteria') ?></title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap"
    rel="stylesheet" />

  <!-- Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script id="tailwind-config">
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            "primary": "#33210d",
            "on-primary": "#ffffff",
            "primary-container": "#4b3621",
            "on-primary-container": "#bd9f83",
            "secondary": "#79573f",
            "on-secondary": "#ffffff",
            "secondary-container": "#ffd1b3",
            "on-secondary-container": "#7a5840",
            "tertiary": "#312200",
            "on-tertiary": "#ffffff",
            "error": "#ba1a1a",
            "on-error": "#ffffff",
            "error-container": "#ffdad6",
            "on-error-container": "#93000a",
            "background": "#faf9f5",
            "on-background": "#1b1c1a",
            "surface": "#faf9f5",
            "on-surface": "#1b1c1a",
            "on-surface-variant": "#4e453d",
            "outline": "#80756c",
            "outline-variant": "#d2c4ba",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f4f4f0",
            "surface-container": "#efeeea",
            "surface-container-high": "#e9e8e4",
            "surface-container-highest": "#e3e2df",
            "surface-dim": "#dbdad6",
            "surface-bright": "#faf9f5",
            "inverse-surface": "#2f312e",
            "inverse-on-surface": "#f2f1ed",
            "inverse-primary": "#e1c1a4",
            "tertiary-container": "#f5deb3",
            "on-tertiary-container": "#312200",
            "success": "#16a34a",
            "on-success": "#ffffff",
            "success-container": "#dcfce7",
          },
          fontFamily: {
            "headline": ["Manrope", "sans-serif"],
            "body": ["Inter", "sans-serif"],
          },
          borderRadius: {
            "DEFAULT": "0.5rem",
            "lg": "0.5rem",
            "xl": "1.5rem",
          },
        },
      },
    }
  </script>

  <link rel="stylesheet"
    href="<?= (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . '/assets/css/components.css' ?>" />

  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .glass-panel {
      background: rgba(244, 244, 240, 0.85);
      backdrop-filter: blur(16px);
    }

    .editorial-shadow {
      box-shadow: 0 8px 24px rgba(41, 24, 6, 0.06);
    }
  </style>
</head>