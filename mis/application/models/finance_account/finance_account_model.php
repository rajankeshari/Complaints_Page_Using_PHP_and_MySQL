<?php

/**
 * Author: Anuj
*/
class Finance_account_model extends CI_Model {
    
    

    function __construct() {
        parent::__construct();
    }
        // inserting data in table leave_notice_tbl
      
        
       
        
        public function get_emp_personal($id) 
        {

            $myquery = "select distinct a.id,a.photopath,concat(a.first_name,' ',a.middle_name,' ',a.last_name) as emp_name,d.name as designation,c.PNRNO,
(case when (c.PFACNO is null or c.PFACNO='' or c.PFACNO='0') then c.PRAN
 when (c.PRAN is null or c.PRAN='' or c.PRAN='0') then c.PFACNO
else null end ) as pf_pran,uod.bank_name,uod.bank_accno,
EXTRACT(YEAR FROM b.joining_date) AS start_year,EXTRACT(YEAR FROM b.retirement_date) AS end_year,dep.name as dept_id
from user_details a 
inner join user_other_details uod on uod.id=a.id
inner join emp_basic_details b on a.id=b.emp_no
inner join acc_pay_details c on a.id=c.EMPNO
inner join designations d on d.id=b.designation
inner join departments dep on a.dept_id=dep.id
where a.id=?";

             $query = $this->db->query($myquery,array($id));
             //echo $this->db->last_query();die(); 
             if ($query->num_rows() > 0) {
                 return $query->row();

             } else {
                 return FALSE;
             }
            
            
        }

 // replaced d.name as designation by c.DESIG as designation 
// This is done as we want to show he designation from acc_pay_details table
// This is because only this table keep month wise designation of all employee 
// c.DESIG as designation       
        
        public function get_emp_personal_ss($id,$mon,$yr) 
        {

            /*$myquery = "select distinct a.id,a.photopath,concat(a.first_name,' ',a.middle_name,' ',a.last_name) as emp_name,c.DESIG as designation,c.PNRNO,
(case when (c.PFACNO is null or c.PFACNO='' or c.PFACNO='0') then c.PRAN
 when (c.PRAN is null or c.PRAN='' or c.PRAN='0') then c.PFACNO
else null end ) as pf_pran,uod.bank_name,uod.bank_accno,
EXTRACT(YEAR FROM b.joining_date) AS start_year,EXTRACT(YEAR FROM b.retirement_date) AS end_year,dep.name as dept_id
from user_details a 
inner join user_other_details uod on uod.id=a.id
inner join emp_basic_details b on a.id=b.emp_no
inner join acc_pay_details c on a.id=c.EMPNO
inner join designations d on d.id=b.designation
inner join departments dep on a.dept_id=dep.id
where a.id=? and c.MON=? and c.YR=?";
*/

// The above query does not work for empno 66 month 09 year 19 to be tested the reason

$myquery = "select distinct a.id,a.photopath,concat(a.first_name,' ',a.middle_name,' ',a.last_name) as emp_name,c.DESIG as designation,c.PNRNO,
(case when (c.PFACNO is null or c.PFACNO='' or c.PFACNO='0') then c.PRAN
 when (c.PRAN is null or c.PRAN='' or c.PRAN='0') then c.PFACNO
else c.PFACNO end ) as pf_pran,uod.bank_name,uod.bank_accno,
EXTRACT(YEAR FROM b.joining_date) AS start_year,EXTRACT(YEAR FROM b.retirement_date) AS end_year,dep.name as dept_id
from user_details a
inner join user_other_details uod on uod.id=a.id
inner join emp_basic_details b on a.id=b.emp_no
inner join acc_pay_details c on a.id=c.EMPNO
inner join designations d on d.id=b.designation
inner join departments dep on a.dept_id=dep.id
where a.id=? and c.MON=? and c.YR=?";
             $query = $this->db->query($myquery,array($id,$mon,$yr));
             //echo $this->db->last_query();die(); 
             if ($query->num_rows() > 0) {
                 return $query->row();

             } else {
                 return FALSE;
             }
            
            
        }


