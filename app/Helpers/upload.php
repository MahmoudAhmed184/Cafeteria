function uploadImage($file, $folder = 'uploads')
{
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload failed");
    }

    $maxSize = 2 * 1024 * 1024; // 2MB

    if ($file['size'] > $maxSize) {
        throw new Exception("File is too large");
    }
    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp'
    ];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);

    if (!in_array($mime, $allowedTypes)) {
        throw new Exception("Invalid file type");
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    $filename = uniqid() . '.' . $extension;

    $uploadPath = __DIR__ . "/../../public/uploads/" . $folder;

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $destination = $uploadPath . '/' . $filename;

    move_uploaded_file($file['tmp_name'], $destination);

    return "uploads/$folder/$filename";
}