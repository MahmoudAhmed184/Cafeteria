<body class="bg-background text-on-surface font-body antialiased">
<header class="bg-[#faf9f5] flex justify-between items-center w-full px-8 py-4 max-w-[1920px] mx-auto sticky top-0 z-50">
<div class="flex items-center gap-8">
<span class="text-2xl font-black text-[#33210d] italic">Cafeteria</span>
<nav class="hidden md:flex items-center gap-6 font-['Manrope'] font-bold tracking-tight text-body-md">
<?php $p = $active_page ?? ''; ?>
<?php foreach ([
'orders' => ['name' => 'Orders', 'url' => '/admin/orders'],
'products' => ['name' => 'Products', 'url' => '/admin/products'],
'users' => ['name' => 'Users', 'url' => '/admin/users'],
'manual_order' => ['name' => 'Manual Order', 'url' => '/admin/manual-order'],
'checks' => ['name' => 'Checks', 'url' => '/admin/checks'],
] as $k => $v): ?>
<a class="<?= $p === $k ? 'text-[#33210d] font-extrabold border-b-2 border-[#33210d] pb-1' : 'text-[#4B3621]/70 font-medium hover:text-[#33210d] transition-colors duration-200'?>" href="<?= htmlspecialchars(defined('BASE_URL') ? rtrim(BASE_URL, '/') . $v['url'] : $v['url'])?>"><?= htmlspecialchars($v['name'])?></a>
<?php
endforeach; ?>
</nav>
</div>
<div class="flex items-center gap-4">
<!-- FIX: BUG_06 — bell and settings icons with safe onclick handlers -->
<button class="p-2 hover:bg-[#e3e2df]/50 rounded-full transition-colors duration-200 active:scale-95" onclick="/* TODO: notifications panel */">
<span class="material-symbols-outlined text-[#4B3621]">notifications</span>
</button>
<button class="p-2 hover:bg-[#e3e2df]/50 rounded-full transition-colors duration-200 active:scale-95" onclick="/* TODO: settings panel */">
<span class="material-symbols-outlined text-[#4B3621]">settings</span>
</button>
<!-- FIX: BUG_06 — clickable admin user block with dropdown and logout -->
<div class="relative" id="admin-profile-dropdown-wrapper">
<button type="button" id="admin-profile-toggle" class="flex items-center gap-3 pl-4 border-l border-[#e3e2df] cursor-pointer hover:opacity-80 transition-opacity">
<div class="text-right">
<p class="text-sm font-bold text-primary"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
</div>
<img alt="Admin Profile Avatar" class="w-10 h-10 rounded-full border-2 border-primary/10" src="<?= htmlspecialchars($_SESSION['profile_pic'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user_name'] ?? 'A') . '&background=e3e2df&color=33210d') ?>"/>
</button>
<div id="admin-profile-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-surface-container-lowest rounded-xl shadow-[0px_12px_32px_rgba(41,24,6,0.12)] border border-outline-variant/20 py-2 z-50">
<div class="px-4 py-3 border-b border-outline-variant/10">
<p class="text-sm font-bold text-primary"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
<p class="text-xs text-secondary">Administrator</p>
</div>
<a href="<?= htmlspecialchars(defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/logout' : '/logout') ?>"
   class="flex items-center gap-3 px-4 py-3 text-sm text-error font-bold hover:bg-error-container/30 transition-colors">
<span class="material-symbols-outlined text-sm">logout</span>
Logout
</a>
</div>
</div>
</div>
</header>
<!-- FIX: BUG_06 — toggle dropdown on click, close on outside-click and Escape -->
<script>
(function(){
var toggle=document.getElementById('admin-profile-toggle');
var menu=document.getElementById('admin-profile-menu');
if(!toggle||!menu)return;
toggle.addEventListener('click',function(e){e.stopPropagation();menu.classList.toggle('hidden');});
document.addEventListener('click',function(){menu.classList.add('hidden');});
document.addEventListener('keydown',function(e){if(e.key==='Escape')menu.classList.add('hidden');});
})();
</script>
