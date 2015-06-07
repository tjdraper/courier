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
			lang('courier_members_page_tab_name') => $this->baseUrl . AMP . 'method=members'
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
			$this->submission('insertNewList', 'updateLists');
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
			$this->submission('insertNewMember', 'updateMembers');
		}

		// Set breadcrumb and page name from language file
		ee()->cp->set_breadcrumb($this->baseUrl, lang('courier_lists_page_name'));
		ee()->view->cp_page_title = lang('courier_members_page_name');

		// Get members and set to variable for view
		$this->vars['members'] = ee()->courier_model->getAllMembers();

		// Download a CSV if requested
		if (ee()->input->get('csv') === 'true') {
			$this->vars['csv_items'] = array(
				'member_name' => 'Name',
				'member_email' => 'Email'
			);
			$this->vars['csv_item_variable'] = 'members';
			$this->vars['csv_body'] = $this->vars['members'];
			$this->csvDownload('All Members.csv');
		}

		// Return the view
		return ee()->load->view('members', $this->vars, true);
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
	private function submission($modelInsertMethod, $modelUpdateMethod)
	{
		// Check for new list submission
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
}