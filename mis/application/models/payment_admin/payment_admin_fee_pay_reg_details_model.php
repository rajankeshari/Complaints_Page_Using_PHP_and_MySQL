<?php

class Payment_admin_fee_pay_reg_details_model extends CI_Model{

       // private $tabulation='parentlive';
        //private $pbeta = 'parentlive';
        //private $misdev = 'misdev';
        private $pbeta = 'parentbeta';

	function __construct(){
		parent::__construct();
    }


    public function get_list_student_paid_online($session_year,$session,$course,$semester)
  {


    // condition both semester and course are selected

        if ($course != '' && $semester != '') {

        $sql = "SELECT t1.student_name, t1.admn_no,t1.email_id,t1.session_year,t1.`session`,t1.course_id,t1.branch_id,t1.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,z.status
        FROM (
        SELECT *
        FROM bank_fee_details a
        WHERE a.payment_status = '1' AND payment_mode='online' AND a.session_year = '".$session_year."' AND a.`session`='".$session."') t1
        INNER JOIN reg_regular_fee d ON d.admn_no = t1.admn_no
        INNER JOIN reg_regular_form f ON f.form_id = d.form_id AND t1.session_year = f.session_year AND t1.session = f.session AND f.hod_status='1' AND f.acad_status='1'
        INNER JOIN users z ON z.id = t1.admn_no
        WHERE t1.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."') t2
        WHERE t2.course_id = '".$course."') group by t1.admn_no";


        $query = $this->db->query($sql);

        }



    // condition both semester and course are selected


     // condition when course is selected

        elseif ($course != '') {

        $sql = "SELECT t1.student_name, t1.admn_no,t1.email_id,t1.session_year,t1.`session`,t1.course_id,t1.branch_id,t1.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,z.status
        FROM (
        SELECT *
        FROM bank_fee_details a
        WHERE a.payment_status = '1' AND payment_mode='online' AND a.session_year = '".$session_year."' AND a.`session`='".$session."') t1
        INNER JOIN reg_regular_fee d ON d.admn_no = t1.admn_no
        INNER JOIN reg_regular_form f ON f.form_id = d.form_id AND t1.session_year = f.session_year AND t1.session = f.session AND f.hod_status='1' AND f.acad_status='1'
        INNER JOIN users z ON z.id = t1.admn_no
        WHERE t1.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1') t2
        WHERE t2.course_id = '".$course."')
        GROUP BY t1.admn_no";

