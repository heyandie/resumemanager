<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('company_model');
	}
	function clear_cache(){
		$this->output->set_header("Expires: Tue, 01 Jan 2000 00:00:00 GMT"); 
		$this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); 
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false); 
		$this->output->set_header("Pragma: no-cache"); 
	}

	public function index()
	{
			date_default_timezone_set('Asia/Manila');
    		 $today=date('Y-m-d');
    		 echo $today;
	}

}

/* End of file company_checklist.php */
/* Location: ./application/controllers/company_checklist.php */