<?php
 
class Stu_registration_model_newreport extends CI_Model {

    function __construct() {
        parent::__construct();
    }
  
        
    function get_stu_registration($data)
    {


       // echo '<pre>';print_r($data);echo '</pre>';die();
       
            $sql = "select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as sname,d.enrollment_year as year_of_admission,
b.category,b.physically_challenged,
b.dept_id,a.course_id,a.branch_id,a.semester,a.section,e.domain_name,f.mobile_no
 from reg_regular_form a    
inner join user_details b on b.id=a.admn_no
inner join users c on c.id=a.admn_no and c.`status`='A'
inner join stu_academic d on d.admn_no=a.admn_no
left JOIN emaildata e ON e.admission_no=a.admn_no
INNER JOIN user_other_details f ON f.id=a.admn_no
where a.hod_status='1' and a.acad_status='1' and a.`status`='1' and a.re_id like '%ver%'  and 1=1 ";
        
        
            if ($data['selsyear'])
            {
                    $sql .= " AND a.session_year='".$data['selsyear']."'";
            }
            if ($data['selsess'])
            {
                    $sql .= " AND a.session='".$data['selsess']."'";
            }

            if ($data['dept_id']!='none' && $data['dept_id']!='')
            {
                    $sql .= " AND b.dept_id='".$data['dept_id']."'";
            }

            if ($data['course_id']!='none' && $data['course_id']!='')
            {
                    $sql .= " AND a.course_id='".$data['course_id']."'";
            }

            if ($data['branch_id']!='none' && $data['branch_id']!='')
            {
                    $sql .= " AND a.branch_id='".$data['branch_id']."'";
            }


        
        
        $query = $this->db->query("$sql order by b.dept_id,a.course_id,a.branch_id,a.semester,a.section");


       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	
	function get_stu_registration_with_course($data) 
    {
         $sql = "SELECT t.* from
(select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as sname,d.enrollment_year as year_of_admission,
b.category,b.physically_challenged,
b.dept_id,a.course_id,a.branch_id,a.semester,a.section,e.domain_name,f.mobile_no,
g.subject_code,g.subject_name,g.sub_category,h.sub_type,a.session_year,a.`session`
 from reg_regular_form a    
inner join user_details b on b.id=a.admn_no
inner join users c on c.id=a.admn_no and c.`status`='A'
inner join stu_academic d on d.admn_no=a.admn_no
left JOIN emaildata e ON e.admission_no=a.admn_no
INNER JOIN user_other_details f ON f.id=a.admn_no
inner join cbcs_stu_course g on g.form_id=a.form_id and g.admn_no=a.admn_no
left join cbcs_subject_offered h on h.id=g.sub_offered_id and h.session_year=a.session_year and h.`session`=a.`session`
where a.session_year='".$data['selsyear']."' and a.session='".$data['selsess']."' and  a.hod_status='1' and a.acad_status='1' and a.`status`='1' and a.re_id like '%ver%' and 1=1
UNION
select a.admn_no,concat_ws(' ',b.first_name,b.middle_name,b.last_name)as sname,d.enrollment_year as year_of_admission,
b.category,b.physically_challenged,
b.dept_id,a.course_id,a.branch_id,a.semester,a.section,e.domain_name,f.mobile_no,
g.subject_code,g.subject_name,g.sub_category,h.sub_type,a.session_year,a.`session`
 from reg_regular_form a    
inner join user_details b on b.id=a.admn_no
inner join users c on c.id=a.admn_no and c.`status`='A'
inner join stu_academic d on d.admn_no=a.admn_no
left JOIN emaildata e ON e.admission_no=a.admn_no
INNER JOIN user_other_details f ON f.id=a.admn_no
inner join old_stu_course g on g.form_id=a.form_id and g.admn_no=a.admn_no
left join old_subject_offered h on h.id=g.sub_offered_id and h.session_year=a.session_year and h.`session`=a.`session`
where a.session_year='".$data['selsyear']."' and a.session='".$data['selsess']."' and  a.hod_status='1' and a.acad_status='1' and a.`status`='1' and a.re_id like '%ver%' and 1=1)t where 1=1
 ";
        
        
            // if ($data['selsyear'])
            // {
            //         $sql .= " AND t.session_year='".$data['selsyear']."'";
            // }
            // if ($data['selsess'])
            // {
            //         $sql .= " AND t.session='".$data['selsess']."'";
            // }

            if ($data['dept_id']!='none' && $data['dept_id']!='')
            {
                    $sql .= " AND t.dept_id='".$data['dept_id']."'";
            }

            if ($data['course_id']!='none' && $data['course_id']!='')
            {
                    $sql .= " AND t.course_id='".$data['course_id']."'";
            }

            if ($data['branch_id']!='none' && $data['branch_id']!='')
            {
                    $sql .= " AND t.branch_id='".$data['branch_id']."'";
            }


        
        
        $query = $this->db->query("$sql  ORDER BY t.admn_no");


       
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
           
    }
	
	
	
	
	

    function get_subjects($form_id,$tbl_subj)
    {
        $sql = "select a.*,b.subject_id,b.name,b.`type`,c.semester,c.sequence,c.aggr_id from ".$tbl_subj." a
inner join subjects b on b.id=a.sub_id left join course_structure c on c.id=a.sub_id where a.form_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($form_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

    function get_details_by_formID($form_id){
        $sql = "select * from reg_regular_form where form_id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($form_id));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }

    }

    function get_honour_minor($admn_no){
        $sql = "select a.* from hm_form a where a.admn_no=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }


    function get_subjects_cs($cid,$sem,$sec=null){

        $caggrid=explode('_', $cid);
        $tsem=$sem.'_'.$sec;
        if($caggrid[0]=="comm"){
            $sql = "select 'NA' as form_id ,c.sequence as sub_seq,c.id as sub_id,b.subject_id,b.name,b.`type`,c.semester,c.sequence,c.aggr_id from subjects b
inner join course_structure c on c.id=b.id
where c.aggr_id=? and c.semester=? and  c.sequence not like '%.%' order by c.sequence+0";
$query = $this->db->query($sql,array($cid,$tsem));

        }else{

        $sql = "select 'NA' as form_id ,c.sequence as sub_seq,c.id as sub_id,b.subject_id,b.name,b.`type`,c.semester,c.sequence,c.aggr_id from subjects b
inner join course_structure c on c.id=b.id
where c.aggr_id=? and c.semester=? and c.sequence not like '%.%' order by c.sequence+0";

$query = $this->db->query($sql,array($cid,$sem));
}

       // echo $sql;die();
       

      // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }

function get_minor($admn_no){
        $sql = "select b.minor_agg_id from hm_form a 
inner join hm_minor_details b on b.form_id=a.form_id
where a.admn_no=? and b.offered='1'";

        //echo $sql;die();
        $query = $this->db->query($sql,array($admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }

    }
    

 
    

}

?>