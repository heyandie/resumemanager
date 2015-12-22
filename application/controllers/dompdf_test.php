<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dompdf_test extends CI_Controller {

	/**
	 * Example: DOMPDF 
	 *
	 * Documentation: 
	 * http://code.google.com/p/dompdf/wiki/Usage
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_model');
		$this->load->model('account_model');
		$this->load->model('resume_model');
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
			return $data;
		}
		else{
			redirect(site_url());
		}
	}
	public function index() {	
		
		$this->load->driver('minify');
		$files =['js/personal-infomodel.js','js/educationmodel.js','js/workmodel.js','js/affiliationmodel.js','js/resume.js'];
		/*file_put_contents('js/companieschecklist.min.js', $this->minify->min("js/companieschecklist.js"));*/
		file_put_contents('js/resume-1.0.5.min.js', "");

		foreach($files as $file) {
			file_put_contents('js/resume-1.0.5.min.js', $this->minify->min($file),FILE_APPEND);
		}
		$file='css/signup.css';
		file_put_contents('css/signup-1-20-15-3-25.min.css', $this->minify->min($file));
	}
}
//