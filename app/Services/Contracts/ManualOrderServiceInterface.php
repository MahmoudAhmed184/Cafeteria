<?php

namespace App\Services\Contracts;

interface ManualOrderServiceInterface
{
    public function searchUsers(string $searchTerm = ''): array;
}