        $query = $this->db->query($sql);


        }

     // condition when only course is selected

     //condition when only sem is selected


        elseif ($semester != '') {

        $sql = "SELECT t1.student_name, t1.admn_no,t1.email_id,t1.session_year,t1.`session`,t1.course_id,t1.branch_id,t1.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,z.status
        FROM (
        SELECT *
        FROM bank_fee_details a
        WHERE a.payment_status = '1' AND payment_mode='online' AND a.session_year = '".$session_year."' AND a.`session`='".$session."') t1
        INNER JOIN reg_regular_fee d ON d.admn_no = t1.admn_no
        INNER JOIN reg_regular_form f ON f.form_id = d.form_id AND t1.session_year = f.session_year AND t1.session = f.session AND f.hod_status='1' AND f.acad_status='1'
        INNER JOIN users z ON z.id = t1.admn_no
        WHERE t1.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester='".$semester."') t2
        )
        GROUP BY t1.admn_no";

        $query = $this->db->query($sql);

        }


     //condition when only sem is selected

     // condition when neither of them is selected


        else {

        $sql = "SELECT t1.student_name, t1.admn_no,t1.email_id,t1.session_year,t1.`session`,t1.course_id,t1.branch_id,t1.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,z.status
        FROM (
        SELECT *
        FROM bank_fee_details a
        WHERE a.payment_status = '1' AND payment_mode='online' AND a.session_year = '".$session_year."' AND a.`session`='".$session."') t1
        INNER JOIN reg_regular_fee d ON d.admn_no = t1.admn_no
        INNER JOIN reg_regular_form f ON f.form_id = d.form_id AND t1.session_year = f.session_year AND t1.session = f.session AND f.hod_status='1' AND f.acad_status='1'
        INNER JOIN users z ON z.id = t1.admn_no
        WHERE t1.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1') t2
        )
        GROUP BY t1.admn_no";

        $query = $this->db->query($sql);

        }



    // condition when neither of them is selected



    // return $query->result_array();
    // $query = $this->db->query($sql);

    //echo $this->db->last_query(); die();

    if ($query !== false) {

    return $query->result_array();

    }

    else {

      return false;

    }

  }

  public function get_list_student_paid_online_not_registered($session_year,$session,$course,$semester)
  {

    // condition when both sem and course are selected

    if ($semester != '' and $course != '') {

    // $sql = "SELECT t1.student_name, t1.admn_no,t1.email_id,t1.session_year,t1.`session`,t1.course_id,t1.branch_id,t1.category,if(f.form_id != '',f.form_id,'Registration Details Not Found') AS form_id,e.amount,e.added_on,e.txnid,f.acad_remark,z.status
    // FROM (
    // SELECT *
    // FROM bank_fee_details a
    // WHERE a.payment_mode='online' AND a.payment_status='1' AND a.session_year = '".$session_year."' AND a.`session`='".$session."') t1
    // LEFT JOIN sbi_success_details_semester_fees e ON e.admn_no = t1.admn_no AND t1.bank_reference_no = e.sbireferenceno
    // LEFT JOIN reg_regular_fee d ON d.admn_no = t1.admn_no
    // LEFT JOIN reg_regular_form f ON f.form_id = d.form_id AND t1.session_year = f.session_year AND t1.session = f.session AND f.hod_status='1' AND f.acad_status='1'
    // INNER JOIN users z ON z.id = t1.admn_no
    // WHERE t1.admn_no NOT IN (
    // SELECT t2.admn_no
    // FROM (
    // SELECT *
    // FROM reg_regular_form c
    // WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' AND c.semester = '".$semester."') t2 where t2.course_id = '".$course."')
    // GROUP BY t1.admn_no";
    // $query = $this->db->query($sql);

        $sql = "SELECT t1.student_name, t1.admn_no,t1.email_id,t1.session_year,t1.`session`,t1.course_id,t1.branch_id,t1.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,z.status
        FROM (
        SELECT *
        FROM bank_fee_details a
        WHERE a.payment_status = '1' AND payment_mode='online' AND a.session_year = '".$session_year."' AND a.`session`='".$session."') t1
        INNER JOIN reg_regular_fee d ON d.admn_no = t1.admn_no
        INNER JOIN reg_regular_form f ON f.form_id = d.form_id AND t1.session_year = f.session_year AND f.course_id='b.tech' AND f.semester='2' AND t1.session = f.session AND f.hod_status='1' AND f.acad_status='1'
        INNER JOIN users z ON z.id = t1.admn_no
        WHERE t1.admn_no NOT IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."') t2
        WHERE t2.course_id = '".$course."')
        GROUP BY t1.admn_no";

        $query = $this->db->query($sql);

    }

    // condition when both sem and course are selected


    // condition when sem is selected

    elseif ($semester != '') {

      $sql = "SELECT t1.student_name, t1.admn_no,t1.email_id,t1.session_year,t1.`session`,t1.course_id,t1.branch_id,t1.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,z.status
      FROM (
      SELECT *
      FROM bank_fee_details a
      WHERE a.payment_status = '1' AND payment_mode='online' AND a.session_year = '".$session_year."' AND a.`session`='".$session."') t1
      INNER JOIN reg_regular_fee d ON d.admn_no = t1.admn_no
      INNER JOIN reg_regular_form f ON f.form_id = d.form_id AND t1.session_year = f.session_year AND f.course_id='b.tech' AND f.semester='2' AND t1.session = f.session AND f.hod_status='1' AND f.acad_status='1'
      INNER JOIN users z ON z.id = t1.admn_no
      WHERE t1.admn_no NOT IN (
      SELECT t2.admn_no
      FROM (
      SELECT *
      FROM reg_regular_form c
      WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester='".$semester."') t2
      )
      GROUP BY t1.admn_no";

      $query = $this->db->query($sql);

    }

    // condition when both sem and course are selected



    // condition when course is selected

    elseif ($course != '') {

      $sql = "SELECT t1.student_name, t1.admn_no,t1.email_id,t1.session_year,t1.`session`,t1.course_id,t1.branch_id,t1.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,z.status
      FROM (
      SELECT *
      FROM bank_fee_details a
      WHERE a.payment_status = '1' AND payment_mode='online' AND a.session_year = '".$session_year."' AND a.`session`='".$session."') t1
      INNER JOIN reg_regular_fee d ON d.admn_no = t1.admn_no
      INNER JOIN reg_regular_form f ON f.form_id = d.form_id AND t1.session_year = f.session_year AND f.course_id='b.tech' AND f.semester='2' and t1.session = f.session AND f.hod_status='1' AND f.acad_status='1'
      INNER JOIN users z ON z.id = t1.admn_no
      WHERE t1.admn_no NOT IN (
      SELECT t2.admn_no
      FROM (
      SELECT *
      FROM reg_regular_form c
      WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1') t2
      WHERE t2.course_id = '".$course."')
      GROUP BY t1.admn_no";

      $query = $this->db->query($sql);

    }

    // condition when course is selected



    // condition neither of sem and course are selected

    else {

      $sql = "SELECT t1.student_name, t1.admn_no,t1.email_id,t1.session_year,t1.`session`,t1.course_id,t1.branch_id,t1.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,z.status
      FROM (
      SELECT *
      FROM bank_fee_details a
      WHERE a.payment_status = '1' AND payment_mode='online' AND a.session_year = '".$session_year."' AND a.`session`='".$session."') t1
      INNER JOIN reg_regular_fee d ON d.admn_no = t1.admn_no
      INNER JOIN reg_regular_form f ON f.form_id = d.form_id AND t1.session_year = f.session_year AND f.course_id='b.tech' AND f.semester='2' and t1.session = f.session AND f.hod_status='1' AND f.acad_status='1'
      INNER JOIN users z ON z.id = t1.admn_no
      WHERE t1.admn_no NOT IN (
      SELECT t2.admn_no
      FROM (
      SELECT *
      FROM reg_regular_form c
      WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1') t2
      )
      GROUP BY t1.admn_no";

      $query = $this->db->query($sql);

    }

    // condition neither of sem and course are selected

    //echo $this->db->last_query(); die();


    if ($query !== false) {

    return $query->result_array();

    }

    else {

      return false;

    }

  }


  public function get_session_year()
  {

    //$this->db->select('*');
    $query = $this->db->get('mis_session_year');
    //echo $this->db->last_query(); //die();
    return $query->result_array();
  }


  public function get_session_upload()
  {

    //$this->db->select('*');
    $query = $this->db->get('mis_session');
   // echo $this->db->last_query(); die();
    return $query->result_array();
  }


  public function get_cbcs_courses(){

    $query = $this->db->select('name,id')
                       ->from('cbcs_courses')
                       ->get();

   return $query->result_array();
}


public function get_total_bank_fee_details($session,$session_year,$course,$semester)
  {


      if ($course != '' and $semester != '') {

        $query = "SELECT *
        FROM bank_fee_details a INNER JOIN reg_regular_form b ON a.admn_no = b.admn_no AND b.session_year='2020-2021' AND b.`session`='Winter'
        WHERE a.session_year = '".$session_year."' AND a.session = '".$session."' AND a.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2 where t2.course_id = '".$course."') group by a.admn_no";

        $query = $this->db->query($query);

       }

      elseif ($course != '') {

        $query = "SELECT *
        FROM bank_fee_details a INNER JOIN reg_regular_form b ON a.admn_no = b.admn_no AND b.session_year='2020-2021' AND b.`session`='Winter'
        WHERE a.session_year = '".$session_year."' AND a.session = '".$session."' AND a.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2 where t2.course_id = '".$course."') group by a.admn_no";

        $query = $this->db->query($query);

          # code...
      }

      elseif ($semester != '') {

        $query = "SELECT *
        FROM bank_fee_details a INNER JOIN reg_regular_form b ON a.admn_no = b.admn_no AND b.session_year='2020-2021' AND b.`session`='Winter'
        WHERE a.session_year = '".$session_year."' AND a.session = '".$session."' AND a.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2) group by a.admn_no";

        $query = $this->db->query($query);
      }

      else{


        $query = "SELECT *
        FROM bank_fee_details a INNER JOIN reg_regular_form b ON a.admn_no = b.admn_no AND b.session_year='2020-2021' AND b.`session`='Winter'
        WHERE a.session_year = '".$session_year."' AND a.session = '".$session."' AND a.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2) group by a.admn_no";

        $query = $this->db->query($query);


      }

      //echo $this->db->last_query(); die();


      return $query->num_rows();


  }

  public function total_bank_fee_payment_details_online($session_year,$session,$course,$semester)
  {

    // $query = $this->db->select('*')
    // ->from('bank_fee_details')
    // ->where('payment_status','1')
    // ->where('payment_mode','online')
    // ->where('session_year',$session_year)
    // ->where('session',$session)
    // ->get();

       if($course != '' and $semester != '')
       {

        $sql = "SELECT *
        FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 1 and a.payment_mode = 'online')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`= t1.`session`
        WHERE t1.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2 where t2.course_id = '".$course."') group by t1.admn_no";
        $query = $this->db->query($sql);

       }

       elseif ($course != '') {


        $sql = "SELECT *
        FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 1 and a.payment_mode = 'online')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`= t1.`session`
        WHERE t1.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2 where t2.course_id = '".$course."') group by t1.admn_no";
        //$query = $this->db->query($sql);
        $query = $this->db->query($sql);

       }

       elseif ($semester != '') {

        $sql = "SELECT *
        FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 1 and a.payment_mode = 'online')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`=t1.`session`
        WHERE t1.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2) group by t1.admn_no";
        $query = $this->db->query($sql);

       }

       else{

        $sql = "SELECT *
        FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 1 and a.payment_mode = 'online')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`=t1.`session`
        WHERE t1.admn_no IN (
        SELECT t2.admn_no
        FROM (
        SELECT *
        FROM reg_regular_form c
        WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2) group by t1.admn_no";
        $query = $this->db->query($sql);

       }

       //echo $this->db->last_query(); die();

        return $query->num_rows();

  }

  public function get_list_student_paid_offline($session_year,$session,$course,$semester)
  {

    if($course != '' and $semester != '')
    {
    $sql = "SELECT CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) AS student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,if(s.status != '',s.`status`,'Status Details Not Found') as status FROM bank_fee_details a
    INNER JOIN reg_regular_fee d ON d.admn_no = a.admn_no and d.fee_amt != '0.00'
    INNER JOIN reg_regular_form f ON f.form_id = d.form_id and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1'
    INNER JOIN user_details q ON q.id = a.admn_no
    INNER JOIN users s ON s.id = q.id
    WHERE a.payment_mode='offline' AND a.session_year='".$session_year."' AND a.`session`='".$session."' AND a.admn_no IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2 where t2.course_id = '".$course."') group by a.admn_no";
    $query = $this->db->query($sql);
    }

    elseif ($course != '') {

      $sql = "SELECT CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) AS student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,if(s.status != '',s.`status`,'Status Details Not Found') as status FROM bank_fee_details a
    INNER JOIN reg_regular_fee d ON d.admn_no = a.admn_no and d.fee_amt != '0.00'
    INNER JOIN reg_regular_form f ON f.form_id = d.form_id and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1'
    INNER JOIN user_details q ON q.id = a.admn_no
    INNER JOIN users s ON s.id = q.id
    WHERE a.payment_mode='offline' AND a.session_year='".$session_year."' AND a.`session`='".$session."' AND a.admn_no IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2 where t2.course_id = '".$course."') group by a.admn_no";
    $query = $this->db->query($sql);
      # code...
    }

    elseif($semester != '')
    {

    $sql = "SELECT CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) AS student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,if(s.status != '',s.`status`,'Status Details Not Found') as status FROM bank_fee_details a
    INNER JOIN reg_regular_fee d ON d.admn_no = a.admn_no and d.fee_amt != '0.00'
    INNER JOIN reg_regular_form f ON f.form_id = d.form_id and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1'
    INNER JOIN user_details q ON q.id = a.admn_no
    INNER JOIN users s ON s.id = q.id
    WHERE a.payment_mode='offline' AND a.session_year='".$session_year."' AND a.`session`='".$session."' AND a.admn_no IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2) group by a.admn_no";
    $query = $this->db->query($sql);

    }

    else {

    $sql = "SELECT CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) AS student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,if(s.status != '',s.`status`,'Status Details Not Found') as status FROM bank_fee_details a
    INNER JOIN reg_regular_fee d ON d.admn_no = a.admn_no and d.fee_amt != '0.00'
    INNER JOIN reg_regular_form f ON f.form_id = d.form_id and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1'
    INNER JOIN user_details q ON q.id = a.admn_no
    INNER JOIN users s ON s.id = q.id
    WHERE a.payment_mode='offline' AND a.session_year='".$session_year."' AND a.`session`='".$session."' AND a.admn_no IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2) group by a.admn_no";
    $query = $this->db->query($sql);

    }

    //echo $this->db->last_query(); die();

    if ($query !== false) {
    return $query->result_array();
    }

    else {

      return false;

    }


  }

  // offline not registered yet
  public function get_list_student_paid_offline_not_registered($session_year,$session,$course,$semester)
  {

    // condition when both semester and course is selected

    if ($course != '' and $semester != '') {


    $sql = "SELECT CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) AS student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,if(s.status != '',s.`status`,'Status Details Not Found') as status FROM bank_fee_details a
    INNER JOIN reg_regular_fee d ON d.admn_no = a.admn_no and d.fee_amt != '0.00'
    INNER JOIN reg_regular_form f ON f.form_id = d.form_id and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1' and f.course_id = '".$course."' and f.semester = '".$semester."'
    INNER JOIN user_details q ON q.id = a.admn_no
    INNER JOIN users s ON s.id = q.id
    WHERE a.payment_mode='offline' AND a.session_year='".$session_year."' AND a.`session`='".$session."' AND f.hod_status = '1' AND f.acad_status = '1' and a.admn_no NOT IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2 where t2.course_id = '".$course."') group by a.admn_no";
    $query = $this->db->query($sql);

    }

    // condition when both semester and course is selected

    elseif ($semester != '') {

    // condition when only sem is selected

    $sql = "SELECT CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) AS student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,if(s.status != '',s.`status`,'Status Details Not Found') as status FROM bank_fee_details a
    INNER JOIN reg_regular_fee d ON d.admn_no = a.admn_no and d.fee_amt != '0.00'
    INNER JOIN reg_regular_form f ON f.form_id = d.form_id and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1' and f.semester='".$semester."'
    INNER JOIN user_details q ON q.id = a.admn_no
    INNER JOIN users s ON s.id = q.id
    WHERE a.payment_mode='offline' AND a.session_year='".$session_year."' AND a.`session`='".$session."' AND f.hod_status = '1' AND f.acad_status = '1' and a.admn_no NOT IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2) group by a.admn_no";
    $query = $this->db->query($sql);

    }

    // condition when only sem is selected


    elseif ($course != '') {



    // condition when only course is selected

    $sql = "SELECT CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) AS student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,if(s.status != '',s.`status`,'Status Details Not Found') as status FROM bank_fee_details a
    INNER JOIN reg_regular_fee d ON d.admn_no = a.admn_no and d.fee_amt != '0.00'
    INNER JOIN reg_regular_form f ON f.form_id = d.form_id and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1' and f.course_id='".$course."'
    INNER JOIN user_details q ON q.id = a.admn_no
    INNER JOIN users s ON s.id = q.id
    WHERE a.payment_mode='offline' AND a.session_year='".$session_year."' AND a.`session`='".$session."' AND f.hod_status = '1' AND f.acad_status = '1' and a.admn_no NOT IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2 where t2.course_id = '".$course."') group by a.admn_no";
    $query = $this->db->query($sql);

    }

    // condition when only course is selected

    else {


    // condition when neither of them selected

    $sql = "SELECT CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) AS student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id,if(s.status != '',s.`status`,'Status Details Not Found') as status FROM bank_fee_details a
    INNER JOIN reg_regular_fee d ON d.admn_no = a.admn_no and d.fee_amt != '0.00'
    INNER JOIN reg_regular_form f ON f.form_id = d.form_id and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1'
    INNER JOIN user_details q ON q.id = a.admn_no
    INNER JOIN users s ON s.id = q.id
    WHERE a.payment_mode='offline' AND a.session_year='".$session_year."' AND a.`session`='".$session."' AND f.hod_status = '1' AND f.acad_status = '1' and a.admn_no NOT IN (SELECT c.admn_no
    FROM reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1') group by a.admn_no";
    $query = $this->db->query($sql);

    }


    // condition when neither of them selected



    //echo $this->db->last_query(); die();
    if ($query !== false) {
    return $query->result_array();
    }

    else {

      return false;

    }


  }

  public function total_bank_fee_payment_details_offline($session_year,$session,$course,$semester)
  {

    //    $query = $this->db->select('*')
    //                 ->from('bank_fee_details')
    //                 ->where('payment_status','1')
    //                 ->where('payment_mode','offline')
    //                 ->where('session_year',$session_year)
    //                 ->where('session',$session)
    //                 ->get();

    //     return $query->num_rows();



        if($course != '' and $semester != '')
        {

         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 1 and a.payment_mode = 'offline')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`= t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2 where t2.course_id = '".$course."') group by t1.admn_no";
         $query = $this->db->query($sql);

        }

        elseif ($course != '') {


         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 1 and a.payment_mode = 'offline')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`= t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2 where t2.course_id = '".$course."') group by t1.admn_no";
         //$query = $this->db->query($sql);
         $query = $this->db->query($sql);

        }

        elseif ($semester != '') {

         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 1 and a.payment_mode = 'offline')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`=t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2) group by t1.admn_no";
         $query = $this->db->query($sql);

        }

        else{

         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 1 and a.payment_mode = 'offline')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`=t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2) group by t1.admn_no";
         $query = $this->db->query($sql);

        }

         return $query->num_rows();

  }


  public function get_list_student_not_paid($session_year,$session,$course,$semester)
  {

    // $sql = "SELECT a.student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category
    // FROM bank_fee_details a
    // WHERE a.session_year = '".$session_year."' AND a.`session`= '".$session."' AND a.payment_status='0' AND a.admn_no IN (
    // SELECT b.admn_no
    // FROM bank_fee_details b
    // INNER JOIN reg_regular_form c ON b.admn_no = c.admn_no and b.session_year = c.session_year and b.session = c.session
    // WHERE c.session_year='".$session_year."' AND c.`session`='".$session."' AND c.acad_status='1' AND c.hod_status='1') group by a.admn_no";

    if($course != '' and $semester != '')

    {

    $sql = "SELECT a.student_name, a.admn_no, a.email_id, a.session_year,a.session,a.course_id,a.branch_id,a.total_amount,a.category,if(d.form_id != '',d.form_id,'Registration Details Not Found') AS form_id,if(d.acad_status != '',d.acad_status,'Acad Status Not Found') AS acad_status,if(d.hod_status != '',d.hod_status,'Hod Status Not Found') AS hod_status,if(z.status != '',z.`status`,'Status Details Not Found') as status
            FROM bank_fee_details a
            left JOIN users z ON z.id = a.admn_no
            LEFT JOIN reg_regular_form d ON a.admn_no=d.admn_no AND a.session_year = d.session_year AND a.`session` = d.`session` and d.hod_status='1' and d.acad_status='1'
            WHERE a.session_year = '".$session_year."' AND a.`session`= '".$session."' AND a.payment_status='0' AND a.admn_no IN (
            SELECT b.admn_no
            FROM bank_fee_details b
            INNER JOIN reg_regular_form c ON b.admn_no = c.admn_no AND b.session_year = c.session_year AND b.session = c.session
            WHERE c.session_year='".$session_year."' AND c.`session`='".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."' and c.course_id = '".$course."')
            GROUP BY a.admn_no";


            $query = $this->db->query($sql);


            }

            elseif($course != ''){

            $sql = "SELECT a.student_name, a.admn_no, a.email_id, a.session_year,a.session,a.course_id,a.branch_id,a.total_amount,a.category,if(d.form_id != '',d.form_id,'Registration Details Not Found') AS form_id,if(d.acad_status != '',d.acad_status,'Acad Status Not Found') AS acad_status,if(d.hod_status != '',d.hod_status,'Hod Status Not Found') AS hod_status,if(z.status != '',z.`status`,'Status Details Not Found') as status
            FROM bank_fee_details a
            left JOIN users z ON z.id = a.admn_no
            LEFT JOIN reg_regular_form d ON a.admn_no=d.admn_no AND a.session_year = d.session_year AND a.`session` = d.`session` and d.hod_status='1' and d.acad_status='1'
            WHERE a.session_year = '".$session_year."' AND a.`session`= '".$session."' AND a.payment_status='0' AND a.admn_no IN (
            SELECT b.admn_no
            FROM bank_fee_details b
            INNER JOIN reg_regular_form c ON b.admn_no = c.admn_no AND b.session_year = c.session_year AND b.session = c.session
            WHERE c.session_year='".$session_year."' AND c.`session`='".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.course_id = '".$course."')
            GROUP BY a.admn_no";

            $query = $this->db->query($sql);


            }

            elseif($semester != '') {

            $sql = "SELECT a.student_name, a.admn_no, a.email_id, a.session_year,a.session,a.course_id,a.branch_id,a.total_amount,a.category,if(d.form_id != '',d.form_id,'Registration Details Not Found') AS form_id,if(d.acad_status != '',d.acad_status,'Acad Status Not Found') AS acad_status,if(d.hod_status != '',d.hod_status,'Hod Status Not Found') AS hod_status,if(z.status != '',z.`status`,'Status Details Not Found') as status
            FROM bank_fee_details a
            left JOIN users z ON z.id = a.admn_no
            LEFT JOIN reg_regular_form d ON a.admn_no=d.admn_no AND a.session_year = d.session_year AND a.`session` = d.`session` and d.hod_status='1' and d.acad_status='1'
            WHERE a.session_year = '".$session_year."' AND a.`session`= '".$session."' AND a.payment_status='0' AND a.admn_no IN (
            SELECT b.admn_no
            FROM bank_fee_details b
            INNER JOIN reg_regular_form c ON b.admn_no = c.admn_no AND b.session_year = c.session_year AND b.session = c.session
            WHERE c.session_year='".$session_year."' AND c.`session`='".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')
            GROUP BY a.admn_no";

            $query = $this->db->query($sql);
              # code...
            }

            else{

            $sql = "SELECT a.student_name, a.admn_no, a.email_id, a.session_year,a.session,a.course_id,a.branch_id,a.total_amount,a.category,if(d.form_id != '',d.form_id,'Registration Details Not Found') AS form_id,if(d.acad_status != '',d.acad_status,'Acad Status Not Found') AS acad_status,if(d.hod_status != '',d.hod_status,'Hod Status Not Found') AS hod_status,if(z.status != '',z.`status`,'Status Details Not Found') as status
            FROM bank_fee_details a
            left JOIN users z ON z.id = a.admn_no
            LEFT JOIN reg_regular_form d ON a.admn_no=d.admn_no AND a.session_year = d.session_year AND a.`session` = d.`session` and d.hod_status='1' and d.acad_status='1'
            WHERE a.session_year = '".$session_year."' AND a.`session`= '".$session."' AND a.payment_status='0' AND a.admn_no IN (
            SELECT b.admn_no
            FROM bank_fee_details b
            INNER JOIN reg_regular_form c ON b.admn_no = c.admn_no AND b.session_year = c.session_year AND b.session = c.session
            WHERE c.session_year='".$session_year."' AND c.`session`='".$session."' AND c.acad_status='1' AND c.hod_status='1')
            GROUP BY a.admn_no";


          $query = $this->db->query($sql);


            }

            //echo $this->db->last_query(); //exit;
            if ($query !== false) {
            return $query->result_array();

          }

          else {

            return false;

          }

  }

  public function total_bank_fee_payment_details_not_paid($session_year,$session,$course,$semester)
  {

    //    $query = $this->db->select('*')
    //                 ->from('bank_fee_details')
    //                 ->where('payment_status','0')
    //                 ->where('session_year',$session_year)
    //                 ->where('session',$session)
    //                 ->get();

    //     return $query->num_rows();


        if($course != '' and $semester != '')
        {

         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 0)t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`= t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2 where t2.course_id = '".$course."')";
         $query = $this->db->query($sql);

        }

        elseif ($course != '') {


         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 0)t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`= t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2 where t2.course_id = '".$course."')";
         //$query = $this->db->query($sql);
         $query = $this->db->query($sql);

        }

        elseif ($semester != '') {

         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 0)t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`=t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2)";
         $query = $this->db->query($sql);

        }

        else{

         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 0)t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`=t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2)";
         $query = $this->db->query($sql);

        }

         return $query->num_rows();

  }

  public function get_list_student_not_paid_not_registered($session_year,$session,$course,$semester)
  {


    if($course != '' and $semester != ''){

    $sql = "SELECT a.student_name, a.admn_no, a.email_id, a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(d.form_id != '',d.form_id,'Registration Details Not Found') AS form_id,if(d.acad_status != '',d.acad_status,'Acad Status Not Found') AS acad_status,if(d.hod_status != '',d.hod_status,'Hod Status Not Found') AS hod_status,d.acad_remark,if(z.status != '',z.`status`,'Status Details Not Found') as status
            FROM bank_fee_details a
            left JOIN users z ON z.id = a.admn_no
            LEFT JOIN reg_regular_form d ON a.admn_no=d.admn_no AND a.session_year = d.session_year AND a.`session` = d.`session` and d.hod_status='1' and d.acad_status='1' and d.course_id = '".$course."' and d.semester = '".$semester."'
            WHERE a.session_year = '".$session_year."' AND a.`session`= '".$session."' AND a.payment_status='0' AND a.admn_no NOT IN (
            SELECT b.admn_no
            FROM bank_fee_details b
            INNER JOIN reg_regular_form c ON b.admn_no = c.admn_no AND b.session_year = c.session_year AND b.session = c.session
            WHERE c.session_year='".$session_year."' AND c.`session`='".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."' and c.course_id = '".$course."')
            GROUP BY a.admn_no";



              $query = $this->db->query($sql);

            }

            elseif($course != ''){


              $sql = "SELECT a.student_name, a.admn_no, a.email_id, a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(d.form_id != '',d.form_id,'Registration Details Not Found') AS form_id,if(d.acad_status != '',d.acad_status,'Acad Status Not Found') AS acad_status,if(d.hod_status != '',d.hod_status,'Hod Status Not Found') AS hod_status,d.acad_remark,if(z.status != '',z.`status`,'Status Details Not Found') as status
              FROM bank_fee_details a
              left JOIN users z ON z.id = a.admn_no
              LEFT JOIN reg_regular_form d ON a.admn_no=d.admn_no AND a.session_year = d.session_year AND a.`session` = d.`session` and d.hod_status='1' and d.acad_status='1' and d.course_id = '".$course."'
              WHERE a.session_year = '".$session_year."' AND a.`session`= '".$session."' AND a.payment_status='0' AND a.admn_no NOT IN (
              SELECT b.admn_no
              FROM bank_fee_details b
              INNER JOIN reg_regular_form c ON b.admn_no = c.admn_no AND b.session_year = c.session_year AND b.session = c.session
              WHERE c.session_year='".$session_year."' AND c.`session`='".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.course_id = '".$course."')
              GROUP BY a.admn_no";




                $query = $this->db->query($sql);


            }

            elseif ($semester != '') {

              $sql = "SELECT a.student_name, a.admn_no, a.email_id, a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(d.form_id != '',d.form_id,'Registration Details Not Found') AS form_id,if(d.acad_status != '',d.acad_status,'Acad Status Not Found') AS acad_status,if(d.hod_status != '',d.hod_status,'Hod Status Not Found') AS hod_status,d.acad_remark,if(z.status != '',z.`status`,'Status Details Not Found') as status
              FROM bank_fee_details a
              left JOIN users z ON z.id = a.admn_no
              LEFT JOIN reg_regular_form d ON a.admn_no=d.admn_no AND a.session_year = d.session_year AND a.`session` = d.`session` and d.hod_status='1' and d.acad_status='1' and d.semester = '".$semester."'
              WHERE a.session_year = '".$session_year."' AND a.`session`= '".$session."' AND a.payment_status='0' AND a.admn_no NOT IN (
              SELECT b.admn_no
              FROM bank_fee_details b
              INNER JOIN reg_regular_form c ON b.admn_no = c.admn_no AND b.session_year = c.session_year AND b.session = c.session
              WHERE c.session_year='".$session_year."' AND c.`session`='".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')
              GROUP BY a.admn_no";




                $query = $this->db->query($sql);
              # code...
            }

            else{

              $sql = "SELECT a.student_name, a.admn_no, a.email_id, a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(d.form_id != '',d.form_id,'Registration Details Not Found') AS form_id,if(d.acad_status != '',d.acad_status,'Acad Status Not Found') AS acad_status,if(d.hod_status != '',d.hod_status,'Hod Status Not Found') AS hod_status,d.acad_remark,if(z.status != '',z.`status`,'Status Details Not Found') as status
              FROM bank_fee_details a
              left JOIN users z ON z.id = a.admn_no
              LEFT JOIN reg_regular_form d ON a.admn_no=d.admn_no AND a.session_year = d.session_year AND a.`session` = d.`session` and d.hod_status='1' and d.acad_status='1'
              WHERE a.session_year = '".$session_year."' AND a.`session`= '".$session."' AND a.payment_status='0' AND a.admn_no NOT IN (
              SELECT b.admn_no
              FROM bank_fee_details b
              INNER JOIN reg_regular_form c ON b.admn_no = c.admn_no AND b.session_year = c.session_year AND b.session = c.session
              WHERE c.session_year='".$session_year."' AND c.`session`='".$session."' AND c.acad_status='1' AND c.hod_status='1')
              GROUP BY a.admn_no";


      // $sql = "SELECT CONCAT_WS(' ',q.first_name,q.middle_name,q.last_name) AS student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.branch_id,a.category,f.form_id,d.fee_amt,d.fee_date,d.transaction_id FROM bank_fee_details a
      // INNER JOIN reg_regular_fee d ON d.admn_no = a.admn_no
      // INNER JOIN reg_regular_form f ON f.form_id = d.form_id and a.session_year = f.session_year and a.session = f.session
      // INNER JOIN user_details q ON q.id = f.admn_no
      // WHERE a.payment_status=0 AND a.session_year='".$session_year."' AND a.`session`='".$session."' AND f.hod_status = '1' AND f.acad_status = '1' and a.admn_no NOT IN (SELECT c.admn_no
      // FROM reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1') group by a.admn_no";


                $query = $this->db->query($sql);



            }
    //echo $this->db->last_query(); exit;
    if ($query !== false) {
    return $query->result_array();

  }

  else {

    return false;

  }

  }

  public function total_bank_fee_payment_details_failed($session_year,$session,$course,$semester)
  {

    //    $query = $this->db->select('*')
    //                 ->from('bank_fee_details')
    //                 ->where('payment_status','2')
    //                 ->where('payment_mode','online')
    //                 ->where('session_year',$session_year)
    //                 ->where('session',$session)
    //                 ->get();

    //     return $query->num_rows();


        if($course != '' and $semester != '')
        {

         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 2 and a.payment_mode = 'online')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`= t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2 where t2.course_id = '".$course."')";
         $query = $this->db->query($sql);

        }

        elseif ($course != '') {


         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 2 and a.payment_mode = 'online')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`= t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2 where t2.course_id = '".$course."')";
         //$query = $this->db->query($sql);
         $query = $this->db->query($sql);

        }

        elseif ($semester != '') {

         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 2 and a.payment_mode = 'online')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`=t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2)";
         $query = $this->db->query($sql);

        }

        else{

         $sql = "SELECT *
         FROM (select * from bank_fee_details a where a.session_year='".$session_year."' and a.session = '".$session."' and a.payment_status = 2 and a.payment_mode = 'online')t1 INNER JOIN reg_regular_form b ON t1.admn_no = b.admn_no AND b.session_year=t1.session_year AND b.`session`=t1.`session`
         WHERE t1.admn_no IN (
         SELECT t2.admn_no
         FROM (
         SELECT *
         FROM reg_regular_form c
         WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2)";
         $query = $this->db->query($sql);

        }

         return $query->num_rows();

  }


  public function get_list_student_paid_online_failure($session_year,$session,$course,$semester)
  {

    if ($course != '' and $semester != '') {


    $sql = "SELECT a.student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(f.form_id != '',f.form_id,'Registration Details not Found') as form_id,if(s.status != '',s.`status`,'Status Details Not Found') as status
    FROM bank_fee_details a
    LEFT JOIN reg_regular_form f ON f.admn_no = a.admn_no and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1'
    INNER JOIN users s ON s.id = a.admn_no
    WHERE a.payment_mode='online' and a.payment_status='2' AND a.session_year='".$session_year."' AND a.`session`='".$session."' and a.admn_no IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2 where t2.course_id = '".$course."') group by a.admn_no";
    $query = $this->db->query($sql);

    }

    elseif ($course != '') {


      $sql = "SELECT a.student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(f.form_id != '',f.form_id,'Registration Details not Found') as form_id,if(s.status != '',s.`status`,'Status Details Not Found') as status
    FROM bank_fee_details a
    LEFT JOIN reg_regular_form f ON f.admn_no = a.admn_no and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1'
    INNER JOIN users s ON s.id = a.admn_no
    WHERE a.payment_mode='online' and a.payment_status='2' AND a.session_year='".$session_year."' AND a.`session`='".$session."' and a.admn_no IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2 where t2.course_id = '".$course_id."') group by a.admn_no";
    $query = $this->db->query($sql);



    }

    elseif ($semester != '') {

      $sql = "SELECT a.student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(f.form_id != '',f.form_id,'Registration Details not Found') as form_id,if(s.status != '',s.`status`,'Status Details Not Found') as status
    FROM bank_fee_details a
    LEFT JOIN reg_regular_form f ON f.admn_no = a.admn_no and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1'
    INNER JOIN users s ON s.id = a.admn_no
    WHERE a.payment_mode='online' and a.payment_status='2' AND a.session_year='".$session_year."' AND a.`session`='".$session."' and a.admn_no IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2) group by a.admn_no";
    $query = $this->db->query($sql);


    }

    else {


      $sql = "SELECT a.student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(f.form_id != '',f.form_id,'Registration Details not Found') as form_id,if(s.status != '',s.`status`,'Status Details Not Found') as status
    FROM bank_fee_details a
    LEFT JOIN reg_regular_form f ON f.admn_no = a.admn_no and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status='1'
    INNER JOIN users s ON s.id = a.admn_no
    WHERE a.payment_mode='online' and a.payment_status='2' AND a.session_year='".$session_year."' AND a.`session`='".$session."' and a.admn_no IN (SELECT c.admn_no
    FROM reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1') group by a.admn_no";
    $query = $this->db->query($sql);




    }


    if ($query !== false) {
    return $query->result_array();
  }

  else {

    return false;

  }

  }


  public function get_list_student_paid_online_failure_not_registered($session_year,$session,$course,$semester)
  {

    if ($course != '' and $semester != '') {


    $sql = "SELECT a.student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(f.form_id != '',f.form_id,'Registration Details not Found') as form_id,if(s.status != '',s.`status`,'Status Details Not Found') as status
    FROM bank_fee_details a
    #INNER JOIN users z ON z.id = a.admn_no and z.status IN ('A','P')
    LEFT JOIN reg_regular_form f ON f.admn_no = a.admn_no and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status = '1' and f.semester = '".$semester."' and f.course_id = '".$course."'
    left JOIN users s ON s.id = a.admn_no
    WHERE a.payment_mode='online' and a.payment_status='2' AND a.session_year='".$session_year."' AND a.`session`='".$session."' and a.admn_no NOT IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2 where t2.course_id = '".$course."') group by a.admn_no";
    $query = $this->db->query($sql);

    }

    elseif ($course != '') {

    $sql = "SELECT a.student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(f.form_id != '',f.form_id,'Registration Details not Found') as form_id,if(s.status != '',s.`status`,'Status Details Not Found') as status
    FROM bank_fee_details a
    #INNER JOIN users z ON z.id = a.admn_no and z.status IN ('A','P')
    LEFT JOIN reg_regular_form f ON f.admn_no = a.admn_no and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status = '1' and f.course_id = '".$course."'
    left JOIN users s ON s.id = a.admn_no
    WHERE a.payment_mode='online' and a.payment_status='2' AND a.session_year='".$session_year."' AND a.`session`='".$session."' and a.admn_no NOT IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1')t2 where t2.course_id = '".$course."') group by a.admn_no";
    $query = $this->db->query($sql);

    }

    elseif ($semester != '') {

    $sql = "SELECT a.student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(f.form_id != '',f.form_id,'Registration Details not Found') as form_id,if(s.status != '',s.`status`,'Status Details Not Found') as status
    FROM bank_fee_details a
    #INNER JOIN users z ON z.id = a.admn_no and z.status IN ('A','P')
    LEFT JOIN reg_regular_form f ON f.admn_no = a.admn_no and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status = '1' and f.semester = '".$semester."'
    left JOIN users s ON s.id = a.admn_no
    WHERE a.payment_mode='online' and a.payment_status='2' AND a.session_year='".$session_year."' AND a.`session`='".$session."' and a.admn_no NOT IN (SELECT t2.admn_no
    FROM (select * from reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1' and c.semester = '".$semester."')t2) group by a.admn_no";
    $query = $this->db->query($sql);

    }

    else {

    $sql = "SELECT a.student_name , a.admn_no , a.email_id , a.session_year,a.session,a.course_id,a.total_amount,a.branch_id,a.category,if(f.form_id != '',f.form_id,'Registration Details not Found') as form_id,if(s.status != '',s.`status`,'Status Details Not Found') as status
    FROM bank_fee_details a
    #INNER JOIN users z ON z.id = a.admn_no and z.status IN ('A','P')
    LEFT JOIN reg_regular_form f ON f.admn_no = a.admn_no and a.session_year = f.session_year and a.session = f.session and f.hod_status='1' and f.acad_status = '1'
    left JOIN users s ON s.id = a.admn_no
    WHERE a.payment_mode='online' and a.payment_status='2' AND a.session_year='".$session_year."' AND a.`session`='".$session."' and a.admn_no NOT IN (SELECT c.admn_no
    FROM reg_regular_form c WHERE c.session_year = '".$session_year."' AND c.`session` = '".$session."' AND c.acad_status='1' AND c.hod_status='1') group by a.admn_no";
    $query = $this->db->query($sql);

    }

    //echo $this->db->last_query(); die();

    if ($query !== false) {
    return $query->result_array();
  }

  else {

    return false;

  }

  }

  //public function


  public function get_course_name($couse_id)
  {

      $sql = "select * from cs_courses where id = ?";
      $query = $this->db->query($sql,array($couse_id));
      $course_array = $query->result_array();
      return $course_array['0']['name'];

  }

  public function get_branch_name($branch_id)
  {

    $sql = "select * from cs_branches where id = ?";
    $query = $this->db->query($sql,array($branch_id));
    $course_array = $query->result_array();
    return $course_array['0']['name'];

  }



  public function semester_fee_upload_with_sink($session_year_select,$session_select)
    {
        if ($session_year_select != '' && $session_select != '') {

          $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details')
                           ->from('semester_fee_offline_upload_details')
                           ->where('sink_details','sink_done')
                           ->where('session_year',$session_year_select)
                           ->where('session',$session_select)
                           ->get();
        }

        elseif ($session_year_select != '') {

          $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details')
          ->from('semester_fee_offline_upload_details')
          ->where('sink_details','sink_done')
          ->where('session_year',$session_year_select)
          ->get();
        }
        elseif ($session_select != '') {

          $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details')
          ->from('semester_fee_offline_upload_details')
          ->where('sink_details','sink_done')
          ->where('session',$session_select)
          ->get();
        }
        else {

          $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details')
          ->from('semester_fee_offline_upload_details')
          ->where('sink_details','sink_done')
          ->get();
        }

                           return $query->result_array();
    }

    public function semester_fee_upload_without_sink($session_year_select,$session_select)
    {

      if ($session_year_select != '' && $session_select != '') {

        $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details,reason_for_pending')
                           ->from('semester_fee_offline_upload_details')
                           ->where('sink_details','')
                           ->where('session_year',$session_year_select)
                           ->where('session',$session_select)
                           ->get();

      }

      elseif ($session_year_select != '') {

        $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details,reason_for_pending')
                           ->from('semester_fee_offline_upload_details')
                           ->where('sink_details','')
                           ->where('session_year',$session_year_select)
                           ->get();

      }

      elseif ($session_select != '') {

        $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details,reason_for_pending')
                          ->from('semester_fee_offline_upload_details')
                          ->where('sink_details','')
                          ->where('session',$session_select)
                          ->get();
      }

      else {

          $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details,reason_for_pending')
                          ->from('semester_fee_offline_upload_details')
                          ->where('sink_details','')
                          ->get();
      }

                           //echo $this->db->last_query(); die();

                           return $query->result_array();
    }


}



    ?>