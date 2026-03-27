<?php /* Frontend Polish Pass: updated labels to Title Case and focus rings to brand colors */
$page_title = 'Forgot Password';
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$csrfValue = function_exists('csrf_token') ? csrf_token() : '';
ob_start();
?>
<main class="w-full max-w-[400px] flex flex-col items-center px-4">
    <!-- Brand -->
    <header class="mb-10 text-center">
        <div class="mb-4 inline-flex items-center justify-center w-14 h-14 rounded-xl bg-surface-container-highest">
            <span class="material-symbols-outlined text-primary text-2xl">lock_reset</span>
        </div>
        <h1 class="font-headline font-bold text-2xl text-primary">Forgot Password</h1>
        <p class="font-body text-on-surface-variant text-sm mt-1">Enter your email to reset your password</p>
    </header>
    <!-- Form -->
    <section class="w-full bg-surface-container-lowest editorial-shadow rounded-xl p-6 sm:p-8">
        <form method="POST" class="space-y-5" novalidate
            action="<?= defined('BASE_URL') ? BASE_URL . '/forget-password' : '/forget-password' ?>">
            <input type="hidden" name="csrf_token" value="<?= $e($csrfValue) ?>">
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-on-surface" for="email">Email</label>
                <input
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface font-body focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition-all duration-150"
                    id="email" name="email" placeholder="username@corporate.com" type="email" required autocomplete="email" />
            </div>
            <button type="submit"
                class="w-full bg-primary text-on-primary font-body font-semibold py-2.5 rounded-lg hover:bg-primary-container active:scale-[0.99] transition-all duration-150 text-sm">
                Send Reset Instructions
            </button>
        </form>
        <div class="mt-6 text-center">
            <a class="text-sm font-medium text-secondary hover:text-primary transition-colors duration-150"
                href="<?= htmlspecialchars(defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/login' : '/login') ?>">
                Back to login
            </a>
        </div>
    </section>
    <footer class="mt-8 text-center">
        <p class="text-xs text-outline/50">© <?= date('Y') ?> Cafeteria. All rights reserved.</p>
    </footer>
</main>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/auth.php';
