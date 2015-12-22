<?php

class Account_Model extends CI_Model {
    function __construct() {
        parent::__construct();
		$this->load->database();
    }

    function login($student_number,$password){

    	$this->db->where("student_number",$student_number);
    	$this->db->where("password",$this->hashpass($password));
    	$query=$this->db->get("account");
    	$this->db->flush_cache();
    	if ($query->num_rows() > 0)
		{
		   $row = $query->row(); 
		   if($row->student_type==1)
		    $student_type="Undergraduate";
		   else
		   	$student_type="Graduate"; 	
		   $userdata = array(
		   	'student_number'=>$row->student_number,
		   	'first_name'=>$row->first_name,
		   	'last_name'=>$row->last_name,
		   	'name'=>$row->first_name." ".$row->last_name,
		   	'email_address'=>$row->email_address,
		   	'student_type'=>$student_type,
		   	'logged_in'=>true
		   	);
		   $this->session->set_userdata($userdata);
		   return true;
		}
		else return false;
    }

    function save_new_password(){
    	$data=array('password'=>$this->hashpass($this->input->post('password')));
    	$this->db->where('student_number',$_POST['student_number']);
    	$this->db->update('account',$data);
    	return $this->input->post('password');

    }

    function has_resume($student_number){
    	$this->db->select('has_resume');
    	$this->db->where('student_number',$student_number);
    	$result=$this->db->get('account')->row()->has_resume;
    	$this->db->flush_cache();
    	return $result;
    }
    function get_degree_program($student_number){
    	$this->db->select('degree_program');
    	$this->db->where('student_number',$student_number);
    	$result=$this->db->get('resume_education_undergraduate')->row()->degree_program;
    	$this->db->flush_cache();
    	return $result;
    }

    function create(){

    	
    	$data=array(

			'student_number'=>$this->input->post('student_number'),
			'first_name'=>$this->input->post('first_name'),
			'last_name'=>$this->input->post('last_name'),
		    'email_address'=>$this->input->post('email_address'),
		    'password'=>$this->hashpass($this->input->post('password')),
	    );
	    if($this->db->insert('account',$data))
	    	return true;
	    else
	    	return false;
        
    	
    }

    function hashpass($password) {
		return hash_hmac('sha1', 'upcapes2014', $password);
	}

	
	function get_student_type($student_number){
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("account");
		$this->db->flush_cache();
		if ($query->num_rows() > 0)
		{
		   $row = $query->row(); 
		   if($row->student_type==1)
		    $student_type="Undergraduate";
		   else
		   	$student_type="Graduate"; 	
		}
		return $student_type;
	}

	function get_personal_info($student_number){
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("account");
		$this->db->flush_cache();
		$result=$query->row();
		if(!$result){
			$personal_info=array();
			$personal_info['student_number']="";
			$personal_info['first_name']="";
			$personal_info['last_name']="";
			$personal_info['address']="";
			$personal_info['email_address']="";
			$personal_info['mobile_number']="";
		}else{
			$personal_info=array();
			$personal_info['student_number']=$result->student_number;
			$personal_info['first_name']=$result->first_name;
			$personal_info['last_name']=$result->last_name;
			$personal_info['address']=$result->address;
			$personal_info['email_address']=$result->email_address;
			$personal_info['mobile_number']=$result->mobile_number;

		}
		return $personal_info;
	}

	function get_education_graduate($student_number){
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_education_graduate");
		$this->db->flush_cache();
		$result=$query->row();
		$education=array();
		if(!$result){
			$education['school_name']="University of the Philippines Diliman";
			$education['graduation_month']="";
			$education['graduation_year']="";
			$education['degree_program']="";
		}else{
			foreach($result as $key=>$value){
				if($key!="student_number")
					$education[$key]=$value;
			}
		}

		$education['type']="graduate";
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_graduate_recognitions");
		$this->db->flush_cache();
		$result=$query->result();
		$education['recognitions']=$result;
		return $education;
	}

