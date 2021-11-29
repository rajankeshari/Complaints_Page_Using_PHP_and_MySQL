<?php

class Mis_office_no_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_phone_no_dept_wise($dept_id)
    {
               
        $sql="select concat_ws(' ',b.salutation,b.first_name,b.middle_name,b.last_name)as emp_name,b.id,a.office_no,b.dept_id,c.name as dname from emp_basic_details a
inner join user_details b on b.id=a.emp_no
inner join departments c on c.id=b.dept_id
where b.dept_id=? and a.auth_id='ft'
order by b.id+0";

            $query = $this->db->query($sql, array($dept_id));

          //  echo $this->db->last_query(); die();
            if ($this->db->affected_rows() >= 0) {
                return $query->result();
            } else {
                return false;
            }
    }
    function update_phone_number($pno,$empid){
        $sql=" update emp_basic_details set office_no=? where emp_no=?";

            $query = $this->db->query($sql, array($pno,$empid));

          //  echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
    }
    function get_phone_number($empid){
        $sql="select office_no from emp_basic_details where emp_no=?";

            $query = $this->db->query($sql, array($empid));

          //  echo $this->db->last_query(); die();
            if ($this->db->affected_rows() > 0) {
                return $query->row()->office_no;
            } else {
                return false;
            }
    }

}

?>