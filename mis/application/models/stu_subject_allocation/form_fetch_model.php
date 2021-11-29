<?php
class Form_fetch_model extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  // function to get the list of session year
  function get_session_year()
  {
      $sql = "SELECT * FROM mis_session_year
              ORDER BY id DESC
      ";
      $query = $this->db->query($sql);
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      } else {
          return false;
      }
  }

  // function to get the list of session
  function get_session()
  {
        $sql = "SELECT * FROM mis_session";
        $query = $this->db->query($sql);
        if ($this->db->affected_rows() >= 0) {
            return $query->result();
        } else {
            return false;
        }
  }

  // function insert data into the final registration table
  function set_data($d,$data)
  {
  //echo"<pre>";  print_r($d);
        $form_id = $d->form_id;
        $session_year = $data['session_year'];
        $session = $data['session'];
        $sub_sys = $data['subject_system'];

        if($sub_sys == "new"){

          $sql = "SELECT a.* FROM
            pre_stu_course a
            WHERE a.session_year = '$session_year'
            AND a.session = '$session'
            AND a.subject_code = '$d->subject_code'
            AND a.sub_category = '$d->sub_category'
            AND a.form_id = '$form_id'
          ";
          $query = $this->db->query($sql);
         
          $ans = $query->result();
          $timestamp = date("Y-m-d H:i:s");
          $X = $ans[0];
          //query to insert data into into final reg table
          // $sql = "INSERT INTO temp_cbcs_stu_course
          //         VALUES(
          //         '',
          //         '$form_id',
          //         '$X->admn_no',
          //         '$X->sub_offered_id',
          //         '$X->subject_code',
          //         '$X->course_aggr_id',
          //         '$X->subject_name',
          //         '$X->priority',
          //         '$X->sub_category',
          //         '$X->sub_category_cbcs_offered',
          //         '$X->course',
          //         '$X->branch',
          //         '$X->session_year',
          //         '$X->session',
          //         '$timestamp'
          //         )
          //   ";
            $sql="UPDATE pre_stu_course SET remark2=1 WHERE id='$X->id'";
            $query = $this->db->query($sql);
            if($this->db->query($sql))
              return true;
            //echo $this->db->last_query(); exit;
          }
        else if($sub_sys == "old"){
          //query to get the number of credit hours of the particular subject
          $sql = "SELECT a.* FROM
            pre_stu_course a
            WHERE a.session_year = '$session_year'
            AND a.session = '$session'
            AND a.subject_code = '$d->subject_code'
            AND a.sub_category = '$d->sub_category'
            AND a.form_id = '$form_id'
          ";
          $query = $this->db->query($sql);
          $ans = $query->result();
          $timestamp = date("Y-m-d H:i:s");
          $X = $ans[0];

          //query to insert data into into final reg table
          // $sql = "INSERT INTO temp_cbcs_stu_course
          //         VALUES(
          //         '',
          //         '$form_id',
          //         '$X->admn_no',
          //         '$X->sub_offered_id',
          //         '$X->subject_code',
          //         '$X->course_aggr_id',
          //         '$X->subject_name',
          //         '$X->priority',
          //         '$X->sub_category',
          //         '$X->sub_category_cbcs_offered',
          //         '$X->course',
          //         '$X->branch',
          //         '$X->session_year',
          //         '$X->session',
          //         '$timestamp'
          //         )
          //   ";
            $sql="UPDATE pre_stu_course SET remark2=1 WHERE id='$X->id'";
            $query = $this->db->query($sql);
            return;
        }

  }

  function get_data_DCDP($data){
        $session = $data['session'];
        $session_year = $data['session_year'];
        $sub_sys = $data['subject_system'];

        if($sub_sys == "new"){
          //query to get student list according to subject category and ordering students on the basis of OGPA
          $sql = "SELECT c.form_id,c.admn_no,c.course_id,c.branch_id,
              c.semester,c.session_year,c.`session`,
              a.subject_code,a.sub_category
              FROM pre_stu_course a
              INNER JOIN reg_regular_form c
              ON c.form_id = a.form_id
              WHERE (a.sub_category LIKE 'DC%' OR a.sub_category LIKE 'DP%')
              AND c.`session` = '$session'
              AND c.session_year = '$session_year' AND a.sub_offered_id LIKE 'c%'";

          $query = $this->db->query($sql);
           // echo $this->db->last_query();
          if ($this->db->affected_rows() >= 0) {
              return $query->result();
          } 
          else {
              return false;
          }
        }
        else if($sub_sys == "old"){
          //query to get student list according to subject category and ordereding students on the basis of OGPA
          $sql = "SELECT c.form_id,c.admn_no,c.course_id,c.branch_id,
              c.semester,c.session_year,c.`session`,
              a.subject_code,a.sub_category
              FROM pre_stu_course a
              INNER JOIN reg_regular_form c
              ON c.form_id = a.form_id
              WHERE (a.sub_category LIKE 'DC%' OR a.sub_category LIKE 'DP%')
              AND c.`session` = '$session'
              AND c.session_year = '$session_year' AND a.sub_offered_id LIKE 'o%'";

          $query = $this->db->query($sql);

          if ($this->db->affected_rows() >= 0) {
              return $query->result();
          }
          else {
              return false;
          }
        }
  }

  // function to get all the data for the current session,session year for the subject category
  function get_data($data){
        $sub_cat = $data['subject_category'];
        $str = $sub_cat.substr(0,2);
        // $str = 'ESO';
        $session = $data['session'];
        $session_year = $data['session_year'];
        $sub_sys = $data['subject_system'];
        if($sub_sys == "new"){
          //query to get student list according to subject category and ordereding students on the basis of GPA       
          // $sql = "SELECT cso.minstu,cso.maxstu,cso.criteria,a.remark2,a.id,c.form_id,c.admn_no,c.course_id,c.branch_id, c.semester,c.session_year,
          // c.`session`,a.subject_code,a.priority,a.sub_category_cbcs_offered,b.gpa FROM pre_stu_course a
          // INNER JOIN reg_regular_form c ON c.form_id = a.form_id
          // INNER JOIN cbcs_subject_offered cso ON cso.sub_code=a.subject_code AND cso.session_year=a.session_year AND cso.`session`=a.`session` 
          // INNER JOIN (SELECT x.* FROM (SELECT a.* FROM final_semwise_marks_foil_freezed AS a ORDER BY a.actual_published_on DESC
          // LIMIT 100000000)x)b ON b.admn_no = c.admn_no AND c.semester-1 = b.semester
          // WHERE a.sub_category_cbcs_offered LIKE '$str%' AND c.`session` = '$session' AND c.session_year = '$session_year' 
          // AND a.sub_offered_id LIKE 'c%' AND /*cso.sub_category LIKE '$str%' AND*/ a.remark2 IS NULL GROUP BY c.form_id,a.sub_category,a.priority
          // ORDER BY b.gpa DESC,a.sub_category ASC,a.priority ASC"; 

          $sql = "select y.*,b.gpa from (SELECT cso.minstu,cso.maxstu,cso.criteria,a.remark2,a.id,c.form_id,c.admn_no,c.course_id,c.branch_id, 
                  c.semester,c.session_year, c.`session`,a.subject_code,a.priority,a.sub_category_cbcs_offered
                  FROM  (select  a.* from   pre_stu_course a  where a.remark2 IS NULL  and  a.`session` = '$session' AND a.session_year = 
                  '$session_year' and  a.sub_category_cbcs_offered LIKE '$str%'  AND a.sub_offered_id LIKE 'c%') a INNER JOIN reg_regular_form c 
                  ON c.form_id = a.form_id INNER JOIN cbcs_subject_offered cso ON cso.sub_code=a.subject_code AND cso.session_year=a.session_year
                  AND cso.`session`=a.`session` GROUP BY a.form_id,a.sub_category,a.priority)y INNER JOIN(SELECT x.* FROM (SELECT a.* FROM 
                  final_semwise_marks_foil_freezed AS a where a.`session`='Monsoon' AND a.session_yr ='2020-2021' ORDER BY 
                  a.admn_no,a.actual_published_on DESC  LIMIT 100000000)x group by x.admn_no)b ON b.admn_no = y.admn_no ORDER BY b.gpa DESC, 
                  y.sub_category_cbcs_offered ASC,y.priority ASC";         

          $query = $this->db->query($sql);
          // echo $this->db->last_query();
          if($this->db->affected_rows() >= 0){
            return $query->result();
          } 
          else{
              return false;
          }
        }
        else if($sub_sys == "old"){
            //query to get student list according to subject category and ordereding students on the basis of OGPA
          $sql = "SELECT c.form_id,c.admn_no,c.course_id,c.branch_id,
              c.semester,c.session_year,c.`session`,
              a.subject_code,a.priority,a.sub_category,b.cgpa
              FROM pre_stu_course a
              INNER JOIN reg_regular_form c
              ON c.form_id = a.form_id
              INNER JOIN final_semwise_marks_foil_freezed b
              ON b.admn_no = c.admn_no AND c.semester-2 = b.semester
              WHERE a.sub_category LIKE '$str%'
              AND c.`session` = '$session'
              AND c.session_year = '$session_year'
              AND a.sub_offered_id LIKE 'o%'
              AND a.remark2 IS NULL
              GROUP BY c.form_id,a.sub_category,a.priority
              ORDER BY b.cgpa DESC,a.sub_category ASC,a.priority ASC
          ";

          $query = $this->db->query($sql);
          //echo $this->db->last_query();
          if ($this->db->affected_rows() >= 0) {
              return $query->result();
          } 
          else {
              return false;
          }
      }
  }

  // function to get all the data for the current session,session year for the subject category which has not been alloted to students
  function get_rem_data($data){
        $sub_cat = $data['subject_category'];
        $str = $sub_cat.substr(0,2);
        $session = $data['session'];
        $session_year = $data['session_year'];
        $sub_sys = $data['subject_system'];

        if($sub_sys == "new"){
          //query to get student list who have not been alloted subject according to subject category and ordereding students on the basis of OGPA
          $sql = "SELECT c.form_id,c.admn_no,c.course_id,c.branch_id,
              c.semester,c.session_year,c.`session`,
              a.subject_code,a.priority,a.sub_category,b.cgpa
              FROM pre_stu_course a
              INNER JOIN reg_regular_form c
              ON c.form_id = a.form_id
              INNER JOIN final_semwise_marks_foil b
              ON b.admn_no = c.admn_no AND c.semester-1 = b.semester
              WHERE a.sub_category LIKE '$str%'
              AND c.`session` = '$session'
              AND c.session_year = '$session_year'
              AND (c.admn_no,a.sub_category) NOT IN (
              SELECT d.admn_no,e.sub_category FROM cbcs_stu_course e
                INNER JOIN reg_regular_form d
                ON d.form_id = e.form_id
                WHERE d.session_year = '$session_year' AND
                d.`session` = '$session'
              )
              GROUP BY c.form_id,a.sub_category,a.priority
              ORDER BY b.cgpa DESC,a.sub_category ASC,a.priority ASC
          ";

          $query = $this->db->query($sql);

          if ($this->db->affected_rows() >= 0) {
              return $query->result();
          } else {
              return false;
          }
        }
        else if($sub_sys == "old"){
          //query to get student list who have not been alloted subject according to subject category and ordereding students on the basis of OGPA
          $sql = "SELECT c.form_id,c.admn_no,c.course_id,c.branch_id,
              c.semester,c.session_year,c.`session`,
              a.subject_code,a.priority,a.sub_category,b.cgpa
              FROM pre_stu_course a
              INNER JOIN reg_regular_form c
              ON c.form_id = a.form_id
              INNER JOIN final_semwise_marks_foil b
              ON b.admn_no = c.admn_no AND c.semester-1 = b.semester
              WHERE a.sub_category LIKE '$str%'
              AND c.`session` = '$session'
              AND c.session_year = '$session_year'
              AND (c.admn_no,a.sub_category) NOT IN (
              SELECT d.admn_no,e.sub_category FROM old_stu_course e
                INNER JOIN reg_regular_form d
                ON d.form_id = e.form_id
                WHERE d.session_year = '$session_year' AND
                d.`session` = '$session'
              )
              GROUP BY c.form_id,a.sub_category,a.priority
              ORDER BY b.cgpa DESC,a.sub_category ASC,a.priority ASC
          ";

          $query = $this->db->query($sql);

          if ($this->db->affected_rows() >= 0) {
              return $query->result();
          } else {
              return false;
          }
        }
  }

  //function to check if the student,subject combination already exists in the final registration table
  function check_data_exist($d,$data){

        $session_year = $data['session_year'];
        $session = $data['session'];
        $sub_sys = $data['subject_system'];

        if($sub_sys == "new")
        {
          $sql = "SELECT a.* FROM
            cbcs_stu_course a
            INNER JOIN reg_regular_form b
            ON b.form_id = a.form_id
            WHERE
            b.admn_no = '$d->admn_no' AND
            a.subject_code = '$d->subject_code'
          ";

          $this->db->query($sql);
          if ($this->db->affected_rows() > 0) {
              return true;
          }
          else 
          {
              $sql = "SELECT b.`day`,b.slot_no FROM
                tt_map_cbcs a INNER JOIN
                tt_subject_slot_map_cbcs b
                ON b.map_id = a.map_id
                WHERE a.`session` = '$session' AND
                a.session_year = '$session_year'
                AND b.subj_code = '$d->subject_code' AND
                (b.`day`,b.slot_no) IN (SELECT
                s.`day`,s.slot_no FROM
                tt_map_cbcs r INNER JOIN
                tt_subject_slot_map_cbcs s
                ON r.map_id = s.map_id
                INNER JOIN
                cbcs_stu_course t
                ON t.subject_code = s.subj_code
                WHERE r.`session` = '$session' AND
                r.session_year = '$session_year'
                AND t.form_id = '$d->form_id')";

              $this->db->query($sql);
              if ($this->db->affected_rows() > 0) {
                return true;
              }
              else
              {
                $sql = "SELECT a.* FROM
                cbcs_stu_course a
                WHERE
                a.form_id = '$d->form_id' AND
                a.sub_category = '$d->sub_category'
                ";
                $this->db->query($sql);
                if ($this->db->affected_rows() > 0) {
                  return true;
                }
                return false;
              }
            }
        }
        else if($sub_sys == "old"){
            $sql = "SELECT a.* FROM
            old_stu_course a
            INNER JOIN reg_regular_form b
            ON b.form_id = a.form_id
            WHERE
            b.admn_no = '$d->admn_no' AND
            a.subject_code = '$d->subject_code'
          ";

          $this->db->query($sql);
          if ($this->db->affected_rows() > 0) {
              return true;
          }
           else {
              $sql = "
                SELECT b.`day`,b.slot_no FROM
                tt_map_old a INNER JOIN
                tt_subject_slot_map_old b
                ON b.map_id = a.map_id
                WHERE a.`session` = '$session' AND
                a.session_year = '$session_year'
                AND b.subj_code = '$d->subject_code' AND
                (b.`day`,b.slot_no) IN (SELECT
                s.`day`,s.slot_no FROM
                tt_map_old r INNER JOIN
                tt_subject_slot_map_old s
                ON r.map_id = s.map_id
                INNER JOIN
                old_stu_course t
                ON t.subject_code = s.subj_code
                WHERE r.`session` = '$session' AND
                r.session_year = '$session_year'
                AND t.form_id = '$d->form_id'
                )
              ";
              $this->db->query($sql);
              if ($this->db->affected_rows() > 0) {
                return true;
              }
              else{
                $sql = "SELECT a.* FROM
                old_stu_course a
                WHERE
                a.form_id = '$d->form_id' AND
                a.sub_category = '$d->sub_category'
                ";
                $this->db->query($sql);
                if ($this->db->affected_rows() > 0) {
                  return true;
                }
                return false;
              }
            }
        }
  }

  //function to get the selection type(OGPA,Random,Random 50-50) and the maximum strength of that particular subject
  function get_max_count($d,$data){
    $session_year = $data['session_year'];
    $session = $data['session'];
    $sub_sys = $data['subject_system'];

    if($sub_sys == "new"){
      $sql = "SELECT criteria,maxstu FROM
          cbcs_subject_offered
          WHERE sub_code = '$d->subject_code'
          AND session = '$session'
          AND session_year = '$session_year'
        ";

      $query = $this->db->query($sql);
    //echo  $this->db->last_query();
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }
    else if($sub_sys == "old"){
      $sql = " SELECT criteria,maxstu FROM
          old_subject_offered
          WHERE sub_code = '$d->subject_code'
          AND session = '$session'
          AND session_year = '$session_year'
        ";

      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }
  }

  function get_curr_count($d,$data){
    $session_year = $data['session_year'];
    $session = $data['session'];
    $sub_sys = $data['subject_system'];

    if($sub_sys == "new"){
          //query to get the count of the students already registered for the subject in this particular session and session year
        // $sql = "SELECT count(b.admn_no) as curr_count FROM
        //   cbcs_stu_course a
        //   INNER JOIN reg_regular_form b
        //   ON a.form_id = b.form_id
        //   WHERE b.session_year = '$session_year'
        //   AND b.session = '$session'
        //   AND a.subject_code = '$d->subject_code'
        //   ";
        $sql = "SELECT count(b.admn_no) as curr_count FROM
          pre_stu_course a
          INNER JOIN reg_regular_form b
          ON a.form_id = b.form_id
          WHERE b.session_year = '$session_year'
          AND b.session = '$session'
          AND a.subject_code = '$d->subject_code' AND a.remark2=1
          ";

          $query = $this->db->query($sql);

          if ($this->db->affected_rows() >= 0) {
              return $query->result();
          }
          else{
              return false;
          }
    }

    else if($sub_sys == "old"){
      //query to get the count of the students already registered for the subject in this particular session and session year
          $sql = "SELECT count(b.admn_no) as curr_count FROM
          old_stu_course a
          INNER JOIN reg_regular_form b
          ON a.form_id = b.form_id
          WHERE b.session_year = '$session_year'
          AND b.session = '$session'
          AND a.subject_code = '$d->subject_code'
          ";

          $query = $this->db->query($sql);

          if ($this->db->affected_rows() >= 0) {
              return $query->result();
          }
          else{
              return false;
          }
    }

  }
  //function to check and insert data of priority subbjects
  // function insert_data($d,$data){

  //       $admn_no = $d->admn_no;
  //       $session_year = $data['session_year'];
  //       $session = $data['session'];
  //       $sub_cat = $data['subject_category'];
  //       $sub_sys = $data['subject_system'];
  //       // $nsub_cat = substr($d->sub_category,0,3);
  //       $nsub_cat = substr($d->sub_category,0,3);

        
       

  //       // query to get the selection type(OGPA,Random,Random 50-50) and the maximum strength of that particular subject       

  //       $ans = $this->get_max_count($d,$data);

  //       $assigned_stu = $this->get_assigned_student($d,$data);

  //       $asg_stu=(int)$assigned_stu[0]->assigned_student;
  //       echo "Assigned ".$asg_stu.'<br>';
       
  //       $max_strength = (int)$d->maxstu;
  //       $sel_type = $d->criteria;

  //       // $ans1 = $this->get_curr_count($d,$data);
  //       // $curr_count = intval($ans1[0]->curr_count);

  //       // echo "Max strength ".$max_strength."<br>";
  //       // echo "Current Count ".$curr_count."<br>";
  //       // exit;


  //       // Check Assigned student to Maximum student every time
  //       // echo $asg_stu."<br>";
  //       if($asg_stu >= $max_strength){
  //         echo "<br>"." Max";
  //             echo $this->db->last_query();
  //             return;
              
  //       }
        
        
  //       // Select only one student in any sub_category
  //       $sql="SELECT x.admn_no,x.sub_cat,x.remark2 FROM 
  //             (SELECT r.sub_category AS sub_cat,r.*  FROM pre_stu_course r WHERE r.session_year='$session_year' 
  //             AND r.session='$session' AND r.admn_no='$admn_no' AND r.sub_category='$d->sub_category') x WHERE  x.remark2 =1"; 

  //       $query=$this->db->query($sql);
  //       echo $this->db->last_query()."<br>";
  //       if($this->db->affected_rows()>0){          
  //         return;     
          
  //       } 

  //       $firstratio=($max_strength*70)/100;
  //       $secondratio=($max_strength*30)/100;
  //       if($firstratio>$asg_stu){
          
  //         if($this->set_data($d,$data))
  //           echo "Bara";
  //           echo $this->db->last_query();
  //         return;
          
  //       }
        
  //       else{
  //         if(rand(0,1)){              
  //           if($this->set_data($d,$data))
  //             echo "Chhota";
  //             echo $this->db->last_query();
  //           return;
            
  //         }
  //       }
  //       else{         
         
  //         // selection based on OGPA(ogpa)
  //         if($sel_type == 'ogpa'){
  //             echo "OGPA";
  //             if($this->set_data($d,$data))
  //               echo $this->db->last_query();
  //             return;
              
  //         }

  //         // selection based on Random 50-50(default)
  //         else if($sel_type == 'default')
  //         {
  //           // $max_strength = intval($max_strength);
  //           $firstratio=($max_strength*70)/100;
  //           $secondratio=($max_strength*30)/100;
  //           if($firstratio>$asg_stu){
              
  //             if($this->set_data($d,$data))
  //               echo "Bara";
  //               echo $this->db->last_query();
  //             return;
              
  //           }
  //           else{
  //             if(rand(0,1)){              
  //               if($this->set_data($d,$data))
  //                 echo "Chhota";
  //                 echo $this->db->last_query();
  //               return;
                
  //             }
  //           }


  //           // if($max_strength <= 100)
  //           // {
  //           //   //$firstratio=($max_strength*70)/100;
  //           //   $max_strength = intval($max_strength);        
  //           //   $mid_strength = intval(($max_strength)/2);
  //           //   if($max_strength%2 != 0){
  //           //     $mid_strength += 1;
  //           //   }
  //           //   if($mid_strength >= $curr_count){
  //           //     $this->set_data($d,$data);
  //           //   }
  //           //   else
  //           //   {
  //           //     if(rand(0,1)){
  //           //       $this->set_data($d,$data);
  //           //     }
  //           //   }
  //           // }
  //           // else
  //           // {
  //           //   $this->set_data($d,$data);
  //           // }
  //         }

  //         // selection based on Random(lottery)
  //         else if($sel_type == 'lottery')
  //         {
  //               if(rand(0,1)){
  //                 $this->set_data($d,$data);
  //                 return;
  //               }
  //         }
  //     }
  // }

  function get_assigned_student($d,$data){
    $str=substr($d->sub_category,0,3);
    $sql = "SELECT count(b.admn_no) AS assigned_student FROM pre_stu_course b WHERE b.`session` = '$d->session' 
    AND b.session_year = '$d->session_year' AND b.subject_code='$d->subject_code' AND b.sub_category='$d->sub_category' AND b.remark2='1'";
    $query=$this->db->query($sql);
    // echo $this->db->last_query();   

    if($this->db->affected_rows()>=0){         
      return $query->result();
    }
    else{
       return false;
    }

  }

  // function check_subcategory($d,$data){
  //   $admn_no = $d->admn_no;
  //   $session_year = $data['session_year'];
  //   $session = $data['session'];
  //   $sub_cat = $data['subject_category'];
  //   $nsub_cat = substr($sub_cat,0,1);

  //   echo $sql="SELECT x.admn_no,x.sub_cat,x.remark2 FROM 
  //         (SELECT r.sub_category AS sub_cat,r.*  FROM pre_stu_course r WHERE r.session_year='$session_year' 
  //         AND r.session='$session' AND r.admn_no='$admn_no' AND r.sub_category LIKE '$nsub_cat%') x  
  //         WHERE  x.remark2 =1";

  //         exit;
          

  //   $query=$this->db->query($sql);
  //   echo $this->db->last_query();
    

  //   if($this->db->affected_rows()>=0){         
  //     return $query->result_array();
  //   }
  //   else{
  //      return false;
  //   }   
    
  // }


  function insert_rem_data($d,$data){

        $admn_no = $d->admn_no;
        $session_year = $data['session_year'];
        $session = $data['session'];
        $sub_sys = $data['subject_system'];

        $ans = $this->get_max_count($d,$data);
        $max_strength = intval($ans[0]->maxstu);

        $ans1 = $this->get_curr_count($d,$data);
        $curr_count = intval($ans1[0]->curr_count);

        // if($curr_count >= $max_strength){
        //       return;
        // }

        //else set data
        $this->set_data($d,$data);
  }


  function get_prev_list($session_year,$session,$sub_cat,$sys){
   
    if($sys == "new"){
      // $sql="SELECT x.subject_code AS sub_code,x.total_reg,c.sub_name,c.maxstu,c.dept_id FROM (SELECT b.subject_code,b.sub_category_cbcs_offered,count(*) as 
      // total_reg,COUNT(if(b.remark2=1,1,NULL)) AS final FROM pre_stu_course b WHERE b.sub_category_cbcs_offered LIKE '$sub_cat%' 
      // AND b.session_year='$session_year' AND b.`session`='$session' GROUP BY b.subject_code)x 
      // INNER JOIN cbcs_subject_offered c ON c.sub_code=x.subject_code GROUP BY x.subject_code,x.sub_category_cbcs_offered";

      $sql = "SELECT c.maxstu,c.criteria,c.dept_id,x.* FROM (SELECT COUNT(p.admn_no) AS total_reg,SUM(CASE WHEN (p.remark2='1') THEN 1 ELSE 0 END)
       AS guided,p.session_year,p.session,p.subject_name,p.subject_code,p.remark2,p.sub_category_cbcs_offered 
      FROM pre_stu_course p LEFT JOIN cbcs_guided_eso q ON p.sub_offered_id=CONCAT('c',q.sub_offered_id) 
      AND p.course=q.course_id AND p.branch=q.branch_id WHERE p.session_year='$session_year' AND p.`session`='$session' 
      AND p.sub_category_cbcs_offered LIKE '$sub_cat%' 
      GROUP BY p.subject_code)x INNER JOIN cbcs_subject_offered c ON c.sub_code=x.subject_code AND c.session_year=x.session_year
      AND c.`session`=x.session WHERE c.sub_category LIKE '$sub_cat%' GROUP BY x.subject_code";

      $query = $this->db->query($sql);
      // echo $this->db->last_query()."<br>";
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }
    else if($sys == "old"){
      $sql = "SELECT a.sub_name,a.sub_code,a.dept_id,a.minstu,a.maxstu,count(*) as total_reg
      FROM old_subject_offered a
      INNER JOIN pre_stu_course b
      ON b.subject_code = a.sub_code
      WHERE
      b.sub_category LIKE '$sub_cat%'
      AND b.`session` = '$session' AND b.session_year = '$session_year'
      GROUP BY b.subject_code";

      $query = $this->db->query($sql);
      //  echo"old". $this->db->last_query();
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }   
  }

  function total_registered_stu($session_year,$session,$sub_cat){
    $sql="SELECT COUNT(x.id) AS tot FROM (SELECT COUNT(t.id),t.* FROM pre_stu_course t 
          WHERE t.session_year='$session_year' AND t.`session`='$session' AND t.sub_category 
          LIKE '$sub_cat%' GROUP BY t.admn_no,t.sub_category)x";

    $query=$this->db->query($sql);
    // echo $this->db->last_query();
    if($this->db->affected_rows()>0){
      return $query->result();
    }
    else{
      return false;
    }
  }

  function total_alloted_stu($session_year,$session,$sub_cat){
    $sql="SELECT COUNT(x.id) AS allot FROM (SELECT COUNT(t.id),t.* FROM pre_stu_course t 
          WHERE t.session_year='$session_year' AND t.`session`='$session' AND t.sub_category 
          LIKE '$sub_cat%' AND t.remark2=1 GROUP BY t.admn_no,t.sub_category)x";
          
    $query=$this->db->query($sql);
    if($this->db->affected_rows()>0){
      return $query->result();
    }
    else{
      return false;
    }
  }

  function get_priority_prev_list($session_year,$session,$sub_cat,$sub_code,$sys){
    if($sys == "new"){
      $sql = "SELECT b.priority,count(*) as total_reg
      FROM cbcs_subject_offered a
      INNER JOIN pre_stu_course b
      ON b.subject_code = a.sub_code
      WHERE
      b.sub_category LIKE '$sub_cat%'
      AND a.`session` = '$session' AND a.session_year = '$session_year'
      AND a.sub_code = '$sub_code'
      GROUP BY b.priority
      ORDER BY b.priority ASC
      ";
      $query = $this->db->query($sql);
      //  echo $this->db->last_query();
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }
    else if($sys == "old"){
      $sql = "SELECT b.priority,count(*) as total_reg
      FROM old_subject_offered a
      INNER JOIN pre_stu_course b
      ON b.subject_code = a.sub_code
      WHERE
      b.sub_category LIKE '$sub_cat%'
      AND a.`session` = '$session' AND a.session_year = '$session_year'
      AND a.sub_code = '$sub_code'
      GROUP BY b.priority
      ORDER BY b.priority ASC
      ";
      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }
  }


  function get_curr_list($data){
    $session_year = $data['session_year'];
    $session = $data['session'];
    $sub_cat = $data['subject_category'];
    $sub_sys = $data['subject_system'];
    $sub_cat = $sub_cat.substr(0,2);
    if($sub_sys == "new"){
      $sql = "SELECT x.*,c.sub_name,c.maxstu,c.dept_id FROM (SELECT b.subject_code,b.sub_category_cbcs_offered,count(*) as total_reg,
      COUNT(if(b.remark2=1,1,NULL)) AS final FROM pre_stu_course b WHERE b.sub_category_cbcs_offered LIKE '$sub_cat%' 
      AND b.session_year='$session_year' AND b.`session`='$session'GROUP BY b.subject_code)x INNER JOIN cbcs_subject_offered c 
      ON c.sub_code=x.subject_code GROUP BY x.subject_code";
      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }
    else if($sub_sys == "old"){
      $sql = "SELECT a.sub_name,a.sub_code,a.dept_id,a.minstu,a.maxstu,count(*) as total_reg
      FROM old_subject_offered a
      INNER JOIN old_stu_course b
      ON b.subject_code = a.sub_code
      WHERE
      b.sub_category LIKE '$sub_cat%'
      AND a.`session` = '$session' AND a.session_year = '$session_year'
      GROUP BY a.sub_code
      ";
      $query = $this->db->query($sql);

      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }

  }


  function get_newcurr_list($data){   
    $session_year = $data['session_year'];
    $session = $data['session'];
    $sub_cat = $data['subject_category'];
    $sub_sys = $data['subject_system'];
    $sub_cat = substr($sub_cat,0,2);

    if($sub_sys == "new"){

      // $sql= "SELECT x.*,c.sub_name,c.maxstu,c.dept_id FROM (SELECT b.subject_code,b.sub_category_cbcs_offered,
      // count(*) as total_reg,COUNT(if(b.remark2=1,1,NULL)) AS final
      // FROM pre_stu_course b WHERE b.sub_category_cbcs_offered LIKE '$sub_cat%' AND b.session_year='$session_year'
      // AND b.`session`='$session'GROUP BY b.subject_code)x INNER JOIN cbcs_subject_offered c ON c.sub_code=x.subject_code
      // GROUP BY x.subject_code";

      $sql = "SELECT c.maxstu,c.criteria,c.dept_id,x.* FROM (SELECT COUNT(p.admn_no) AS total_reg,SUM(CASE WHEN !(q.id IS NULL)
      THEN 1 ELSE 0 END) AS guided,COUNT(if(p.remark2='1',1,NULL)) AS final,p.session_year,p.session,p.subject_name,
      p.subject_code,p.remark2,p.sub_category_cbcs_offered FROM pre_stu_course p LEFT JOIN cbcs_guided_eso q ON 
      p.sub_offered_id=CONCAT('c',q.sub_offered_id) AND p.course=q.course_id AND p.branch=q.branch_id 
      WHERE p.session_year='$session_year' AND p.`session`='$session' AND p.sub_category_cbcs_offered LIKE '$sub_cat%' 
      GROUP BY p.subject_code)x INNER JOIN cbcs_subject_offered c ON c.sub_code=x.subject_code AND c.session_year=x.session_year
      AND c.`session`=x.session WHERE c.sub_category LIKE '$sub_cat%'GROUP BY x.subject_code";

      $query = $this->db->query($sql);
      // echo"news". $this->db->last_query();
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }

    if($sub_sys == "old"){
      $sql = "SELECT a.sub_name,a.sub_code,a.dept_id,a.minstu,a.maxstu,count(*) as total_reg
      FROM old_subject_offered a
      INNER JOIN pre_stu_course b
      ON b.subject_code = a.sub_code
      WHERE
      b.sub_category LIKE '$sub_cat%'
      AND b.`session` = '$session' AND b.session_year = '$session_year'
      GROUP BY b.subject_code";

      $query = $this->db->query($sql);
      //echo"news". $this->db->last_query();
      if ($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
    }

  }


  /* Insert the value into temprory table*/

  function send_data($result){
    // echo "<pre>";
    // print_r($result);
    $size=sizeof($result);
    $sr=1;
    foreach ($result as $key => $value) {
      $insert_data = array(
        'maxstu'=>$value->maxstu,
        'criteria'=>$value->criteria,
        'remark2'=>$value->remark2,
        'pre_id'=>$value->id,
        'form_id'=>$value->form_id,
        'admn_no'=>$value->admn_no,
        'course_id'=>$value->course_id,
        'branch_id'=>$value->branch_id,
        'semester'=>$value->semester,
        'session_year'=>$value->session_year,
        'session'=>$value->session,
        'subject_code'=>$value->subject_code,
        'priority'=>$value->priority,
        'sub_category_cbcs_offered'=>$value->sub_category_cbcs_offered,
        'gpa'=>$value->gpa
      );       
      $this->db->insert('temp_pre_stu_course', $insert_data); 
      $sr++;
    }
    if($size==$sr){
      return true;
    }
    else{
      return false;
    }
  }

  function get_final_data(){
    $sql= "SELECT t.* FROM temp_pre_stu_course t ORDER BY t.gpa DESC,t.sub_category_cbcs_offered DESC ,t.priority ASC";
    $query = $this->db->query($sql);

    if($this->db->affected_rows() >= 0) {
        return $query->result();
    }
    else{
        return false;
    }

  }  
    
  function get_priority_count(){
    $sql= 'SELECT COUNT(x.pt) AS count FROM (SELECT t.priority AS pt FROM temp_pre_stu_course t GROUP BY t.priority)x';
    $query = $this->db->query($sql);
    if($this->db->affected_rows() >= 0) {
        return $query->row()->count;
    }
    else{
        return false;
    }

  }

  function get_max($sub,$data){
    $session=$data['session'];
    $session_year=$data['session_year'];
    $sub_cat=$data['subject_category'];
    $sub_sys=$data['subject_system'];
    if($sub_sys=='new'){
      $sub_sys='c';
    }
    else{
      $sub_sys='o';
    }
    $temp=0;
    $pre=0;
    $sql= "SELECT t.maxstu FROM temp_pre_stu_course t WHERE t.subject_code='$sub' AND t.session_year='$session_year' AND 
    t.`session`='$session' GROUP BY t.subject_code";
    $query = $this->db->query($sql);
    if($this->db->affected_rows() >= 0) {
        $temp = $query->row()->maxstu;
    }

    $sql2="SELECT COUNT(p.id) AS asg FROM pre_stu_course p WHERE p.session_year='$session_year' AND p.`session`='$session' 
    AND p.subject_code='$sub' AND p.sub_category_cbcs_offered LIKE '$sub_cat%' AND p.sub_offered_id LIKE '$sub_sys%'
    AND p.remark2='1'";
    $query2 = $this->db->query($sql2);
    if($this->db->affected_rows() >= 0) {
      
      $pre =  $query2->row()->asg;
    }

    if($pre>=$temp){
      return 0;
    }
    else{
      return ($temp-$pre);
    }
    
  }

  function get_subject(){
    $sql = "SELECT t.subject_code AS subject FROM temp_pre_stu_course t GROUP BY t.subject_code";
    $query = $this->db->query($sql);
    if($this->db->affected_rows() >= 0) {
        return $query->result();
    }
    else{
        return false;
    }
  }

  function get_sub_stu_count($priority,$subject_code){
      $sql= "SELECT COUNT(*) AS stu_count FROM temp_pre_stu_course t WHERE t.subject_code='$subject_code' AND t.priority='$priority'
      AND remark2 IS NULL";
      $query = $this->db->query($sql);
      // echo $this->db->last_query();
      if($this->db->affected_rows() >= 0) {
          return $query->row()->stu_count;
      }
      else{
          return false;
      }
     
  }

  function get_tie_stu_count($priority,$subject_code){
      $sql= "SELECT COUNT(*) AS stu_count FROM temp_pre_stu_course t WHERE t.subject_code='$subject_code' AND t.priority='$priority'
      AND remark2='1'";
      $query = $this->db->query($sql);
      // echo $this->db->last_query();
      if($this->db->affected_rows() >= 0) {
          return $query->row()->stu_count;
      }
      else{
          return false;
      }
     
  }

  function get_final_tie_student($i,$sub,$first_stu_slot){
     $check_count = (int)$first_stu_slot;
     $sql= "SELECT t.gpa FROM temp_pre_stu_course t WHERE t.subject_code='$sub' AND t.priority='$i' LIMIT $check_count,1";
     $query = $this->db->query($sql);
      // echo $this->db->last_query();
      if($this->db->affected_rows() >= 0) {
          $gpa = $query->row()->gpa;

          $sql1 = "SELECT t.* FROM temp_pre_stu_course t WHERE t.subject_code='$sub' AND t.priority='$i' AND CAST(t.gpa as CHAR)=CAST($gpa as CHAR)";
          $query1 = $this->db->query($sql1);
          // echo $this->db->last_query();
          if($this->db->affected_rows() >= 0) {
              return $query1->result();
          }
          else{
              return false;
          }
      }
      else{
          return false;
      }

  }
  
  function get_sub_stu_count_remark($subject_code){
    $sql= "SELECT COUNT(*) AS stu_count FROM temp_pre_stu_course t WHERE t.subject_code='$subject_code' AND t.remark2='1'";
      $query = $this->db->query($sql);
      // echo $this->db->last_query();
      if($this->db->affected_rows() >= 0) {
          return $query->row()->stu_count;
      }
      else{
          return false;
      }
  }

  function get_stu_list($priority,$subject_code){
      $sql= "SELECT t.* FROM temp_pre_stu_course t WHERE t.subject_code='$subject_code' AND t.priority='$priority' AND remark2 IS NULL 
      ORDER BY t.gpa DESC";
      $query = $this->db->query($sql);
      if($this->db->affected_rows() >= 0) {
          return $query->result();
      }
      else{
          return false;
      }
  }

  function get_random_stu_list($priority,$subject_code){
    $sql = "SELECT k.* FROM(SELECT t.* FROM temp_pre_stu_course t WHERE t.subject_code='$subject_code' AND t.priority='$priority' 
    AND t.remark2 IS NULL)k ORDER BY RAND()";
    $query = $this->db->query($sql);
    // echo $this->db->last_query();
    if($this->db->affected_rows() >= 0) {
        return $query->result();
    }
    else{
        return false;
    }
  }

  function check_student($stu_data){
    $sql = "SELECT t.* FROM temp_pre_stu_course t WHERE t.admn_no='$stu_data->admn_no' AND t.remark2='1' AND 
    t.sub_category_cbcs_offered='$stu_data->sub_category_cbcs_offered'";
    $query = $this->db->query($sql);
    if($this->db->affected_rows() >0) {
        return true;
    }
    else{
        return false;
    }
  }

  function set_remark($stu_data){
    $sql = "SELECT t.* FROM temp_pre_stu_course t WHERE t.admn_no='$stu_data->admn_no' AND t.remark2='1' AND 
    t.sub_category_cbcs_offered='$stu_data->sub_category_cbcs_offered'";
    $query = $this->db->query($sql);
    // echo $this->db->last_query();
    if($this->db->affected_rows()> 0) {
      return true;
    }
    else{
      $sql="UPDATE temp_pre_stu_course t SET t.remark2='1' WHERE t.id='$stu_data->id'";      
      if($query = $this->db->query($sql)){
        // echo $this->db->last_query()."<br>";
        return true;
      }
      else{
        return false;
      }
    }
  }

  function set_alloted_data($sub_cat){
    if($sub_cat=='ESO'){
      $sub_cat='ESO%';
    }       

    $sql = "SELECT t.* FROM temp_pre_stu_course t WHERE t.sub_category_cbcs_offered LIKE '$sub_cat' AND t.remark2='1'";
    $query = $this->db->query($sql);
    // echo $this->db->last_query();
    if($this->db->affected_rows()> 0) {
      $ar_size=sizeof($query->result());
      $sr=0;
      foreach ($query->result() as $key => $value) {
        $sql="UPDATE pre_stu_course t SET t.remark2='1' WHERE t.id='$value->pre_id'";
        // echo $this->db->last_query();
        $query = $this->db->query($sql);
        $sr++;
      }
      if($ar_size == $sr){
        return true;
      }
      else{
        return false;
      }
      
    }
    else{
      return false;
    }

  }

  function del_temp(){
    $sql = "DELETE FROM temp_pre_stu_course WHERE admn_no IN (SELECT x.ad FROM(SELECT p.admn_no AS ad FROM temp_pre_stu_course 
    p WHERE p.remark2='1')x)";
    if($query = $this->db->query($sql)){
      return true;
    }
    else{
      return false;
    }
  }



  /* End of Model Class */
}
?>
