<?php
// Check if storage link exists, if not create it
if (!file_exists(__DIR__ . '/../public/storage')) {
    symlink(__DIR__ . '/../storage/app/public', __DIR__ . '/../public/storage');
}
require __DIR__ . '/../public/index.php';