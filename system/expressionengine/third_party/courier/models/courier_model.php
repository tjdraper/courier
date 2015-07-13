<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Courier model
 *
 * @package courier
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/courier
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Courier_model extends CI_Model
{
	/**
	 * Get list token
	 *
	 * @param int $listId
	 *
	 * @return string
	 */
	public function getListToken($listId = false)
	{
		if (! $listId) {
			return false;
		}

		$query = ee()->db->select('list_auth_token')
			->from('courier_lists')
			->where('id', $listId)
			->get()
			->row();

		return $query->list_auth_token;
	}

	/**
	 * Set list token
	 *
	 * @param int $listId
	 * @param string $accessToken
	 *
	 * @return void
	 */
	public function setListToken($listId = false, $accessToken = false)
	{
		if (! $listId || ! $accessToken) {
			return false;
		}

		ee()->db->where('id', $listId);
		ee()->db->update('courier_lists', array(
			'list_auth_token' => $accessToken
		));
	}

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
			'list_email_address' => $data['email_address'],
			'member_count' => 0
		));
	}

	/**
	 * Delete lists
	 *
	 * @param array $listIds
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
	 * Update lists
	 *
	 * @param array $data
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
	 * @return object
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
		$check = ee()->db->select('*')
			->from('courier_members')
			->where('member_email', $data['email'])
			->get()
			->result();

		if ($check) {
			return;
		}

		ee()->db->insert('courier_members', array(
			'member_name' => $data['name'],
			'member_email' => $data['email']
		));
	}

	/**
	 * Delete members
	 *
	 * @param array $memberIds
	 *
	 * @return void
	 */
	public function deleteMembers($memberIds = false)
	{
		if ($memberIds) {
			ee()->db->where_in('id', $memberIds);
			ee()->db->delete('courier_members');

			ee()->db->where_in('member_id', $memberIds);
			ee()->db->delete('courier_member_lists');

			$this->updateListCounts();
		}
	}

	/**
	 * Update members
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function updateMembers($data)
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

		ee()->db->update_batch('courier_members', $updateData, 'id');
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
	 * Get members
	 *
	 * @return object
	 */
	public function getMembers($conf = array())
	{
		$dConf = array(
			'id' => false,
			'list_id' => false,
			'member_name' => false,
			'member_email' => false
		);

		$conf = array_merge($dConf, $conf);

		ee()->db->select('M.*')
			->from('courier_members M')
			->order_by('M.member_name', 'asc');

		if ($conf['id']) {
			ee()->db->where_in('M.id', $conf['id']);
		}

		if ($conf['list_id']) {
			ee()->db->join(
					'courier_member_lists L',
					'M.id = L.member_id'
				)
				->where_in('L.list_id', $conf['list_id']);
		}

		if ($conf['member_name']) {
			ee()->db->where_in('M.member_name', $conf['member_name']);
		}

		if ($conf['member_email']) {
			ee()->db->where_in('M.member_email', $conf['member_email']);
		}

		return ee()->db->get()->result();
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

	public function insertNewMemberIntoList($listId, $name, $email)
	{
		$memberCheck = $this->getMembers(array(
			'member_email' => $email
		));

		if ($memberCheck) {
			$this->attachMemberToList(
				$listId,
				$memberCheck[0]->id
			);

			return;
		}

		$this->insertNewMember(array(
			'name' => $name,
			'email' => $email
		));

		$member = $this->getMembers(array(
			'member_email' => $email
		));

		$this->attachMemberToList(
			$listId,
			$member[0]->id
		);
	}

	public function attachMemberToList($listId, $memberId)
	{
		// Check member lists
		$check = ee()->db->select('*')
			->from('courier_member_lists')
			->where('list_id', $listId)
			->where('member_id', $memberId)
			->get()
			->result();

		if ($check) {
			return false;
		}

		ee()->db->insert('courier_member_lists', array(
			'list_id' => $listId,
			'member_id' => $memberId
		));

		$this->updateListCounts();
	}

	public function removeMembersFromList($listId, $delete)
	{
		if ($listId && $delete) {
			ee()->db->where('list_id', $listId);
			ee()->db->where_in('member_id', $delete);
			ee()->db->delete('courier_member_lists');

			$this->updateListCounts();
		}
	}

	public function updateListCounts()
	{
		$lists = $this->getLists();

		foreach ($lists as $list) {
			$query = ee()->db->select('COUNT(*) AS count')
				->from('courier_member_lists')
				->where('list_id', $list->id)
				->get()
				->row();

			ee()->db->where('id', $list->id);
			ee()->db->update('courier_lists', array(
				'member_count' => $query->count
			));
		}
	}
}