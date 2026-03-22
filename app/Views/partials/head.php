<!DOCTYPE html>

<html class="light" lang="en">

<head>
  <link rel="icon" href="data:,">
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? '') ?>" />
  <title>
    <?= htmlspecialchars($page_title ?? 'Cafeteria')?>
  </title>
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap"
    rel="stylesheet" />
  <!-- Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "on-surface": "#1b1c1a",
            "on-background": "#1b1c1a",
            "on-secondary-fixed-variant": "#5f402a",
            "surface-bright": "#faf9f5",
            "surface-variant": "#e3e2df",
            "on-tertiary-fixed-variant": "#5c4300",
            "error-container": "#ffdad6",
            "surface-tint": "#725a42",
            "surface-container-high": "#e9e8e4",
            "secondary-fixed-dim": "#eabda0",
            "surface-container-low": "#f4f4f0",
            "primary": "#33210d",
            "tertiary-container": "#4c3700",
            "error": "#ba1a1a",
            "primary-container": "#4b3621",
            "surface-dim": "#dbdad6",
            "surface-container-lowest": "#ffffff",
            "surface-container": "#efeeea",
            "on-tertiary": "#ffffff",
            "tertiary": "#312200",
            "secondary-container": "#ffd1b3",
            "surface": "#faf9f5",
            "on-error-container": "#93000a",
            "inverse-on-surface": "#f2f1ed",
            "primary-fixed-dim": "#e1c1a4",
            "on-tertiary-fixed": "#261a00",
            "surface-container-highest": "#e3e2df",
            "on-tertiary-container": "#d19c00",
            "tertiary-fixed-dim": "#fbbc00",
            "on-primary": "#ffffff",
            "on-secondary": "#ffffff",
            "secondary": "#79573f",
            "outline-variant": "#d2c4ba",
            "on-primary-fixed": "#291806",
            "on-secondary-container": "#7a5840",
            "on-surface-variant": "#4e453d",
            "background": "#faf9f5",
            "on-secondary-fixed": "#2d1604",
            "on-error": "#ffffff",
            "secondary-fixed": "#ffdcc6",
            "on-primary-fixed-variant": "#59422c",
            "on-primary-container": "#bd9f83",
            "inverse-primary": "#e1c1a4",
            "primary-fixed": "#fedcbe",
            "inverse-surface": "#2f312e",
            "outline": "#80756c",
            "tertiary-fixed": "#ffdfa0",
            "coffee-brown": "#6F4E37"
          },
          fontFamily: {
            "headline": ["Manrope"],
            "body": ["Inter"],
            "label": ["Inter"]
          },
          borderRadius: { "DEFAULT": "0.5rem", "lg": "0.5rem", "xl": "1.5rem", "full": "9999px" },
        },
      },
    }
  </script>
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }

    .no-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }

    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    .glass-panel {
      background: rgba(244, 244, 240, 0.8);
      backdrop-filter: blur(24px);
    }

    .bg-primary-gradient {
      background: linear-gradient(135deg, #33210d 0%, #4b3621 100%);
    }

    .editorial-shadow {
      box-shadow: 0px 12px 32px rgba(41, 24, 6, 0.08);
    }

    .primary-gradient {
      background: linear-gradient(135deg, #33210d 0%, #4b3621 100%);
    }
  </style>
</head>