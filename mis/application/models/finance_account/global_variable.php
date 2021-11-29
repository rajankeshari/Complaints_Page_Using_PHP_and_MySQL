<?php

class global_variable extends CI_Model
{
	protected $table = 'acc_global_variables';


	function GetVariable($c,$o='',$l=1){
		if($o == '')
			$o='ASC';
		
		$this->db->order_by('effected_from',$o);
		$this->db->limit($l);
		$q=$this->db->get_where($this->table,$c);
		if($q->num_rows() > 0){
			if($l == 1){
				return $q->row();
			}else{
			return $q->result();
			}
		}else
			return false;

	}
}