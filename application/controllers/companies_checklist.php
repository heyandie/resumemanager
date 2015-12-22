<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies_checklist extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('account_model');
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

		if(($this->session->userdata('student_number')!=""))
		{
			$data=array();
			$data['main_content']  = "checklist/index";
			$data['student_number']=$this->session->userdata('student_number');
			$data['degree_program']=$this->account_model->get_degree_program($data['student_number']);
			$data['schedule'] = json_encode($this->company_model->get_current_schedule());
			$data['today']=date("Y-m-d");
			$data['has_resume']=$this->account_model->has_resume($data['student_number']);
			$data['companies'] = json_encode($this->company_model->get_companies_checklist());
			$this->load->view('includes/template', $data);
		}
		else{
			redirect(site_url());
		}

	}

	public function save_checklist(){
		if(($this->session->userdata('student_number')==$_POST['student_number']))
		{
			if($this->company_model->save_checklist()){
				$response=array();
				$response['code']=300;
				echo json_encode($response);
			}
		}
		else{
			$response=array();
			$response['code']=404;
			$response['url']=site_url('login');
			echo json_encode($response);
		}
	}

}

/* End of file companies_checklist.php */
/* Location: ./application/controllers/companies_checklist.php */