<?php

class Resume_Model extends CI_Model {
    function __construct() {
        parent::__construct();
		$this->load->database();
    }
    function save_personal_information(){
		$student_number=$this->input->post('student_number');
		$data=$this->input->post('data');
		$table="account";
		$this->db->where('student_number',$student_number);
		$this->db->update($table,$data);
		$this->db->flush_cache();
		if($this->db->_error_message()){
			return false;
		}else{
			return true;
		}
    }

    function save_skills(){
    	$table="resume_skills";
    	$student_number=$this->input->post('student_number');
    	$skills=$this->input->post('skills');
    	$this->db->where('student_number',$student_number);
    	$row=$this->db->get($table)->row();
    	$this->db->flush_cache();

    	if($row){
    		if($skills==""){
	    		$this->db->where('student_number',$student_number);
	    		$this->db->delete($table);
	    		$this->db->flush_cache();
	    	}else{
	    		$this->db->where('student_number',$student_number);
	    		$this->db->update($table,array('skills'=>$skills));
	    		$this->db->flush_cache();
	    	}
    		
    	}else{
    		if($skills!=""){
    			$this->db->insert($table,array('student_number'=>$student_number,
    			'skills'=>$skills));
    			$this->db->flush_cache();
    		}
    		
    	}
    }
 
    function save_education(){
    	
    	$type=$this->input->post('type');
    	$table="resume_education_".$type;
    	$student_number=$this->input->post('student_number');
    	$data=$this->input->post('data');
    	$response=array();


    	$this->db->where("student_number",$student_number);
		$query=$this->db->get($table);
		$this->db->flush_cache();
		$result=$query->row();
		if(!$result){
			
			$data['student_number']=$student_number;
			$this->db->insert($table,$data);
			$this->db->flush_cache();
		}else{
	
			
			if(!empty($data)){
				$this->db->where('student_number',$student_number);
				$this->db->update($table,$data);	
				$this->db->flush_cache();
				
			}
		}
			
		$table="resume_".$type."_recognitions";
		$recognitions=$this->input->post('recognitions');
		$response['recognitions']=array();
		if(!empty($recognitions))
			foreach($recognitions as $item){
				if($item['award_id']==0){
					$recognition=array();
					$recognition['student_number']=$student_number;
					$recognition['award']=$item['award'];
					$this->db->insert($table,$recognition);
					$response['recognitions'][]=$this->db->insert_id();
					$this->db->flush_cache();
				}else{
					$this->db->where('award_id',$item['award_id']);
					$recognition=$this->db->get($table)->row();
					if($recognition){
						if($recognition->award!=$item['award']){
							$recognition=array();
							$recognition['award']=$item['award'];
							$this->db->where('award_id',$item['award_id']);
							$this->db->update($table,$recognition);

    						$this->db->flush_cache();
						}
					}
					$response['recognitions'][]=$item['award_id'];
				}
			}
		$delete_recognitions=$this->input->post('delete_recognitions');
		if(!empty($delete_recognitions)){
			foreach ($delete_recognitions as $item) {
				$table="resume_".$type."_recognitions";
				$this->db->where("award_id",$item);
				$this->db->delete($table);
				$this->db->flush_cache();
			}
		}
		return $response;
    }

    function save_item(){
    	$section=$this->input->post('section');
		$field=$this->input->post('field_name');
		$value=$this->input->post('value');
		$student_number=$this->input->post('student_number');

		if($section){
			$table=0;
			if($section=="personal_info"){
				$table="account";
				if($field=="student_type"&&$value==1){
					$this->db->where("student_number",$student_number);
					$this->db->delete("resume_education_graduate");
					$this->db->where("student_number",$student_number);
					$education['school_name']="University of the Philippines Diliman";
					$this->db->update("resume_education_undergraduate",$education);
					$this->db->flush_cache();
				}
			}else if($section=="undergraduate_education"){
				$table="resume_education_undergraduate";
			}elseif ($section=="highschool_education") {
				$table="resume_education_highschool";
			}elseif($section=="graduate_education"){
				$table="resume_education_graduate";
				$this->db->where("student_number",$student_number);
				$query=$this->db->get("resume_education_graduate");
				$this->db->flush_cache();
				$result=$query->row();
				$education=array();
				if(!$result){
					
					$education['student_number']=$student_number;
					$education['school_name']="University of the Philippines Diliman";
					$this->db->insert("resume_education_graduate",$education);
					$this->db->flush_cache();
				}
			}
			if($table){
				$this->db->select($field);
				$this->db->where('student_number',$student_number);
				$query = $this->db->get($table);
				$this->db->flush_cache();
				$row=$query->row();
				if($row->$field==$value)
					return 0;
				$data=array(
					$field=>$value);

				$this->db->where('student_number',$student_number);
				$this->db->update($table,$data);
				$this->db->flush_cache();
			}

			if($section=="recognitions"){
				$type=$this->input->post('type');
				$id=$this->input->post('id');
				$table="resume_".$type."_recognitions";
				if($id==0){
					$data=array(
					'student_number'=>$student_number,
					'award'=>$value);
					 $this->db->insert($table,$data);
					 $this->db->flush_cache();
				}elseif($id>0){
					$this->db->where('award_id',$id);
					$this->db->where('student_number',$student_number);
					$query = $this->db->get($table);
					$this->db->flush_cache();
					$row=$query->row();
					if($row->award==$value)
						return 0;
					$data=array(
						'award'=>$value);

					$this->db->where('award_id',$id);
					$this->db->update($table,$data);
					$this->db->flush_cache();
				}
				
				
				
				
			}
			
		}
		return true;
    }

