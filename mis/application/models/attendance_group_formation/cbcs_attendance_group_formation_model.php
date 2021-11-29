<?php
class Cbcs_attendance_group_formation_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function get_session_year()
	{
		$emp_id=$this->session->userdata('id');
		$this->load->database();
		$query=$this->db->query("SELECT DISTINCT session_year
								FROM subject_mapping_des AS A
								INNER JOIN subject_mapping AS B ON A.map_id = B.map_id 
								WHERE emp_no = $emp_id 
								ORDER BY session_year;");
		return $query->result();
	}
	public function get_subjects($data)
	{
		$emp_id=$this->session->userdata('id');
		$session_year=$data['session_year'];
		$session=$data['session'];
		$this->load->database();
	
		$query= $this->db->query("(SELECT A.*,B.*,NULL AS map_id,D.name AS cname,C.name AS bname,e.subject_id,group_concat(concat(e.group_start,'-',e.group_end) ORDER BY e.group_no
    SEPARATOR '<br>') as gc ,'cbcs' as rstatus
FROM cbcs_subject_offered_desc AS A
INNER JOIN cbcs_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
left join cbcs_prac_group_attendance e on e.subject_id=CONCAT('c',cast(A.sub_offered_id AS CHAR))

AND    ( case  when  B.course_id='comm' then   e.section=A.section else 1=1 end)   
WHERE C.`status`='1' AND D.`status`='1'
AND A.emp_no = '$emp_id' 
AND B.`session`='$session' AND B.session_year='$session_year' 
group by A.sub_offered_id)UNION
(
(SELECT A.*,B.*,D.name AS cname,C.name AS bname,e.subject_id, GROUP_CONCAT(CONCAT(e.group_start,'-',e.group_end)
ORDER BY e.group_no SEPARATOR '
') AS gc,'old' as rstatus
FROM old_subject_offered_desc AS A
INNER JOIN old_subject_offered AS B ON A.sub_offered_id = B.id
INNER JOIN cbcs_branches C ON B.branch_id=C.id
INNER JOIN cbcs_courses D ON B.course_id=D.id
LEFT JOIN cbcs_prac_group_attendance e ON e.subject_id=CONCAT('o',cast(A.sub_offered_id AS CHAR)) 
AND    ( case  when  B.course_id='comm' then   e.section=A.section else 1=1 end)   
WHERE C.`status`='1' AND D.`status`='1' AND A.emp_no = '$emp_id'   AND B.`session`='$session' AND B.session_year='$session_year' 
GROUP BY A.sub_offered_id )
)
");
			

		$result= $query->result();
		//echo $this->db->last_query();die();
		return $result;
	
	}

	public function get_branch($data,$subject)
	{
		$emp_id=$data['emp_id'];
		$this->load->database();
		$query=$this->db->query("SELECT DISTINCT branch_id
								FROM sub_mapping 
								INNER JOIN (SELECT map_id 
											FROM sub_mapping_des
											WHERE teacher_id = '$emp_id' AND subject_id='$subject') AS t
								ON t.map_id = sub_mapping.map_id ;");
		$branch_id=array();
		return $query->result();
	}

	public function get_branch_name($branch_id)
	{
		$branch_id_arr=array();
		for($i=0;$i<count($branch_id);$i++)
			$branch_id_arr[$i]=$branch_id[$i]->branch_id;
		$this->load->database();
		$this->db->select('id,name');
		$this->db->from('departments');
		$this->db->where_in('id',$branch_id_arr);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_branch_name_again($branch_id)
	{
		$this->load->database();
		$this->db->select('name');
		$this->db->from('departments');
		$this->db->where_in('id',$branch_id);
		$query=$this->db->get();
		return $query->result();
	}
}
?>