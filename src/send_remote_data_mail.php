<?php

function send_remote_data_mail(array $email_list, string $from): array
{
    mb_language("uni");
    mb_internal_encoding("UTF-8");

    $results = [];

    foreach ($email_list as $email) {
        if (!isset($email['to'], $email['subject'], $email['body'])) {
            $results[] = "Error: Missing required fields in email data.";
            continue;
        }

        $to = $email['to'];

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $results[] = "Error: Invalid email address {$to}.";
            continue;
        }

        $subject = $email['subject'];
        $body = $email['body'];

        $headers = "From: {$from}\r\n";
        $headers .= "Reply-To: {$from}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mb_send_mail($to, $subject, $body, $headers)) {
            $results[] = "Email sent successfully to {$to}.";
        } else {
            $results[] = "Error: Email sending failed for {$to}.";
        }
    }

    return $results;
}
