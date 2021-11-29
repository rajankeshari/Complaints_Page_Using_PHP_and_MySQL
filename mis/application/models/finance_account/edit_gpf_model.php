<?php

class Edit_gpf_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*
     * Get acc_gpf_master by MON
     */

    function get_all_fyear() {
        $myquery = "select distinct YR as fy from acc_gpf_master   order by  YR desc";
        $query = $this->db->query($myquery);
        #echo $this->db->last_query();//die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_emp_fy_reacord($empno, $fy1, $fy2) {
       # echo $empno.''.$fy1.''.$fy2;
        $myquery = "(select a.* from acc_gpf_master a where a.EMPNO=? 
and (a.YR=? and a.MON between '4' and '12'))
union
(select a.* from acc_gpf_master a where a.EMPNO=? 
and (a.YR=? and a.MON between '1' and '3'))";
        $query = $this->db->query($myquery, array($empno, $fy1, $empno, $fy2));
        #echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function update_advwithdraw($pfsub,$adref,$advwth,$interest,$opbal,$mon,$year,$empno) {
        $myquery = "update acc_gpf_master set PFSUB=? ,ADVREF=?,ADVWT=?,INTEREST=?,OPBALANCE=? where mon=? and yr=? and empno=?";
        $query = $this->db->query($myquery, array($pfsub,$adref,$advwth,$interest,$opbal,$mon,$year,$empno));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function update_balance($mon,$yr,$emp){
            $myquery = "update acc_gpf_master set balance=(opbalance+pfsub+advref)-(advwt) where mon=? and yr=? and empno=?";
        $query = $this->db->query($myquery, array($mon,$yr,$emp));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function swap_balance($mon1,$yr1,$emp1,$mon2,$yr2,$emp2){
        
        $myquery = "select balance from acc_gpf_master where mon=? and yr=? and empno=?";
        $query = $this->db->query($myquery, array($mon1,$yr1,$emp1));
        #echo $myquery;
        #echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            $p= $query->row();
        }
        //echo($p->balance);die();
        $myquery1 = "update acc_gpf_master set opbalance=".$p->balance." where mon=? and yr=? and empno=?";
        $query1 = $this->db->query($myquery1, array($mon2,$yr2,$emp2));
        #echo $this->db->last_query();die();
        #echo $myquery1;die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function get_empno_by_gpfno($gpfno){

        $myquery = "select empno from acc_gpf_account where gpfno=?";
        $query = $this->db->query($myquery,array($gpfno));
        #echo $this->db->last_query();die();
        if ($query->num_rows() > 0) {
            return $query->row()->empno;
        } else {
            return false;
        }
    }

    function getInterestToEdit($fy){
        $q="SELECT UCASE(CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name)) AS NAME,aga.GPFNO, UCASE(dpt.name) AS DEPT,UCASE(dsg.name) AS DESIG,agi.*
            FROM acc_gpf_interest agi
            LEFT JOIN user_details ud ON ud.id=agi.EMPNO
            LEFT JOIN departments dpt ON dpt.id=ud.dept_id
            LEFT JOIN emp_basic_details ebd ON ebd.emp_no=agi.EMPNO
            LEFT JOIN designations dsg ON dsg.id=ebd.designation
            LEFT JOIN acc_gpf_account aga on aga.EMPNO=agi.EMPNO
            where 1=1";
            if($fy){
                $q.=" and agi.FY='".$fy."'";
            }
            //echo $q;die();
            if($query=$this->db->query($q)){
                if($query->num_rows()>0){
                    return $query->result();
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
    }

    function getFY(){
        $q="select afd.fy from acc_fyear_details afd";
        if($query=$this->db->query($q)){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    function getInterest($id){
        $q="select agi.INTEREST from acc_gpf_interest agi where agi.SN=?";
        if($query=$this->db->query($q,array($id))){
            if($query->num_rows()>0){
                return $query->result();
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    function edit_gpf_interest($ID,$INTEREST){
        $this->db->where('SN',$ID);
        $this->db->set('INTEREST',$INTEREST);
        if($this->db->update('acc_gpf_interest')){
            return true;
        }
        else{
            return false;
        }
    }

    function getFYBySN($ID){
        $this->db->where('SN',$ID);
        if($query=$this->db->get('acc_gpf_interest')){
            if($query->num_rows()>0){
                $result=$query->result();
                //var_dump($result);
                return $result[0]->FY;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

}
