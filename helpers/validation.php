<?php

function validate_required($value, string $fieldName): ?string
{
    if (empty(trim((string) $value))) {
        return "$fieldName is required.";
    }
    return null;
}

function validate_email($email): ?string
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address.";
    }
    return null;
}

function validate_min_length($value, string $fieldName, int $min): ?string
{
    if (strlen((string) $value) < $min) {
        return "$fieldName must be at least $min characters.";
    }
    return null;
}

function validate_match($value1, $value2, string $fieldName): ?string
{
    if ($value1 !== $value2) {
        return "$fieldName do not match.";
    }
    return null;
}

function validate_positive($value, string $fieldName): ?string
{
    if (!is_numeric($value) || floatval($value) <= 0) {
        return "$fieldName must be greater than 0.";
    }
    return null;
}

function validate_file_upload(array $file, string $fieldName, bool $required = true): ?string
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        if ($required) {
            return "$fieldName is required.";
        }
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "$fieldName upload failed. Please try again.";
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        return "$fieldName must be an image (JPEG, PNG, GIF, or WebP).";
    }

    $max_size = 2 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        return "$fieldName must be smaller than 2MB.";
    }

    return null;
}
