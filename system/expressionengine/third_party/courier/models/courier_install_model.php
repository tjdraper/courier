<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Courier install model
 *
 * @package courier
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/courier
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Courier_install_model extends CI_Model
{
	public function __construct()
	{
		ee()->load->dbforge();
	}

	/**
	 * Insert tables
	 *
	 * @access public
	 * @return bool
	 */
	public function insertTables()
	{
		// Lists Table
		ee()->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'list_handle' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'list_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'member_count' => array(
				'type' => 'INT',
				'unsigned' => true
			)
		));

		ee()->dbforge->add_key('id', true);

		ee()->dbforge->create_table('courier_lists', true);

		// Members Table
		ee()->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'member_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			),
			'member_email' => array(
				'type' => 'VARCHAR',
				'constraint' => 255
			)
		));

		ee()->dbforge->add_key('id', true);

		ee()->dbforge->create_table('courier_members', true);

		// Members mailing list relationship
		ee()->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'unsigned' => true,
				'auto_increment' => true
			),
			'member_id' => array(
				'type' => 'INT',
				'unsigned' => true
			),
			'list_id' => array(
				'type' => 'INT',
				'unsigned' => true
			)
		));

		ee()->dbforge->add_key('id', true);

		ee()->dbforge->create_table('courier_member_lists', true);

		return true;
	}

	/**
	 * Remove tables
	 *
	 * @access public
	 * @return bool
	 */
	public function removeTables()
	{
		ee()->dbforge->drop_table('courier_lists');

		ee()->dbforge->drop_table('courier_members');

		ee()->dbforge->drop_table('courier_member_lists');

		return true;
	}
}