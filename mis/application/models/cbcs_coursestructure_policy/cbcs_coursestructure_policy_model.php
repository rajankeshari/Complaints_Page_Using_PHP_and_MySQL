<?php
class cbcs_coursestructure_policy_model extends CI_Model{
	function __construct() {
        parent::__construct();
    }

    //get curriculam policy list..
    public function get_curriculam_policy(){
		
		//$result=$this->db->query("SELECT * FROM `cbcs_credit_points_policy` WHERE id = (SELECT distinct(cbcs_credit_points_policy_id) FROM `cbcs_curriculam_policy`)"); changed by Kumaraswamy on 23.07.2019
    	//$result=$this->db->query("SELECT * FROM `cbcs_credit_points_policy` WHERE id in (SELECT distinct(cbcs_credit_points_policy_id) FROM `cbcs_curriculam_policy`)");
		
		$result=$this->db->query("SELECT a.*,b.name AS c_policy FROM `cbcs_credit_points_policy` a
INNER JOIN cbcs_courses b ON b.id=a.course_id
WHERE a.id IN (SELECT DISTINCT(cbcs_credit_points_policy_id)FROM `cbcs_curriculam_policy`)");
    	return $result->result();
    }
	
	public function get_curriculam_policy_by_id($id){
		
		//$result=$this->db->query("SELECT * FROM `cbcs_credit_points_policy` WHERE id = (SELECT distinct(cbcs_credit_points_policy_id) FROM `cbcs_curriculam_policy`)"); changed by Kumaraswamy on 23.07.2019
    	$result=$this->db->query("SELECT a.*,b.name AS cname FROM `cbcs_credit_points_policy` a 
INNER JOIN cbcs_courses b ON b.id=a.course_id
WHERE a.id IN 
(SELECT DISTINCT(cbcs_credit_points_policy_id)FROM `cbcs_curriculam_policy` b) AND a.course_id='".$id."'");
    	return $result->result();
    }
	public function get_component_details($id){ //this function added by  kumarswamy on 23.07.2019
		
		
    	$result=$this->db->query("SELECT * FROM `cbcs_credit_points_policy` WHERE id='$id'");
		
    	return $result->result();
    }
	
	public function get_course_component($session_year,$discipline){//function added by kumaraswamy on 23.07.2019
	
		$result=$this->db->query("SELECT * FROM `cbcs_course_component` where course_id='$discipline' and status='Active' and effective_year='$session_year'");
		$result=$result->result();
		return $result;
	}

    //Save new data..
    public function save_cbcs_coursestructure($data){
    	if($this->db->insert('cbcs_coursestructure_policy',$data))
            return true;
        else
            return false;
    }

    //Get list from database 
    public function get_cbcs_coursestructure_list(){
        //$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id ORDER BY A.id"); modified by Kumaraswamy on 23.07.2019
		
		$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id GROUP BY A.id");
        return $result->result();
    }
	
	/* Commented by CK as per Shobhan Request on 24 April 2020
	public function get_cbcs_coursestructure_list_ug(){
        //$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id ORDER BY A.id"); modified by Kumaraswamy on 23.07.2019
		
		$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id WHERE B.status=1 and B.duration in ('4','5') GROUP BY A.id");
        return $result->result();
    } */
	
	public function get_cbcs_coursestructure_list_ug(){
       
        //$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id WHERE B.status=1 and (B.duration in ('4') or (B.duration='5' and B.id = 'dualdegree')) GROUP BY A.id");
        $result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id WHERE B.status=1 and (B.duration in ('4') or (B.duration='5' and B.id in ('dualdegree','int.m.tech'))) GROUP BY A.id");

        return $result->result();
    }
	
	
	/* Commented by CK as per Shobhan Request on 24 April 2020
	public function get_cbcs_coursestructure_list_pg(){
        //$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id ORDER BY A.id"); modified by Kumaraswamy on 23.07.2019
		
		//$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id WHERE B.status=1 and B.duration in ('2','3','4','5') GROUP BY A.id");
		
		$result=$this->db->query("SELECT A.*, CONCAT(B.name,'(', UPPER(CASE WHEN B.id='jrf' THEN 'PHD'  ELSE B.id END),')') AS course, 
CONCAT(C.wef,'(',(CASE WHEN C.course_id='jrf' THEN 'PHD'  ELSE upper(C.course_id) END),')') AS c_policy, 
CONCAT(D.name,'(',D.id,')') AS d_comp
FROM `cbcs_coursestructure_policy` A
JOIN `cbcs_courses` B ON A.course_id=B.id
JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id
JOIN `cbcs_course_component` D ON A.course_component=D.id
WHERE B.status=1 AND B.duration IN ('2','3','4','5')
GROUP BY A.id
");
        return $result->result();
    } */
	
	public function get_cbcs_coursestructure_list_pg(){
       
        //$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id WHERE B.status=1 and (B.duration in ('2','3') or (B.duration='5' and B.id != 'dualdegree')) GROUP BY A.id");
        $result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id WHERE B.status=1 and (B.duration in ('2','3') or (B.duration='5' and B.id not in ('dualdegree','int.m.tech'))) GROUP BY A.id");

        return $result->result();
    }
	


    //Update data..
    public function update_cbcs_coursestructure($data,$rowid){
        $query = $this->db->query("INSERT INTO cbcs_coursestructure_policy_backup SELECT * FROM cbcs_coursestructure_policy WHERE id='$rowid'");
        $this->db->where('id', $rowid);
        if($this->db->update('cbcs_coursestructure_policy',$data))
            return true;
        else
            return false;

    }

    //Delete data...
    public function delete_coursestructure($id){
        $this->db->query("INSERT INTO cbcs_coursestructure_policy_backup SELECT * FROM cbcs_coursestructure_policy WHERE id='$id'");
        $user_id=$this->session->userdata('id');
        $date=date('Y-m-d H:i:s');
        $this->db->query("UPDATE `cbcs_coursestructure_policy_backup` SET `action`='delete',`last_updated_by`='$user_id'");
        if($this->db->query("DELETE FROM cbcs_coursestructure_policy WHERE id='$id'"))
            return TRUE;
        else
            return FALSE;
    }

    //Check duplicate entry when we insert new data.
    public function check_duplicate_coursestructure($course,$sem,$ccp,$comp,$sequence,$status,$rowid){
        if($rowid == '' || $rowid == 0)
            $qu='';
        else
            $qu="AND `id` != '$rowid'";
        $result=$this->db->query("SELECT * FROM `cbcs_coursestructure_policy` WHERE `course_id`='$course' AND `sem`='$sem' AND `cbcs_curriculam_policy_id`='$ccp' AND `course_component`='$comp' AND `sequence`='$sequence' AND `status`='$status' $qu");
        return $result->num_rows();
    }


    public function ajax_coursestructure_data($course,$sem,$curriculam,$component,$sequence,$status){
        $qu=$wh='';
        if($course != '' || $sem != '' || $curriculam != '' || $component != '' || $sequence != '' || $status != ''){
            $wh='WHERE';
        }

        if($course != ''){
            if($qu != '')
                $qu .= " AND ";
            $qu.=" A.course_id = '$course'";
        }

        if($sem != ''){
            if($qu != '')
                $qu .= " AND ";
            $qu.=" A.sem = '$sem'";
        }

        if($curriculam != ''){
            if($qu != '')
                $qu .= " AND ";
            $qu.=" A.cbcs_curriculam_policy_id = '$curriculam'";
        }

        if($component != ''){
            if($qu != '')
                $qu .= " AND ";
            $qu.=" A.course_component = '$component'";
        }

        if($sequence != ''){
            if($qu != '')
                $qu .= " AND ";
            $qu.=" A.sequence = '$sequence'";
        }

        if($status != ''){
            if($qu != '')
                $qu .= " AND ";
            $qu.=" A.status = '$status'";
        }
        $wh .= $qu;
        
        //$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',C.course_id,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id $wh ORDER BY A.id"); mODIFIED BY KUMARASWAMY ON 23.07.2019
		
		$result=$this->db->query("SELECT A.*,concat(B.name,'(',UPPER(B.id),')') AS course,concat(C.wef,'(',B.name,')') AS c_policy,concat(D.name,'(',D.id,')') AS d_comp FROM `cbcs_coursestructure_policy` A JOIN `cbcs_courses` B ON A.course_id=B.id JOIN `cbcs_credit_points_policy` C ON A.cbcs_curriculam_policy_id=C.id JOIN `cbcs_course_component` D ON A.course_component=D.id $wh GROUP BY A.id");
        return $result->result();
    }

}
?>