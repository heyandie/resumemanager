<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resume_editor extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_model');
		$this->load->model('account_model');
		$this->load->model('resume_model');
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
		if(($this->session->userdata('student_number')!=""))
		{

			$data=$this->get_resume_contents();
			$data['main_content']  = "resume_editor/index";
			$data['resume_editor']=1;
			$this->load->view('includes/template', $data);
		}
		else{
			redirect('login');
		}

	}
	public function get_resume_contents(){
		if(($this->session->userdata('student_number')!=""))
		{

			$student_number=$this->session->userdata('student_number');
			$data=array();
			$data['student_number']=$student_number;
			$data['personal_info'] = json_encode($this->account_model->get_personal_info($student_number));
			$data['student_type'] = json_encode($this->account_model->get_student_type($student_number));
			$data['education_graduate'] = json_encode($this->account_model->get_education_graduate($student_number));
			$data['education_undergraduate'] = json_encode($this->account_model->get_education_undergraduate($student_number));
			$data['education_highschool'] = json_encode($this->account_model->get_education_highschool($student_number));
			$recognition=$this->account_model->get_recognitions($student_number);
			$data['graduate_recognitions']=json_encode($recognition['graduate']);
			$data['undergraduate_recognitions']=json_encode($recognition['undergraduate']);
			$data['highschool_recognitions']=json_encode($recognition['highschool']);
			$data['work_experience']=json_encode($this->account_model->get_work_experience($student_number));
			$data['affiliations']=json_encode($this->account_model->get_affiliations($student_number));
			$data['skills']=$this->account_model->get_skills($student_number);
			$data['skills']=preg_replace("/\r\n|\r|\n/",'<br/>',$data['skills']);
			return $data;
		}
		else{
			redirect(site_url());
		}
	}
	public function save_item(){
		if($this->input->post('section')==""){
			redirect(site_url(),'location');
		}else{
			
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
				if($this->resume_model->save_item()){
					$response=array();
					$response['code']=302;
					print json_encode($response);
				}
			}else{
				$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
			}
			

			

		}
	}

	public function save_skills(){
		if($this->input->post('section')==""){
			$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
		}else{
			
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
				if($this->resume_model->save_skills()){
					$response=array();
					$response['code']=302;
					print json_encode($response);
				}
			}else{
				$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
			}
			

			

		}
	}

	public function save_personal_information(){
		if($this->input->post('section')==""){
			redirect(site_url(),'location');
		}else{
			
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
				if($this->resume_model->save_personal_information()){
					$response=array();
					$response['code']=1;
					print json_encode($response);
				}else{
					$response=array();
					$response['code']=-1;
					$response['url']=site_url();
					print json_encode($response);
				}
			}else{
				$response=array();
				$response['code']=-1;
				$response['url']=site_url();
				print json_encode($response);
			}
			

			

		}
	}

	public function save_work_experience(){
		if($this->input->post('section')==""){
			redirect(site_url(),'location');
		}else{
			
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
				$result=$this->resume_model->save_work_experience();
				if($result){

					$response=array();
					$response['code']=302;
					if(!$this->input->post('work_id'))
						$response['work_id']=$result['work_id'];
					if(isset($result['from_date']))
						$response['from_date']=$result['from_date'];
					if(isset($result['work_descriptions']))
						$response['work_descriptions']=$result['work_descriptions'];
					print json_encode($response);
				}else{
					$response=array();
					$response['code']=404;
					$response['url']=site_url();
					print json_encode($response);
				}
			}else{
				$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
			}
			

		}
	}

	public function save_education(){
		if($this->input->post('section')==""){
			redirect(site_url(),'location');
		}else{
			
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
				$result=$this->resume_model->save_education();
				if($result){

					$response=array();
					$response['code']=302;
					if(isset($result['recognitions']))
						$response['recognitions']=$result['recognitions'];
					print json_encode($response);
				}else{
					$response=array();
					$response['code']=404;
					$response['url']=site_url();
					print json_encode($response);
				}
			}else{
				$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
			}
			

		}
	}
	public function save_affiliation(){
		if($this->input->post('section')==""){
			redirect(site_url(),'location');
		}else{
			
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
				$result=$this->resume_model->save_affiliation();
				if($result==-1){
					$response=array();
					$response['code']=404;
					$response['url']=site_url();
					print json_encode($response);
				}elseif($result){

					$response=array();
					$response['code']=302;
					if(!$this->input->post('affiliation_id'))
						$response['affiliation_id']=$result['affiliation_id'];
					if(isset($result['committees'])){
						$response['committees']=$result['committees'];
					}
					print json_encode($response);
				}else{
					$response=array();
					$response['code']=404;
					$response['url']=site_url();
					print json_encode($response);
				}
			}else{
				$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
			}
			

		}
	}

	public function delete_work_experience(){
		if($this->input->post('section')==""){
			redirect(site_url(),'location');
		}else{
			
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
				$result=$this->resume_model->delete_work_experience();
				if($result==1){

					$response=array();
					$response['code']=1;
					print json_encode($response);
				}elseif($result==-1){
					$response=array();
					$response['code']=-1;
					$response['url']=site_url();
					print json_encode($response);
				}elseif($result==0){
					$response=array();
					$response['code']=0;
					print json_encode($response);
				}
			}else{
				$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
			}
			

		}
	}

	public function delete_affiliation(){
		if($this->input->post('section')==""){
			redirect(site_url(),'location');
		}else{
			
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
				$result=$this->resume_model->delete_affiliation();
				if($result==1){

					$response=array();
					$response['code']=1;
					print json_encode($response);
				}elseif($result==-1){
					$response=array();
					$response['code']=-1;
					$response['url']=site_url();
					print json_encode($response);
				}elseif($result==0){
					$response=array();
					$response['code']=0;
					print json_encode($response);
				}
			}else{
				$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
			}
			

		}
	}

	public function save_affiliations_order(){
		if($this->input->post('section')==""){
			redirect(site_url(),'location');
		}else{
			
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
				$result=$this->resume_model->save_affiliations_order();
				if($result==1){

					$response=array();
					$response['code']=1;
					print json_encode($response);
				}
			}else{
				$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
			}
			

		}
	}

	public function delete_recognition(){
		if($this->input->post('student_number')==$this->session->userdata('student_number')){
			$this->resume_model->delete_recognition();
		}else{
			$response=array();
			$response['code']=404;
			$response['url']=site_url();
			print json_encode($response);

		}
	}
	public function submit_resume(){
		if(($this->session->userdata('student_number')!=""))
		{
			if($this->input->post('student_number')==$this->session->userdata('student_number')){
			
				$this->load->database();

				// Set has_resume to 1
				$table="account";
				$array=array("has_resume"=>"0");
				$this->db->where("student_number",(string)$this->session->userdata('student_number'));
				$this->db->update($table,$array);
				$this->db->flush_cache();
				$data=$this->get_resume_contents();
				$data['nopreload']=true;
				$data['colorscheme']=$this->input->post('colorscheme');
				$this->load->view('resume_output/resume_html', $data);
				$html = $this->output->get_output();
				
				// Load library
				$this->load->library('dompdf_gen');
				$this->resume_model->update_has_resume($this->session->userdata('student_number'));
				// Convert to PDF
				$this->dompdf->load_html($html);
				$this->dompdf->render();
				$pdf = $this->dompdf->output();


				$application_type=$this->input->post('application_type');			
				if($application_type==1){
					$application_type="graduating";
				}else{
					$application_type="internship";
				}

			
				$this->load->database();
				$student_number=$this->session->userdata('student_number');
				$this->db->select('student_type,resume_file,first_name,last_name,email_address,resume_upload');
				$this->db->where('student_number',$student_number);
				$account=$this->db->get('account')->row();
				$this->db->flush_cache();
				if($account->student_type==1){
					$this->db->where('student_number',$student_number);
					$this->db->select('degree_program');
					$table='resume_education_undergraduate';
					$student_type="undergraduate";
				}else if($account->student_type==2){
					$this->db->where('student_number',$student_number);
					$this->db->select('degree_program');
					$table='resume_education_graduate';
					$student_type="graduate";
				}
				$this->session->unset_userdata(array('email_address'=>''));
				$this->session->set_userdata('email_address', $account->email_address);
				$old_resume=$account->resume_file;
				if($old_resume!=""){
					unlink($old_resume);
				}
				$degree_program=$this->db->get($table)->row()->degree_program;
				$this->db->flush_cache();
				$file_location=$_SERVER['DOCUMENT_ROOT']."/uploads/resumes/".$student_type.'/'.$application_type.'/'.$degree_program;

				if (!file_exists($file_location)) {
				    mkdir($file_location, 0777, true);
				}
				$filename=$account->last_name.", ".$account->first_name." - ".$student_number.".pdf";
				$file_location=$file_location."/".$filename;
				file_put_contents($file_location,$pdf); 

				$file_upload="";
                $has_upload=0;
                if(isset($_FILES['userfile'])){
	            	if($account->resume_upload!=""){
						unlink($account->resume_upload);
					}
	            	$config['upload_path'] = $_SERVER['DOCUMENT_ROOT']."/uploads/resumes-uploaded/".$student_type.'/'.$application_type.'/'.$degree_program;
	            	if (!file_exists($config['upload_path'])) {
					    mkdir($config['upload_path'], 0777, true);
					}
					$config['allowed_types'] = 'pdf';
					$config['file_name'] = $filename;
					$config['max_size']	= TRUE;
					$config['overwrite']	= '5000000';
					$config['remove_spaces']= FALSE;
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload('userfile'))
					{
						$data = array('message' => 'File upload error.');
						$this->output->set_output(0);

					}
					else
					{
						$file_upload=$config['upload_path']."/".$filename;
					}

                }
                $maildata=array();
                if($file_upload!=""){
                	$has_upload=1;
                	$maildata['upload']=TRUE;
                }
                	

				$this->load->library("my_phpmailer");
                $mail = $this->my_phpmailer->new_mail();
                $mail->IsHTML(true);
                $mail->SetFrom('resumebox@upcapes.org', 'UP CAPES');  //Who is sending the email
                $mail->Subject    = $account->first_name.", your resume has arrived.";
               
                $maildata['first_name']=$account->first_name;
                $maildata['last_name']=$account->last_name;
                $mail->Body      = $this->load->view('mail/upload_confirm', $maildata , TRUE); 
                $mail->AddAddress($account->email_address, $maildata['first_name']." ".$maildata['last_name']);
               	$mail->AddAttachment($file_location,"Resume - ".$filename);
                $mail->Send();

               
               
				$this->load->database();
				$resume_details=array(
					'resume_type'=>$this->input->post('application_type'),
					'resume_file'=>$file_location,
					'resume_upload'=>$file_upload,
					'has_upload'=>$has_upload,
					'resume_submitted_at'=>date('Y-m-d H:i:s'));
				$this->db->where('student_number',$this->session->userdata('student_number'));
				$this->db->update('account',$resume_details);
			}else{
				$response=array();
				$response['code']=404;
				$response['url']=site_url();
				print json_encode($response);
			}
			
			
		}
		else{
			redirect(site_url());
		}
	}
	public function stream_resume_to_html(){
		
			
			$student_number=$_GET['student_number'];
			if(!$student_number)
				return;
			$data=array();
			$data['student_number']=$student_number;
			$data['personal_info'] = json_encode($this->account_model->get_personal_info($student_number));
			$data['student_type'] = json_encode($this->account_model->get_student_type($student_number));
			$data['education_graduate'] = json_encode($this->account_model->get_education_graduate($student_number));
			$data['education_undergraduate'] = json_encode($this->account_model->get_education_undergraduate($student_number));
			$data['education_highschool'] = json_encode($this->account_model->get_education_highschool($student_number));
			$recognition=$this->account_model->get_recognitions($student_number);
			$data['graduate_recognitions']=json_encode($recognition['graduate']);
			$data['undergraduate_recognitions']=json_encode($recognition['undergraduate']);
			$data['highschool_recognitions']=json_encode($recognition['highschool']);
			$data['work_experience']=json_encode($this->account_model->get_work_experience($student_number));
			$data['affiliations']=json_encode($this->account_model->get_affiliations($student_number));
			$data['skills']=$this->account_model->get_skills($student_number);
			$data['skills']=preg_replace("/\r\n|\r|\n/",'<br/>',$data['skills']);
			$data['nopreload']=true;
			$this->load->view('resume_output/resume_html', $data);
			$html = $this->output->get_output();
			// Load library
			$this->load->library('dompdf_gen');
			
			// Convert to PDF

			$this->dompdf->load_html($html);
			$this->dompdf->render();
			$this->dompdf->stream("welcome.pdf",array('Attachment'=>0));

		
	}
}

/* End of file resume_editor.php */
/* Location: ./application/controllers/resume_editor.php */