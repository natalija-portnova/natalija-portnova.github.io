<?php
// api/delete_all.php

header("Content-Type: application/json; charset=utf-8");

$dir = __DIR__ . '/../uploads/';
if (!is_dir($dir)) {
  http_response_code(500);
  echo json_encode(["ok" => false, "error" => "Uploads folder not found"]);
  exit;
}

$patterns = ["*.jpg","*.jpeg","*.png","*.webp","*.gif","*.heic","*.heif"];
$deleted = 0;

foreach ($patterns as $pat) {
  foreach (glob($dir . $pat) as $file) {
    if (is_file($file) && @unlink($file)) {
      $deleted++;
    }
  }
}

echo json_encode([
  "ok" => true,
  "deleted" => $deleted
]);