<?php

class search_by_prescription_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_prescription_details($presno)
    {
  
        
        $sql="select concat_ws(' ',b.salutation,b.first_name,b.middle_name,b.last_name)as pname ,
c.m_name,a.mqty,date_format(a.visit_date,'%d-%m-%Y')as visit_date,a.m_status,a.visit_no,a.pid,a.prel,a.doc_id,a.med_issued_by
from hc_patient a 
inner join user_details b on b.id=a.pid
inner join hc_medicine c on c.m_id=a.mid
where a.pres_no=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($presno));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    
    function get_emp_details($empno)
    {
  
        
        $sql="select concat_ws(' ',salutation,first_name,middle_name,last_name) as ename
from user_details where id=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($empno));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->ename;
        } else {
            return false;
        }
    }
    
    function get_emp_relation($empno,$rel)
    {
  
        
        $sql="select relationship from emp_family_details where emp_no=? and sno=?";

        //echo $sql;die();
        $query = $this->db->query($sql,array($empno,$rel));

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->row()->relationship;
        } else {
            return false;
        }
    }

}

?>