<?php

class Final_year_passlist_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_student_passlist($syear,$sess,$etype,$did,$cid,$bid,$sem,$stu_status)
    {
        //echo $syear;echo $sess; echo $etype; echo $did; echo cid;echo $bid; echo $sem; die();
    
        $sql = "select a.admn_no,
concat_ws(' ' ,b.first_name,b.middle_name,b.last_name) as stu_name,
c.name_in_hindi,
a.dept,
a.course,
a.branch,
a.semester,
a.status,
e.name as dname,
d.name as cname,
f.name as bname,
g.line1,g.line2,
g.city,g.state,
g.pincode,
a.hstatus,
b.email,
h.mobile_no,
h.father_name,b.sex
 from final_semwise_marks_foil_freezed a
inner join user_details b on a.admn_no=b.id
inner join stu_details c on c.admn_no=a.admn_no
left join cs_courses d on d.id=a.course
inner join departments e on e.id=a.dept
left join cs_branches f on f.id=a.branch
left join user_address g on g.id=a.admn_no
inner join user_other_details h on h.id=a.admn_no
where 1=1 and g.`type`='permanent'";

        
        if($syear)
        {
            $sql .= " and a.session_yr='".$syear."'";
        }
        
        if($sess)
        {
            $sql .= " and a.session='".$sess."'";
        }
        if($etype)
        {
            if($etype=="Regular"){$etype='R';}
            if($etype=="Ohter"){$etype='O';}
            if($etype=="Special"){$etype='S';}
            if($etype=="JRF"){$etype='J';}
            $sql .= " and a.type='".$etype."'";
        }
        if($did<>'none')
        {
            $sql .= " and a.dept='".$did."'";
        }
        if($cid<>'none')
        {
            $sql .= " and a.course='".$cid."'";
        }
        if($bid<>'none')
        {
            $sql .= " and a.branch='".$bid."'";
        }
        
        if($sem)
        {
            $sql .= " and a.semester='".$sem."'";
        }
        
       if($stu_status=="PASS" || $stu_status=="FAIL"){
           $sql .= " and a.status='".$stu_status."'";
       }
        
        $query = $this->db->query("$sql order by a.dept,a.course,a.branch,a.admn_no");
        //$query = $this->db->query($syear,$sess,$etype,$did,$cid,$bid,$sess);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    

}

?>