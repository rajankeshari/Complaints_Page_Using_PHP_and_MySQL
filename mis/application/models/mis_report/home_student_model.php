<?php

class Home_student_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    function get_stu_details($admn_no)
    {
        $sql1="select id from cs_courses where id=(select course_id from stu_academic where admn_no=?)" ;
        $query = $this->db->query($sql1,array($admn_no));
        $c=$query->row()->id;
        if($c==''){
            $sql="select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as stu_name,
a.category,date_format(a.dob,'%d-%b-%Y')as dob,c.name as dname,'N/A' as cname,'N/A' as bname,
case when f.name_in_hindi='' then 'Not Available' else f.name_in_hindi end as hindi_name,g.line1,g.line2
 from user_details a
inner join stu_academic b on a.id=b.admn_no
inner join departments c on c.id=a.dept_id
inner join stu_details f on f.admn_no=a.id
inner join user_address g on g.id=a.id
where a.id=? and g.`type`='permanent'";
            
        }else{
                
        $sql = "select a.id,concat_ws(' ',a.first_name,a.middle_name,a.last_name)as stu_name,
a.category,date_format(a.dob,'%d-%b-%Y')as dob,c.name as dname,d.name as cname,e.name as bname,
case when f.name_in_hindi='' then 'Not Available' else f.name_in_hindi end as hindi_name,g.line1,g.line2
 from user_details a
inner join stu_academic b on a.id=b.admn_no
inner join departments c on c.id=a.dept_id
inner join cs_courses d on d.id=b.course_id
inner join cs_branches e on e.id=b.branch_id
inner join stu_details f on f.admn_no=a.id
inner join user_address g on g.id=a.id
where a.id=? and g.`type`='permanent'";
        }

        $query = $this->db->query($sql,array($admn_no));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    

}

?>