<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Medicine_return_patient_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

//================================hc_counter_med_issue=======================================	
	function get_patient($pid,$prel,$mid,$vdate,$vno,$pno){
        

       // $myquery = "select * from hc_counter_med_issue  where pid='".$pid."' and prel='".$prel."' and date(m_rec_date)='".$vdate."'";
	   
	    $myquery = "select * from hc_counter_med_issue  where pid='".$pid."' and prel='".$prel."' and m_id='".$mid."' and date(m_rec_date)='".$vdate."' and visit_no='".$vno."' and pres_no='".$pno."'";
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
	function update_patient_medicine($m_con_id,$mqty){
		$sql = "update hc_counter_med_issue set mqty=".$mqty." where m_con_id=".$m_con_id;
		$query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
	}
	//================================hc_counter_master=======================================
	
	function get_medicine_from_counter($mid){
		$myquery = "select * from hc_counter_master where m_id=".$mid;
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
	}
	
	function update_counter_medicine($cid,$cqty,$mqty,$issmed){
            $p=intval($cqty)+(intval($issmed)-intval($mqty));
		$sql = "update hc_counter_master set cs_qty=".$p." where cs_id=".$cid;
		$query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
	}
	
	//================================hc_patient=======================================	
	
	function get_patient_hc_patient($pid,$prel,$mid,$vdate,$vno,$pno){
        

       // $myquery = "select * from hc_counter_med_issue  where pid='".$pid."' and prel='".$prel."' and date(m_rec_date)='".$vdate."'";
	   
	    $myquery = "select * from hc_patient  where pid='".$pid."' and prel='".$prel."' and mid='".$mid."' and date(visit_date)='".$vdate."' and visit_no='".$vno."' and pres_no='".$pno."'";
        $query = $this->db->query($myquery);

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return FALSE;
        }
    }
	function update_patient_medicine_hc_patient($vid,$mqty){
		$sql = "update hc_patient set mqty=".$mqty." where visitor_id=".$vid;
		$query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
	}
        function medicine_return_backup($vid){
            $sql = "insert into hc_patient_medi_return select * from hc_patient where visitor_id=?";
		$query = $this->db->query($sql,array($vid));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        }
	
	
	
	
/*
    function check_auth($id) {

        $sql="select auth_id from users where id=? and status='A'";
        $query = $this->db->query($sql,array($id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row()->auth_id;
        } else {
            return false;
        }
    }
    function get_family_list($id){
        $sql="select a.* from emp_family_details a where a.emp_no=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function get_self_list($id){
        $sql="select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as name,'Self' as relationship from user_details a where a.id=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }

	*/
}

?>