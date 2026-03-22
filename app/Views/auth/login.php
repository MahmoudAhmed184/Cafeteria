<?php $page_title = 'Login';
ob_start(); ?>
<main class="w-full max-w-[420px] flex flex-col items-center">
<!-- Brand Header Section -->
<header class="mb-12 text-center">
<div class="mb-4 inline-flex items-center justify-center w-16 h-16 rounded-xl bg-surface-container-highest">
<span class="material-symbols-outlined text-primary text-3xl" data-icon="restaurant_menu">restaurant_menu</span>
</div>
<h1 class="font-headline font-extrabold text-4xl tracking-tight text-primary mb-2">Cafeteria</h1>
<p class="font-body text-secondary text-sm tracking-wide opacity-80 uppercase font-semibold">Cafeteria</p>
</header>
<!-- Form Section -->
<section class="w-full bg-surface-container-lowest editorial-shadow rounded-xl p-8 md:p-10">
<form method="POST" class="space-y-6">
<input type="hidden" name="csrf_token" value="<?= csrf_token()?>">
<!-- Email Input Group -->
<div class="space-y-2">
<label class="block font-label text-sm font-semibold text-on-surface-variant" for="email">Email</label>
<div class="relative">
<input class="w-full bg-surface-container-low border-none rounded-lg py-3.5 px-4 text-on-surface font-body text-base focus:ring-2 focus:ring-primary/10 focus:bg-surface-container-lowest transition-all duration-200 outline-none placeholder:text-outline/50" id="email" name="email" placeholder="username@corporate.com" type="email"/>
</div>
</div>
<!-- Password Input Group -->
<div class="space-y-2">
<div class="flex justify-between items-center">
<label class="block font-label text-sm font-semibold text-on-surface-variant" for="password">Password</label>
</div>
<div class="relative">
<input class="w-full bg-surface-container-low border-none rounded-lg py-3.5 px-4 text-on-surface font-body text-base focus:ring-2 focus:ring-primary/10 focus:bg-surface-container-lowest transition-all duration-200 outline-none placeholder:text-outline/50" id="password" name="password" placeholder="••••••••" type="password"/>
</div>
</div>
<!-- CTA Button -->
<button class="w-full primary-gradient text-on-primary font-headline font-bold py-4 rounded-lg shadow-md hover:opacity-90 active:scale-[0.98] transition-all duration-200 text-lg" type="submit">
                    Login
                </button>
</form>
<!-- Secondary Actions -->
<div class="mt-8 text-center">
<a class="font-label text-sm font-semibold text-tertiary-fixed-dim hover:text-tertiary transition-colors duration-200" href="#">
                    Forget Password?
                </a>
</div>
</section>
<!-- Footer Decoration / Info -->
<footer class="mt-12 text-center">
<div class="flex items-center justify-center space-x-2 text-outline/60 mb-4">
<div class="h-[1px] w-8 bg-outline-variant/30"></div>
<span class="text-[10px] uppercase tracking-widest font-bold">Handcrafted by The Digital Maître D’</span>
<div class="h-[1px] w-8 bg-outline-variant/30"></div>
</div>
<!-- Atmospheric Image Element -->
<div class="mt-4 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
<img alt="Artisan coffee beans texture" class="h-12 w-auto mx-auto rounded-lg object-cover" data-alt="Close up of dark roasted coffee beans texture" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBY69OUbTnkPuXOcOTkKojVC8qiYFnmEz6Flix-cDA2mdLBcmdmcKvVlY3svgfzrzxfvNPefNpJnsZeLUExgfKi4Sg_YRyQvnr73-19w-ZDwF6R_MnsKri74GDidddH7KZ2ju2Tv83Rerx-8nEw_ZknEha9NAG6Un-Yo6xVpgXyEWIHuj5C86V_0tgniSSzfPIVQFGe5-ZzXc1PCwTVf1KEONkH4p2pi8l5yRwqqIMW-cxP1gR6otfRgVWn52butOlpRf-GNTVhcyA"/>
</div>
</footer>
</main>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/auth.php'; ?>
