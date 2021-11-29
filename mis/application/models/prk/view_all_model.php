<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class View_all_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getOwnPublications($id){
		$query = " SELECT rec_id FROM prk_ism_author WHERE emp_id = '$id' ";
		return $this->db->query($query)->result();
	}
	
	public function getAllPublications(){
		$query = " SELECT rec_id FROM prk_ism_author WHERE 1";
		return $this->db->query($query)->result();
	}

	public function checkApprovalOfPublication($rec_id){
		$query = " SELECT * FROM prk_ism_author WHERE rec_id = '$rec_id' AND (notify_status = '0' OR notify_status = '2')  ";
		if ($this->db->query($query)->num_rows() == 0)
			return true;
		return false;
	}
	public function getPublicationType($rec_id){
		$query = " SELECT type FROM prk_record WHERE rec_id = '$rec_id' ";
		$result = $this->db->query($query)->result();
		return $result[0]->type;
	}

	public function getMonth($frmmonth,$frmyear,$tomonth,$toyear,$dept,$index,$name){
	
	// echo $frmmonth.','.$frmyear.','.$tomonth.','.$toyear.','.$dept.','.$index.','.$name; 
	 //      Jan,1970,    Jan,2035,                             Computer Science and Engineering,All,All Authors
   if($dept!='All Departments'){
	 if($name=='All Authors')
	   {
		$rep=" ";
        $join_extra_part2= " and u.id=prk_ism_author.emp_id    and  u.dept_id= d.id   AND d.name='".$dept."'";
		$join_extra_part1= " ,user_details u ,departments d ";   
        }
   
   
        else
		{
			$rep=" and prk_ism_author.emp_id = '$name' ";
	    }
	  }
	  else{
		    $join_extra_part2="";$join_extra_part1="";$rep="";
	  }
	 
		$strtdate= date("Y-m-d", strtotime("$frmmonth $frmyear"));
		$enddate = date("Y-m-d", strtotime("$tomonth $toyear +1 month -1 day"));
/*		$query = " SELECT prk_record.rec_id as rec_id,prk_record.owner as emp_id,prk_conference.end_date as date FROM prk_conference,prk_record prk_ism_author WHERE (prk_conference.end_date>='$strtdate' AND prk_conference.end_date <= '$enddate') AND prk_record.rec_id = prk_conference.rec_id UNION SELECT prk_record.rec_id as rec_id,prk_record.owner as emp_id,prk_patent.publication_date as date FROM prk_patent,prk_record WHERE (prk_patent.publication_date >='$strtdate' AND prk_patent.publication_date <= '$enddate') AND (prk_patent.rec_id= prk_record.rec_id) order by date DESC"; */

	$query = "SELECT prk_ism_author.rec_id AS rec_id,prk_ism_author.emp_id AS emp_id,prk_conference.end_date AS DATE
FROM prk_conference,prk_ism_author   $join_extra_part1
WHERE (prk_conference.end_date>='$strtdate' AND prk_conference.end_date <= '$enddate') AND prk_ism_author.rec_id = prk_conference.rec_id  $rep  $join_extra_part2

UNION

SELECT prk_ism_author.rec_id AS rec_id,prk_ism_author.emp_id AS emp_id,prk_patent.publication_date AS DATE
FROM prk_patent,prk_ism_author $join_extra_part1
WHERE (prk_patent.publication_date >='$strtdate' AND prk_patent.publication_date <= '$enddate') AND (prk_patent.rec_id= prk_ism_author.rec_id) $rep  $join_extra_part2
ORDER BY DATE DESC";
	// echo $query;
		$ar =  $this->db->query($query)->result();
		// echo '<br/>'.$this->db->last_query();
		
	/*	$query = "SELECT prk_journal.rec_id as rec_id,prk_journal.year as year,prk_journal.month as month,  prk_record.owner as emp_id FROM prk_journal, prk_record WHERE prk_record.rec_id= prk_journal.rec_id 
				  UNION
				  SELECT prk_book.rec_id as rec_id,prk_book.year as year,prk_book.month as month,prk_record.owner as emp_id FROM prk_book,prk_record WHERE prk_book.rec_id= prk_record.rec_id ORDER BY year DESC, ( CASE WHEN month= 'Jan' THEN 11 WHEN month= 'Feb' THEN 10 WHEN month= 'Mar' THEN 9 WHEN month= 'Apr' THEN 8 WHEN month= 'May' THEN 7 WHEN month= 'Jun' THEN 6 WHEN month= 'Jul' THEN 5 WHEN month= 'Aug' THEN 4 WHEN month= 'Sep' THEN 3 WHEN month= 'Oct' THEN 2 WHEN month= 'Nov' THEN 1 WHEN month= 'Dec' Then 0 ELSE 12 END ), month"; */
			
$query = "SELECT prk_journal.rec_id AS rec_id,prk_journal.year AS YEAR,prk_journal.month AS MONTH, prk_ism_author.emp_id AS emp_id
FROM prk_journal,prk_ism_author $join_extra_part1
WHERE prk_ism_author.rec_id= prk_journal.rec_id $rep  $join_extra_part2

UNION 

SELECT prk_book.rec_id AS rec_id,prk_book.year AS YEAR,prk_book.month AS MONTH,prk_ism_author.emp_id AS emp_id
FROM prk_book,prk_ism_author $join_extra_part1
WHERE prk_book.rec_id= prk_ism_author.rec_id $rep  $join_extra_part2
ORDER BY YEAR DESC, (CASE WHEN MONTH= 'Jan' THEN 11 WHEN MONTH= 'Feb' THEN 10 WHEN MONTH= 'Mar' THEN 9 WHEN MONTH= 'Apr' THEN 8 WHEN MONTH= 'May' THEN 7 WHEN MONTH= 'Jun' THEN 6 WHEN MONTH= 'Jul' THEN 5 WHEN MONTH= 'Aug' THEN 4 WHEN MONTH= 'Sep' THEN 3 WHEN MONTH= 'Oct' THEN 2 WHEN MONTH= 'Nov' THEN 1 WHEN MONTH= 'Dec' THEN 0 ELSE 12 END), MONTH";			
				  
		$arr = $this->db->query($query)->result();
		//echo '<br/>'.$this->db->last_query();
	
		$a = date("Y-m-d", strtotime("$frmmonth $frmyear"));
		$date2 = $a;
		
		$b = date("Y-m-d", strtotime("$tomonth $toyear +1 month -1 day"));
		$date3 = $b;

		foreach($arr as $value){
			$aa = $value->month;
			$bb = $value->year;
			$t = date("Y-m-d", strtotime("$aa $bb"));
			$date1 = $t;
			if( $date1 >= $date2 && $date3 >= $date1){
				$temp =new stdClass;
				$temp->rec_id = $value->rec_id;
				$temp->emp_id = $value->emp_id;
				array_push($ar,$temp);
			}
		}
		$temp =array();
		if($dept != "All Departments"&& $dept!=""){
			$query = "SELECT id FROM departments WHERE name = '$dept'";
			$result =  $this->db->query($query)->result();
			$id = $result[0]->id;
			$query = "SELECT user_details.id as id FROM user_details WHERE user_details.dept_id = '$id' AND user_details.id IN (SELECT emp_no as 'user_details.id' FROM emp_basic_details WHERE auth_id = 'ft')";
			$result =  $this->db->query($query)->result();
			
			foreach($ar as $value){
				$t2=$value->emp_id;
				
				foreach($result as $a){
					$t1=$a->id;
					
					if(strcmp($t1,$t2)== 0){
						$l =new stdClass;
						$l->rec_id = $value->rec_id;
						array_push($temp, $l);
						break;
					}
				}
			}
		}
		else{
			$temp = $ar;
		}
		// print_r($index);
		// exit;
		if($index != 'All'){
			if($index =='SCI'||$index=='SSCI'||$index=='SCIE'||$index=='SCOPUS'){
				$result = array();
				if($index=='SCI'){
					$query = "SELECT rec_id FROM prk_journal WHERE indexing = 'SCI'or indexing like 'SCI,%'";
					$result = $this->db->query($query)->result();
				}
				else{
					$str = "%".$index."%";
					$query = "SELECT rec_id FROM prk_journal WHERE indexing like '$str'";
					$result = $this->db->query($query)->result();
				}
				// print_r($result);
				// exit;
				$ck = array();
				foreach ($temp as $value) {
					foreach ($result as $re) {
						if($value->rec_id == $re->rec_id){
							array_push($ck, $value);
							break;
						}
					}
				}
				$temp =$ck;
			}
			else{
				$result = array();
				$str = "%".$index."%";

				$query = "SELECT rec_id FROM prk_journal WHERE other_indexing LIKE '$str'";
				// print_r($query);

				$result = $this->db->query($query)->result();
				//  print_r($result);
				// // exit;
				// print_r($temp);
				// exit;
				$ck = array();
				foreach ($temp as $value) {
					foreach ($result as $re) {
						if($value->rec_id == $re->rec_id){
							array_push($ck, $value);
							break;
						}
					}

				}
				// print_r($ck);
				// //print_r($temp)
				// exit;
				$temp = $ck;
			}
		}
		// print_r($temp);
		// exit;

		// print_r($temp);
		// exit;
		if($name != 'All Authors'&&$name !=NULL){
			
			// $query ="SELECT prk_record.rec_id as rec_id FROM prk_record,user_details WHERE ((user_details.middle_name = '' AND concat(user_details.first_name,' ',user_details.last_name) = '$name') OR (user_details.middle_name != '' AND concat(user_details.first_name,' ',user_details.middle_name,' ',user_details.last_name) = '$name' )) AND user_details.id = prk_record.owner ORDER BY `prk_record`.`rec_id` DESC";
			
	/*		$query ="SELECT rec_id FROM prk_record WHERE owner = '$name'";*/

	$query ="SELECT rec_id FROM prk_ism_author WHERE emp_id = '$name'";
	
	
			$result = $this->db->query($query)->result();
			//$query = "SELECT rec_id FROM prk_journal WHERE indexing like '$str'";
			//$result = $this->db->query($query)->result();
			$ck = array();
			// print_r($name.' ');
			//print_r($result);
			//exit;
			foreach ($temp as $value) {
				foreach ($result as $re) {
					if($value->rec_id == $re->rec_id){
						array_push($ck, $value);
						break;
					}
				}
			}
			$temp = $ck;
		}
		// 
		// print_r($temp);
		// exit;
		return $temp;
		
	}


	public function getPublicationDetails($rec_id,$type){
		if ($type == 1 || $type == 2)
			$query = " SELECT * FROM prk_journal WHERE rec_id = '$rec_id' ";
		if ($type == 3 || $type == 4)
			$query = " SELECT * FROM prk_conference WHERE rec_id = '$rec_id' ";
		if ($type == 5 || $type == 6)
			$query = " SELECT * FROM prk_book WHERE rec_id = '$rec_id' ";
		if ($type == 7)
			$query = " SELECT * FROM prk_patent WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
	public function getfile($rec_id){
		$query = " SELECT path FROM publication_keeper WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
	public function getIsmAuthors($rec_id){
		$query = " SELECT emp_id,position FROM prk_ism_author WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
	public function getUserNameByUserId($id){
		$query = " SELECT concat(first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id = '$id' ";
		$result = $this->db->query($query)->result();
		return $result[0]->name;
	}
	public function getOtherAuthors($rec_id){
		$query = " SELECT concat(first_name,' ',middle_name,' ',last_name) AS name,position FROM prk_other_author WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
}