    function save_work_experience(){
    	$table="resume_work_experience";
    	$student_number=$this->input->post('student_number');
    	$id=$this->input->post('work_id');
    	$data=(array) $this->input->post('data');
    	$response=array();
    	if($id==0){
    		
    		$this->db->insert($table,$data);
    		$id=$this->db->insert_id();
			$this->db->flush_cache();
    		$response['work_id']=$id;
			$this->db->where('work_id',$response['work_id']);
    		$work_experience=$this->db->get($table)->row();
    		$from_date=$work_experience->from_month." ".$work_experience->from_year;
			$from_date=strtotime($from_date);
			$response['from_date']=$from_date;


    	}elseif($id>0){
    		$this->db->where('work_id',$id);
			$this->db->where('student_number',$student_number);
			$query = $this->db->get($table);
			$row=$query->row();
			if(empty($row))
				return false;
			else{
				$newdata=array();
				foreach ($row as $key => $value) {
					if(isset($data[$key]))
						if($row->$key!=$data[$key])
							$newdata[$key]=$data[$key];
				}
			}
			
			
			if(!empty($newdata)){
				$this->db->where('work_id',$id);
				$this->db->update($table,$newdata);	
				$this->db->flush_cache();
				if(isset($data['from_month'])&&!isset($data['from_year']))
					$data['from_year']=$row->from_year;
				elseif(!isset($data['from_month'])&&isset($data['from_year']))
					$data['from_month']=$row->from_month;

				if(isset($data['from_month'])||isset($data['from_year'])){
					$from_date=$data['from_month']." ".$data['from_year'];
					$from_date=strtotime($from_date);
					$response['from_date']=$from_date;
				}
				
				
			}
			
    	}

		$new_work_descriptions=$this->input->post('work_descriptions');
		$response['work_descriptions']=array();
		if(!empty($new_work_descriptions))
			foreach($new_work_descriptions as $item){
				if($item['work_description_id']==0){
					$work_description=array();
					$work_description['work_id']=$id;
					$work_description['work_description']=$item['work_description'];
					$this->db->insert("resume_work_descriptions",$work_description);
					$response['work_descriptions'][]=$this->db->insert_id();
					$this->db->flush_cache();
				}else{
					$this->db->where('work_description_id',$item['work_description_id']);
					$work_description=$this->db->get("resume_work_descriptions")->row();
					if($work_description){
						if($work_description->work_description!=$item['work_description']){
							$work_description=array();
							$work_description['work_description']=$item['work_description'];
							$this->db->where('work_description_id',$item['work_description_id']);
							$this->db->update("resume_work_descriptions",$work_description);

    						$this->db->flush_cache();
						}
					}
					$response['work_descriptions'][]=$item['work_description_id'];
				}
			}
		$delete_work_descriptions=$this->input->post('delete_work_descriptions');
		if(!empty($delete_work_descriptions)){
			foreach ($delete_work_descriptions as $item) {
				$table="resume_work_descriptions";
				$this->db->where("work_description_id",$item['work_description_id']);
				$this->db->delete($table);
				$this->db->flush_cache();
			}
		}
		return $response;
    }

