<?php /* Frontend Polish Pass: updated labels to Title Case and focus rings to brand colors */
$page_title = 'Login';
ob_start(); ?>
<main class="w-full max-w-[400px] flex flex-col items-center px-4">
    <!-- Brand -->
    <header class="mb-10 text-center">
        <div class="mb-4 inline-flex items-center justify-center w-14 h-14 rounded-xl bg-surface-container-highest">
            <span class="material-symbols-outlined text-primary text-2xl">restaurant_menu</span>
        </div>
        <h1 class="font-headline font-bold text-2xl text-primary">Cafeteria</h1>
        <p class="font-body text-on-surface-variant text-sm mt-1">Sign in to your account</p>
    </header>
    <!-- Form -->
    <section class="w-full bg-surface-container-lowest editorial-shadow rounded-xl p-6 sm:p-8">
        <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-4 p-3 bg-error-container/30 rounded-lg text-sm text-error font-medium">
            <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8')?>
        </div>
        <?php
endif; ?>
        <form method="POST" class="auth-form space-y-5" novalidate>
            <input type="hidden" name="csrf_token" value="<?= csrf_token()?>">
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-on-surface"
                    for="email">Email</label>
                <input
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface font-body focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition-all duration-150"
                    id="email" name="email" placeholder="username@corporate.com" type="email" required />
            </div>
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-on-surface"
                    for="password">Password</label>
                <input
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface font-body focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition-all duration-150"
                    id="password" name="password" placeholder="••••••••" type="password" required />
            </div>
            <button
                class="w-full bg-primary text-on-primary font-body font-semibold py-2.5 rounded-lg hover:bg-primary-container active:scale-[0.99] transition-all duration-150 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                type="submit">
                Login
            </button>
        </form>
        <div class="mt-6 text-center">
            <a class="text-sm font-medium text-secondary hover:text-primary transition-colors duration-150"
                href="<?= htmlspecialchars(defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/forget-password' : '/forget-password')?>">
                Forgot password?
            </a>
        </div>
    </section>
    <footer class="mt-8 text-center">
        <p class="text-xs text-outline/50">©
            <?= date('Y')?> Cafeteria. All rights reserved.
        </p>
    </footer>
</main>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/auth.php'; ?>