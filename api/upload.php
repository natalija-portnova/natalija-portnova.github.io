<?php

$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (!isset($_FILES['photo'])) {
  http_response_code(400);
  echo "Missing field 'photo'";
  exit;
}

$tmp = $_FILES['photo']['tmp_name'];
$err = $_FILES['photo']['error'];
if ($err !== UPLOAD_ERR_OK) {
  http_response_code(400);
  echo "Upload error code: " . $err;
  exit;
}

// Choose extension (we expect jpg/png mostly; HEIC should be converted by browser now)
$mime = mime_content_type($tmp);
$ext = "jpg";
if ($mime === "image/png") $ext = "png";
if ($mime === "image/webp") $ext = "webp";
if ($mime === "image/jpeg") $ext = "jpg";

$name = date("Ymd_His") . "_" . bin2hex(random_bytes(4)) . "." . $ext;
$dest = $uploadDir . $name;

if (!move_uploaded_file($tmp, $dest)) {
  http_response_code(500);
  echo "Failed to move uploaded file";
  exit;
}

header("Content-Type: application/json");
$basePath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
echo json_encode(["ok" => true, "url" => $basePath . "/uploads/" . $name]);

