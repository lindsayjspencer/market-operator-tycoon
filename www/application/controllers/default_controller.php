<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'helpers/EnvironmentHelper.php';

class default_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('default_view');
	}

}

/* End of file market_controller.php */
/* Location: ./application/controllers/market_controller.php */
