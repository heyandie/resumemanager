<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

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


	public function index(){

		if(($this->session->userdata('student_number')!=""))
		{
			redirect('companies_checklist');
		}
		else{

			$data=array();
			$data['main_content']  = "account/login";
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

	public function auth(){
		$this->clear_cache();

		$this->_set_rules();
		if ($this->form_validation->run() == FALSE){
			echo 0;
		}else{
			$student_number = $this->input->post('student_number');
			$password = $this->input->post('password');
			$result = $this->account_model->login($student_number,$password);
			if(!$result){
	
		
	            echo false ;
			}
			else{
				$result=array();
				$result['alert_class']="alert-success";
				$result['alert_message']="Successful! Redirecting..";
				$result['url']=site_url('companies_checklist');
				$result['timeout']=1500;
				print json_encode($result);
			}
		}

			

	}
	public function forgot_password(){
		$this->clear_cache();
		$data=array();
		$data['main_content']  = "account/forgot_password";

		if(($this->session->userdata('student_number')!=""))
		{
			redirect('resume_editor');
		}else{
			
			    $this->load->view('includes/template',$data);
		}

		
		
	}

	public function reset_password(){
		$this->clear_cache();
		$data['main_content']  = "account/reset_password";
		if(($this->session->userdata('student_number')!=""))
		{
			redirect('resume_editor');
		}else{
			if($_GET['id']==""){
				redirect('login');
			}else{
				$data['key']=$_GET['id'];
				$this->load->database();
				$this->db->where('key',$data['key']);
				$row=$this->db->get('reset_password')->row();
				if($row){
					$this->load->view('includes/template',$data);
				}else{
					redirect('login');
				}
			    
			}
			
		}
	}

	public function confirm_reset_password(){
		$this->clear_cache();
		$data['main_content']  = "account/reset_password";
		if(($this->session->userdata('student_number')!=""))
		{
			redirect('resume_editor');
		}else{
			if($_POST['request_id']==""){
				redirect('register');
			}else{
				$key=$_POST['request_id'];
				$student_number=$_POST['student_number'];
				$this->load->database();
				$this->db->where('key',$key);
				$this->db->where('student_number',$student_number);
				$row=$this->db->get('reset_password')->row();
				$this->db->flush_cache();
				if($row){

		
					$password=$this->account_model->save_new_password();
					$this->db->where('key',$key);
					$this->db->delete('reset_password');
					$this->db->flush_cache();
					//$this->account_model->login($student_number,$password);
					$this->session->set_flashdata('success',"Congratulations. You have successfully reset your password.");
					$this->session->set_flashdata('student_number',$student_number);
					redirect('login');
				}else{
					redirect('login');
				}
			    
			}
			
		}
	}

	public function check_account(){

		if ($this->input->post('student_number')!="") {
		    $this->load->database();
		    $this->db->select('first_name,last_name,email_address');
			$this->db->where('student_number',$this->input->post('student_number'));
		    $query=$this->db->get('account');
		    $this->db->flush_cache();

		    if($query->row()){
		    	$account['name']=$query->row()->first_name." ".$query->row()->last_name;

		    	$mail_segments = explode("@", $query->row()->email_address);
			    $mail_segments[0] =$mail_segments[0][0].str_repeat("*", strlen($mail_segments[0])-2).$mail_segments[0][strlen($mail_segments[0])-1];

			    
				$account['email_address']=implode("@", $mail_segments);
				
		    	print_r(json_encode($account));
		    	
		    }else{
		    	echo "";
		    }
		
		}
	}

	public function send_password_reset_link(){

		if($this->input->post('student_number')!=""){
			function _random_string($length) {
			    $key = '';
			    $keys = array_merge(range(0, 9), range('a', 'z'),range('A', 'Z'));

			    for ($i = 0; $i < $length; $i++) {
			        $key .= $keys[array_rand($keys)];
			    }

			    return $key;
			}
			
			$student_number=$this->input->post('student_number');
			$this->load->database();
			$this->db->where('student_number',$student_number);
			$row=$this->db->get('reset_password')->row();
			if($row){
				print_r(2);
				return false;
			}
			$row=1;
			while($row){
				$key=_random_string(15);
				$this->db->where('key',$key);
				$row=$this->db->get('reset_password')->row();
				$this->db->flush_cache();
			}
			$array=array('student_number'=>$student_number,'key'=>$key);
			$this->db->insert('reset_password',$array);
			
			$this->load->library("my_phpmailer");

            $mail = $this->my_phpmailer->new_mail();
            $mail->IsHTML(true);
            $mail->SetFrom('resumebox@upcapes.org', 'UP CAPES');  //Who is sending the email
            $mail->Subject = "Reset password request for the Resume Manager";
            $maildata=array();
            $this->db->select('first_name,last_name,email_address');
           	$this->db->where('student_number',$student_number);
           	$row=$this->db->get('account')->row();
			$this->db->flush_cache();

            $maildata['first_name']=$row->first_name;
            $maildata['last_name']=$row->last_name;
            $maildata['key']=$key;
            $mail->Body = $this->load->view('mail/password_reset', $maildata , TRUE); 
            $mail->AddAddress($row->email_address, $row->first_name." ".$row->last_name);

			$mail->Send();

			print_r(1);
		}
		
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */