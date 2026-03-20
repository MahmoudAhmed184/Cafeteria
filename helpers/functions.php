<?php

declare(strict_types=1);

function e(?string $string): string
{
    return htmlspecialchars((string) ($string ?? ''), ENT_QUOTES, 'UTF-8');
}

