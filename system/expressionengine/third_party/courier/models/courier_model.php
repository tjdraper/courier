<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Courier model
 *
 * @package courier
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/mailing-list-manager
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Courier_model extends CI_Model
{
	/**
	 * Insert a new list
	 *
	 * @param array $data {
	 *     @var string $name Name of the list
	 *     @var string $handle List handle
	 * }
	 *
	 * @return void
	 */
	public function insertNewList($data)
	{
		ee()->db->insert('courier_lists', array(
			'list_handle' => $data['handle'],
			'list_name' => $data['name'],
			'member_count' => 0
		));
	}

	/**
	 * Get lists
	 *
	 * @return void
	 */
	public function getLists()
	{
		$query = ee()->db->select('*')
			->from('courier_lists')
			->order_by('list_name', 'asc')
			->get()
			->result();

		return $query;
	}

	/**
	 * Insert a new member
	 *
	 * @param array $data {
	 *     @var string $name Name of the list
	 *     @var string $email Email address
	 * }
	 *
	 * @return void
	 */
	public function insertNewMember($data)
	{
		ee()->db->insert('courier_members', array(
			'member_name' => $data['name'],
			'member_email' => $data['email']
		));
	}

	/**
	 * Get all members
	 *
	 * @return void
	 */
	public function getAllMembers()
	{
		$query = ee()->db->select('*')
			->from('courier_members')
			->order_by('member_name', 'asc')
			->get()
			->result();

		return $query;
	}
}