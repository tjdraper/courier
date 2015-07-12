<?

// Loop through headers
foreach ($headers as $key => &$header) {
	$name = $header->name;

	// Find "To" header
	if ($name == 'TO' || $name == 'To' || $name == 'to') {
		unset($headers[$key]);
	}

	// Find "Reply-To" header
	elseif ($name == 'Reply-To' || $name == 'Reply-to' || $name == 'reply-to') {
		unset($headers[$key]);
	}
}

// Remove To and Reply-To headers from raw message
$rawMessage = preg_replace(
	'/(?:\r|\n)?(?:To|Reply-To): (?:.*)/im',
	'',
	$rawMessage
);

// Set To header
$thisMessage = 'To: ' . $member->member_name .
	' <' . $member->member_email . '>' . PHP_EOL .
	$rawMessage;

// This would send the email with gmail
// Encode the message
$thisMessage = base64_encode($thisMessage);
$thisMessage = strtr($thisMessage, '+/=', '-_,');
$thisMessage = rtrim($thisMessage, '=');

// Set the Google Message
$msg = new Google_Service_Gmail_Message();
$msg->setRaw($thisMessage);

try {
	$service->users_messages->send('me', $msg);
} catch (Exception $e) {
	echo $e->getMessage();
}