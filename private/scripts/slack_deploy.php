<?php
// Find out what tag we are on and get the annotation.
$deploy_tag = `git describe --tags`;
$annotation = `git tag -l -n99 $deploy_tag`;
$deploy_message = preg_replace('/pantheon_(\w+)_(\d+)/', '', $annotation);
// Load our hidden credentials.
// See the README.md for instructions on storing secrets.
$secrets = json_decode(file_get_contents($_SERVER['HOME'] . '/files/private/secrets.json'), 1);
if ($secrets == FALSE) {
  die('No secrets file found. Aborting!');
}
// Prepare the slack payload as per:
// https://api.slack.com/incoming-webhooks
// TODO: use awesome attachment-style formatting.
// https://api.slack.com/docs/attachments
$text = $_POST['user_email'] . ' deployed to the '. PANTHEON_ENVIRONMENT . ' environment of <https://dashboard.pantheon.io/sites/'. PANTHEON_SITE .'#'. PANTHEON_ENVIRONMENT .'/deploys|' . $_ENV['PANTHEON_SITE_NAME'] . '>. ';
//$text .= ' <https://dashboard.pantheon.io/sites/'. PANTHEON_SITE .'#'. PANTHEON_ENVIRONMENT .'/deploys|View Dashboard>';
$text .= "\n*DEPLOY MESSAGE*: $deploy_message";
$post = array(
  'username' => 'Pantheon-Quicksilver',
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
print("\n===== Post Complete! =====\n");
curl_close($ch);
