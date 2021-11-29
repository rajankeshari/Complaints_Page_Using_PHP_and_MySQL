<?php

class Passout_student_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function add_passout_student_details($data){
         if($this->db->insert('alumni_student_status',$data))
            return $this->db->insert_id();
        else
            return FALSE;
    }

    function check_exists($id){
        $sql="select * from alumni_student_status a where a.admn_no=?";
        $query = $this->db->query($sql,array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_all_drop_tables(){
              $sql="SELECT table_name FROM INFORMATION_SCHEMA.tables  WHERE table_schema='mis_40_50' and TABLE_NAME LIKE 'drop%'";
              $query = $this->db->query($sql);

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }
         
         }
      

function get_all_passout_student_list(){

    $sql="select * from alumni_student_status";
              $query = $this->db->query($sql);

              if ($this->db->affected_rows() > 0) {
                    return $query->result();
                } else {
                    return false;
                }

}
 function update_users_table_status_passout($admn_no){
	 $sql = "UPDATE users SET STATUS='P' WHERE id=?";
        $query = $this->db->query($sql,array($admn_no));
        
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
	 
 }
 function update_convocation_table($admn_no,$yop){
	 $sql = " insert into convocation_admin_final(certificate_no,admn_no,NAME,course,branch,ogpa,ogpa_h,final_ogpa,division,date_of_result,yop,dept_id,course_id,branch_id,deptnm)
	 
select
null as 'certificate_no',
a.id as admn_no,
concat_ws(' ',a.first_name,a.middle_name,a.last_name)as name,
c.name as course,
d.name as branch,
f.core_cgpa as ogpa,
f.cgpa as ogpa_h,
f.cgpa as final_ogpa,
(CASE
 WHEN round(f.cgpa,2)>=5 and round(f.cgpa,2)<7 THEN 'Second CLass'
 WHEN round(f.cgpa,2)>=7 and round(f.cgpa,2)<9 THEN 'First CLass'
 WHEN round(f.cgpa,2)>=9 THEN 'First Class with Distinction'
END) as division,
null as 'date_of_result',
$yop as yop,
a.dept_id,b.course_id,b.branch_id,e.name as deptnm

from user_details a

inner join stu_academic b on b.admn_no=a.id  AND  a.id =?
inner join cs_courses c on c.id=b.course_id


inner join cs_branches d on d.id=b.branch_id
inner join departments e on e.id=a.dept_id
left join final_semwise_marks_foil_freezed f on f.admn_no=a.id  AND (c.duration*2)=f.semester
ORDER BY f.actual_published_on DESC  LIMIT 1
	 
	 ";
        $query = $this->db->query($sql,array($admn_no));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
	 
}
function check_convocation_table($admn_no){
	$sql="select * from convocation_admin_final a where a.admn_no=?";
        $query = $this->db->query($sql,array($admn_no));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
	
}


}
?>