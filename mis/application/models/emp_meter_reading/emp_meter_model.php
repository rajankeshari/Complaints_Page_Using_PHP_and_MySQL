<?php

/**
 * Author: Anuj
*/
class Emp_meter_model extends CI_Model {
    
    

    function __construct() {
        parent::__construct();
    }

    

    function get_emp_meter_reading_temp($empno){
    //echo $empno;die();

    //$myquery="select aemr.*, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select dp.name from departments dp where dp.id=ud.dept_id) as department from acc_emp_meter_reading_temp aemr inner join user_details ud on ud.id=aemr.empno";

    $myquery="select aemr.*, aeas.no_of_ac, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select dp.name from departments dp where dp.id=ud.dept_id) as department from acc_emp_meter_reading_temp aemr inner join user_details ud on ud.id=aemr.empno left join acc_emp_ac_status aeas on aeas.empno=aemr.empno";

		            $query=$this->db->query($myquery);

		            if($query->num_rows()>0){
			                 return $query->result();
		            }
		            else{
			          return false;
		            }
    }

    function get_previous_elec_bill_details($empno){
      
      /*$myquery="select aeu.*, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select dp.name from departments dp where dp.id=ud.dept_id) as department from acc_elect_unit aeu inner join user_details ud on ud.id=aeu.empno where aeu.empno='".$empno."' order by aeu.year desc limit 12";*/
	  
	  /*$myquery="SELECT aeu.*, CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) AS name,(
SELECT dp.name
FROM departments dp
WHERE dp.id=ud.dept_id) AS department, aemrt.elec_meter_reading,aemrt.ac_meter_reading,aemrt.extra_meter_reading
FROM acc_elect_unit aeu
INNER JOIN user_details ud ON ud.id=aeu.empno
INNER JOIN acc_emp_meter_reading_temp aemrt ON aemrt.empno=aeu.empno and aemrt.month=aeu.month and aemrt.year=aeu.year
WHERE aeu.empno='".$empno."'
ORDER BY aeu.id DESC
LIMIT 12"; Modified on 06.09.2019*/ 

$myquery="SELECT aeu.*, CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) AS name,(
SELECT dp.name
FROM departments dp
WHERE dp.id=ud.dept_id) AS department, IFNULL(aemrt.elec_meter_reading,'Not recorded') as elec_meter_reading,IFNULL(aemrt.ac_meter_reading, 'Not recorded') as ac_meter_reading,IFNULL(aemrt.extra_meter_reading,'Not recorded') as extra_meter_reading
FROM acc_elect_unit aeu
INNER JOIN user_details ud ON ud.id=aeu.empno
LEFT JOIN acc_emp_meter_reading_temp aemrt ON aemrt.empno=aeu.empno AND aemrt.month=aeu.month AND aemrt.year=aeu.year
WHERE aeu.empno='".$empno."'
ORDER BY aeu.id DESC
/*LIMIT 12*/";
        
                $query=$this->db->query($myquery);

                if($query->num_rows()>0){
                      return $query->result();
                }
                else{
                return false;
                }

    }
	
	function get_latest_elec_meter_reading($empno){
		 $myquery = "select * from acc_emp_meter_reading_temp where empno=? order by id  desc limit 1";

                $query = $this->db->query($myquery,array($empno));

                if ($query->num_rows() > 0){
                 //return TRUE;
                return $query->row();

                } 
                else {
                return FALSE;
                }
	}
	
		function get_latest_elec_meter_reading_in_edit_mode($empno){
		 $myquery = "select * from acc_emp_meter_reading_temp where empno=? order by id  desc limit 1,1";

                $query = $this->db->query($myquery,array($empno));

                if ($query->num_rows() > 0){
                 //return TRUE;
                return $query->row();

                } 
                else {
                return FALSE;
                }
	}
 
 
    function check_status($yr,$mon,$emp){

    $myquery = "select * from  acc_emp_meter_reading_temp where year=? and month=? and empno=?";

                $query = $this->db->query($myquery,array($yr,$mon,$emp));

                if ($query->num_rows() > 0){
                 //return TRUE;
                return $query->row();

                } 
                else {
                return FALSE;
                }
    }
   
      function update_emp_meter_reading($data,$con)
        {
            if ($this->db->update('acc_emp_meter_reading_temp', $data,$con))
                    return true;
                else
                    return FALSE;
        }

    function delete_emp_meter_readings($id){
        $this->db->where('id',$id);
        $this->db->delete("acc_emp_meter_reading_temp");
    }

    function insert_emp_meter_reading($data)
        {
            if ($this->db->insert('acc_emp_meter_reading_temp', $data))
                    return $this->db->insert_id();
                else
                    return FALSE;
        }

        function get_no_of_ac($empno){

$myquery = "select no_of_ac from acc_emp_ac_status where empno=? and status=1";

             $query = $this->db->query($myquery,array($empno));

             if ($query->num_rows() > 0) {
                 return $query->row();

             } else {
                 return FALSE;
             }

        }


        function get_emp_meter_reading(){
        $myquery="select aemrt.*, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select dp.name from departments dp where dp.id=ud.dept_id) as department from acc_emp_meter_reading_temp aemrt inner join user_details ud on ud.id=aemrt.empno";
        $query=$this->db->query($myquery);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }


    function get_meter_date_data(){
      $myquery="select * from acc_meter_date";
      $query=$this->db->query($myquery);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
    }



         function check_meter_date($yr,$mon,$start_date,$end_date,$status)
        {
            $myquery = "select * from acc_meter_date where year=? and month=? and starting_date=? and end_date=? and status='active'";

             $query = $this->db->query($myquery,array($yr,$mon,$start_date,$end_date));

             if ($query->num_rows() > 0) {
                 return TRUE;

             } else {
                 return 0;
             }
        }


         function insert_meter_date($data)
        {
            if ($this->db->insert('acc_meter_date', $data))
                    return $this->db->insert_id();
                else
                    return FALSE;
        }

        function update_previous_toDeactive($id)
        {
            $sql = "update acc_meter_date set `status`='deactive' where id<>".$id;
            $query = $this->db->query($sql);
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        

        function check_dates(){

$myquery="select * from acc_meter_date where status='active'";
          $query=$this->db->query($myquery);
        if($query->num_rows()>0){
            return $query->result();
        }
        else{
            return false;
        }
        }


        function get_emp_meter_reading_monthwise_temp($yr,$mon){
//var_dump($yr);die();
        $myquery="select aemr.*, aeas.no_of_ac, concat(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name) as name,(select dp.name from departments dp where dp.id=ud.dept_id) as department from acc_emp_meter_reading_temp aemr inner join user_details ud on ud.id=aemr.empno left join acc_emp_ac_status aeas on aeas.empno=aemr.empno where aemr.year='".$yr."' and aemr.month='".$mon."'";  

                $query=$this->db->query($myquery);
          //echo $this->db->last_query();die();

                    if($query->num_rows()>0){
                             return $query->result();
                    }
                    else{
                      return false;
                    }
        }
}