        public function get_emp_personal_temp($id) 
        {

            $myquery = "select distinct a.id,a.photopath,concat(a.first_name,' ',a.middle_name,' ',a.last_name) as emp_name,d.name as designation,c.PNRNO,
(case when (c.PFACNO is null or c.PFACNO='' or c.PFACNO='0') then c.PRAN
 when (c.PRAN is null or c.PRAN='' or c.PRAN='0') then c.PFACNO
else null end ) as pf_pran,uod.bank_name,uod.bank_accno,
EXTRACT(YEAR FROM b.joining_date) AS start_year,EXTRACT(YEAR FROM b.retirement_date) AS end_year,dep.name as dept_id
from user_details a 
inner join user_other_details uod on uod.id=a.id
inner join emp_basic_details b on a.id=b.emp_no
inner join acc_pay_details_temp c on a.id=c.EMPNO
inner join designations d on d.id=b.designation
inner join departments dep on a.dept_id=dep.id
where a.id=?";

             $query = $this->db->query($myquery,array($id));

             
             if ($query->num_rows() > 0) {
                 return $query->row();

             } else {
                 return FALSE;
             }
            
            
        }
        function get_emp_account($id,$mon,$year)
        {
            $myquery = "select * from acc_pay_details where empno=? and MON=? and YR=?";

             $query = $this->db->query($myquery,array($id,$mon,$year));

             if ($query->num_rows() > 0) {
                 return $query->row();

             } else {
                 return FALSE;
             }
        }
        
        function get_emp_img_address($id)
        {
        $myquery = "SELECT a.id, CONCAT(a.first_name,' ',a.middle_name,' ',a.last_name) AS emp_name,a.photopath, CONCAT(b.line1,'  ',b.line2) AS eaddress,
            (case when (c.no_of_ac is null or c.no_of_ac='' or c.no_of_ac='0') then 0
            else c.no_of_ac end ) as no_of_ac,d.name as dept_nm,f.name as emp_desi
            FROM user_details a
            INNER JOIN user_address b ON a.id=b.id
            left join acc_emp_ac_status c on a.id=c.empno
            inner join departments d on d.id=a.dept_id
            inner join emp_basic_details e on e.emp_no=a.id
            inner join designations f on f.id=e.designation
            WHERE a.id=? AND b.`type`='present'";   
            $query = $this->db->query($myquery,array($id));
            //echo $this->db->last_query();die();
             if ($query->num_rows() > 0) {
                 return $query->row();

             } else {
                 return FALSE;
             }
        }
        
        function insert_elect_bill($data)
        {
            if ($this->db->insert('acc_elect_unit_temp', $data))
                    return $this->db->insert_id();
                else
                    return FALSE;
        }
		
		function insert_elect_bill_main_table($data) //This function added on 08.05.2020 by Kumaraswamy
        {
			
            if ($this->db->insert('acc_elect_unit', $data))
                    return true;
                else
                    return FALSE;
        }

          function UP_elect_bill($data)
        {
            if ($this->db->insert('acc_elect_unit_temp', $data))
                    return $this->db->insert_id();
                else
                    return FALSE;
        }



         function update_elect_bill($data,$con)
        {
            if ($this->db->update('acc_elect_unit_temp', $data,$con))
                    return true;
                else
                    return FALSE;
        }
		
		function update_elect_bill_main_table($data,$con)//This function added on 08.05.2020 by Kumaraswamy
        {
            if ($this->db->update('acc_elect_unit', $data,$con))
                    return true;
                else
                    return FALSE;
        }
        function insert_elect_master($data)
        {
            if ($this->db->insert('acc_elect_master', $data))
                    return $this->db->insert_id();
                else
                    return FALSE;
        }
        
         
        function insert_ac_master($data)
        {
            if ($this->db->insert('acc_ac_master', $data))
                    return $this->db->insert_id();
                else
                    return FALSE;
        }
        
        
        function insert_elect_master_description($data)
        {
            if ($this->db->insert('acc_elect_master_description', $data))
                    return $this->db->insert_id();
                     
                else
                    return FALSE;
        }
        
