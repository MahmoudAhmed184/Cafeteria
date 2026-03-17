<?php

namespace App\Services\Contracts;

interface ProductServiceInterface
{
    public function getAllProducts(bool $availableOnly = false): array;
    public function getProductById(int $productId): ?array;
    public function createProduct(array $data, ?array $file = null): int;
    public function updateProduct(int $productId, array $data, ?array $file = null): bool;
    public function deleteProduct(int $productId): bool;
    public function toggleAvailability(int $productId): bool;
    public function getAllCategories(): array;
    public function createCategory(string $name): int;
}
