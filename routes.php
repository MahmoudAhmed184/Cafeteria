<?php

/**
 * Application routes
 * Aligned with implemented controllers and frontend endpoints.
 */

// -------------------------
// Auth
// -------------------------
$router->get('/login', 'AuthController@showLoginForm', 'auth.login.form');
$router->post('/login', 'AuthController@login', 'auth.login');
$router->post('/logout', 'AuthController@logout', 'auth.logout');
$router->get('/forget-password', 'AuthController@showForgetPasswordForm', 'auth.forget.form');
$router->post('/forget-password', 'AuthController@resetPassword', 'auth.forget.submit');

// -------------------------
// User dashboard + cart
// -------------------------
$router->get('/dashboard', 'CartController@dashboard', 'user.dashboard');
$router->get('/cart/state', 'CartController@state', 'cart.state');
$router->post('/cart/add', 'CartController@add', 'cart.add');
$router->post('/cart/update', 'CartController@update', 'cart.update');
$router->post('/cart/remove', 'CartController@remove', 'cart.remove');
$router->post('/cart/clear', 'CartController@clear', 'cart.clear');
$router->post('/cart/confirm', 'CartController@confirm', 'cart.confirm');

// Backward-compatible legacy cart routes
$router->get('/cart', 'CartController@dashboard', 'cart.index');
$router->post('/cart', 'CartController@add', 'cart.store');
$router->get('/cart/user', 'CartController@dashboard', 'cart.user');

// -------------------------
// User orders
// -------------------------
$router->get('/orders', 'OrderController@index', 'orders.index');
$router->post('/orders/cancel', 'OrderController@cancel', 'orders.cancel');
$router->get('/orders/items', 'OrderController@items', 'orders.items');

// -------------------------
// Admin - orders
// -------------------------
$router->get('/admin/orders', 'Admin\\AdminOrderController@index', 'admin.orders.index');
$router->get('/admin/orders/items', 'Admin\\AdminOrderController@items', 'admin.orders.items');
$router->post('/admin/orders/update-status', 'Admin\\AdminOrderController@updateStatus', 'admin.orders.update_status');

// -------------------------
// Admin - manual orders
// -------------------------
$router->get('/admin/manual-order', 'Admin\\ManualOrderController@index', 'admin.manual_order.index');
$router->post('/admin/manual-order/select-user', 'Admin\\ManualOrderController@selectUser', 'admin.manual_order.select_user');
$router->post('/admin/manual-order/add-to-cart', 'Admin\\ManualOrderController@addToCart', 'admin.manual_order.add_to_cart');
$router->post('/admin/manual-order/update-cart', 'Admin\\ManualOrderController@updateCart', 'admin.manual_order.update_cart');
$router->post('/admin/manual-order/remove-item', 'Admin\\ManualOrderController@removeItem', 'admin.manual_order.remove_item');
$router->post('/admin/manual-order/confirm', 'Admin\\ManualOrderController@confirm', 'admin.manual_order.confirm');

// -------------------------
// Admin - products
// -------------------------
$router->get('/admin/products', 'Admin\\ProductController@index', 'admin.products.index');
$router->get('/admin/products/create', 'Admin\\ProductController@create', 'admin.products.create');
$router->post('/admin/products/store', 'Admin\\ProductController@store', 'admin.products.store');
$router->get('/admin/products/edit', 'Admin\\ProductController@edit', 'admin.products.edit');
$router->post('/admin/products/update', 'Admin\\ProductController@update', 'admin.products.update');
$router->post('/admin/products/toggle-availability', 'Admin\\ProductController@toggleAvailability', 'admin.products.toggle_availability');
$router->post('/admin/products/delete', 'Admin\\ProductController@delete', 'admin.products.delete');

// Category endpoint used by admin product "Add category" modal
$router->post('/admin/categories/store', 'Admin\\ProductController@storeCategory', 'admin.categories.store');

// -------------------------
// Admin - users
// -------------------------
$router->get('/admin/users', 'Admin\\UserController@index', 'admin.users.index');
$router->get('/admin/users/create', 'Admin\\UserController@create', 'admin.users.create');
$router->post('/admin/users/store', 'Admin\\UserController@store', 'admin.users.store');
$router->get('/admin/users/edit', 'Admin\\UserController@edit', 'admin.users.edit');
$router->post('/admin/users/update', 'Admin\\UserController@update', 'admin.users.update');
$router->post('/admin/users/delete', 'Admin\\UserController@delete', 'admin.users.delete');

// -------------------------
// Admin - checks / reports
// -------------------------
$router->get('/admin/checks', 'Admin\\CheckController@index', 'admin.checks.index');
