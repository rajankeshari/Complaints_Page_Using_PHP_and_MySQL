<?php
class Download_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_schedule($session_year,$session,$type)
	{
		$this->db->select('map_id');
		$this->db->from('exam_schedule_mapping');
		$this->db->where('session_year',$session_year);
		$this->db->where('session',$session);
		$this->db->where('type',$type);
		$this->db->where('section','0');
		$query=$this->db->get();
		$map=$query->result()[0]->map_id;

		$this->db->select('*');
		$this->db->from('exam_schedule');
		$this->db->where('map_id',$map);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_time($session_year,$session,$type,$shift)
	{
		$this->db->select('map_id');
		$this->db->from('exam_schedule_mapping');
		$this->db->where('session_year',$session_year);
		$this->db->where('session',$session);
		$this->db->where('section','0');
		$this->db->where('type',$type);
		$q=$this->db->get();
		$map=$q->result()[0]->map_id;

		$this->db->select('*');
		$this->db->from('exam_shift');
		$this->db->where('map_id',$map);
		$this->db->where('shift',$shift);
		$q=$this->db->get();
		return $q->result()[0];
	}
}
?>