	function get_education_undergraduate($student_number){
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_education_undergraduate");
		$this->db->flush_cache();
		$result=$query->row();
		$education=array();
		if(!$result){
			
			$education['student_number']=$student_number;
			$student_type=$this->get_student_type($student_number);
			if($student_type=="Undergraduate")
				$education['school_name']="University of the Philippines Diliman";
			$this->db->insert("resume_education_undergraduate",$education);
			$query=$this->db->get("resume_education_undergraduate");
			$this->db->flush_cache();
			$result=$query->row();
		}
		$education=array();
		foreach($result as $key=>$value){
			if($key!="student_number"){
				$education[$key]=$value;
			}
		}

		$education['type']="undergraduate";
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_undergraduate_recognitions");
		$this->db->flush_cache();
		$result=$query->result();
		$education['recognitions']=$result;
		return $education;
	}

	function get_education_highschool($student_number){
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_education_highschool");
		$this->db->flush_cache();
		$result=$query->row();
		$education=array();
		if(!$result){
			
			$education['student_number']=$student_number;
			$this->db->insert("resume_education_highschool",$education);
			$query=$this->db->get("resume_education_highschool");
			$this->db->flush_cache();
			$result=$query->row();
		}
		$education=array();
		foreach($result as $key=>$value){
			if($key!="student_number"){
				$education[$key]=$value;
			}
		}
		$education['type']="highschool";
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_highschool_recognitions");
		$this->db->flush_cache();
		$result=$query->result();
		$education['recognitions']=$result;
		return $education;
	}

	function get_recognitions($student_number){
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_graduate_recognitions");
		$this->db->flush_cache();
		$result=$query->result();
		$recognitions['graduate']['type']='graduate';
		$recognitions['graduate']['recognitions']=$result;

		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_undergraduate_recognitions");
		$this->db->flush_cache();
		$result=$query->result();
		$recognitions['undergraduate']['type']='undergraduate';
		$recognitions['undergraduate']['recognitions']=$result;

		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_highschool_recognitions");
		$this->db->flush_cache();
		$result=$query->result();
		$recognitions['highschool']['type']='highschool';
		$recognitions['highschool']['recognitions']=$result;

		return $recognitions;
	}
	function get_skills($student_number){
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_skills");
		$this->db->flush_cache();

		$result=$query->row();
		if($result){
			return $result->skills;
		}
		else{
			return "";
		}
	}
	
	function get_work_experience($student_number){
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_work_experience");
		$this->db->flush_cache();
		$result=$query->result();
		foreach ($result as &$value) {
			$value->from_date=$value->from_month." ".$value->from_year;
			$value->from_date=strtotime($value->from_date);
			$this->db->where("work_id",$value->work_id);
			$value->work_descriptions=$this->db->get("resume_work_descriptions")->result();
			$this->db->flush_cache();
		}
		function work_sort_function($a, $b)
	    {
	        return $a->from_date < $b->from_date;
	    }
		usort($result, 'work_sort_function');
		return $result;
	}

	function get_affiliations($student_number){
		$this->db->where("student_number",$student_number);
		$query=$this->db->get("resume_affiliations");
		$this->db->flush_cache();
		$result=$query->result();

		function affiliation_sort_function($a, $b)
	    {
	    	if ($a->order  != $b->order ) {
        		return $a->order > $b->order;
		    } else {
		       return $a->affiliation_id > $b->affiliation_id;
		    }
	    }
		function committee_sort_function($a, $b)
	    {
	        return $a->from_date < $b->from_date;
	    }
		foreach ($result as &$value){
			$this->db->where("affiliation_id",$value->affiliation_id);
			$value->committees=$this->db->get("resume_affiliations_committees")->result();
			$this->db->flush_cache();
		
			foreach ($value->committees as &$value) {
				$value->from_date=$value->from_month." ".$value->from_year;
				$value->from_date=strtotime($value->from_date);
				$value->to_present=$value->to_present ? true : false;
			}
			
		}
		foreach ($result as &$value) {
			usort($value->committees, 'committee_sort_function');
		}
		usort($result, 'affiliation_sort_function');
		
		return $result;
	}

}
?>