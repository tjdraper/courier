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
	 * Delete Lists
	 *
	 * @param array $data {
	 *     @var string $listIds Array of list IDs to delete
	 * }
	 *
	 * @return void
	 */
	public function deleteLists($listIds = false)
	{
		if ($listIds) {
			ee()->db->where_in('id', $listIds);
			ee()->db->delete('courier_lists');

			ee()->db->where_in('list_id', $listIds);
			ee()->db->delete('courier_member_lists');
		}
	}

	/**
	 * Update Lists
	 *
	 * @param array $data {
	 *     @var array $data
	 * }
	 *
	 * @return void
	 */
	public function updateLists($data)
	{
		$updateData = array();
		$i = 0;

		foreach ($data as $key => $val) {
			$updateData[$i]['id'] = $key;

			foreach ($val as $key => $val) {
				$updateData[$i][$key] = $val;
			}

			$i++;
		}

		ee()->db->update_batch('courier_lists', $updateData, 'id');
	}

	/**
	 * Get lists
	 *
	 * @param array $conf {
	 *      @var false|int|array $list_id
	 *      @var false|string|array $list_handle
	 *      @var false|string|array $list_name
	 * }
	 *
	 * @return void
	 */
	public function getLists($conf = array())
	{
		$defaultConf = array(
			'list_id' => false,
			'list_handle' => false,
			'list_name' => false
		);

		$conf = array_merge($defaultConf, $conf);

		ee()->db->select('*')
			->from('courier_lists');

		if ($conf['list_id']) {
			ee()->db->where_in('id', $conf['list_id']);
		}

		if ($conf['list_handle']) {
			ee()->db->where_in('list_handle', $conf['list_handle']);
		}

		if ($conf['list_name']) {
			ee()->db->where_in('list_name', $conf['list_name']);
		}

		ee()->db->order_by('list_name', 'asc');

		$query = ee()->db->get()->result();

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

	/**
	 * Get list with list members
	 *
	 * @return false|array
	 */
	public function getListWithMembers($conf = array())
	{
		$defaultConf = array(
			'list_id' => false,
			'list_handle' => false,
			'list_name' => false
		);

		$conf = array_merge($defaultConf, $conf);

		if (! $conf['list_id'] && ! $conf['list_handle'] && ! $conf['list_name']) {
			return false;
		}

		$list = $this->getLists(array(
			'list_id' => $conf['list_id'],
			'list_handle' => $conf['list_handle'],
			'list_name' => $conf['list_name']
		));

		$list = $list[0];

		$query = ee()->db->select(array(
				'M.id',
				'M.member_name',
				'M.member_email'
			))
			->from('courier_member_lists L')
			->join('courier_members M', 'L.member_id = M.id')
			->where('L.list_id', $list->id)
			->order_by('M.member_name', 'asc')
			->get()
			->result();

		return array(
			'list' => $list,
			'members' => $query
		);
	}
}