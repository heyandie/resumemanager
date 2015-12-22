<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {

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
        $this->clear_cache();
        if(($this->session->userdata('student_number')==""))
        {
            $data['main_content']  = "account/register";
            $this->load->view('includes/template', $data);
        }
        else{
            redirect(site_url(),'location');
        }
		

	}

	public function submit(){
		$this->clear_cache();

		// set validation properties       
        $this->_set_create_rules();

        // run validation
        //if all input in the form are valid
        if ($this->form_validation->run() == FALSE){
        	 // Set the flashdata that will be preserved for only the next server request
            if (form_error('first_name')) {
                $this->session->set_flashdata('first_name',form_error('first_name'));
               
            }
            if (form_error('last_name')) {
                $this->session->set_flashdata('last_name',form_error('last_name'));
                
            }
            if (form_error('student_number')) {
                $this->session->set_flashdata('student_number',form_error('student_number'));
                
            }
            if (form_error('email_address')) {
                $this->session->set_flashdata('email_address',form_error('email_address'));
                
            }
            $this->session->set_flashdata('first_name_val',$this->input->post('first_name'));
            $this->session->set_flashdata('last_name_val',$this->input->post('last_name'));
            $this->session->set_flashdata('student_number_val',$this->input->post('student_number'));
            $this->session->set_flashdata('email_address_val',$this->input->post('email_address'));
        	redirect(base_url());
        }
			
		else{
			if($this->account_model->create()){
                $this->load->library("my_phpmailer");

                $mail = $this->my_phpmailer->new_mail();
                $mail->IsHTML(true);
                $mail->SetFrom('resumebox@upcapes.org', 'UP CAPES');  //Who is sending the email
                $mail->Subject    = "Welcome to UP CAPES Resume Box";
                $maildata=array();
                $maildata['first_name']=$this->input->post('first_name');
                $maildata['last_name']=$this->input->post('last_name');
                $mail->Body      = $this->load->view('mail/welcome', $maildata , TRUE); 
                $mail->AddAddress($this->input->post('email_address'), $this->input->post('first_name')." ".$this->input->post('last_name'));

                $this->session->set_flashdata('email',$this->input->post('email_address'));
                $this->session->set_flashdata('registered',$this->input->post('student_number'));
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


                $mail->Send();
                $newdata=array(

                    'student_number'=>$this->input->post('student_number'),
                    'first_name'=>$this->input->post('first_name'),
                    'last_name'=>$this->input->post('last_name'),
                    'name'=>$this->input->post('first_name').' '.$this->input->post('last_name'),
                    'email_address'=>$this->input->post('email_address'),
                    'student_type'=>'1',
                    'logged_in'=>true

                );
                 $this->session->set_userdata($newdata);
                 redirect('resume_editor','location');
            }
			redirect('site_url()','location');
		}
	}
	function alpha_space($str)
    {
        $this->form_validation->set_message('alpha_space','Only alphabetic characters and spaces are allowed.');
        return ( ! preg_match("/^([ña-z0-9 ])+$/i", $str)) ? FALSE : TRUE;
    } 

	// validation rules
    function _set_create_rules(){
               
        $this->form_validation->set_rules('first_name', 'First name', 'trim|required|callback_alpha_space|max_length[35]|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|callback_alpha_space|max_length[35]|xss_clean');
        $this->form_validation->set_rules('student_number', 'Student number', 'trim|required|numeric|exact_length[9]|is_unique[account.student_number]|xss_clean');
        $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email|is_unique[account.email_address]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|
        	max_length[12]|xss_clean');
		
		
       
        $this->form_validation->set_message('required', '%s is required.');
        $this->form_validation->set_message('is_unique', '%s already exists.');
        $this->form_validation->set_message('min_length[6]', '%s is must be at least 6 characters.');
        $this->form_validation->set_message('max_length[15]', '%s cannot be more than 15 characters.');
        $this->form_validation->set_message('exact_length[9]','%s must consist of 9 numbers.');
        $this->form_validation->set_message('numeric', '%s may only contain numbers.');
        $this->form_validation->set_message('alpha_dash', '%s may only contain alpha-numeric characters, underscores, and dashes.');
        $this->form_validation->set_message('alpha', '%s may only contain alphabetic characters.');
        $this->form_validation->set_message('valid_email', '%s must contain a valid email address.');
        $this->form_validation->set_message('isset', '%s is required.');
 

        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */