<?php

class Company_Model extends CI_Model {
    function __construct() {
        parent::__construct();
		$this->load->database();
    }
    function add_company(){
        $dates=$this->input->post('dates');
        $company_name=$this->input->post('company_name');
        $this->db->insert('company',array('company_name'=>$company_name));
        $company_id=$this->db->insert_id();
        foreach($dates as $date){
            $this->db->insert('company_date',array('company_id'=>$company_id,'date'=>$date));
            
        }
        return $this->get_schedule_by_company($company_id);
    }

    function update_company(){
        $table="company_date";
        $schedule=$this->input->post('schedule');
        $company_id=$this->input->post('company_id');
        foreach($schedule as $item){
            $data=array(
                'company_id'=>$company_id,
                'date'=>date("Y-m-d",strtotime($item['date']))  
                );
            $this->db->where($data);
            $row=$this->db->get($table)->row();
            if($item['selected']=="true"){
                if(!$row){
                    $this->db->insert($table,$data);
                }
            }
        }
        return $this->get_schedule_by_company($company_id);
    }

    function update_company_name(){
        $table="company";
        $company_id=$this->input->post('company_id');

        $data=array(
            'company_name'=>$this->input->post('company_name')  
            );
        $this->db->where('company_id',$company_id);
        $this->db->update($table,$data);
     
        return true;
    }

    function delete_company(){
        $table="company";
        $company_id=$this->input->post('company_id');
        $this->db->where('company_id',$company_id);
        $this->db->delete($table);
        $this->db->flush_cache();
       
        return true;
    }

    function get_companies(){
        $this->db->order_by("UPPER(company_name)","asc");
    	$query=$this->db->get("company");
        $this->db->flush_cache();
    	return $query;
    }

    function get_company_date(){
        $this->db->select('company_date.company_id,company.company_name,company_date.date');
        $this->db->from('company_date');
        $this->db->join('company', 'company.company_id = company_date.company_id');
        $this->db->order_by("UPPER(company.company_name)","asc");
        $query=$this->db->get();
        $this->db->flush_cache();
        $result = $query->result();
        foreach ($result as $row) {
            $date = strtotime($row->date);
            $date =  date("M d, Y D", $date);
            $row->date=$date;
        }
        return $result;
    }

    function get_companies_checklist(){
        date_default_timezone_set('Asia/Manila');
        $today=date('Y-m-d');
        if($this->session->userdata('student_number')=="201148070"){
            $date = new DateTime();
            $date->setDate(2015, 2, 8);
            $today= $date->format('Y-m-d');
        }
        
        $this->db->where('company_date.date',$today);

        $this->db->select('*');
       
        $this->db->from('company_date');

        $this->db->join('company', 'company.company_id = company_date.company_id');
                $this->db->order_by("UPPER(company.company_name)","asc");
        $query=$this->db->get();

        $this->db->flush_cache();
        $result = $query->result();
        foreach ($result as &$row) {
            $this->db->where('student_number',$this->session->userdata('student_number'));
            $this->db->where('company_date_id',$row->company_date_id);

            if($this->db->get('company_checklist')->row())
                $row->selected = true;
            else
                $row->selected = false;
            $date = strtotime($row->date);
            $date =  date("M d, Y D", $date);
            $row->datetext=$row->date;
            $row->date=$date;

        }
        return $result;
    }

    
    function get_schedule(){
        
        $this->db->order_by("date","asc");
    	$query=$this->db->get("schedule");
        $result = $query->result();
        foreach ($result as &$row) {
            $date = strtotime($row->date);
            $date =  date("M d, Y D", $date);
            $row->sqldate=$row->date;
            $row->date=$date;
        }
          
        $this->db->flush_cache();
        return $result;
    }

    function get_current_schedule(){

        $this->db->where('date',date("Y-m-d"));
        $query=$this->db->get("schedule");
        $row= $query->row();
        if($row){
            $date = strtotime($row->date);
            $date =  date("M d, Y D", $date);
            $row->sqldate=$row->date;
            $row->date=$date;
        }
        $this->db->flush_cache();
        return $row;
        
    }

    function save_checklist(){
        $table="company_checklist";
        $checklist=$this->input->post('checklist');
        $student_number=$this->input->post('student_number');
        foreach($checklist as $item){

            $data=array(
                'student_number'=>$student_number,
                'company_date_id'=>$item['company_date_id']);
            $this->db->where($data);
            $row=$this->db->get($table)->row();
            $this->db->flush_cache();
            if($item['selected']=="true"){
               
                
                if(!$row){
                    $this->db->insert($table,$data);
                    $this->db->flush_cache();
                }
            }else{
                if($row){
                    $this->db->where($data);
                    $this->db->delete($table); 
                }
                
            }
           
        }
        return true;
    }

    function get_schedule_by_company($company_id=""){
        if($company_id!="")
            $this->db->where('company_date.company_id',$company_id);
        $this->db->select('company_date.company_id,company.company_name,company_date.date');
        $this->db->from('company_date');
        $this->db->join('company', 'company.company_id = company_date.company_id');
        $this->db->order_by("UPPER(company.company_name)","asc");
        $query=$this->db->get();
        $result = $query->result();
        $this->db->flush_cache();
        $schedule = $this->get_schedule();
     
        $companies=array();
        function my_sort_function($a, $b)
        {
            return $a['date'] > $b['date'];
        }
        foreach ($result as $row) {
            $date = strtotime($row->date);
            $date =  date("M d, Y D", $date);
            $row->date=$date;

        }
        foreach ($result as $value) {
            $companies[$value->company_name]['schedule'][]=array('date'=>$value->date,'selected'=>true);
            $companies[$value->company_name]['company_id']=$value->company_id;
        }
        foreach ($companies as &$company) {
            $company_dates=array();
            foreach ($company['schedule'] as $value) {
                $company_dates[]=$value['date'];
            }

            $schedule_dates=array();
            foreach ($schedule as $value) {
                $schedule_dates[]=$value->date;
            }
            foreach ($schedule_dates as $value) {
                if(!in_array($value, $company_dates))
                    $company['schedule'][]=array('date'=>$value,'selected'=>false);
            }
            
            usort($company['schedule'], 'my_sort_function');
        }
        $result=array();
        foreach ($companies as $key => $value) {
            $result[]=array('company'=>$key,'schedule'=>$value['schedule'],'company_id'=>$value['company_id']);
        }
        return $result;
    }
}
?>