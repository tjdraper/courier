<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Courier Mandrill Handler library
 *
 * @package courier
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/mailing-list-manager
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Mandrill_handler_lib
{
	public function __construct()
	{
		// Require the Mandrill API
		require_once COURIER_PATH . 'lib/Mandrill/src/Mandrill.php';

		// Set up Mandrill
		$this->mandrill = new Mandrill(
			ee()->config->item('courier_mandrill_key')
		);
	}

	/**
	 * Send raw message with Mandrill
	 *
	 * @param string
	 */
	public function sendRawMessage($message, $conf = array())
	{
		$dConf = array(
			'async' => true,
			'fromEmail' => null,
			'fromName' => null,
			'ipPool' => '',
			'returnPathDomain' => '',
			'sendAt' => '',
			'to' => null
		);

		$conf = array_merge($dConf, $conf);

		try {
			$this->mandrill->messages->sendRaw(
				$message,
				$conf['fromEmail'],
				$conf['fromName'],
				$conf['to'],
				$conf['async'],
				$conf['ipPool'],
				$conf['sendAt'],
				$conf['returnPathDomain']
			);
		} catch (Mandrill_Error $e) {
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		}

		return;
	}
}