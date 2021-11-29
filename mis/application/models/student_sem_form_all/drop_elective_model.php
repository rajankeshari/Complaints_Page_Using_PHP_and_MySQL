<?php
class Drop_elective_model extends CI_Model
{
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	function get_elective_by_admnno($admn_no){
$query = $this->db->query("select a.form_id,b.sub_id,c.name from reg_regular_form a 
inner join reg_regular_elective_opted b on b.form_id=a.form_id
inner join subjects c on c.id=b.sub_id
where a.admn_no='".$admn_no."'");
        if ($query->num_rows() > 0) { // 
            return $query->result();
        } else {
            return false;
        }

    }

    function delete_subject($sub_id,$form_id){

 		$array = array('form_id' => $form_id, 'sub_id' => $sub_id);
 		$this->db->where($array); 
 		$this->db->delete('reg_regular_elective_opted');
 		
    }

	
	
}?>