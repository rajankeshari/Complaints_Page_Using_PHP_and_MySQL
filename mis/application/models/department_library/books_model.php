<?php

class Books_model extends CI_Model
{
	
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
        
        function get_subject()
        {
             $query = $this-> db-> get('lib_dept_subject'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        
        function get_purchase_place()
        {
             $query = $this-> db-> get('lib_purchase'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        function get_location($id)
        {
             $query = $this-> db->get_where('lib_dept_location',array('dept'=>$id)); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        function check_location($data)
        {
             $query = $this-> db->get_where('lib_dept_location',$data); 
                if($query->num_rows() > 0)
			return TRUE;
		else
			return FALSE;
        }
        function check_publication($data)
        {
             $query = $this-> db->get_where('lib_book_publication',$data); 
                if($query->num_rows() > 0)
			return TRUE;
		else
			return FALSE;
        }
        function check_subject($data)
        {
             $query = $this-> db->get_where('lib_dept_subject',$data); 
                if($query->num_rows() > 0)
			return TRUE;
		else
			return FALSE;
        }
        function get_subject_byDept($id)
        {
             $query = $this-> db->get_where('lib_dept_subject',array('dept'=>$id)); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        function get_author_byDept($id)
        {
             $query = $this-> db->get_where('lib_books',array('dept'=>$id)); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        function get_subject_byID($id)
        {
             $query = $this-> db->get_where('lib_dept_subject',array('id'=>$id)); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        
        function get_location_byID($id)
        {
             $query = $this-> db->get_where('lib_dept_location',array('id'=>$id)); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        function get_publication_byID($id)
        {
             $query = $this-> db->get_where('lib_book_publication',array('id'=>$id)); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        
        function insert_dept_subject($data)
        {
            if($this->db->insert('lib_dept_subject',$data))
			return TRUE;
		else
			return FALSE;
        }
        function insert_dept_location($data)
        {
            if($this->db->insert('lib_dept_location',$data))
			return TRUE;
		else
			return FALSE;
        }
        function insert_no_dues($data)
        {
            if($this->db->insert('lib_no_dues',$data))
			return TRUE;
		else
			return FALSE;
        }
        
        function insert_books($data)
        {
            if($this->db->insert('lib_books',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        }
        function insert_publication($data)
        {
            if($this->db->insert('lib_book_publication',$data))
			return $this->db->insert_id();
		else
			return FALSE;
        }
        
        function get_emp_details_byID($id)
        {
            $query = $this-> db->get_where('user_details',array('id'=>$id)); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
            
        }
        function get_books_atuhor()
        {
             $query = $this-> db-> get('lib_books'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        function get_subjects()
        {
             $query = $this-> db-> get('lib_dept_subject'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
    
        function get_publication()
        {
             $query = $this-> db->query("Select * from lib_book_publication");
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        
        function get_books()
        {
             $query = $this-> db-> get('lib_books'); 
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        function get_book_details($accno='',$bt='',$sub='',$aut='',$pub='')
        {
           $sql="SELECT
  `lib_books`.`book_id`,
  `lib_books`.`btitle`,
  `lib_books`.`auth1`,
  `lib_books`.`auth2`,
  `lib_books`.`auth3`,
  `lib_books`.`subject`,
  `lib_books`.`publication`,
  `lib_books`.`copies`,
  `lib_books`.`dept`,
  `lib_dept_subject`.`subject` AS `subject1`,
  `lib_book_publication`.`publication` AS `publication1`,
  `lib_books_detail`.`acc_no`,
  `lib_dept_location`.`location`
FROM
  `lib_books`
  INNER JOIN `lib_dept_subject` ON `lib_books`.`subject` =
    `lib_dept_subject`.`id`
  INNER JOIN `lib_book_publication` ON `lib_books`.`publication` =
    `lib_book_publication`.`id`
  INNER JOIN `lib_books_detail` ON `lib_books`.`book_id` =
    `lib_books_detail`.`book_id`
  INNER JOIN `lib_dept_location` ON `lib_books_detail`.`location` =
    `lib_dept_location`.`id`
WHERE   1 = 1 and status='s' ";
            if($accno)
            {
                $sql.= " and lib_books_detail.acc_no=".$accno;
                
            }
            if($bt!="none")
            {
                $sql.= " and  lib_books.book_id=".$bt;
                
            }
            if($sub!="none")
            {
                $sql.= " and lib_books.subject=".$sub;
                
            }
            if($aut!="none")
            {
                $sql.= " and lib_books.auth1='".$aut."'or `lib_books`.`auth2`='".$aut."' or `lib_books`.`auth3`='".$aut."'";
                
            }
            if($pub!="none")
            {
                $sql.= " and lib_books.publication=".$pub;
                
            }
            $query = $this->db->query($sql);
                
                if($this->db->affected_rows() >=0)
                { 
                   return $query->result();
                }
                else
                {
                    return false;
                }
            
        }
        
        //-------------------------------Report--------------------
        
        function get_book_details_report($accno='',$bt='',$sub='',$aut='',$pub='',$loc='')
        {
           $sql="SELECT
  `lib_books`.`book_id`,
  `lib_books`.`btitle`,
  `lib_books`.`auth1`,
  `lib_books`.`auth2`,
  `lib_books`.`auth3`,
  `lib_books`.`subject`,
  `lib_books`.`publication`,
  `lib_books`.`copies`,
  `lib_books`.`dept`,
  `lib_dept_subject`.`subject` AS `subject1`,
  `lib_book_publication`.`publication` AS `publication1`,
  `lib_books_detail`.`acc_no`,
  `lib_dept_location`.`location`
FROM
  `lib_books`
  INNER JOIN `lib_dept_subject` ON `lib_books`.`subject` =
    `lib_dept_subject`.`id`
  INNER JOIN `lib_book_publication` ON `lib_books`.`publication` =
    `lib_book_publication`.`id`
  INNER JOIN `lib_books_detail` ON `lib_books`.`book_id` =
    `lib_books_detail`.`book_id`
  INNER JOIN `lib_dept_location` ON `lib_books_detail`.`location` =
    `lib_dept_location`.`id`
WHERE   1 = 1 ";
            if($accno)
            {
                $sql.= " and lib_books_detail.acc_no=".$accno;
                
            }
            if($bt!="none")
            {
                $sql.= " and  lib_books.book_id=".$bt;
                
            }
            if($sub!="none")
            {
                $sql.= " and lib_books.subject=".$sub;
                
            }
            if($aut!="none")
            {
                //$sql.= " and lib_books.auth1='".$aut."'";
                 $sql.= " and lib_books.auth1='".$aut."'or `lib_books`.`auth2`='".$aut."' or `lib_books`.`auth3`='".$aut."'";
                
            }
            if($pub!="none")
            {
                $sql.= " and lib_books.publication=".$pub;
                
            }
            if($loc!="none")
            {
                $sql.= " and lib_books_detail.location=".$loc;
                
            }
            $query = $this->db->query($sql);
                
                if($this->db->affected_rows() >=0)
                { 
                   return $query->result();
                }
                else
                {
                    return false;
                }
            
        }
        
        //---------------------------------------------------------------
        
        function insert_batch($data)
	{
		if($this->db->insert_batch('lib_book_issue',$data))
                {
                    
                    return TRUE;
                }
		else
                {
			return FALSE;
                }
	}
        //-------------------------------
            function update_lib_books_details_by_accno($data,$con){
             $this->db->update('lib_books_detail', $data, $con);
         }
        
        //-----------------------------------------------------
        
        function get_issued_books_byID($id)
        {
            
           // $query = $this-> db->get_where('lib_book_issue',array('mem_id'=>$id,'status'=>'I')); 
            $sql="
                SELECT
  `lib_books`.`book_id`,
  `lib_books`.`btitle`,
  `lib_books`.`auth1`,
  `lib_books`.`auth2`,
  `lib_books`.`auth3`,
  `lib_books`.`subject`,
  `lib_books`.`publication`,
  `lib_books`.`copies`,
  `lib_books`.`dept`,
  `lib_dept_subject`.`subject` AS `subject1`,
  `lib_book_publication`.`publication` AS `publication1`,
  `lib_books_detail`.`acc_no`,
  `lib_dept_location`.`location`,
  `lib_book_issue`.`mem_id`,
  `lib_book_issue`.`iss_date`,
  `lib_book_issue`.`status`
FROM
  `lib_books`
  INNER JOIN `lib_dept_subject` ON `lib_books`.`subject` =
    `lib_dept_subject`.`id`
  INNER JOIN `lib_book_publication` ON `lib_books`.`publication` =
    `lib_book_publication`.`id`
  INNER JOIN `lib_books_detail` ON `lib_books`.`book_id` =
    `lib_books_detail`.`book_id`
  INNER JOIN `lib_dept_location` ON `lib_books_detail`.`location` =
    `lib_dept_location`.`id`
  INNER JOIN `lib_book_issue` ON `lib_book_issue`.`acc_no` =
    `lib_books_detail`.`acc_no`
WHERE
  `lib_book_issue`.`mem_id` = '".$id."' AND
  `lib_book_issue`.`status` = 'i';
 ";
            
                $query = $this->db->query($sql);
                
                if($this->db->affected_rows() >=0)
                { 
                   return $query->result();
                }
                else
                {
                    return false;
                }
            
        }
          function get_issued_books_byID_all($id)
        {
            
           // $query = $this-> db->get_where('lib_book_issue',array('mem_id'=>$id,'status'=>'I')); 
            $sql="
                SELECT
  `lib_books`.`book_id`,
  `lib_books`.`btitle`,
  `lib_books`.`auth1`,
  `lib_books`.`auth2`,
  `lib_books`.`auth3`,
  `lib_books`.`subject`,
  `lib_books`.`publication`,
  `lib_books`.`copies`,
  `lib_books`.`dept`,
  `lib_dept_subject`.`subject` AS `subject1`,
  `lib_book_publication`.`publication` AS `publication1`,
  `lib_books_detail`.`acc_no`,
  `lib_dept_location`.`location`,
  `lib_book_issue`.`mem_id`,
  `lib_book_issue`.`iss_date`,
   `lib_book_issue`.`ret_date`,
  `lib_book_issue`.`status`
FROM
  `lib_books`
  INNER JOIN `lib_dept_subject` ON `lib_books`.`subject` =
    `lib_dept_subject`.`id`
  INNER JOIN `lib_book_publication` ON `lib_books`.`publication` =
    `lib_book_publication`.`id`
  INNER JOIN `lib_books_detail` ON `lib_books`.`book_id` =
    `lib_books_detail`.`book_id`
  INNER JOIN `lib_dept_location` ON `lib_books_detail`.`location` =
    `lib_dept_location`.`id`
  INNER JOIN `lib_book_issue` ON `lib_book_issue`.`acc_no` =
    `lib_books_detail`.`acc_no`
WHERE
  `lib_book_issue`.`mem_id` = '".$id."' ";
            
                $query = $this->db->query($sql);
                
                if($this->db->affected_rows() >=0)
                { 
                   return $query->result();
                }
                else
                {
                    return false;
                }
            
        }
      
        function get_user_type($id)
        {
            $len=strlen($id);
            if($len<6)
            {
            $query = $this-> db->select('auth_id')->get_where('emp_basic_details',array('emp_no'=>$id)); 
            $xyz=$query->row()->auth_id;
            }
            else
            {
               $query = $this-> db->select('stu_type')->get_where('stu_details',array('admn_no'=>$id)); 
               $xyz=$query->row()->stu_type;
            }
            
            
            $query1 = $this-> db->select('b_limit')->get_where('lib_mem_book_limit',array('m_type'=>$xyz)); 
            
               if($query1->num_rows() > 0)
			return $query1->row();
		else
			return false;
        }
        function get_total_books_byID($id)
        {
            $sql="select count(acc_no) as tot_sum from lib_book_issue where mem_id='".$id."' and status='i'";
            
                $query = $this->db->query($sql);
                
                if($this->db->affected_rows() >=0)
                { 
                  // return $query->row()->tot_sum;
                     return $query->row();
                }
                else
                {
                    return false;
                }
        }
        
        function get_books_byID($id)
        {
           // $sql="select * from lib_books where book_id=".$id;
           // $sql="select a.*,b.subject as subjectnm from lib_books a, lib_dept_subject b where a.subject=b.id and book_id=".$id;
            
            $sql="SELECT
  `lib_dept_subject`.`subject` AS `subject1`,
  `lib_book_publication`.`publication` AS `publication1`,
  `lib_books`.`book_id`,
  `lib_books`.`btitle`,
  `lib_books`.`auth1`,
  `lib_books`.`auth2`,
  `lib_books`.`auth3`,
  `lib_books`.`copies`,
  `lib_books`.`dept`,
  `lib_books`.`subject`,
  `lib_books`.`publication`
FROM
  `lib_books`
  INNER JOIN `lib_dept_subject` ON `lib_books`.`subject` =
    `lib_dept_subject`.`id`
  INNER JOIN `lib_book_publication` ON `lib_books`.`publication` =
    `lib_book_publication`.`id`
WHERE
    `lib_books`.`book_id` =".$id; // original
	//`lib_books`.`book_id` =".$id order by `lib_books`.`btitle`; // Added  order by `lib_books`.`btitle`;
//     `lib_books`.`book_id` ='".$id."' 
//ORDER BY  `lib_books`.`btitle`
 //"; 
//echo  $sql ; die();
            $query = $this->db->query($sql);
               // echo $this->db->last_query();die();
                if($query->num_rows() > 0)
			return $query->row();
		else
			return false;
        }
        function get_existing_books_byID($id)
        {
            
           // $sql="select a.*,b.subject as subjectnm from lib_books a, lib_dept_subject b where a.subject=b.id and book_id=".$id;
            $sql="SELECT
  `lib_books_detail`.*,
  `lib_dept_location`.`location` AS `location1`,
  `lib_purchase`.`place`
FROM
  `lib_books_detail`
  INNER JOIN `lib_dept_location` ON `lib_books_detail`.`location` =
    `lib_dept_location`.`id`
  INNER JOIN `lib_purchase` ON `lib_books_detail`.`pur_source` =
    `lib_purchase`.`id`
    where book_id=".$id;
            $query = $this->db->query($sql);
                
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        function get_books_byDept($id)
        {
           // $sql="select * from lib_books where book_id=".$id;
            $sql="
                
SELECT
  `lib_dept_subject`.`subject` AS `subject1`,
  `lib_book_publication`.`publication` AS `publication1`,
  `lib_books`.`book_id`,
  `lib_books`.`btitle`,
  `lib_books`.`auth1`,
  `lib_books`.`auth2`,
  `lib_books`.`auth3`,
  `lib_books`.`copies`,
  `lib_books`.`dept`,
  `lib_books`.`subject`,
  `lib_books`.`publication`
FROM
  `lib_books`
  INNER JOIN `lib_dept_subject` ON `lib_books`.`subject` =
    `lib_dept_subject`.`id`
  INNER JOIN `lib_book_publication` ON `lib_books`.`publication` =
    `lib_book_publication`.`id`

WHERE
  `lib_books`.`dept` = '".$id."' order by `lib_books`.`btitle`; ";
        
        
          
            $query = $this->db->query($sql);
                //echo $this->db->last_query();die();
                if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
        }
        
        function insert_batch_bookDetails($data)
	{
		if($this->db->insert_batch('lib_books_detail',$data))
			return TRUE;
		else
			return FALSE;
	}
        
        function update_book($id,$data)
        {
            $this->db->where('book_id', $id);
            $this->db->update('lib_books', $data);

        }
        function update_subject($id,$data)
        {
            $this->db->where('id', $id);
            $this->db->update('lib_dept_subject', $data);

        }
         function update_location($id,$data)
        {
            $this->db->where('id', $id);
            $this->db->update('lib_dept_location', $data);

        }
        
         function update_publication($id,$data)
        {
            $this->db->where('id', $id);
            $this->db->update('lib_book_publication', $data);

        }
        
        
        function update_book_return_bookIssue_tbl($data_bookIssue,$con_bookIssue)
        {
            $this->db->update('lib_book_issue', $data_bookIssue, $con_bookIssue);
             return TRUE;
        }
        function update_book_return_booksDetail_tbl($data,$con)
        {
            $this->db->update('lib_books_detail', $data, $con);
            return TRUE;
        }
        
        
}
