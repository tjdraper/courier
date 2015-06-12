<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

// Include configuration
include(PATH_THIRD . 'courier/config.php');

/**
 * Courier control panel
 *
 * @package courier
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/ee-add-ons/courier
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

class Courier_mcp
{
	public function __construct()
	{
		// Load the model
		ee()->load->model('courier_model');

		// Set class variables
		$this->modulesUrl = BASE . AMP . 'C=addons_modules';
		$this->baseUrl = $this->modulesUrl . AMP .
			'M=show_module_cp' . AMP . 'module=courier';
		$this->methodUrl = $this->baseUrl . AMP . 'method=';
		$this->themesUrl = URL_THIRD_THEMES . 'courier/';

		// Set initial breadcrumb to try to provide some consistentcy
		ee()->cp->set_breadcrumb($this->modulesUrl, 'Modules');

		// Set nav
		ee()->cp->set_right_nav(array(
			lang('courier_lists_page_tab_name') => $this->baseUrl,
			lang('courier_members_page_tab_name') => $this->baseUrl . AMP . 'method=members',
			lang('courier_settings_tab_name') => $this->baseUrl . AMP . 'method=settings'
		));

		// Set JS
		ee()->cp->load_package_js('courier.min');

		// Set CSS
		// ee()->cp->add_to_head('<link rel="stylesheet" href="' . $this->themesUrl . 'chosen.min.css">');
		// ee()->cp->load_package_css('collective.min');

		// Set vars we always want in the views
		$this->vars = array(
			'base_url' => $this->baseUrl,
			'method_url' => $this->methodUrl,
			'themes_url' => $this->themesUrl
		);
	}

	/**
	 * Home page
	 *
	 * @return string
	 */
	public function index()
	{
		// Check for post to process
		if ($_POST) {
			$this->submission('deleteLists', 'insertNewList', 'updateLists');
		}

		// Set the page title from the language file
		ee()->view->cp_page_title = lang('courier_lists_page_name');

		// Get the lists and set to var to pss to view
		$this->vars['lists'] = ee()->courier_model->getLists();

		// Return the view
		return ee()->load->view('index', $this->vars, true);
	}

	/**
	 * Members page
	 *
	 * @return string
	 */
	public function members()
	{
		// Check for post to process
		if ($_POST) {
			$this->submission(
				'deleteMembers',
				'insertNewMember',
				'updateMembers'
			);
		}

		// Get members and set to variable for view
		$this->vars['members'] = ee()->courier_model->getAllMembers();

		// Download a CSV if requested
		if (ee()->input->get('csv') === 'true') {
			$this->vars['csv_items'] = array(
				'member_name' => 'Name',
				'member_email' => 'Email'
			);
			$this->vars['csv_item_variable'] = 'members';
			$this->csvDownload('All Members.csv');
		}

		// Set breadcrumb and page name from language file
		ee()->cp->set_breadcrumb($this->baseUrl, lang('courier_lists_page_name'));
		ee()->view->cp_page_title = lang('courier_members_page_name');

		// Return the view
		return ee()->load->view('members', $this->vars, true);
	}

	/**
	 * View list page
	 *
	 * @return string
	 */
	public function view_list()
	{
		// Check for post to process
		if ($_POST) {
			$this->listSubmission();
		}

		// Get the list data
		$this->vars['listData'] = ee()->courier_model->getListWithMembers(array(
			'list_id' => ee()->input->get('id', true)
		));

		// Get all members
		$this->vars['members'] = ee()->courier_model->getAllMembers();

		// Remove members that are already on the list
		foreach ($this->vars['listData']['members'] as $key => $val) {
			foreach ($this->vars['members'] as $mKey => $mVal) {
				if ($val->id === $mVal->id) {
					unset($this->vars['members'][$mKey]);

					break;
				}
			}
		}

		// Download a CSV if requested
		if (ee()->input->get('csv') === 'true') {
			$this->vars['csv_items'] = array(
				'member_name' => 'Name',
				'member_email' => 'Email'
			);
			$this->vars['csv_item_variable'] = 'members';
			$this->vars['members'] = $this->vars['listData']['members'];
			$this->csvDownload($this->vars['listData']['list']->list_name . ' Members.csv');
		}

		// Set breadcrumb and page name from language file
		ee()->cp->set_breadcrumb($this->baseUrl, lang('courier_lists_page_name'));
		ee()->view->cp_page_title = $this->vars['listData']['list']->list_name;

		// Return the view
		return ee()->load->view('view_list', $this->vars, true);
	}

	/**
	 * Settings page
	 *
	 * @return string
	 */
	public function settings()
	{
		// Set breadcrumb and page name from language file
		ee()->cp->set_breadcrumb($this->baseUrl, lang('courier_lists_page_name'));
		ee()->view->cp_page_title = lang('courier_settings_page_name');

		$siteUrl = trim(ee()->config->item('site_url'), '/');
		$actionId = ee()->cp->fetch_action_id('Courier', 'check_mail');
		$this->vars['cron'] = $siteUrl . '/index.php?ACT=' . $actionId;

		return ee()->load->view('settings', $this->vars, true);
	}

	/**
	 * Set a CSV Download
	 *
	 * @param string $filename
	 *
	 * @return string
	 */
	private function csvDownload($filename = 'download.csv')
	{
		// Set the headers
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		// Send the CSV file
		exit(ee()->load->view('csv', $this->vars, true));
	}

	/**
	 * Process submissions
	 *
	 * @return void
	 */
	private function submission(
		$modelDeleteMethod = false,
		$modelInsertMethod = false,
		$modelUpdateMethod = false
	)
	{
		// Check for lists to delete
		if ($modelDeleteMethod) {
			$delete = array();

			if (isset($_POST['delete'])) {
				foreach ($_POST['delete'] as $key => $val) {
					$delete[] = ee()->security->xss_clean($val);
				}
			}

			if ($delete) {
				ee()->courier_model->{$modelDeleteMethod}($delete);
			}
		}

		// Check for new list submission
		if ($modelInsertMethod) {
			$new = array();

			foreach ($_POST['new'] as $key => $val) {
				if (! $val) {
					$new = array();

					break;
				}

				$key = ee()->security->xss_clean($key);

				$new[$key] = ee()->security->xss_clean($val);
			}

			if ($new) {
				ee()->courier_model->{$modelInsertMethod}($new);
			}
		}

		// Check for items to update
		if ($modelUpdateMethod) {
			$update = array();

			if (isset($_POST['update'])) {
				foreach ($_POST['update'] as $key => $val) {
					foreach ($val as $valKey => $valVal) {
						$update[ee()->security->xss_clean($key)][ee()->security->xss_clean($valKey)] = ee()->security->xss_clean($valVal);
					}
				}
			}

			if ($update) {
				ee()->courier_model->{$modelUpdateMethod}($update);
			}
		}
	}

	/**
	 * Process attach member submissions
	 *
	 * @return void
	 */
	private function listSubmission()
	{
		$listId = ee()->input->post('list_id', true);

		// Check for addition of existing member
		$existingMemberId = ee()->input->post('existing_member', true);

		if ($existingMemberId) {
			ee()->courier_model->attachMemberToList(
				$listId,
				$existingMemberId
			);
		}

		// Check for lists to delete
		$delete = array();

		if (isset($_POST['delete'])) {
			foreach ($_POST['delete'] as $key => $val) {
				$delete[] = ee()->security->xss_clean($val);
			}
		}

		if ($delete) {
			ee()->courier_model->removeMembersFromList($listId, $delete);
		}

		// Check if a new name and email is being submitted
		$name = ee()->input->post('new_name');
		$email = ee()->input->post('new_email');

		if ($name && $email) {
			ee()->courier_model->insertNewMemberIntoList(
				$listId,
				$name,
				$email
			);
		}
	}
}