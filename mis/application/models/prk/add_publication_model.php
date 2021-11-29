<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Add_publication_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get_prk_types()	{
		// Get the different publication types
		$query = " SELECT * FROM prk_types WHERE 1 ";
		$result = $this->db->query($query)->result();
		$data = array();
		$data['prk_types'] = array();
		$i = 1;
		foreach ($result as $pub){
			$data['prk_types'][$i] = $pub->type_name;
			$i++;
		}
		$data['prk_type_size'] = $i - 1;
		return $data;
	}
	public function getUserNameByUserId($id){
		// Get user's name
		$query = " SELECT concat(salutation,' ',first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id = '$id' ";
		$result = $this->db->query($query)->result();
		return $result[0]->name;
	}
	public function isStudent($id){
		// Check if the user is student or not
		$query = " SELECT auth_id FROM users WHERE id = '$id' ";
		$result = $this->db->query($query)->result();
		$user_type = $result[0]->auth_id;
		if ($user_type == "stu")
			return true;
		return false;
	}
	public function getDepartmentList($dept_type){
		$query = "SELECT id,name FROM departments WHERE type = '$dept_type'";
		return $this->db->query($query)->result();
	}
	public function getAuthorByDepartment($dept){
		$basic_query = "SELECT id,concat(salutation,' ',first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE dept_id = '$dept' AND id IN (SELECT id FROM users WHERE auth_id = 'emp')";
		return $this->db->query($basic_query)->result();
	}
	public function getJrfAndPostdoc($dept,$course){
		$basic_query = " SELECT id,concat(first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id IN (SELECT admn_no FROM stu_details WHERE stu_type = '$course') AND dept_id = '$dept'";
		return $this->db->query($basic_query)->result();
	}
	public function getStudentsByCourseAndYear($arr){
		$dept = $arr['dept'];
		$year = $arr['year'];
		$even_sem = $year * 2;
		$odd_sem = $even_sem - 1;
		$course = $arr['course'];
		$session_year = $arr['session_year'];
		$basic_query = "SELECT id, concat(first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE dept_id = '$dept' AND id IN (SELECT admn_no FROM stu_details WHERE stu_type = '$course' AND admn_no IN (SELECT admn_no FROM reg_regular_form WHERE session_year = '$session_year' AND (semester = '$even_sem') OR semester = '$odd_sem')) ORDER BY name";
		return $this->db->query($basic_query)->result();
	}
	public function insertJournal($data){
		// To insert new journal
		$type = $data['type'];
		$owner = $data['owner'];
		$title = $data['title'];
		$month = $data['month'];
		$year = $data['year'];
		$name_of_journal = $data['name_of_journal'];
		$issue_no = $data['issue_no'];
		$volume_no = $data['volume_no'];
		$page_range = $data['page_range'];
		$other = $data['other'];
		$indexing = $data['indexing'];
		$other_indexing = $data['other_indexing'];
		$impact_factor = $data['impact_factor'];
		$path = $data['path'];
		$query = " INSERT INTO prk_record (type,owner) VALUES ($type,$owner) ";
		$this->db->query($query);
		$query = " SELECT max(rec_id) AS max_rec_id FROM prk_record ";
		$result = $this->db->query($query)->result();
		$rec_id = $result[0]->max_rec_id;
		$query = " INSERT INTO prk_journal VALUES ('$rec_id','$title','$type','$month','$year','$name_of_journal','$issue_no','$volume_no','$indexing','$impact_factor','$other_indexing','$page_range','$other') ";
		$this->db->query($query);
		$query = "INSERT INTO publication_keeper VALUES ('$owner','$rec_id','$type','$path') ";
		$this->db->query($query);
	}
	public function insertConference($data){
		// To insert new conference
		$type = $data['type'];
		$owner = $data['owner'];
		$title = $data['title'];
		$begin_date = $data['begin_date'];
		$end_date = $data['end_date'];
		$name_of_conference = $data['name_of_conference'];
		$venue = $data['venue'];
		$page_range = $data['page_range'];
		$other = $data['other'];
		$path = $data['path'];
		$query = " INSERT INTO prk_record (type,owner) VALUES ($type,$owner) ";
		$this->db->query($query);
		$query = " SELECT max(rec_id) AS max_rec_id FROM prk_record ";
		$result = $this->db->query($query)->result();
		$rec_id = $result[0]->max_rec_id;
		$query = " INSERT INTO prk_conference VALUES ('$rec_id','$title','$type','$begin_date','$end_date','$name_of_conference','$venue','$page_range','$other') ";
		$this->db->query($query);
		$query = "INSERT INTO publication_keeper VALUES ('$owner','$rec_id','$type','$path') ";
		$this->db->query($query);
	}
	public function insertBook($data){
		// To insert new book
		$type = $data['type'];
		$owner = $data['owner'];
		$title = $data['title'];
		$publisher = $data['publisher'];
		$month = $data['month'];
		$year = $data['year'];
		$isbn_no = $data['isbn_no'];
		$chapter_no = $data['chapter_no'];
		$chapter_name = $data['chapter_name'];
		$edition = $data['edition'];
		$other = $data['other'];
		$path = $data['path'];
		$query = " INSERT INTO prk_record (type,owner) VALUES ($type,$owner) ";
		$this->db->query($query);
		$query = " SELECT max(rec_id) AS max_rec_id FROM prk_record ";
		$result = $this->db->query($query)->result();
		$rec_id = $result[0]->max_rec_id;
		$query = " INSERT INTO prk_book VALUES ('$rec_id','$title','$type','$month','$year','$edition','$isbn_no','$publisher','$chapter_no','$chapter_name','$other') ";
		$this->db->query($query);
		$query = "INSERT INTO publication_keeper VALUES ('$owner','$rec_id','$type','$path') ";
		$this->db->query($query);
	}
	public function insertPatent($data){
		// To insert new patent
		$type = $data['type'];
		$owner = $data['owner'];
		$title = $data['title'];
		$date_of_filing = $data['date_of_filing'];
		$application_no = $data['application_no'];
		$publication_date = $data['publication_date'];
		$patent_no = $data['patent_no'];
		$other = $data['other'];
		$path = $data['path'];
		$query = " INSERT INTO prk_record (type,owner) VALUES ($type,$owner) ";
		$this->db->query($query);
		$query = " SELECT max(rec_id) AS max_rec_id FROM prk_record ";
		$result = $this->db->query($query)->result();
		$rec_id = $result[0]->max_rec_id;
		$query = " INSERT INTO prk_patent VALUES ('$rec_id','$title','$type','$date_of_filing','$application_no','$publication_date','$patent_no','$other') ";
		$this->db->query($query);
		$query = "INSERT INTO publication_keeper VALUES ('$owner','$rec_id','$type','$path') ";
		$this->db->query($query);
	}
	public function insertAuthors($data){
		// Add details of authors both from ISM and other instititions
		$query = " SELECT max(rec_id) AS max_rec_id FROM prk_record ";
		$result = $this->db->query($query)->result();
		$rec_id = $result[0]->max_rec_id;
		if ($data['no_of_authors'] != 1){
			for ($i = 1; $i <= $data['no_of_authors']; $i++){
				if ($data['author_type'][$i] == 'ISM'){
					$emp_id = $data['ism'][$i]['emp_id'];
					$notify_status = $data['ism'][$i]['notify_status'];
					$query = " INSERT INTO prk_ism_author VALUES ('$rec_id','$emp_id','$notify_status','$i') ";
				}
				else if ($data['author_type'][$i] == "OTHER"){
					$first_name = $data['other'][$i]['first_name'];
					$middle_name = $data['other'][$i]['middle_name'];
					$last_name = $data['other'][$i]['last_name'];
					$email_id = $data['other'][$i]['email_id'];
					$institution = $data['other'][$i]['institution'];
					$position = $i;
					$query = " INSERT INTO prk_other_author (rec_id,first_name,middle_name,last_name,email_id,institution,position) VALUES ('$rec_id','$first_name','$middle_name','$last_name','$email_id','$institution','$position') ";
				}
				$this->db->query($query);
			}
		}
		else {// For Single Author
			$i = 1;
			$emp_id = $data['ism'][$i]['emp_id'];
			$notify_status = $data['ism'][$i]['notify_status'];
			$position = $data['ism'][$i]['position'];
			$query = " INSERT INTO prk_ism_author VALUES ('$rec_id','$emp_id','$notify_status','$position') ";
			$this->db->query($query);
		}	
	}
}
