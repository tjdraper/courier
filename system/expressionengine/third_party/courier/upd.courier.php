<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

// Include configuration
include(PATH_THIRD . 'courier/config.php');

/**
 * Courier intaller/updater
 *
 * @package courier
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/courier
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Courier_upd
{
	public $name = COURIER_NAME;
	public $version = COURIER_VER;

	public function __construct()
	{
		ee()->load->model('courier_install_model');
	}

	/**
	 * Install method
	 *
	 * @return bool
	 */
	public function install()
	{
		// Insert Module
		ee()->db->insert('modules', array(
			'module_name' => 'Courier',
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		));

		// Insert cron end point
		ee()->db->insert('actions', array(
			'class' => 'Courier',
			'method' => 'check_mail'
		));

		ee()->courier_install_model->insertTables();

		return true;
	}

	/**
	 * Uninstall method
	 *
	 * @return bool
	 */
	public function uninstall()
	{
		ee()->db->delete('modules', array(
			'module_name' => 'Courier'
		));

		ee()->db->delete('actions', array(
			'class' => 'Courier'
		));

		ee()->courier_install_model->removeTables();

		return true;
	}

	/**
	 * Update method
	 *
	 * @return bool
	 */
	public function update($current = '')
	{
		return true;
	}
}