         function update_previous_toDeactive($id) //not in use
        {
            $sql = "update acc_ac_master set `status`='deactive' where id=".($id-1);
            $query = $this->db->query($sql);
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        function update_all_previous_toDeactive($id){
            $sql = "update acc_ac_master set `status`='deactive' where id<>".$id;
            $query = $this->db->query($sql);
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
            function update_previous_toDeactive_billmaster($id)
        {
            //$sql = "update acc_elect_master set `status`='deactive' where id=".($id-1);
            $sql = "update acc_elect_master set `status`='deactive' where id<>".$id;
            $query = $this->db->query($sql);
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        
        
        
        
        function check_status($yr,$mon,$emp)
        {
            $myquery = "select * from  acc_elect_unit_temp where year=? and month=? and empno=?";

             $query = $this->db->query($myquery,array($yr,$mon,$emp));

             if ($query->num_rows() > 0) {
                 return TRUE;

             } else {
                 return FALSE;
             }
        }
        
           function check_status_bill_master($yr,$mon)
        {
            $myquery = "select * from  acc_elect_master where year=? and month=?";

             $query = $this->db->query($myquery,array($yr,$mon));
             if ($query->num_rows() > 0) {
                 return TRUE;

             } else {
                 return FALSE;
             }
        }
        
        
         function check_ac_master($yr,$mon)
        {
            $myquery = "select * from acc_ac_master where year=? and month=?";

             $query = $this->db->query($myquery,array($yr,$mon));

             if ($query->num_rows() > 0) {
                 return TRUE;

             } else {
                 return FALSE;
             }
        }
        
        
        
        
        function insert_ac_details($data)
        {
            if ($this->db->insert('acc_emp_ac_status', $data))
                    return $this->db->insert_id();
                else
                    return FALSE;
        }
        
        function check_status_ac($emp)
        {
            $myquery = "select * from  acc_emp_ac_status where empno=? and status='1'";

             $query = $this->db->query($myquery,array($emp));

             if ($query->num_rows() > 0) {
                 return TRUE;

             } else {
                 return FALSE;
             }
        }
        
        function cal_elec_bill($unit)
        {

            $q="SET @a=?";
            //echo $q;die();
            $this->db->query($q,array($unit));
            $q="SET @b='' ";
            $this->db->query($q);
            $q="SET @tot = 0";
            $this->db->query($q);
             
            /* $myquery = "select max(A.amount) amount from 
(
SELECT 
IF(@b='',if(@a<b.unit_to,@a*b.unit_rate,@tot:=b.unit_to*b.unit_rate+@tot), IF(@b<b.unit_to,@tot:=@b*unit_rate+@tot,@tot:=b.unit_to*b.unit_rate+@tot))  as amount, 
IF(@b='',if(@a<b.unit_to,@b:=-1,@b:=@a-b.unit_to),IF(@b<b.unit_to,@b:=-1,@b:=@b-b.unit_to))AS rest_unit
FROM acc_elect_master a
JOIN acc_elect_master_description b ON a.id=b.desc_id
WHERE a.`status`='active' AND @b >=0
) A ";*/

$myquery="select max(A.amount) amount from 
(
SELECT 
IF(@b='',if(@a<(b.unit_to-b.unit_from)+1,@a*b.unit_rate,@tot:=((b.unit_to-b.unit_from)+1)*b.unit_rate+@tot), IF(@b<(b.unit_to-b.unit_from)+1,@tot:=@b*unit_rate+@tot,@tot:=((b.unit_to-b.unit_from)+1)*b.unit_rate+@tot))  as amount, 

IF(@b='',if(@a<(b.unit_to-b.unit_from)+1,@b:=-1,@b:=@a-((b.unit_to-b.unit_from)+1)),IF(@b<(b.unit_to-b.unit_from)+1,@b:=-1,@b:=@b-((b.unit_to-b.unit_from)+1)))AS rest_unit
FROM acc_elect_master a
JOIN acc_elect_master_description b ON a.id=b.desc_id
WHERE a.`status`='active' AND @b >=0
) A ";

             $query = $this->db->query($myquery);
             //echo $this->db->last_query();die();
             if ($query->num_rows() > 0) {
                  return $query->row();

             } else {
                 return FALSE;
             }
        }

        function get_prev_elect_unit($mon,$year,$empno){
            $arr=array('mon'=>$mon,'year'=>$year);
            for($i=1;;$i++){
                $arr=$this->get_mon_yr_for_elect_unit_prev($arr);
                $q="select * from acc_elect_unit aeu where aeu.month=? and aeu.year=? and aeu.empno=?";
                if($query=$this->db->query($q,array($arr['mon'],$arr['year'],$empno))){
                    if($query->num_rows()>0){
                        return $query->row();
                    }
                    else{
                        if($i==4){
                        return false;
                        } 
                    }
                }
            }
        }
        
        function get_row_by_id($lid)
        {
        $myquery = "select * from acc_elect_master_description where id=?";

             $query = $this->db->query($myquery,array($lid));

             if ($query->num_rows() > 0) {
                  return $query->row();

             } else {
                 return FALSE;
             }
        }
        
        function cheak_elect_master_description($did,$uf,$uto)
        {
            $myquery = "select * from acc_elect_master_description where desc_id=? and unit_from=? and unit_to=?";

             $query = $this->db->query($myquery,array($did,$uf,$uto));

             if ($query->num_rows() > 0) {
                  return TRUE;

             } else {
                 return FALSE;
             }
        }
        
      function get_current_active_rate()
        {
            $myquery = "select * from acc_ac_master where `status`='active'";

             $query = $this->db->query($myquery);

             if ($query->num_rows() > 0) {
                return $query->row();

             } else {
                 return FALSE;
             }
        }
        
        function get_mon_yr_for_elect_unit_prev($arr){
            if($arr['mon']==1){
                $arr['mon']=12;
                $arr['year']=$arr['year']-1;
                return $arr;
            }
            else{
                $arr['mon']=$arr['mon']-1;
                $arr['year']=$arr['year'];
                return $arr;
            }

        }
		function get_current_month_unit($empid){
           $q="select aeu.unit,aeu.prev_reading,aeu.curr_reading,aeu.tot_amt from acc_elect_unit_temp aeu where aeu.empno=? ";
           if($query=$this->db->query($q,$empid)){
            //echo $this->db->last_query();die();
            if($query->num_rows()>0){
                return $query->row();
            }
            else{
                return false;
            }
           }
        }
    // This function was added to edit the Gross and Net salary of all emp in Temp file        
		
		function update_gross_net_temp_table()
        {
		//echo " trying to update gross and net in acc_pay_temp"; die();
		$sql="(SELECT REPLACE((GROUP_CONCAT(CONCAT_WS('+',(field )))),',','+') as paydata from acc_pay_field_tbl where status='Y')";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() > 0) {
        //  echo  $this->db->last_query();
            $st1= $query->result();
        } else {
            return false;
        }
        $sql2="(SELECT REPLACE((GROUP_CONCAT(CONCAT_WS('+',(field )))),',','+') as ddata from acc_deduction_field_tbl where status='Y')";
        $query2 = $this->db->query($sql2);
        if ($this->db->affected_rows() > 0) {
        //  echo  $this->db->last_query();
            $st2= $query2->result();
        } else {
            return false;
        }

		$dt1=$st1['0']->paydata;
		$dt2=$st2['0']->ddata;
		$sqlupdate="UPDATE acc_pay_details_temp set GROSS =($dt1),TOTD=($dt2),NETPAY=($dt1)-($dt2)";
		$queryupdate = $this->db->query($sqlupdate);
		
		//echo  $this->db->last_query(); die();
   
		if ($this->db->affected_rows() > 0)
			{
        // return $queryupdate->result();
				echo "Record Updated";
			} else 
			{
				echo "All Record are already updated ";
				return false;
			}

		  
	}
	// End of function update_gross_net_temp_table    
			
        
    
}