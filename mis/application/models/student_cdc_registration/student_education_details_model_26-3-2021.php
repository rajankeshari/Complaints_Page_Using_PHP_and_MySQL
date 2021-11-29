<?php

class Student_education_details_model extends CI_Model
{
	var $table = 'stu_prev_education';

	function __construct()
	{
		parent::__construct();
	}

	function insert($data)
	{
		if($this->db->insert($this->table,$data))
			return TRUE;
		else
			return FALSE;
	}

	function pending_insert($data)
	{
		if($this->db->insert('pending_'.$this->table,$data))
			return TRUE;
		else
			return FALSE;
	}

	function insert_batch($data)
	{
		if($this->db->insert_batch($this->table,$data))
			return TRUE;
		else
			return FALSE;
	}
	
	function pending_insert_batch($data)
	{
		if($this->db->insert_batch('pending_'.$this->table,$data))
			return TRUE;
		else
			return FALSE;
	}

	function getStuEduById($id = '')
	{
		if($id != '')
		{
			$query = $this->db->where('admn_no',$id)->get($this->table);
			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
		}
		else
			return FALSE;
	}


		function ug_getStuEduById($id = '')
	{


		if($id != '')
		{



//$query = $this->db->query('SELECT p.* from (SELECT a.* FROM final_semwise_marks_foil_freezed a WHERE  a.admn_no ="'.$id.'"  
 //ORDER BY a.admn_no,a.session_yr,a.actual_published_on DESC LIMIT 100000)p GROUP BY p.admn_no,p.session_yr,p.session;');

      $query = $this->db->query('SELECT p.* from     
         (SELECT a.* FROM final_semwise_marks_foil_freezed a WHERE     
          a.admn_no ="'.$id.'" ORDER BY a.admn_no,a.session_yr,a.actual_published_on DESC LIMIT 100000)p     
          GROUP BY p.admn_no,p.session_yr,p.session;');

			//$query = $this->db->query('select a.* from final_semwise_marks_foil_freezed a where a.admn_no="'.$id.'" group by a.session_yr,a.`session`;');
			



			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
		}
		else
			return FALSE;
	}




		function acad_getStuEduById($id = '')
	{


		if($id != '')
		{



			$query = $this->db->query('select a.* from stu_prev_education a where a.admn_no="'.$id.'"');
			


// $str = $this->db->last_query();   
//     echo "<pre>";
//     print_r($str);
//    exit;


			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
		}
		else
			return FALSE;
	}

	function getStuEduByIdOrder($admn_no = '',$sno=-1)
	{
		if($sno == -1) {
			$query = $this->db->where('admn_no',$admn_no)->get($this->table);
			return $query->result();
		}else {
			$query = $this->db->where('admn_no',$admn_no)->where('sno',$sno)->get($this->table);
			return $query->row();
		}
	}

	function copyDetailsToPendingById($admn_no='')
	{
		$query = $this->db->where('admn_no',$admn_no)->get($this->table);
		foreach ($query->result() as $row) {
			$this->db->insert('pending_'.$this->table,$row);
		}
	}

	function getAllStudentId()
	{
		$query = $this->db->distinct()->select('admn_no')->order_by('admn_no')->get($this->table);
		if($query->num_rows() > 0)
			return $query->result();
	}
	function getPendingStuEduById($id = '')
	{
		if($id != '')
		{
			$query = $this->db->where('admn_no',$id)->get('pending_'.$this->table);
			if($query->num_rows() == 0)	return FALSE;
			return $query->result();
		}
		else
			return FALSE;
	}

	function getPendingStuEduByIdOrder($admn_no = '',$sno=-1)
	{
		if($sno == -1) {
			$query = $this->db->where('admn_no',$admn_no)->get($this->table);
			return $query->result();
		}else {
			$query = $this->db->where('admn_no',$admn_no)->where('sno',$sno)->get('pending_'.$this->table);
			return $query->row();
		}
	}

	function updatePendingDetailsWhere($data,$where_array)
	{
		$this->db->update('pending_'.$this->table,$data,$where_array);
	}

	function delete_record($where_array)
	{
		$this->db->delete($this->table,$where_array);
	}

	function pending_delete_record($where_array)
	{
		$this->db->delete('pending_'.$this->table,$where_array);
	}

	function MoveDetailsFromPendingById($admn_no='')
	{
		//copy details to real table from pending table
		$query = $this->db->where('admn_no',$admn_no)->get('pending_'.$this->table);
		foreach ($query->result() as $row) {
			$this->db->insert($this->table,$row);
		}
		//delete pending details from pending table
		$this->db->delete('pending_'.$this->table,array('admn_no' => $admn_no));
	}

	function update_record($data,$where_array)
	{
		$this->db->update($this->table,$data,$where_array);
	}
    
    function update_by_sl_id($data,$id)//Added as education details modification required Author:RituRaj dated:16-sept15
	{
             //print_r($data); die();
              foreach ($data as $row)    {
                  
                  $this->db->update($this->table,(array)$row,array('sno'=>$row->sno,'admn_no'=>$id));

              }
		
	}

	function deletePendingDetailsWhere($data)
	{
		$this->db->delete('pending_'.$this->table,$data);
	}
    function deleteEducationDetailsWhere($data)
    {
        $this->db->delete($this->table,$data);

    }
}