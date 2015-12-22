<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_model');
		$this->load->model('company_model');
		$this->load->helper('html');

		

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
		$data=array();
		$data['admin_css']=link_tag('css/admin.css');

		$this->clear_cache();
		if(($this->session->userdata('admin')!=""))
		{
			redirect('admin/company_list');
		}
		else{
			
			$data['main_content']  = "admin/login";
			$this->load->view('includes/template', $data);
		}

	}

	public function login()
	{
		$this->clear_cache();
		if(($this->session->userdata('admin')!=""))
		{
			redirect('admin');
		}
		else{

			
			$this->_set_rules();
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$result = $this->admin_model->login($username,$password);
			if(!$result){
				$this->index();
			}
			else{
				redirect('admin');
			}
		}
	}

	public function logout()
	{
		$this->clear_cache();


	  	$newdata = array(
	  	'admin_id'   =>'',
		'admin'  =>'',
		'admin_logged_in' => FALSE,
		);
		$this->session->unset_userdata($newdata);
		$this->session->sess_destroy();

	
		redirect('admin');

	}

	public function company_list(){
		$this->clear_cache();
		$data=array();
		$data['admin_css']=link_tag('css/admin.css');
		if(($this->session->userdata('admin')==""))
		{
			redirect('admin');
		}
		else{
			$data['main_content']  ='admin/companies';
			$data['schedule'] = json_encode($this->company_model->get_schedule());
			$data['companies'] = json_encode($this->company_model->get_company_date());
			$this->load->view('includes/template', $data);
		}
	}

	public function manage_companies(){
		$this->clear_cache();
		$data=array();
		$data['admin_css']=link_tag('css/admin.css');
		if(($this->session->userdata('admin')==""))
		{
			redirect('admin');
		}
		else{
			$data['main_content']  ='admin/companies-manager';
			$data['companies'] = json_encode($this->company_model->get_schedule_by_company());
			$data['schedule'] = json_encode($this->company_model->get_schedule());
			$data['stat']=$this->admin_model->count_all_resumes();
			$this->load->view('includes/template', $data);
		}
	}

	public function manage_schedule(){
		$this->clear_cache();
		$data=array();
		$data['admin_css']=link_tag('css/admin.css');
		if(($this->session->userdata('admin')==""))
		{
			redirect('admin');
		}
		else{
			$data['main_content']  ='admin/schedule-manager';
			$data['schedule'] = json_encode($this->company_model->get_schedule());
			$this->load->view('includes/template', $data);
		}
	}

	public function add_company(){
		if(($this->session->userdata('admin')==""))
		{
			redirect('admin');
		}
		else{
			echo json_encode($this->company_model->add_company());
		}
	}

	public function update_company(){
		if(($this->session->userdata('admin')==""))
		{
			redirect('admin');
		}
		else{
			echo json_encode($this->company_model->update_company());
		}
	}
	public function update_company_name(){
		if(($this->session->userdata('admin')==""))
		{
			redirect('admin');
		}
		else{
			echo json_encode($this->company_model->update_company_name());
		}
	}
	public function delete_company(){
		if(($this->session->userdata('admin')==""))
		{
			redirect('admin');
		}
		else{
			echo json_encode($this->company_model->delete_company());
		}
	}
	public function count_resumes(){
		if(($this->session->userdata('admin')=="")){
			redirect('admin');
		}else{
			$this->admin_model->count_resumes();
		}
	}
	public function view_list_of_resumes(){
		if(($this->session->userdata('admin')=="")){
			redirect('admin');
		}else{
			$this->admin_model->view_list_of_resumes();
		}
	}
	public function download_resumes(){
		if(($this->session->userdata('admin')=="")){
			redirect('admin');
		}else{
			$this->admin_model->download_resumes();
		}
	}
	public function download_all_resumes(){

		if(($this->session->userdata('admin')=="")){
			redirect('admin');
		}else{
			//$this->load->library("zend");
			//$this->zend->load("Zend/Pdf");
		 	//$this->load->library("my_watermark");
			$this->admin_model->download_all_resumes();
	
		 	
			
		}
	}
	function _set_rules(){
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[6]|
        	max_length[12]|alpha_dash|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|
        	max_length[12]|xss_clean');

		$this->form_validation->set_message('required', '%s is required.');
		$this->form_validation->set_message('alpha_dash', '%s may only contain alpha-numeric characters, underscores, and dashes.');

		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */