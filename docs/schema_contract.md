# Service Interface Contracts

## 1. AuthServiceInterface
```php
interface AuthServiceInterface {
    public function login(string $email, string $password): array|false;
    public function logout(): void;
    public function initiatePasswordReset(string $email): bool;
}
```

## 2. CartServiceInterface
```php
interface CartServiceInterface {
    public function getCartState(): array;
    public function addItem(int $productId, int $quantity = 1): void;
    public function updateItemQuantity(int $productId, int $quantity): void;
    public function removeItem(int $productId): void;
    public function clearCart(): void;
    public function validateCart(): bool;
    public function calculateTotal(): float;
}
```

## 3. OrderServiceInterface
```php
interface OrderServiceInterface {
    public function createOrder(int $userId, string $roomNo, ?string $notes, array $cartItems, float $totalAmount): int;
    public function getUserOrders(int $userId, ?string $dateFrom = null, ?string $dateTo = null, int $limit = 10, int $offset = 0): array;
    public function getAllProcessingOrders(): array;
    public function getOrderItems(int $orderId): array;
    public function cancelOrder(int $orderId, int $userId): bool;
    public function updateOrderStatus(int $orderId, string $status): bool;
}
```

## 4. ProductServiceInterface
```php
interface ProductServiceInterface {
    public function getAllProducts(bool $availableOnly = false): array;
    public function getProductById(int $productId): ?array;
    public function createProduct(array $data, ?array $file = null): int;
    public function updateProduct(int $productId, array $data, ?array $file = null): bool;
    public function deleteProduct(int $productId): bool;
    public function toggleAvailability(int $productId): bool;
    public function getAllCategories(): array;
    public function createCategory(string $name): int;
}
```

## 5. UserServiceInterface
```php
interface UserServiceInterface {
    public function getAllUsers(): array;
    public function getUserById(int $userId): ?array;
    public function createUser(array $data, ?array $file = null): int;
    public function updateUser(int $userId, array $data, ?array $file = null): bool;
    public function deleteOrDeactivateUser(int $userId): bool;
}
```

## 6. CheckServiceInterface
```php
interface CheckServiceInterface {
    public function getUserSpendingSummary(?string $dateFrom, ?string $dateTo, ?int $userId = null): array;
    public function getUserOrdersInRange(int $userId, ?string $dateFrom, ?string $dateTo): array;
    public function getOrderDetails(int $orderId): array;
}
```
