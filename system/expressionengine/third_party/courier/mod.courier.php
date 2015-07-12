<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

// Include configuration
include(PATH_THIRD . 'courier/config.php');

/**
 * Mailing List Manager module
 *
 * @package courier
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/courier
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Courier
{
	public function __construct()
	{
		// Load the model
		ee()->load->model('courier_model');

		// Load the library
		ee()->load->library('courier_google_lib');
	}

	public function check_mail()
	{
		// Make sure we have a Mandrill API Key
		if (! ee()->config->item('courier_mandrill_key')) {
			return;
		}

		// Include the Google API
		set_include_path(
			get_include_path() .
			PATH_SEPARATOR .
			COURIER_PATH .
			'/lib/google-api/src'
		);

		// Get lists
		$lists = ee()->courier_model->getLists();

		// Loop through each of the lists
		foreach ($lists as $list) {
			// Get the list members
			$list->members = ee()->courier_model->getMembers(array(
				'list_id' => $list->id
			));

			// End this iteration if list has no members
			if (! $list->members) {
				continue;
			}

			// Get the gmail client
			$client = ee()->courier_google_lib->getClient(
				$list->id,
				$list->list_auth_token
			);

			// Get the service
			$service = new Google_Service_Gmail($client);

			// Get all messages in the inbox
			$messages = $service->users_messages->listUsersMessages('me', array(
				'labelIds' => 'INBOX'
			));

			// Get the messages array
			$messagesArray = $messages->getMessages();

			// If there are no messages we can go to the next list
			if (! count($messagesArray)) {
				continue;
			}

			// Load the Mandrill API handler
			ee()->load->library('mandrill_handler_lib');

			// Set list as default from name and email
			$fromEmail = $list->list_email_address;
			$fromName = $list->list_name;

			// Build array of members to send the message to
			$members = array();

			foreach ($list->members as $member) {
				$members[] = $member->member_email;
			}

			// Loop through the messages
			foreach($messagesArray as $messageInfo) {
				// Get the email metadata
				$messageMeta = $service->users_messages->get(
					'me',
					$messageInfo->getId(),
					array(
						'format' => 'metadata'
					)
				);

				// Set from variables
				foreach ($messageMeta->getPayload()->getHeaders() as $header) {
					$name = $header->name;

					if ($name == 'From' || $name == 'from' || $name == 'FROM') {
						preg_match_all(
							'/\s*"?([^><,"]+)"?\s*((?:<[^><,]+>)?)\s*/',
							$header->value,
							$matches,
							PREG_SET_ORDER
						);

						foreach($matches as $m) {
							if(! empty($m[2])) {
								$fromEmail = trim($m[2], '<>');
								$fromName = trim($m[1]);
							} else {
								$fromEmail = $m[1];
								$fromName = $m[1];
							}
						}
					}
				}

				// Set the recipients of this message (remove sender)
				$recipients = $members;
				$fromEmailKey = array_search($fromEmail, $recipients);

				if ($fromEmailKey) {
					unset($recipients[$fromEmailKey]);

					$recipients = array_values($recipients);
				}

				// Get the raw message
				$rawMessage = $service->users_messages->get(
					'me',
					$messageInfo->getId(),
					array(
						'format' => 'raw'
					)
				);

				// Decode the raw message
				$rawMessage = base64_decode(
					strtr($rawMessage->getRaw(), '-_,', '+/=')
				);

				// Remove Reply-To headers from raw message
				$rawMessage = preg_replace(
					'/(?:\r|\n)?Reply-To: (?:.*)/im',
					'',
					$rawMessage
				);

				// Set list Reply-To header
				$rawMessage = 'Reply-To: ' . $list->list_name .
					' <' . $list->list_email_address . '>' . PHP_EOL .
					$rawMessage;

				// Send the message
				ee()->mandrill_handler_lib->sendRawMessage(
					$rawMessage,
					array(
						'fromEmail' => $fromEmail,
						'fromName' => $fromName,
						'to' => $recipients
					)
				);

				// Remove INBOX label
				$mods = new Google_Service_Gmail_ModifyMessageRequest();
				$mods->setRemoveLabelIds(array(
					'INBOX'
				));

				$service->users_messages->modify(
					'me',
					$messageInfo->getId(),
					$mods
				);
			}

			exit();
		}
	}
}