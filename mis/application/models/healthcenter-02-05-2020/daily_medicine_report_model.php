<?php

class Daily_medicine_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_patient_list($dtfrom,$dtto)
    {
  
        
        $sql="select x.* from(
select b.m_name,sum(a.mqty) as tot_med_qty,group_concat(a.pid,' [ ',a.prel,' ] ')as patient ,
group_concat(a.pres_no)as prescription,date_format(a.visit_date,'%d-%m-%Y')as visit_date,group_concat(a.doc_id) as doctor,a.mid from hc_patient a 
inner join hc_medicine b on b.m_id=a.mid
where DATE(a.visit_date) BETWEEN '".$dtfrom."' AND '".$dtto."'
group by a.mid)x
order by x.visit_date,x.mid;";

        //echo $sql;die();
        $query = $this->db->query($sql);

       //echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    

}

?>