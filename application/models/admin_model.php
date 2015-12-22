<?php


class Admin_Model extends CI_Model {
    function __construct() {
        parent::__construct();
		$this->load->database();
    }

    function login($username,$password){
    	if($username="admin"&&$password=="password") {
			//add all data to session

		    /*foreach($query->result() as $rows)
   			{
  
				$newdata = array(
			      'admin_id'  => $rows->admin_id,
			      'admin'  => $rows->username,
			      'admin_logged_in'  => TRUE
			    );

		   		$this->session->set_userdata($newdata);
			}*/
			$newdata = array(
			      'admin_id'  => 1,
			      'admin'  => $username,
			      'admin_logged_in'  => TRUE
			    );

		   		$this->session->set_userdata($newdata);
		   return true;
		}
		else return false;
    }
  	function deldir($dir){ 
	  $current_dir = opendir($dir); 
	  while($entryname = readdir($current_dir)){ 
	     if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){ 
	       $this->deldir("${dir}/${entryname}"); 
	     }elseif($entryname != "." and $entryname!=".."){ 
	       unlink("${dir}/${entryname}"); 
	     } 
	  } 
	  closedir($current_dir); 
	  rmdir($dir); 
	} 
	
    function download_all_resumes(){
    	$this->db->select("account.last_name,account.first_name,account.resume_file,account.resume_upload,account.student_number,
    		account.resume_type,resume_education_undergraduate.degree_program,undergraduate_degree_program.degree_program_abbr");
    	$this->db->from('account');
    	$this->db->join('resume_education_undergraduate','account.student_number=resume_education_undergraduate.student_number');
    	$this->db->join('undergraduate_degree_program','undergraduate_degree_program.degree_program=resume_education_undergraduate.degree_program');

	    $this->db->where('account.student_type',1);
	    $this->db->where('resume_education_undergraduate.degree_program !=','');
	    $this->db->order_by("resume_education_undergraduate.degree_program,trim(account.last_name)"); 

    	$result=$this->db->get('')->result();


    	/*echo '<pre>';
 		print_r($result);
 		echo '</pre>';*/
    	if(!empty($result)){
    		$zip = new ZipArchive;
			# create a temp file & open it
			$tmp_file = tempnam('.','');
			$zip->open($tmp_file, ZipArchive::CREATE);
			 
			foreach($result as $student){
				$application_type=$student->resume_type;			
				if($application_type==1){
					$application_type="Employment";
				}else{
					$application_type="Internship";
				}
				$degree_program=$student->degree_program;
				$this->db->flush_cache();

				if($degree_program){
					$file_location=$application_type.'/'.$degree_program;
		    	
					$filename=$student->last_name.", ".$student->first_name." - ".$student->student_number.".pdf";

					if($student->resume_upload){
						if(!file_exists($student->resume_upload))
						{

							$resume_upload=str_replace(".pdf", "_.pdf", $student->resume_upload);
							if(file_exists($resume_upload))
								//copy($student->resume_upload,$file_location."/".$filename);
								  $zip->addFile($resume_upload, $file_location."/".$filename);
							

						}else{
							 $zip->addFile($student->resume_upload, $file_location."/".$filename);
						}
					}elseif($student->resume_file){
						 $zip->addFile($student->resume_file, $file_location."/".$filename);
					}
				}
				

			}
			$zip->close();
			# send the file to the browser as a download
			header('Content-disposition: attachment; filename=resumes.zip');
			header('Content-type: application/zip');
			readfile($tmp_file);
			unlink($tmp_file);

    	}
    }

    function count_all_resumes(){
    	$this->db->select("account.last_name,account.first_name,account.resume_file,account.resume_upload,account.student_number,
    		account.resume_type,resume_education_undergraduate.degree_program,undergraduate_degree_program.degree_program_abbr");
    	$this->db->from('account');
    	$this->db->join('resume_education_undergraduate','account.student_number=resume_education_undergraduate.student_number');
    	$this->db->join('undergraduate_degree_program','undergraduate_degree_program.degree_program=resume_education_undergraduate.degree_program');

	    $this->db->where('account.student_type',1);
	    $this->db->where('resume_education_undergraduate.degree_program !=','');
	    $this->db->order_by("resume_education_undergraduate.degree_program,trim(account.last_name)"); 

    	$result=$this->db->get('')->result();

		$employment=0;
    	$internship=0;
    	if(!empty($result)){

			foreach($result as $student){
				$application_type=$student->resume_type;			
				$degree_program=$student->degree_program;	
				$this->db->flush_cache();

				if($degree_program){

					if($student->resume_upload){
						if(!file_exists($student->resume_upload))
						{

							$resume_upload=str_replace(".pdf", "_.pdf", $student->resume_upload);
							if(file_exists($resume_upload))
								if($application_type==1){
									$employment+=1;
								}else{
									$internship+=1;
								}
							

						}else{
							if($application_type==1){
								$employment+=1;
							}else{
								$internship+=1;
							}
						}
					}elseif($student->resume_file){
						if($application_type==1){
							$employment+=1;
						}else{
							$internship+=1;
						}
					}
				}
				

			}

			return array('employment'=>$employment,'internship'=>$internship);

    	}
    }
    function count_resumes(){
    	$company_id= $this->input->post('company_id');
    	$this->db->where('company_date.company_id',$company_id);
    	$this->db->where('account.student_type',1);
    	$this->db->group_by('company_checklist.student_number');
    	$this->db->select("*");
    	$this->db->from('company_checklist');
    	$this->db->join('company_date','company_date.company_date_id=company_checklist.company_date_id');
    	$this->db->join('account','account.student_number=company_checklist.student_number');
    	$result=$this->db->get()->result();
    	$internship=0;
    	$employment=0;
 		
    	if(!empty($result)){
    		$this->db->where('company_id',$company_id);
    		$this->db->select('company_name');
    		$company_name=$this->db->get('company')->row()->company_name;
			foreach($result as $student){
				$application_type=$student->resume_type;			
				if($application_type==1){
					$application_type="Employment";
					$employment+=1;
				}else{
					$application_type="Internship";
					$internship+=1;
				}
				if($student->student_type==1){
					$this->db->where('student_number',$student->student_number);
					$this->db->select('degree_program');
					$table='resume_education_undergraduate';
					$student_type="undergraduate";
				}else if($student->student_type==2){
					$this->db->where('student_number',$student->student_number);
					$this->db->select('degree_program');
					$table='resume_education_graduate';
					$student_type="graduate";
				}
				
				$degree_program=$this->db->get($table)->row();
				$this->db->flush_cache();
				if($degree_program&&$degree_program->degree_program){
					$degree_program=$degree_program->degree_program;
					$file_location=$application_type.'\\'.$degree_program;
		    	
					$filename=$student->last_name.", ".$student->first_name." - ".$student->student_number;

					if($student->resume_upload){
						if(!file_exists($student->resume_upload))
						{

							$resume_upload=str_replace(".pdf", "_.pdf", $student->resume_upload);
							if(file_exists($resume_upload))
								if($application_type=="Employment"){
									$employment+=1;
								}else{
									
									$internship+=1;
								}
							

						}else{
							if($application_type=="Employment"){
								$employment+=1;
							}else{
								
								$internship+=1;
							}
						}
					}elseif($student->resume_file){
						if($application_type=="Employment"){
							$employment+=1;
						}else{
							
							$internship+=1;
						}
					}
				}
				

			}
			$stat=array('internship'=>$internship,'employment'=>$employment);
			echo json_encode($stat);
    	}
    }
    function view_list_of_resumes(){
    	$company_id= $this->uri->segment(3);
    	$this->db->where('company_date.company_id',$company_id);
    	$this->db->where('account.student_type',1);
    	$this->db->group_by('company_checklist.student_number');
    	$this->db->select("*");
    	$this->db->from('company_checklist');
    	$this->db->join('company_date','company_date.company_date_id=company_checklist.company_date_id');
    	$this->db->join('account','account.student_number=company_checklist.student_number');
    	$result=$this->db->get()->result();
    	$internship=0;
    	$employment=0;
 		
    	if(!empty($result)){
    		$this->db->where('company_id',$company_id);
    		$this->db->select('company_name');
    		$company_name=$this->db->get('company')->row()->company_name;
			foreach($result as $student){
				$application_type=$student->resume_type;			
				if($application_type==1){
					$application_type="Employment";
					$employment+=1;
				}else{
					$application_type="Internship";
					$internship+=1;
				}
				if($student->student_type==1){
					$this->db->where('student_number',$student->student_number);
					$this->db->select('degree_program');
					$table='resume_education_undergraduate';
					$student_type="undergraduate";
				}else if($student->student_type==2){
					$this->db->where('student_number',$student->student_number);
					$this->db->select('degree_program');
					$table='resume_education_graduate';
					$student_type="graduate";
				}
				
				$degree_program=$this->db->get($table)->row();
				$this->db->flush_cache();
				if($degree_program&&$degree_program->degree_program){
					$degree_program=$degree_program->degree_program;
					$file_location=$application_type.'\\'.$degree_program;
		    	
					$filename=$student->last_name.", ".$student->first_name." - ".$student->student_number;

					if($student->resume_upload){
						if(!file_exists($student->resume_upload))
						{

							$resume_upload=str_replace(".pdf", "_.pdf", $student->resume_upload);
							if(file_exists($resume_upload))
								//copy($student->resume_upload,$file_location."/".$filename);
								//$zip->addFile($resume_upload, $file_location."/".$filename);
								echo $company_name."\\".$file_location."\\".$filename. "<br/>";
							

						}else{
							echo $company_name."\\".$file_location."\\".$filename. "<br/>";
						}
					}elseif($student->resume_file){
						echo $company_name."\\".$file_location."\\".$filename. "<br/>";
					}
				}
				

			}
			echo $internship;
			echo $employment;
    	}
    }

     
    function download_resumes(){
    	$company_id= $this->uri->segment(3);
    	$this->db->where('company_date.company_id',$company_id);
    	$this->db->distinct();
    	$this->db->select("*");
    	$this->db->from('company_checklist');
    	$this->db->join('company_date','company_date.company_date_id=company_checklist.company_date_id');
    	$this->db->join('account','account.student_number=company_checklist.student_number');
    	$result=$this->db->get()->result();
 	
    	if(!empty($result)){
    		$this->db->where('company_id',$company_id);
    		$this->db->select('company_name');
    		$company_name=$this->db->get('company')->row()->company_name;
    		$zip = new ZipArchive;
			# create a temp file & open it
			$tmp_file = tempnam('.','');
			$zip->open($tmp_file, ZipArchive::CREATE);
			 
			foreach($result as $student){
				$application_type=$student->resume_type;			
				if($application_type==1){
					$application_type="Employment";
				}else{
					$application_type="Internship";
				}
				if($student->student_type==1){
					$this->db->where('student_number',$student->student_number);
					$this->db->select('degree_program');
					$table='resume_education_undergraduate';
					$student_type="undergraduate";
				}else if($student->student_type==2){
					$this->db->where('student_number',$student->student_number);
					$this->db->select('degree_program');
					$table='resume_education_graduate';
					$student_type="graduate";
				}
				
				$degree_program=$this->db->get($table)->row();
				$this->db->flush_cache();

				if($degree_program){
					$degree_program=$degree_program->degree_program;
					$file_location=$application_type.'/'.$degree_program;
		    	
					$filename=$student->last_name.", ".$student->first_name." - ".$student->student_number.".pdf";

					if($student->resume_upload){
						if(!file_exists($student->resume_upload))
						{

							$resume_upload=str_replace(".pdf", "_.pdf", $student->resume_upload);
							if(file_exists($resume_upload))
								//copy($student->resume_upload,$file_location."/".$filename);
								  $zip->addFile($resume_upload, $file_location."/".$filename);
							

						}else{
							 $zip->addFile($student->resume_upload, $file_location."/".$filename);
						}
					}elseif($student->resume_file){
						 $zip->addFile($student->resume_file, $file_location."/".$filename);
					}
				}
				

			}
			$zip->close();
			# send the file to the browser as a download
			header('Content-disposition: attachment; filename='.$company_name.'.zip');
			header('Content-type: application/zip');
			readfile($tmp_file);
			/*$this->load->library('zip');
			$path=$_SERVER['DOCUMENT_ROOT']."/downloads/".$company_name."/";
			if($this->zip->read_dir($path,FALSE))
				$this->zip->download($company_name.'.zip'); */
    	}
    }

}
?>