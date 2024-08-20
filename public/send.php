<?php

require __DIR__ . '/../src/send-email.php.php';

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $post_data = file_get_contents('php://input');

        if ($post_data === false) {
            throw new Exception("Invalid post data.");
        }

        $input_data = json_decode($post_data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON data: " . json_last_error_msg());
        }

        $email_list = $input_data['from'] ?? [];
        $from = $input_data['from'] ?? '';
        $results = send_email($emailList, $from);

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
}
