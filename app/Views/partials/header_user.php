<body class="bg-surface text-on-surface">
<header class="fixed top-0 w-full z-50 bg-[#faf9f5] dark:bg-stone-950">
<div class="flex justify-between items-center w-full px-8 py-4 max-w-[1440px] mx-auto">
<div class="flex items-center gap-8">
<span class="text-2xl font-black text-[#33210d] dark:text-[#faf9f5] tracking-tighter italic brand-font">Cafeteria</span>
<nav class="hidden md:flex gap-6 font-['Manrope'] font-bold tracking-tight text-lg">
<?php $p = $active_page ?? ''; ?>
<?php foreach ([
'home' => ['name' => 'Home', 'url' => '/dashboard'],
'orders' => ['name' => 'My Orders', 'url' => '/orders'],
] as $k => $v): ?>
<a class="<?= $p === $k ? 'text-[#33210d] dark:text-[#faf9f5] border-b-2 border-[#33210d] dark:border-[#faf9f5] pb-1 font-bold' : 'text-[#4B3621]/70 dark:text-[#d2c4ba]/70 hover:text-[#33210d] dark:hover:text-[#faf9f5] transition-colors'?>" href="<?= htmlspecialchars(defined('BASE_URL') ? rtrim(BASE_URL, '/') . $v['url'] : $v['url'])?>"><?= htmlspecialchars($v['name'])?></a>
<?php
endforeach; ?>
</nav>
</div>
<div class="flex items-center gap-4">
<!-- FIX: BUG_03 — user profile dropdown with real name and logout -->
<div class="relative" id="user-profile-dropdown-wrapper">
<button type="button" id="user-profile-toggle" class="flex items-center gap-3 bg-surface-container-low px-4 py-2 rounded-full cursor-pointer hover:bg-surface-container transition-all">
<span class="text-sm font-bold text-primary"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User')?></span>
<img alt="User Profile Avatar" class="w-8 h-8 rounded-full border border-outline-variant" src="<?= !empty($_SESSION['profile_pic']) ? ( (strpos($_SESSION['profile_pic'], 'http') === 0) ? $_SESSION['profile_pic'] : '/uploads/' . $_SESSION['profile_pic'] ) : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name'] ?? 'U') . '&background=e3e2df&color=33210d' ?>"/>
</button>
<div id="user-profile-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-surface-container-lowest rounded-xl shadow-[0px_12px_32px_rgba(41,24,6,0.12)] border border-outline-variant/20 py-2 z-50">
<div class="px-4 py-3 border-b border-outline-variant/10">
<p class="text-sm font-bold text-primary"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></p>
</div>
<a href="<?= htmlspecialchars(defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/logout' : '/logout') ?>"
   class="flex items-center gap-3 px-4 py-3 text-sm text-error font-bold hover:bg-error-container/30 transition-colors">
<span class="material-symbols-outlined text-sm">logout</span>
Logout
</a>
</div>
</div>
<div class="flex gap-2">
<span class="material-symbols-outlined text-primary p-2 hover:bg-surface-container rounded-full transition-all cursor-pointer" data-icon="notifications">notifications</span>
<span class="material-symbols-outlined text-primary p-2 hover:bg-surface-container rounded-full transition-all cursor-pointer" data-icon="shopping_cart">shopping_cart</span>
</div>
</div>
</div>
<div class="bg-[#e3e2df] dark:bg-stone-800 h-[1px] w-full"></div>
</header>
<!-- FIX: BUG_03 — toggle dropdown on click, close on outside-click and Escape -->
<script>
(function(){
var toggle=document.getElementById('user-profile-toggle');
var menu=document.getElementById('user-profile-menu');
if(!toggle||!menu)return;
toggle.addEventListener('click',function(e){e.stopPropagation();menu.classList.toggle('hidden');});
document.addEventListener('click',function(){menu.classList.add('hidden');});
document.addEventListener('keydown',function(e){if(e.key==='Escape')menu.classList.add('hidden');});
})();
</script>
