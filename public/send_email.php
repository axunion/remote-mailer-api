<?php

require __DIR__ . '/../src/send_remote_data_mail.php.php';

header('Content-Type: application/json');

try {
  $url = isset($_GET['url']) ? $_GET['url'] : '';
  $from = isset($_GET['from']) ? $_GET['from'] : '';

  if (!filter_var($url, FILTER_VALIDATE_URL)) {
    throw new Exception("Invalid URL provided.");
  }

  $jsonData = file_get_contents($url);

  if ($jsonData === false) {
    throw new Exception("Unable to fetch data from URL: $url.");
  }

  $emailList = json_decode($jsonData, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception("Invalid JSON data: " . json_last_error_msg());
  }

  $results = send_remote_data_mail($emailList, $from);

  echo json_encode([
    'status' => 'success',
    'results' => $results
  ]);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode([
    'status' => 'error',
    'message' => $e->getMessage()
  ]);
}
