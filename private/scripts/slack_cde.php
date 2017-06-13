<?php

$secrets = json_decode(file_get_contents($_SERVER['HOME'] . '/files/private/secrets.json'), 1);
if ($secrets == FALSE) {
  die('No secrets file found. Aborting!');
}
$text = $_ENV[user_email] .' created the ' . PANTHEON_ENVIRONMENT . ' environment for <https://dashboard.pantheon.io/sites/'. PANTHEON_SITE .'#'. PANTHEON_ENVIRONMENT .'/code|' . $_ENV['PANTHEON_SITE_NAME'] . '>. ';
$post = array(
  'username' => 'Environment Created',
  'text' => $text,
  'channel' => '#sales-demo',
  'icon_url' => 'http://i.imgur.com/hb4pXwD.png'
);
$payload = json_encode($post);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $secrets['slack_url']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
print("\n==== Posting to Slack ====\n");
$result = curl_exec($ch);
print("RESULT: $result");
print_r($_ENV);
print("\n===== Post Complete! =====\n");
curl_close($ch);
