<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('account_model');
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
			redirect('companies_checklist');
		}
		else{


			$data=array();
			$data['main_content']  = "account/register";
			$this->load->view('includes/template', $data);

			
		}
		

	}
	
	
	function _set_rules(){
		
		$this->form_validation->set_rules('student_number', 'Student number', 'trim|required|exact_length[9]|alpha_dash|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|
        	max_length[12]|xss_clean');

		$this->form_validation->set_message('required', '%s is required.');
		$this->form_validation->set_message('alpha_dash', '%s may only contain alpha-numeric characters, underscores, and dashes.');

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}

	public function logout()
	{
		$this->clear_cache();
	  	$newdata = array(
	  	'student_number'=>'',
        'first_name'=>'',
        'last_name'=>'',
        'name'=>'',
        'email_address'=>'',
        'student_type'=>'',
        'logged_in'=>''
		);
		$this->session->unset_userdata($newdata);
		$this->session->sess_destroy();

		
		redirect(site_url('login'));
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */