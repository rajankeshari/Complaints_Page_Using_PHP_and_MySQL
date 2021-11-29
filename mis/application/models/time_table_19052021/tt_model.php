<?php

class Tt_model extends CI_Model
{
	
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}



	function getvenue(){
		$query = $this->db->query("SELECT * from venue");

		return $query->result();
	}


	//get active time table row (should be only one)
	function getactive_tt(){

		 $this->db->where('active', 1);
		 $q = $this->db->get('tt_structure_master');

		 return $q->result();

	}
	function get_tt_row_by_id($id){

		 $this->db->where('tt_id', $id);
		 $q = $this->db->get('tt_structure_master');

		 return $q->result();

	}


	function get_all_tt_structs(){
		$q = $this->db->query(" SELECT * from tt_structure_master");

		if($q->num_rows()>0)
			return $q->result();

		return -1;
	}

	function add_struct($row){
		print_r($row);
		$numdays = $row['num_days'];
		$totalslots = $row['total_slots'];
		$befbreak = $row['bef_break'];
		$aftbreak = $row['aft_break'];
		$created_by = $this->session->userdata('id');
		$q = $this->db->query("INSERT into tt_structure_master(num_days, total_slots, bef_break, aft_break, active, complete, created_by) VALUES($numdays, $totalslots, $befbreak, $aftbreak, 0, 0, '$created_by')");

		return $q;


	}

	function tt_dependency($id){
		//$query = $this->db->query("SELECT DISTINCT tt_id from tt_map where tt_id=$id");
		$query = $this->db->query("SELECT DISTINCT tt_id from tt_map_old where tt_id=$id
union
SELECT DISTINCT tt_id from tt_map_cbcs where tt_id=$id");

		if($query->num_rows() > 0)
		return 1;
		else
		return 0; 

	}

	function delete_struct_by_id($id){
		$this->db->query("DELETE from tt_structure_master where tt_id=$id");
		$this->db->query("DELETE from tt_slot_master where tt_id=$id");

	}

	function get_slot_times($id){
		$q = $this->db->query("SELECT slot_no, start_time, end_time FROM tt_slot_master WHERE tt_id=$id");

		return $q->result();

	}

	function delete_slot_times($id){
		$this->db->query(" DELETE from tt_slot_master WHERE tt_id=$id");
	}

	function insert_slot_time($slot_no, $tt_id, $stime, $etime){
		$this->db->query(" INSERT into tt_slot_master(slot_no, tt_id, start_time, end_time) VALUES($slot_no, $tt_id, '$stime', '$etime') ");


	}

	function num_slot_times($id){
		$q = $this->db->query("SELECT COUNT(*) as count from tt_slot_master WHERE tt_id = $id ");

		return $q->result()[0]->count;

	}

	function mark_complete_incomplete($id, $c){
		$this->db->query("UPDATE tt_structure_master SET complete=$c WHERE tt_id = $id");

	}

	function getdays(){
		$data = array();

		$q = $this->db->get('tt_days_master');

		$data['numrows'] = $q->num_rows();
		$data['result'] = $q->result();

		return $data;


	}
	function set_active_tt($id){
		$this->db->query("UPDATE tt_structure_master SET active=0");
		$this->db->query("UPDATE tt_structure_master SET active=1 WHERE tt_id=$id ");


	}



	function getslotsbyttid($tt_id){
	//	$this->db->where('tt_id', $tt_id);
	//	 $q = $this->db->get('tt_slot_master');
	
	//	$a = "'"+$tt_id+"'";
			$q = $this->db->query("SELECT slot_no, tt_id, TIME_FORMAT(start_time, '%h:%i %p') as start_time, TIME_FORMAT(end_time, '%h:%i %p') as end_time from tt_slot_master where tt_id=$tt_id");
	


		 $data = array();
		 $data['numrows'] = $q->num_rows();
		 $data['result'] = $q->result();

		 return $data;

	}

	function insertslotdata($data){
			$total = $data['total'];
			$tt_id = $data['tt_id'];
			
			for($i=1; $i<$total+1; $i++){
				$data2  = array(

					'slot_no' => $i,
					'tt_id'=> $tt_id,
					'start_time' => $data['slot'][$i]['stime'],
					'end_time' => $data['slot'][$i]['etime']

					 );

				$this->db->insert('tt_slot_master', $data2);

			}


	}


	 function insert_mapping_tt($data){
    //  print_r($data); 
            if($this->db->insert('tt_map',$data))
                return $this->db->insert_id();
            else
            return false;
       }

    function insert_subject_slot_map($map_id, $subj_id, $ch){
    		$q = $this->db->query("DELETE from tt_subject_slot_map where map_id=$map_id AND subj_id='$subj_id'");

    		foreach ($ch as $c) {
    			$slot = $c;

    			explode("_", $slot);

    			$ins = $this->db->query(" INSERT into tt_subject_slot_map values($map_id, '$subj_id', $slot[0], $slot[1] ) ");



    		}


    }


}
	


?>