    function save_affiliation(){
    	$table="resume_affiliations";
    	$student_number=$this->input->post('student_number');
    	$id=$this->input->post('affiliation_id');
    	$data=(array) $this->input->post('data');
    	$response=array();
    	if($id==0){
    		
    		$this->db->insert($table,$data);
    		$id=$this->db->insert_id();
    		$response['affiliation_id']=$id;
    		$this->db->flush_cache();

    	}elseif($id>0){
    		$this->db->where('affiliation_id',$id);
			$this->db->where('student_number',$student_number);
			$query = $this->db->get($table);
			$this->db->flush_cache();
			$row=$query->row();
			if(empty($row))
				return false;
			else{
				$newdata=array();
				foreach ($row as $key => $value) {
					if(isset($data[$key]))
						if($row->$key!=$data[$key])
							$newdata[$key]=$data[$key];
				}
			}
			
			
			if(!empty($newdata)){
				$this->db->where('affiliation_id',$id);
				$this->db->update($table,$newdata);	
				$this->db->flush_cache();
			}
			
    	}

		$committees=$this->input->post('committees');
		$response['committees']=array();
		if(!empty($committees))
			foreach($committees as $item){
				if($item['committee_id']==0){
					$data=$item['data'];
					$data['affiliation_id']=$id;
					$this->db->insert("resume_affiliations_committees",$data);
		    		$committee_id=$this->db->insert_id();
					$this->db->where('committee_id',$committee_id);
		    		$committee=$this->db->get("resume_affiliations_committees")->row();
		    		$from_date=$committee->from_month." ".$committee->from_year;
					$from_date=strtotime($from_date);
					$response['committees'][]=array('committee_id'=>$committee_id,'from_date'=>$from_date);
					$this->db->flush_cache();
				}else{
					$committee_id=$item['committee_id'];
					$data=$item['data'];
					$this->db->where("committee_id",$committee_id);
					$this->db->update("resume_affiliations_committees",$data);
					$this->db->where('committee_id',$committee_id);
					$query = $this->db->get("resume_affiliations_committees");
					$this->db->flush_cache();
					$row=$query->row();
					if(empty($row))
						return false;
					$from_date="";
					if(isset($data['from_month'])&&!isset($data['from_year']))
						$data['from_year']=$row->from_year;
					elseif(!isset($data['from_month'])&&isset($data['from_year']))
						$data['from_month']=$row->from_month;

					if(isset($data['from_month'])||isset($data['from_year'])){
						$from_date=$data['from_month']." ".$data['from_year'];
						$from_date=strtotime($from_date);
					}
					$response['committees'][]=array('committee_id'=>$committee_id,'from_date'=>$from_date);
				}
			}

		$delete_committees=$this->input->post('delete_committees');
		if(!empty($delete_committees)){
			foreach ($delete_committees as $item) {
				$table="resume_affiliations_committees";
				$this->db->where("committee_id",$item);
				$this->db->delete($table);
				$this->db->flush_cache();
				if($this->db->_error_message())
					return -1;
				}
		}
		return $response;
    }
    function update_has_resume($student_number){

		$this->db->start_cache();
		$table="account";
		$array=array("has_resume"=>1);
		$this->db->where("student_number",$student_number);
		$this->db->update($table,$array);
		$this->db->flush_cache();
    }
    function delete_work_experience(){
    	$table="resume_work_experience";
    	$this->db->where('work_id',$this->input->post('work_id'));
    	$this->db->where('student_number',$this->input->post('student_number'));
    	$this->db->delete($table);
    	$this->db->flush_cache();
    	if($this->db->_error_message())
			return -1;
    	$table="resume_work_descriptions";
    	$this->db->where('work_id',$this->input->post('work_id'));
    	$this->db->delete($table);
    	$this->db->flush_cache();
    	return 1;
    }

    function delete_affiliation(){
    	$table="resume_affiliations";
    	$this->db->where('affiliation_id',$this->input->post('affiliation_id'));
    	$this->db->where('student_number',$this->input->post('student_number'));
    	$this->db->delete($table);
    	$this->db->flush_cache();
    	if($this->db->_error_message())
			return -1;
		
    	$table="resume_affiliations_committees";
    	$this->db->where('affiliation_id',$this->input->post('affiliation_id'));
    	$this->db->delete($table);
    	$this->db->flush_cache();
    	return 1;
    }

   	function save_affiliations_order(){
   		$table="resume_affiliations";
   		$affiliations=$this->input->post('affiliations');
   		foreach($affiliations as $item){
   			$this->db->where('affiliation_id',$item['affiliation_id']);
   			$this->db->update($table,array('order'=>$item['order']));
   			$this->db->flush_cache();
   		}
   		return 1;
    }

    function delete_recognition(){
    	$table="resume_".$this->input->post('type')."_recognitions";
    	$this->db->where('award_id',$this->input->post('id'));
    	$this->db->where('student_number',$this->input->post('student_number'));
    	$this->db->delete($table);
    	$this->db->flush_cache();
    	if($this->db->_error_message())
			return -1;
		elseif(!$this->db->affected_rows())
			return 0;
		else
			return 1;
    }

}
?>