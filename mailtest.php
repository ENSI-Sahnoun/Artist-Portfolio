<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/notify.php';

$api_key = getenv('RESEND_API_KEY');
$to      = getenv('NOTIFY_EMAIL');

echo "API KEY: " . ($api_key ? substr($api_key, 0, 8) . '...' : 'MISSING') . "<br>";
echo "NOTIFY EMAIL: " . ($to ?: 'MISSING') . "<br>";

$payload = json_encode([
    'from'    => 'onboarding@resend.dev',
    'to'      => [$to],
    'subject' => 'Test email from portfolio',
    'html'    => '<p>If you see this, emails work.</p>',
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
$response = curl_exec($ch);
$err      = curl_error($ch);
curl_close($ch);

echo "CURL ERROR: " . ($err ?: 'none') . "<br>";
echo "RESEND RESPONSE: " . $response;
