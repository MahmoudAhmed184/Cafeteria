<header class="bg-surface sticky top-0 z-50 w-full border-b border-outline-variant/40">
   <div class="flex items-center justify-between mx-auto max-w-[1400px] px-6 py-3 lg:px-8">
      <!-- Left: brand + nav -->
      <div class="flex items-center gap-8">
         <a href="<?= htmlspecialchars(defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/dashboard' : '/dashboard') ?>"
            class="text-xl font-bold text-primary font-headline tracking-tight">
            Cafeteria
         </a>
         <nav class="hidden md:flex items-center gap-1 font-body" aria-label="Main navigation">
            <?php $p = $active_page ?? ''; ?>
            <?php foreach ([
               'home' => ['name' => 'Home', 'url' => '/dashboard'],
               'orders' => ['name' => 'My Orders', 'url' => '/orders'],
            ] as $k => $v): ?>
               <a href="<?= htmlspecialchars(defined('BASE_URL') ? rtrim(BASE_URL, '/') . $v['url'] : $v['url']) ?>" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 <?= $p === $k
                          ? 'bg-primary text-on-primary'
                          : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' ?>">
                  <?= htmlspecialchars($v['name']) ?>
               </a>
               <?php
            endforeach; ?>
         </nav>
      </div>

      <!-- Right: actions + profile -->
      <div class="flex items-center gap-2">
         <button type="button"
            class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high transition-colors duration-150"
            aria-label="Notifications">
            <span class="material-symbols-outlined text-[20px]">notifications</span>
         </button>
         <button type="button"
            class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high transition-colors duration-150"
            aria-label="Shopping cart" data-icon="shopping_cart">
            <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
         </button>

         <!-- Profile dropdown -->
         <div class="relative ml-2" id="user-profile-dropdown-wrapper">
            <button type="button" id="user-profile-toggle"
               class="flex items-center gap-2 pl-3 ml-1 border-l border-outline-variant/50 cursor-pointer hover:opacity-80 transition-opacity"
               aria-haspopup="true" aria-expanded="false" aria-label="User menu">
               <span class="hidden sm:inline text-sm font-semibold text-primary">
                  <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
               </span>
               <img alt="Profile picture" class="w-8 h-8 rounded-full border border-outline-variant/30 object-cover"
                  src="<?= !empty($_SESSION['profile_pic'])
                     ? ((strpos($_SESSION['profile_pic'], 'http') === 0) ? $_SESSION['profile_pic'] : '/uploads/' . $_SESSION['profile_pic'])
                     : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name'] ?? 'U') . '&background=e3e2df&color=33210d&size=64' ?>" />
            </button>

            <div id="user-profile-menu"
               class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-lg border border-outline-variant/30 py-1 z-50"
               role="menu" aria-label="User actions">
               <div class="px-4 py-2.5 border-b border-outline-variant/20">
                  <p class="text-sm font-semibold text-primary">
                     <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                  </p>
               </div>
               <a href="<?= htmlspecialchars(defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/logout' : '/logout') ?>"
                  class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-error font-medium hover:bg-error-container/20 transition-colors"
                  role="menuitem">
                  <span class="material-symbols-outlined text-[18px]">logout</span>
                  Logout
               </a>
            </div>
         </div>
      </div>
   </div>
</header>

<script>
   (function () {
      var toggle = document.getElementById('user-profile-toggle');
      var menu = document.getElementById('user-profile-menu');
      if (!toggle || !menu) return;
      toggle.addEventListener('click', function (e) {
         e.stopPropagation();
         var open = menu.classList.toggle('hidden');
         toggle.setAttribute('aria-expanded', !open);
      });
      document.addEventListener('click', function () {
         menu.classList.add('hidden');
         toggle.setAttribute('aria-expanded', 'false');
      });
      document.addEventListener('keydown', function (e) {
         if (e.key === 'Escape') {
            menu.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
         }
      });
   })();
</script>