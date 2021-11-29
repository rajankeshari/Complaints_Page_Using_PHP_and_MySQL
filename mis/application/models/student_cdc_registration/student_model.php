<?php

class Student_model extends CI_Model {


    private $tabulation='placement';

    function __construct() {
        parent::__construct();
    }

    public function get_records_from_id($table_name, $request_fields = "", $id_name, $id_value) {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str.= $column_name . ', ';
            }
        } else {
            $str = '*';
        }

        $this->db->select($str);
        $this->db->where($id_name, $id_value);
        $query = $this->db->get($table_name);
        return $query->row_array();
     }

   public function add_update_record($table, $data = array(), $id_name = '') {
        $this->db->set($data);
        if ($id_name) {
            $this->db->where($id_name, $data[$id_name]);
            $query = $this->db->update($table);
        } else {//adding record in table
            $query = $this->db->insert($table);
        }
    }

    public function fill_selected_hostel($value = "") {

        $opt_str = "";
        $str[1] = 'Amber';
        $str[2] = 'Diamond';
		$str[3] = 'International';
        $str[4] = 'Emerald';
        $str[5] = 'Jasper';
        $str[6] = 'Opal';
        $str[7] = 'Rosaline';
        $str[8] = 'Ruby';
		$str[9] = 'Ruby Annexe';
        $str[10] = 'Sapphire';
        $str[11] = 'Shanti Bhavan';
        $str[12] = 'Topaz';
        $str[13] = 'N/A';

        if (!$value) {
            $opt_str.='<option selected="selected" value=""> Select Hostel Name</option>';
        } else {
            $opt_str.='<option value=""> Select Hostel Name</option>';
        }

        foreach ($str as $hostel) {
            $opt_str.='<option ';
            if (($value) && ($value == $hostel)) {
                $opt_str.='selected="selected" ';
            }
            $opt_str.='value="' . $hostel . '">' . $hostel . '</option>';
        }
        return $opt_str;
    }



    function email_data($id = '')
    {


        if($id != '')
        {

            $query = $this->db->query('select a.* from emaildata a where a.admission_no="'.$id.'"');
            

            if($query->num_rows() == 0) return FALSE;
            return $query->result();
        }
        else
            return FALSE;
    }


  function course_branch($id = '')
    {


        if($id != '')
        {

            $query = $this->db->query("select a.*,b.course_id,b.branch_id,c.name as cname,d.name as bname,e.name as dname from user_details a 
inner join stu_academic b on b.admn_no=a.id
inner join cbcs_courses c on c.id=b.course_id
inner join cbcs_branches d on d.id=b.branch_id
inner join cbcs_departments e on e.id=a.dept_id
where a.id='$id'");
            

         

            if($query->num_rows() == 0) return FALSE;
            return $query->result();
        }
        else
            return FALSE;
    }


    


    public function fill_selected_block($value = "") {

        $opt_str = "";
        $str[1] = 'A/I';
        $str[2] = 'B/II';
        $str[3] = 'C/III';
        $str[4] = 'D/IV';
        $str[5] = 'E/V';
        $str[6] = 'F/VI';
        $str[7] = 'G/VII';
        $str[8] = 'H/VIII';
        $str[9] = 'N/A';

        if (!$value) {
            $opt_str.='<option selected="selected" value=""> Select Block No.  </option>';
        } else {
            $opt_str.='<option value=""> Select Block No.  </option>';
        }

        foreach ($str as $block) {
            $opt_str.='<option ';
            if (($value) && ($value == $block)) {
                $opt_str.='selected="selected" ';
            }
            $opt_str.='value="' . $block . '">' . $block . '</option>';
        }
        return $opt_str;
    }

    public function fill_selected_floor($value = "") {

        $opt_str = "";
        $str[1] = '0';
        $str[2] = '1';
        $str[3] = '2';
        $str[4] = '3';
        $str[5] = '4';
        $str[6] = '5';
        $str[7] = '6';
        $str[8] = '7';
        $str[9] = '8';
        $str[10] = '9';
        $str[11] = '10';



        if (!$value) {
            $opt_str.='<option selected="selected" value=""> Select Floor No.  </option>';
        } else {
            $opt_str.='<option value=""> Select Floor No.  </option>';
        }

        foreach ($str as $floor) {
            $opt_str.='<option ';
            if (($value) && ($value == $floor)) {
                $opt_str.='selected="selected" ';
            }
            $opt_str.='value="' . $floor . '">' . $floor . '</option>';
        }
        return $opt_str;
    }

    function fill_selected_with_table($table, $filters_id_values = array(), $value = "", $text = "", $order_by = "", $selected = "") {

        $this->db->select($value . "," . $text);

        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $this->db->from($table);
        $return_data = $this->db->get()->result_array();
        $opt_str = ""; //for return on UI
        if (!$selected) {
            $opt_str.='<option selected="selected" value=""> Select an option  </option>';
        } else {
            $opt_str.='<option value="">Select an option </option>';
        }

        if ($return_data) {
            foreach ($return_data as $return_data_value) {
                $opt_str.='<option ';
                if (($return_data_value[$value]) && ($return_data_value[$value] == $selected)) {
                    $opt_str.='selected="selected" ';
                }
                $opt_str.='value="' . $return_data_value[$value] . '">' . $return_data_value[$text] . '</option>';
            }
        }

        return $opt_str;
    }

    public function get_many_records($table_name = '', $filters_id_values, $request_fields = "", $order_by = '') {
        $str = '';
        if ($request_fields) {
            foreach ($request_fields as $column_name) {
                $str.= $column_name . ', ';
            }
        } else {
            $str = '*';
        }
        $this->db->select($str);

        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {
                $this->db->where($filter['id'], $filter['value']);
            }
        }

        if ($order_by) {
            $this->db->order_by($order_by);
        }
        $this->db->from($table_name);
        return $this->db->get()->result_array();
    }

    function get_course_bydept($dept_id) {

        $query = $this->db->query("SELECT DISTINCT course_branch.course_id,id,name,duration FROM
		courses INNER JOIN course_branch ON course_branch.course_id = courses.id INNER JOIN dept_course ON
		dept_course.course_branch_id = course_branch.course_branch_id WHERE dept_course.dept_id = '$dept_id'");
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_branch_bycourse($course, $dept) {
        $query = $this->db->query("SELECT DISTINCT id,name,dept_course.course_branch_id FROM branches INNER JOIN course_branch ON course_branch.branch_id = branches.id INNER JOIN dept_course ON dept_course.course_branch_id = course_branch.course_branch_id WHERE course_branch.course_id = '" . $course . "' AND dept_course.dept_id = '" . $dept . "'");
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    public function get_records_from_three_join($tableOne, $tableOneColumn, $tableTwo, $tableTwoColumn, $tableThree, $tableThreeColumn, $tableFour, $tableFourColumn, $filters_id_values, $orderBy = "") {
        $this->db->select('*');
        $this->db->from("$tableOne a");
        $this->db->join("$tableTwo b", "b.$tableTwoColumn=a.$tableOneColumn", "inner");
        $this->db->join("$tableThree c", "c.$tableThreeColumn=a.$tableOneColumn", "inner");
        $this->db->join("$tableFour d", "d.$tableFourColumn=a.$tableOneColumn", "inner");
        if ($filters_id_values) {
            foreach ($filters_id_values as $filter) {//filter should be Table combination LIKE   $this->db->where("b.enrollment_year", $id);
                $this->db->where($filter['id'], $filter['value']);
            }
        }
        if ($orderBy) {//filter should be Table combination LIKE    $this->db->order_by('c.track_title', 'asc');
            $this->db->order_by($orderBy, 'desc');
        }

        return $this->db->get()->result_array();
    }




//public function transfer_user_details($users_edu,$admn_no,$whereClouse)

     //public function transfer_user_details($acad_student_education)


  public function transfer_user_details($student_basic_details,$users_details,$permanent_address,$present_address,$ug_student_education,$admn_no,$acad_student_education,$users,$student_academic,$user_other_details)


//public function transfer_user_details($stu_basic_details)
{



    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);



// echo"<pre>";
// print_r($user_other_details);
// echo"<pre>";
// exit;





    // echo"<pre>";
    // print_r($whereClouse);
    // echo"</pre>";
    // exit;



// print_r($whereClouse);
// exit;

// $this->db2->insert('stu_basic_table',$stu_basic_details);

// echo $this->db2->last_query();
// exit;


try {



   $this->db2->trans_begin();
  

    $this->db2->insert_batch('final_semwise_marks_foil_freezed',$ug_student_education);





    $this->db2->insert('user_address',$present_address);
	
	
        
    $this->db2->insert('user_address',$permanent_address);


    $this->db2->insert('stu_basic_table',$student_basic_details);


     


    $this->db2->insert('user_details',$users_details);


    $this->db2->insert('users',$users);


    $this->db2->insert_batch('stu_prev_education',$acad_student_education);


     $this->db2->insert('stu_academic',$student_academic);


     $this->db2->insert('user_other_details',$user_other_details);
/*

*/



        // echo"pankaj";
       // exit;
      // $this->db2->select('*');
     // $this->db2->from('stu_prev_education');
    // $this->db2->where($whereClouse);
   // $query = $this->db2->get();

   

// echo $query->num_rows();
// exit;

   // $query->num_rows();






//     if ( $query->num_rows() == 0)
//     {
             





//       $query = $this->db2->insert('stu_prev_education',$users_edu);


//     $str = $this->db->last_query();   
//     echo "<pre>";
//     print_r($str);
//    exit;
  
//    echo"pankaj";
//    exit;
       


//  return '0';
 



 //}

if ($this->db2->trans_status() === FALSE)
{
        $this->db2->trans_rollback();
          echo" Data not transfered";
        exit;
}
else
{
  
        $this->db2->trans_commit();

        return '1';

        // echo" Information transfered to placement database plz login to check";
        // exit;

}



     // $row = $query->row_array();
             

    //    return '0';
  


//echo"dfgdfg";


    // $str = $this->db->last_query();   
    // echo "<pre>";
    // print_r($str);
   // exit;

 // $this->db2->insert('users',$users);




// if ($this->db2->trans_status() === FALSE)
// {
//         $this->db2->trans_rollback();
// }
// else
// {
  
//         $this->db2->trans_commit();
//         echo"pankaj";
//         exit;
// }

} 
catch( Exception $e ) 
{

    /* 
     echo $this->db2->last_query();
     exit;
	 */
	 

    return '0';
  //echo "Either You are already registered in placement/or information not transfered now";


 // echo "friendly error message";
  // logging_function($e->getMessage());
  // throw $e;
}








 


     // $row = $query->row_array();
             

      //  return '1';
  

 //echo $query->num_rows();


// echo "2342234";
//   echo $admn_no;
 
}






public function changepass($password,$admn_no)
{
 
  // echo $admn_no;
  // exit;
  //    echo "<pre>";
  //     print_r($password);
  //     echo "</pre>";

  //     exit;

   

    $CI = &get_instance();
    $this->db2 = $CI->load->database($this->tabulation, TRUE);

    $query1 = $this->db2->where('id', $admn_no);
    //$query2 = $this->db2->update('users', $password);
 
 


         if ($query2 = $this->db2->update('users', $password))
         {




       

              return '0';
         }




         return '1';


        

    // $query = $this->db2->update('users',$confirm_pass);

  // $str = $this->db->last_query();
   
  //   echo "<pre>";
  //   print_r($str);
  //   exit;

     
}






public function transfer_user_education($users_edu,$admn_no,$whereClouse)
{
   


    




     $CI = &get_instance();
        $this->db2 = $CI->load->database($this->tabulation, TRUE);






// $query = $this->db->query('SELECT * FROM users');

// echo $query->num_rows();


// exit;








    //   $this->db2->select('*');
    //   $this->db2->from('stu_prev_education');
    // $this->db2->where($whereClouse);
    //   $query = $this->db2->get();
      // echo $this->db2->last_query();
      






// echo $query->num_rows();
// exit;

   // $query->num_rows();






   //  if ( $query->num_rows() == 0)
   //  {
             





      //$query = $this->db2->insert('stu_prev_education',$users_edu);
  
 
       


//  return '0';
 



// }



// return '1';


                  

     
 
     



   }












}
