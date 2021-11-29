<?php

class Summer_subject_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_department($id) {
        $sql = "select dept_id from user_details where id=?";

        $query = $this->db->query($sql, array($id));
        if ($this->db->affected_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
    function get_summer_subject($syear,$did){
        $sql = "select a.sub_id,c.subject_id,c.name,d.aggr_id,d.semester,count(b.admn_no)as nostu from reg_summer_subject a
inner join reg_summer_form b on b.form_id=a.form_id
inner join subjects c on c.id=a.sub_id
inner join course_structure d on d.id=a.sub_id
where b.session_year=? and b.`session`='Summer'
and b.hod_status<>'2' and b.acad_status<>'2' and d.aggr_id like ?
group by a.sub_id";

        $query = $this->db->query($sql, array($syear,'%'.$did.'%'));
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
	
	function get_summer_subject_common($syear){
        $sql = "SELECT a.sub_id,c.subject_id,c.name,d.aggr_id,concat(d.semester,'[',f.section,']')as semester, COUNT(b.admn_no) AS nostu
FROM reg_summer_subject a 
INNER JOIN reg_summer_form b ON b.form_id=a.form_id
INNER JOIN subjects c ON c.id=a.sub_id
INNER JOIN course_structure d ON d.id=a.sub_id
inner join stu_section_data f on f.admn_no=b.admn_no and f.session_year=b.session_year
WHERE b.session_year=? AND b.`session`='Summer' AND b.hod_status<>'2' AND b.acad_status<>'2' AND d.aggr_id LIKE '%comm%'
GROUP BY a.sub_id";

        $query = $this->db->query($sql, array($syear));
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}
?>

