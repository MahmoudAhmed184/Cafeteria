<?php $page_title = 'Login';
ob_start(); ?>
<main class="w-full max-w-[420px] flex flex-col items-center">
    <!-- Brand Header Section -->
    <header class="mb-12 text-center">
        <div class="mb-4 inline-flex items-center justify-center w-16 h-16 rounded-xl bg-surface-container-highest">
            <span class="material-symbols-outlined text-primary text-3xl"
                data-icon="restaurant_menu">restaurant_menu</span>
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
                    <input
                        class="w-full bg-surface-container-low border-none rounded-lg py-3.5 px-4 text-on-surface font-body text-base focus:ring-2 focus:ring-primary/10 focus:bg-surface-container-lowest transition-all duration-200 outline-none placeholder:text-outline/50"
                        id="email" name="email" placeholder="username@corporate.com" type="email" />
                </div>
            </div>
            <!-- Password Input Group -->
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label class="block font-label text-sm font-semibold text-on-surface-variant"
                        for="password">Password</label>
                </div>
                <div class="relative">
                    <input
                        class="w-full bg-surface-container-low border-none rounded-lg py-3.5 px-4 text-on-surface font-body text-base focus:ring-2 focus:ring-primary/10 focus:bg-surface-container-lowest transition-all duration-200 outline-none placeholder:text-outline/50"
                        id="password" name="password" placeholder="••••••••" type="password" />
                </div>
            </div>
            <!-- CTA Button -->
            <button
                class="w-full primary-gradient text-on-primary font-headline font-bold py-4 rounded-lg shadow-md hover:opacity-90 active:scale-[0.98] transition-all duration-200 text-lg"
                type="submit">
                Login
            </button>
        </form>
        <!-- Secondary Actions -->
        <div class="mt-8 text-center">
            <a class="font-label text-sm font-semibold text-secondary hover:text-primary transition-colors duration-200"
                href="#">
                Forget Password?
            </a>
        </div>
    </section>
    <!-- Footer Information -->
    <footer class="mt-8 text-center">
        <p class="text-xs text-outline/50 font-body">©
            <?= date('Y')?> Cafeteria. All rights reserved.
        </p>
    </footer>
</main>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/auth.php'; ?>