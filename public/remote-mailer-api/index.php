<?php

require __DIR__ . '/../src/send_email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        $post_data = file_get_contents('php://input');

        if ($post_data === false) {
            throw new Exception('Invalid POST data.');
        }

        $input_data = json_decode($post_data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data: ' . json_last_error_msg());
        }

        $email_list = $input_data['email_list'] ?? [];
        $from = $input_data['from'] ?? '';

        if (empty($email_list)) {
            throw new Exception('Email list is required.');
        }

        if (empty($from)) {
            throw new Exception('Sender "from" email is required.');
        }

        $results = send_email($email_list, $from);

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
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed.'
    ]);
}
