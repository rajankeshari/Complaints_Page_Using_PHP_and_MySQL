<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class Edit_publication_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getOwnPublications($id){
		$query = " SELECT rec_id FROM prk_ism_author WHERE emp_id = '$id' AND rec_id IN (SELECT rec_id FROM prk_record WHERE owner = '$id') ";
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
		$query = " SELECT serial_no,concat(first_name,' ',middle_name,' ',last_name) AS name,position FROM prk_other_author WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
	public function updateJournal($data,$rec_id){
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
		$query = " UPDATE prk_journal SET title = '$title',month = '$month',year = '$year',name_of_journal = '$name_of_journal',issue_no = '$issue_no',volume_no = '$volume_no',indexing = '$indexing',impact_factor = '$impact_factor',other_indexing = '$other_indexing',page_range = '$page_range',other = '$other' WHERE rec_id = '$rec_id' ";
		$this->db->query($query);

	}
	public function updateConference($data,$rec_id){
		$title = $data['title'];
		$begin_date = $data['begin_date'];
		$end_date = $data['end_date'];
		$name_of_conference = $data['name_of_conference'];
		$venue = $data['venue'];
		$page_range = $data['page_range'];
		$other = $data['other'];

		$query = " UPDATE prk_conference SET title = '$title',begin_date = '$begin_date',end_date = '$end_date',name_of_conference = '$name_of_conference',venue = '$venue',page_range = '$page_range',other = '$other' WHERE rec_id = '$rec_id' ";
		$this->db->query($query);
	}
	public function updateBook($data,$rec_id){
		$title = $data['title'];
		$publisher = $data['publisher'];
		$month = $data['month'];
		$year = $data['year'];
		$isbn_no = $data['isbn_no'];
		$chapter_no = $data['chapter_no'];
		$chapter_name = $data['chapter_name'];
		$edition = $data['edition'];
		$other = $data['other'];
		$query = " UPDATE prk_book SET title = '$title',month = '$month',year = '$year',edition = '$edition',isbn_no = '$isbn_no',publisher = '$publisher',chapter_no = '$chapter_no',chapter_name = '$chapter_name',other = '$other' WHERE rec_id = '$rec_id' ";
		$this->db->query($query);  
	}
	public function updatePatent($data,$rec_id){
		$title = $data['title'];
		$date_of_filing = $data['date_of_filing'];
		$application_no = $data['application_no'];
		$publication_date = $data['publication_date'];
		$patent_no = $data['patent_no'];
		$other = $data['other'];

		$query = " UPDATE prk_patent SET title = '$title',date_of_filing = '$date_of_filing',application_no = '$application_no',publication_date = '$publication_date',patent_no = '$patent_no',other = '$other' WHERE rec_id = '$rec_id' ";
		$this->db->query($query);
	}
	public function updateAuthorNotifyStatus($rec_id,$own_emp_id){
		$query = " SELECT emp_id FROM prk_ism_author WHERE $rec_id = '$rec_id' ";
		$emp_id = $this->db->query($query)->result();
		foreach($emp_id as $result){
			$query1 = " SELECT auth_id FROM users WHERE id = '$result->emp_id' ";
			$auth_id = $this->db->query($query1)->result();
			if ($auth_id[0]->auth_id != 'stu' AND $result->emp_id != $own_emp_id){
				$query2 = " UPDATE prk_ism_author SET notify_status = '0' WHERE rec_id = '$rec_id' AND emp_id = '$result->emp_id' ";
				$this->db->query($query2);
			}
		}
	}
	public function getDepartmentList($dept_type){
		$query = "SELECT id,name FROM departments WHERE type = '$dept_type'";
		return $this->db->query($query)->result();
	}
	public function getAuthorByDepartment($dept){
		$query = "SELECT id,concat(salutation,' ',first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE dept_id = '$dept' AND id IN (SELECT id FROM users WHERE auth_id = 'emp')";
		return $this->db->query($query)->result();
	}
	public function getJrfAndPostdoc($dept,$course){
		$query = " SELECT id,concat(first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE id IN (SELECT admn_no FROM stu_details WHERE stu_type = '$course') AND dept_id = '$dept'";
		return $this->db->query($query)->result();
	}
	public function getStudentsByCourseAndYear($arr){
		$dept = $arr['dept'];
		$year = $arr['year'];
		$even_sem = $year * 2;
		$odd_sem = $even_sem - 1;
		$course = $arr['course'];
		$session_year = $arr['session_year'];
		$query = "SELECT id, concat(first_name,' ',middle_name,' ',last_name) AS name FROM user_details WHERE dept_id = '$dept' AND id IN (SELECT admn_no FROM stu_details WHERE stu_type = '$course' AND admn_no IN (SELECT admn_no FROM reg_regular_form WHERE session_year = '$session_year' AND (semester = '$even_sem') OR semester = '$odd_sem')) ORDER BY name";
		return $this->db->query($query)->result();
	}
	public function getCurrentNoOfAuthors($rec_id){
		$query = " SELECT count(*) AS count FROM prk_ism_author WHERE rec_id = '$rec_id' ";
		$result = $this->db->query($query)->result();
		$ism_authors = $result[0]->count;
		$query = " SELECT count(*) AS count FROM prk_other_author WHERE rec_id = '$rec_id' ";
		$result = $this->db->query($query)->result();
		$other_authors = $result[0]->count;
		return $ism_authors + $other_authors;
	}
	public function isStudent($id){
		$query = " SELECT auth_id FROM users WHERE id = '$id' ";
		$result = $this->db->query($query)->result();
		$user_type = $result[0]->auth_id;
		if ($user_type == "stu")
			return true;
		return false;
	}
	public function insertAuthors($data,$rec_id){
		for ($i = 1; $i <= $data['no_of_authors']; $i++){
			if ($data['author_type'][$i] == 'ISM'){
				$emp_id = $data['ism'][$i]['emp_id'];
				$notify_status = $data['ism'][$i]['notify_status'];
				$position = $data['ism'][$i]['position'];
				$query = " INSERT INTO prk_ism_author VALUES ('$rec_id','$emp_id','$notify_status','$position') ";
			}
			else if ($data['author_type'][$i] == "OTHER"){
				$first_name = $data['other'][$i]['first_name'];
				$middle_name = $data['other'][$i]['middle_name'];
				$last_name = $data['other'][$i]['last_name'];
				$email_id = $data['other'][$i]['email_id'];
				$institution = $data['other'][$i]['institution'];
				$position = $data['other'][$i]['position'];
				$query = " INSERT INTO prk_other_author (rec_id,first_name,middle_name,last_name,email_id,institution,position) VALUES ('$rec_id','$first_name','$middle_name','$last_name','$email_id','$institution','$position') ";
			}
			$this->db->query($query);
		}
	}
	public function getPubIsmAuthors($rec_id){
		$query = " SELECT emp_id FROM prk_ism_author WHERE rec_id = '$rec_id' ";
		return $this->db->query($query)->result();
	}
	public function changePositionIsmAuthor($emp_id,$rec_id,$position){
		$query = " UPDATE prk_ism_author SET position = '$position' WHERE emp_id = '$emp_id' AND rec_id = '$rec_id' ";
		$this->db->query($query);
		return true;
	}
	public function getPublicationTitle($rec_id){
		$query = " SELECT title FROM prk_record WHERE rec_id = '$rec_id' ";
		$result = $this->db->query($query)->result();
		return $result[0]->title;
	}
	public function changePositionOtherAuthor($serial_no,$rec_id,$position){
		$query = " UPDATE prk_other_author SET position = '$position' WHERE serial_no = '$serial_no' AND rec_id = '$rec_id' ";
		$this->db->query($query);
		return true;
	}
	public function updateNotifyStatus($rec_id,$emp_id,$value){
		$query = " UPDATE prk_ism_author SET notify_status = '$value' WHERE rec_id = '$rec_id' AND emp_id = '$emp_id' ";
		$this->db->query($query);
		return true;
	}
	public function deleteIsmAuthor($rec_id,$emp_id){
		$query = " DELETE FROM prk_ism_author WHERE rec_id = '$rec_id' AND emp_id = '$emp_id' ";
		$this->db->query($query);
	}
	public function deleteOtherAuthor($rec_id,$serial_no){
		$query = " DELETE FROM prk_other_author WHERE rec_id = '$rec_id' AND serial_no = '$serial_no' ";
		$this->db->query($query);
	}
	public function updateIsmAuthorPosition($rec_id,$emp_id,$position){
		$query = " UPDATE prk_ism_author SET position = '$position' WHERE rec_id = '$rec_id' AND emp_id = '$emp_id' ";
		$this->db->query($query);
	}
	public function updateOtherAuthorPosition($rec_id,$serial_no,$position){
		$query = " UPDATE prk_other_author SET position = '$position' WHERE rec_id = '$rec_id' AND serial_no = '$serial_no' ";
		$this->db->query($query);
	}
	public function deletePublication($rec_id){
		$query = " DELETE FROM prk_record WHERE rec_id = '$rec_id' ";
		$this->db->query($query);
		$query = "SELECT path FROM publication_keeper WHERE rec_id = '$rec_id' ";
		$result = $this->db->query($query)->result();
		$path = "./assets/images/prk_abs/journal/".$result[0]->path;
		$this->load->helper("file");
		if (is_dir('./assets/images/prk_abs/journal/')) 
		{
			if($result[0]->path!="")
			unlink($path);
		}
		$query = " DELETE FROM publication_keeper WHERE rec_id = '$rec_id' ";
		$this->db->query($query);
	}
	public function deleteAbstract($rec_id){
		$query = "SELECT path FROM publication_keeper WHERE rec_id = '$rec_id' ";
		$result = $this->db->query($query)->result();
		$path = "./assets/images/prk_abs/journal/".$result[0]->path;
		$this->load->helper("file");
		if (is_dir('./assets/images/prk_abs/journal/')) 
		{
			if($result[0]->path!="")
			unlink($path);
			else
				return 0;
		}
		$j = "";
		$query = "DELETE FROM publication_keeper WHERE rec_id = '$rec_id' ";
		$this->db->query($query);
		return 1;
	}
	public function deleteFile($data,$rec_id){
		$path = "./assets/images/prk_abs/journal/".$data['path'];
		$this->load->helper("file");
		if (is_dir('./assets/images/prk_abs/journal/')) 
		{
			if($data['path']!="")
			unlink($path);
			
		}
		$query = "SELECT rec_id From publication_keeper WHERE rec_id = '$rec_id'";
		$result = $this->db->query($query)->result();
		if($result[0]->rec_id==NULL){
			$query = "SELECT type FROM prk_record WHERE rec_id = '$rec_id'";
			$result1 = $this->db->query($query)->result();
			$type = $result1[0]->type;
			$owner = $data['owner'];
			$file = $data['path'];
			$query = "INSERT INTO publication_keeper VALUES ('$owner','$rec_id','$type','$file') ";

			print_r($query);
			//exit;
			$this->db->query($query);
		}
	}
}
