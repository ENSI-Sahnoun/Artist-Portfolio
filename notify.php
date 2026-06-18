<?php
function send_notification(string $subject, string $body): void {
    $api_key = getenv('RESEND_API_KEY');
    $to      = getenv('NOTIFY_EMAIL');

    if (!$api_key || !$to) return;

    $payload = json_encode([
        'from'    => 'onboarding@resend.dev',
        'to'      => [$to],
        'subject' => $subject,
        'html'    => $body,
    ]);

    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json',
        ],
    ]);
    curl_exec($ch);
    curl_close($ch);
}
