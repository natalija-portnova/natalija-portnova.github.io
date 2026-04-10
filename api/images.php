<?php
header("Access-Control-Allow-Origin: https://preview.p5js.org");
header("Vary: Origin");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
  header("Access-Control-Allow-Methods: GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");
  exit;
}

$dir = __DIR__ . '/../uploads/';
if (!is_dir($dir)) {
  echo json_encode(["images" => []]);
  exit;
}

// Build correct public base path for /qr/uploads/
$basePath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/'); // -> /qr
$baseUrl  = $basePath . '/uploads/';

$files = glob($dir . "*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
usort($files, fn($a,$b) => filemtime($b) <=> filemtime($a)); // newest first

$images = array_map(fn($f) => $baseUrl . basename($f), $files);
echo json_encode(["images" => $images]);
