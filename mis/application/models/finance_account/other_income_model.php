<?php

class other_income_model extends CI_Model
{
	var $table_name = 'acc_other_income';
	function getAll($type){
		
		/*$q="SELECT
  				`acc_other_income`.*,
  				concat(`user_details`.`first_name`,' ',	`user_details`.`salutation`,' ',`user_details`.`middle_name`,' ',`user_details`.`last_name`) as name
				FROM `acc_other_income` left join `user_details` on `acc_other_income`.emp_no=`user_details`.id where `acc_other_income`.type=? group by `acc_other_income`.id,`acc_other_income`.emp_no order by `acc_other_income`.date desc";*/
		//$q="select aoc.*,ucase(concat(ud.first_name,' ',ud.middle_name,' ',ud.last_name)) as name from acc_other_income aoc left join user_details ud on ud.id=aoc.emp_no where aoc.type=? group by aoc.id,aoc.emp_no order by aoc.date desc limit 1000";
		//echo $q;die();
		$q="select a.*,UCASE(CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name)) AS name,
			UCASE(dpt.name) AS dept, UCASE(c.name) as design 
			from (select x.* from acc_other_income x where x.`type`=? ) a 
			INNER JOIN user_details ud ON ud.id=a.emp_no 
			INNER JOIN departments dpt on dpt.id=ud.dept_id 
			INNER JOIN emp_basic_details b ON b.emp_no=a.emp_no 
			INNER join designations c on c.id=b.designation 
			group by a.emp_no,a.id ORDER BY cast(a.emp_no as decimal)";
		if($query=$this->db->query($q,$type)){	
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

	function getAllIntegrated($type,$fy){
	$fy['start_from']=date('Y-m-d',strtotime($fy['start_from']));
	$fy['end_to']=date('Y-m-d',strtotime($fy['end_to']));
	$q="SELECT A.*,UCASE(CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name)) AS name,
			UCASE(dpt.name) AS dept, UCASE(c.name) as design,(SELECT SUM(y.gross)	FROM acc_other_income y	WHERE y.emp_no=A.emp_no AND y.`type`=? and y.date BETWEEN ? and ?) AS gross,(SELECT SUM(y.itax)	FROM acc_other_income y	WHERE y.emp_no=A.emp_no AND y.`type`=? and y.date BETWEEN ? and ?) AS itax,(SELECT SUM(y.net) FROM acc_other_income y WHERE y.emp_no=A.emp_no AND y.`type`=? and y.date BETWEEN ? and ?) AS net	FROM (SELECT DISTINCT(x.emp_no)	FROM acc_other_income x	WHERE x.`type`=? and x.date BETWEEN ? AND ?) A 
		INNER JOIN user_details ud ON ud.id=A.emp_no 
		INNER JOIN departments dpt on dpt.id=ud.dept_id 
		INNER JOIN emp_basic_details b ON b.emp_no=A.emp_no 
		INNER join designations c on c.id=b.designation 
		ORDER BY cast(A.emp_no as decimal)";
	if($query=$this->db->query($q,array($type,$fy['start_from'],$fy['end_to'],$type,$fy['start_from'],$fy['end_to'],$type,$fy['start_from'],$fy['end_to'],$type,$fy['start_from'],$fy['end_to']))){
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
	/*function getAll($type){
		$q="select * from acc_other_income aoc where aoc.type=? order by aoc.date desc limit 1000";
		if($query=$this->db->query($q,$type)){	
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
	}*/

	function save_consultancy($data){
		//echo "Hello";die();
		if($this->db->insert('acc_other_income',$data)){
			return true;
		}
		else{
			return false;
		}
	}

	function get_emp_img_address($id)
    {
        $myquery = "SELECT a.id, CONCAT(a.first_name,' ',a.middle_name,' ',a.last_name) AS emp_name,a.photopath, CONCAT(b.line1,'  ',b.line2) AS eaddress,
			d.name AS dept_nm,f.name AS emp_desi,apd.PNRNO as pan,case when apd.BACNO is null or apd.BACNO=0 then  uod.bank_accno else apd.BACNO  end as bacno
			FROM user_details a
			INNER JOIN user_address b ON a.id=b.id
			INNER JOIN departments d ON d.id=a.dept_id
			INNER JOIN emp_basic_details e ON e.emp_no=a.id
			INNER JOIN designations f ON f.id=e.designation
			INNER JOIN acc_pay_details_temp apd on apd.EMPNO=a.id
			INNER JOIN user_other_details uod on uod.id=a.id
            WHERE a.id=? AND b.`type`='present'";   
            $query = $this->db->query($myquery,array($id));
            //echo $this->db->last_query();die();
             if ($query->num_rows() > 0) {
                 return $query->row();

             } else {
                 return FALSE;
             }
        }

        /*function get_record($data){
        	//var_dump($data);die();
        	$q="SELECT a.*,apd.PNRNO, UCASE(CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name)) AS name,(
			SELECT UCASE(dpt.name) AS dept
			FROM departments dpt
			WHERE dpt.id=ud.dept_id) AS dept,
			(select ucase(c.name) from designations c where c.id=b.designation) as design,case when apd.BACNO is null or apd.BACNO=0 then  uod.bank_accno else apd.BACNO  end as bacno 
			FROM acc_other_income a
			LEFT JOIN acc_pay_details_temp apd ON apd.EMPNO=a.emp_no
			LEFT JOIN user_details ud ON ud.id=a.emp_no
			LEFT JOIN user_other_details uod on uod.id=a.emp_no
			LEFT JOIN emp_basic_details b on b.emp_no=a.emp_no WHERE a.type='".$data['type']."'";
		    if(strcmp($data['from'], "")!=0 && strcmp($data['to'],"")!=0){
		    	$q.=" and a.date between '".date('Y-m-d',strtotime($data['from']))."' and '".date('Y-m-d',strtotime($data['to']))."'";
		    //echo $q;die();
		    }

		    if(strcmp($data['emp_no'],"")!=0){
		    	//echo"Hello";
		    	$q.=" and a.emp_no=".$data['emp_no'];
		    }
		    $q.=" group by a.emp_no order by a.date desc";
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
		*/
		function get_record($data){
        	//var_dump($data);die();
        	$q="select a.*,apd.PNRNO,UCASE(CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name)) AS name,UCASE(dpt.name) AS dept,  UCASE(c.name) as design
				from (select x.* from acc_other_income x where x.`type`='".$data['type']."' ";
			if(strcmp($data['from'], "")!=0 && strcmp($data['to'],"")!=0){
		    	$q.=" and x.date between '".date('Y-m-d',strtotime($data['from']))."' and '".date('Y-m-d',strtotime($data['to']))."'";
		    //echo $q;die();
		    }

		    if(strcmp($data['emp_no'],"")!=0){
		    	//echo"Hello";
		    	$q.=" and x.emp_no=".$data['emp_no'];
		    }
			$q.=" ) a 
				INNER JOIN user_details ud ON ud.id=a.emp_no
			 	INNER JOIN departments dpt on dpt.id=ud.dept_id
			 	INNER JOIN emp_basic_details b ON b.emp_no=a.emp_no
				INNER JOIN acc_pay_details_temp apd on apd.EMPNO=a.emp_no
			 	INNER join designations c on c.id=b.designation  group by a.emp_no,a.id ORDER BY cast(a.emp_no as decimal)";


        	/*$q="SELECT a.*,apd.PNRNO, UCASE(CONCAT(ud.salutation,' ',ud.first_name,' ',ud.middle_name,' ',ud.last_name)) AS name,(
			SELECT UCASE(dpt.name) AS dept
			FROM departments dpt
			WHERE dpt.id=ud.dept_id) AS dept,
			(select ucase(c.name) from designations c where c.id=b.designation) as design,case when apd.BACNO is null or apd.BACNO=0 then  uod.bank_accno else apd.BACNO  end as bacno 
			FROM acc_other_income a
			LEFT JOIN acc_pay_details_temp apd ON apd.EMPNO=a.emp_no
			LEFT JOIN user_details ud ON ud.id=a.emp_no
			LEFT JOIN user_other_details uod on uod.id=a.emp_no
			LEFT JOIN emp_basic_details b on b.emp_no=a.emp_no WHERE a.type='".$data['type']."'";
		    if(strcmp($data['from'], "")!=0 && strcmp($data['to'],"")!=0){
		    	$q.=" and a.date between '".date('Y-m-d',strtotime($data['from']))."' and '".date('Y-m-d',strtotime($data['to']))."'";
		    //echo $q;die();
		    }

		    if(strcmp($data['emp_no'],"")!=0){
		    	//echo"Hello";
		    	$q.=" and a.emp_no=".$data['emp_no'];
		    }
		    $q.=" order by a.date desc";*/
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
        function delete_other_income($id){
        	$this->db->where('id',$id);
        	$this->db->delete('acc_other_income');
        }
}
