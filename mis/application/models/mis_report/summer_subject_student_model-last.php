<?php

class Summer_subject_student_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    
    function get_student($syear, $sess, $did, $cid, $bid, $sem) {

        if($did!='none' && $did!='comm'){
            $where_con.=" and b.dept_id='".$did."'";
        }
        if($cid!='none' && $did!='comm'){
            $where_con.=" and a.course_id='".$cid."'";
        }
        if($bid!='none' && $did!='comm'){
            $where_con.=" and a.branch_id='".$bid."'";
        }
        if($sem!=''){
            $where_con.=" and d.semester like'%".$sem."%'";
        }
        if($did=='comm' && $cid=='comm' && $bid=='comm'){
            $where_con.=" and d.aggr_id like '%comm%'";
        }
      
               $sql="select x.* , group_concat(x.admn_no separator ', ') as admn_no_list,count(x.admn_no) as no_of_stu,dd.name as dname,cc.name as cname,bb.name as bname from 
(
select e.subject_id,e.name,a.admn_no,b.dept_id,a.course_id,a.branch_id,a.session_year,a.`session`,c.sub_id,d.semester,d.aggr_id
 from reg_summer_form a
inner join user_details b on a.admn_no=b.id
inner join reg_summer_subject c on c.form_id=a.form_id
inner join course_structure d on d.id=c.sub_id
inner join subjects e on e.id=c.sub_id
where a.session_year=? and a.`session`=?".$where_con."
and a.hod_status='1' and a.acad_status='1' 
order by b.dept_id,a.course_id,a.branch_id,d.semester,a.admn_no)x
inner join departments dd on dd.id=x.dept_id
inner join cs_courses cc on cc.id=x.course_id
left join cs_branches bb on bb.id=x.branch_id
group by x.subject_id
order by dd.name";
               $query = $this->db->query($sql, array($syear, $sess)); 
            
              // echo $this->db->last_query();die();
             if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
        
    }
    
    function get_department($id){
        $sql="select name from departments where id=?";
               $query = $this->db->query($sql, array($id)); 
            
            if ($this->db->affected_rows() >= 0) {
                return $query->row()->name;
            } else {
                return false;
            }
        
    }
    function get_course($id){
        $sql="select name from cs_courses where id=?";
               $query = $this->db->query($sql, array($id)); 
            
            if ($this->db->affected_rows() >= 0) {
                return $query->row()->name;
            } else {
                return false;
            }
        
    }
    function get_branch($id){
        $sql="select name from cs_branches where id=?";
               $query = $this->db->query($sql, array($id)); 
            
            if ($this->db->affected_rows() >= 0) {
                return $query->row()->name;
            } else {
                return false;
            }
        
    }
    
    
}

?>