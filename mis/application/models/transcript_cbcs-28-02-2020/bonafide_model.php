<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bonafide_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    
    
        function get_stu_details($id)
        {
            $sql = "select a.id,concat( a.salutation,' ',a.first_name,' ',a.middle_name,' ',a.last_name)as stu_name,b.father_name as fname,
c.course_id as cid,c.branch_id as bid,d.name as cname,e.name as bname,d.duration as cduration
 from  user_details a 
inner join user_other_details b on b.id=a.id
inner join stu_academic c on a.id=c.admn_no
inner join cs_courses d on d.id=c.course_id
inner join cs_branches e on e.id=c.branch_id
where a.id=?";




        $query = $this->db->query($sql, array($id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    function get_registered_or_not($id)
        {
            $sql = "select * from 
(
select admn_no,semester,session_year,session from reg_regular_form where admn_no=?
union
select admn_no,semester,session_year,session from  reg_other_form where admn_no=?
union
select admn_no,semester,session_year,session from  reg_exam_rc_form where admn_no=?
union
select admn_no,semester,session_year,session from reg_idle_form where admn_no=?
)A order by 0+semester desc limit 1";




        $query = $this->db->query($sql, array($id,$id,$id,$id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_pass_session($id)
        {
            $sql = "select a.enrollment_year,b.duration,((a.enrollment_year+0)+b.duration) as passout,
concat((a.enrollment_year+0),'-',(a.enrollment_year+0)+b.duration) as pass_session from stu_academic a 
inner join cs_courses b on a.course_id=b.id
where a.admn_no=?
";
        $query = $this->db->query($sql, array($id));

        //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    


}

?>