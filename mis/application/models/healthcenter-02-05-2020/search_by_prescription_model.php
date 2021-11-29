<?php

class search_by_prescription_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

        
    function get_prescription_details($presno)
    {
  
        
        $sql="select concat_ws(' ',b.salutation,b.first_name,b.middle_name,b.last_name)as pname ,
c.m_name,a.mqty,date_format(a.visit_date,'%d-%m-%Y')as visit_date,a.m_status,a.visit_no,a.pid,a.prel,a.doc_id,a.med_issued_by,a.mid
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
    function get_prescription_details_family($presno)
    {
  
        
        $sql="select d.name as pname ,
c.m_name,a.mqty,date_format(a.visit_date,'%d-%m-%Y')as visit_date,a.m_status,a.visit_no,a.pid,a.prel,a.doc_id,a.med_issued_by,a.mid
from hc_patient a 
inner join user_details b on b.id=a.pid
inner join hc_medicine c on c.m_id=a.mid
inner join emp_family_details d on d.sno=a.prel and d.emp_no=a.pid
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

    //////////==============================================
    function check_medicineIssue_patientTable($pid,$prel,$dt,$pvno,$ppresno,$mid,$mqty)
    {
        $q = $this->db->query("select distinct pid, prel,m_status , visit_date,visit_no,pres_no from hc_patient WHERE pid='".$pid."' and prel='".$prel."' and date(visit_date)= '".$dt."' and visit_no='".$pvno."' and pres_no='".$ppresno."' and mid=".$mid." and mqty=".$mqty);
        
       // echo $this->db->last_query();
       
        if($q->num_rows() > 0)  
                {
            return $q->result();
                }
        else
                    {
            return false;   
                }
    }

    function update_counter_master($pid, $prel, $pvdate,$pvno,$ppresno,$mid,$mqty) {
        date_default_timezone_set('Asia/Calcutta');
        $myquery = "select * from hc_patient where pid='" . $pid . "' and prel='" . $prel . "' and date(visit_date)='" . $pvdate . "' and visit_no='".$pvno."' and pres_no='".$ppresno."' and mid=".$mid." and mqty=".$mqty;
        $query = $this->db->query($myquery);
        //echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            $users = $query->result();
            foreach ($users as $u) {
                $r = array();
                $r['pid'] = $u->pid;
                $r['prel'] = $u->prel;
                $r['m_id'] = $u->mid;
                $r['mqty'] = $u->mqty;
                $r['m_rec_date'] = date("Y-m-d H:i:s");
                $r['user_id'] = $this->session->userdata('id');
                $r['visit_no'] = $u->visit_no;
                $r['pres_no'] = $u->pres_no;
                $this->db->insert('hc_counter_med_issue', $r);
                //echo $this->db->last_query();die();
                // print_r($r);
                // To update counter master table
                $data = $this->db->select('cs_qty')->from('hc_counter_master')->where("m_id", $u->mid)->get();
                $p = $data->row()->cs_qty;
                if ($p) {
                    $up['cs_qty'] = $p - ($u->mqty);
                    $this->db->update('hc_counter_master', $up, array('m_id' => $u->mid));
                }
                //echo $this->db->last_query();die();
                // To update doctor issue table

                $data = $this->db->select('qty')->from('hc_doc_medi_issue')->where("m_id", $u->mid)->get();
                $p = $data->row()->qty;
                if ($p) {
                    $doc_up['qty'] = $p - ($u->mqty);
                    $this->db->update('hc_doc_medi_issue', $doc_up, array('m_id' => $u->mid));
                }
            }
            return TRUE;
        } else {
            return false;
        }
    }

        function update_patientTable($pid, $prel, $pvdate,$pvno,$ppresno,$mid,$mqty) {
        
        $myquery = "update hc_patient set m_status='Issued',hc_patient.med_issued_by='".$this->session->userdata('id')."', hc_patient.med_issued_on = date_format('".$pvdate."','%Y-%m-%d %H:%i:%s')
 WHERE hc_patient.pid = '".$pid."' AND hc_patient.prel = '".$prel."' and visit_no='".$pvno."' and pres_no='".$ppresno."' and mid=".$mid." and mqty=".$mqty  ;
        $tqty = $this->db->query($myquery);
        //echo $this->db->last_query();die();
        return $tqty;
    }

    function get_counter_stock($mid){
            $q = $this->db->query("select a.* from hc_counter_master a where a.m_id=".$mid);
        
       // echo $this->db->last_query();
       
        if($q->num_rows() > 0)  
                {
            return $q->row();
                }
        else
                    {
            return false;   
                }

    }

}

?>