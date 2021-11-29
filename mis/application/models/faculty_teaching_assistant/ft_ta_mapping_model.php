<?php

class ft_ta_mapping_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_faculty_sub_list($syear,$sess,$user_id)
    {
          
        $sql = "(select b.*,c.subject_id,c.name,a.dept_id,a.course_id,a.branch_id,a.semester,a.section,
concat_ws(' ',d.salutation,d.first_name,d.middle_name,d.last_name)as faculty,f.name as dname,
g.name as cname,h.name as bname,e.aggr_id,
SUBSTRING_INDEX(e.aggr_id, '_', -2)as cs
 from subject_mapping a 
inner join subject_mapping_des b on b.map_id=a.map_id
inner join subjects c on c.id=b.sub_id
inner join user_details d on d.id=b.emp_no
inner join course_structure e on e.id=c.id
inner join departments f on f.id=a.dept_id
inner join cs_courses g on g.id=a.course_id
inner join cs_branches h on h.id=a.branch_id
where a.session_year=? and a.`session`=?
and b.emp_no=? and c.`type`<>'Non-Contact')union (SELECT b.id,b.emp_no,b.coordinator,b.sub_id,NULL AS M,NULL AS id,a.sub_code AS subject_id,a.sub_name AS NAME,a.dept_id,a.course_id,a.branch_id,a.semester,
b.section,CONCAT_WS(' ',d.salutation,d.first_name,d.middle_name,d.last_name) AS faculty,
f.name AS dname, g.name AS cname,h.name AS bname,'cbcs' AS aggr_id,NULL AS cs
 FROM cbcs_subject_offered a
INNER JOIN cbcs_subject_offered_desc b ON b.sub_offered_id=a.id
INNER JOIN user_details d ON d.id=b.emp_no
INNER JOIN cbcs_departments f ON f.id=a.dept_id
INNER JOIN cbcs_courses g ON g.id=a.course_id
INNER JOIN cbcs_branches h ON h.id=a.branch_id
WHERE a.session_year=? AND a.`session`=? AND b.emp_no=? AND a.sub_type<>'Non-Contact' GROUP BY a.id)";

        //echo $sql;die();
        $query = $this->db->query($sql,array($syear,$sess,$user_id,$syear,$sess,$user_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

   /* function get_jrf_list($id){

    $sql = "select ucase(a.id)as admn_no,ucase(concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name))stu_name from user_details a 
inner join stu_academic b on b.admn_no=a.id
where a.dept_id=? and b.auth_id='jrf' and b.branch_id='fulltime'
and a.id not in (select a.admn_no from phd_awarded a where a.dept_id=?)
order by a.first_name,a.middle_name,a.last_name";

            //echo $sql;die();
            $query = $this->db->query($sql,array($id,$id));

           //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }

    }*/
	function get_jrf_list($id,$sy,$sess){

    $sql = "(select ucase(a.id)as admn_no,ucase(concat_ws(' ',a.salutation,a.first_name,a.middle_name,a.last_name))stu_name from user_details a 
inner join stu_academic b on b.admn_no=a.id
where a.dept_id=? and b.auth_id='jrf' and b.branch_id='fulltime'
and a.id not in (select a.admn_no from phd_awarded a where a.dept_id=?)
order by a.first_name,a.middle_name,a.last_name) 
union
(SELECT UCASE(a.id) AS admn_no, UCASE(CONCAT_WS(' ',a.salutation,a.first_name,a.middle_name,a.last_name))stu_name
FROM user_details a
INNER JOIN stu_academic b ON b.admn_no=a.id
INNER JOIN reg_regular_form c ON c.admn_no=a.id
WHERE a.dept_id=? AND b.auth_id='pg' AND c.course_id='m.tech' AND c.session_year=? AND c.`session`=? AND c.hod_status='1' AND c.acad_status='1'
GROUP BY a.id
ORDER BY a.first_name,a.middle_name,a.last_name)";

            //echo $sql;die();
            $query = $this->db->query($sql,array($id,$id,$id,$sy,$sess));

           //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }

    }

    function insert_batch($data)
    {
        if($this->db->insert_batch('faculty_ta_mapping_tbl',$data))
            return TRUE;
        else
            return FALSE;
    }
    function insert_batch_auth($data)
    {

        if($this->db->insert_batch('user_auth_types',$data))
            return TRUE;
        else
            return FALSE;
    }
    function delete_auth($id){
        $this->db->delete('user_auth_types', array('id' => $id)); 
        
    }
    function get_details($tmapid,$tempno,$tsubid,$tcid,$bid){
            $sql = "(select a.*,b.subject_id,b.name,f.name as cname,g.name as bname,c.aggr_id,
concat_ws(' ',h.first_name,h.middle_name,h.last_name)as sname,SUBSTRING_INDEX(c.aggr_id, '_', -2)as cs,c.section from faculty_ta_mapping_tbl a 
inner join subjects b on b.id=a.sub_id 
inner join subject_mapping c on c.map_id=a.map_id
inner join cs_courses f on f.id=c.course_id
inner join cs_branches g on g.id=c.branch_id
inner join user_details h on h.id=a.admn_no
where a.map_id=? and a.emp_no=? and a.sub_id=?)union(SELECT a.*,b.sub_code AS subject_id,b.sub_name AS name,
f.name as cname,g.name as bname,'cbcs' AS aggr_id,
concat_ws(' ',h.first_name,h.middle_name,h.last_name)as sname, NULL AS cs,i.section
 FROM faculty_ta_mapping_tbl a
INNER JOIN cbcs_subject_offered b ON b.sub_code=a.sub_id
inner join cbcs_courses f on f.id=b.course_id
inner join cbcs_branches g on g.id=b.branch_id
inner join user_details h on h.id=a.admn_no
INNER JOIN cbcs_subject_offered_desc i ON i.sub_offered_id=b.id
where a.map_id=? and a.emp_no=? and a.sub_id=? AND f.id=? AND g.id=? )";

            //echo $sql;die();
            $query = $this->db->query($sql,array($tmapid,$tempno,$tsubid,$tmapid,$tempno,$tsubid,$tcid,$bid));

          // echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }

    }
    function delete_record($id,$admn_no){
            $sql = "delete from faculty_ta_mapping_tbl where id=? and admn_no=?";

            //echo $sql;die();
            $query = $this->db->query($sql,array($id,$admn_no));

           //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                return false;
            }

    }

    function get_faculty_details($syear,$sess,$emp_no){
         $sql = "select a.*,d.subject_id,d.name,f.name as cname,g.name as bname,b.aggr_id,
concat_ws(' ',h.first_name,h.middle_name,h.last_name)as sname,SUBSTRING_INDEX(b.aggr_id, '_', -2)as cs,b.section from faculty_ta_mapping_tbl a
inner join subject_mapping b on b.map_id=a.map_id
inner join subject_mapping_des c on c.map_id=b.map_id
inner join subjects d on d.id=a.sub_id
inner join cs_courses f on f.id=b.course_id
inner join cs_branches g on g.id=b.branch_id
inner join user_details h on h.id=a.admn_no
where b.session_year=? and b.`session`=?
and c.emp_no=?
group by a.map_id,a.sub_id,a.admn_no";

            //echo $sql;die();
            $query = $this->db->query($sql,array($syear,$sess,$emp_no));

           //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                 return $query->result();
            } else {
                return false;
            }

    }

    function get_sub_status($mapid,$empid,$subid){
         $sql = "select x.* from faculty_ta_mapping_tbl x where x.map_id=? and x.emp_no=? and x.sub_id=?";

            //echo $sql;die();
            $query = $this->db->query($sql,array($mapid,$empid,$subid));

           //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                 return TRUE;
            } else {
                return false;
            }

    }

    function check_exists($id,$auth_id){
        $sql = "select * from user_auth_types where id=? and auth_id=?";

            //echo $sql;die();
            $query = $this->db->query($sql,array($id,$auth_id));

           //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                 return TRUE;
            } else {
                return false;
            }

    }

    function check_stu_status($id){
        $sql = "select a.* from faculty_ta_mapping_tbl a where a.admn_no=?";

            //echo $sql;die();
            $query = $this->db->query($sql,array($id,$auth_id));

           //echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                 return TRUE;
            } else {
                return false;
            }
    }
    

}

?>