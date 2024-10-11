<?php

function send_email(array $email_list, string $from): array
{
    mb_language('uni');
    mb_internal_encoding('UTF-8');

    $results = [];

    $sanitized_from = filter_var($from, FILTER_SANITIZE_EMAIL);

    if (!filter_var($sanitized_from, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('Error: Invalid sender email address.');
    }

    foreach ($email_list as $email) {
        if (!isset($email['to'], $email['subject'], $email['body'])) {
            $results[] = 'Error: Missing required fields in email data.';
            continue;
        }

        $to = filter_var($email['to'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $results[] = "Error: Invalid email address {$to}.";
            continue;
        }

        $subject = $email['subject'];
        $body = $email['body'];

        $headers = "From: {$sanitized_from}\r\n";
        $headers .= "Reply-To: {$sanitized_from}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();

        if (mb_send_mail($to, $subject, $body, $headers)) {
            $results[] = "Email sent successfully to {$to}.";
        } else {
            $results[] = "Error: Email sending failed for {$to}.";
        }
    }

    return $results;
}
