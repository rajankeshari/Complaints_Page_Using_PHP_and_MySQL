<?php

class Semester_fee_upload_model extends CI_Model{

        private $nfrlive='nfrlive';
        private $nfrbeta='nfrbeta';
        private $parentbeta='parentlive';
        //nfrbeta';

       

	function __construct(){
		parent::__construct();
    }


    public function get_last_updated_order_date(){


    $sql = "SELECT created_date
    FROM  semester_fee_offline_upload_details
    group BY created_date ORDER BY STR_TO_DATE(created_date,'%d-%m-%y') DESC LIMIT 1";

// $sql = "SELECT order_booking_date_time
// FROM  sbi_full_transaction
// group BY order_booking_date_time ORDER BY STR_TO_DATE(order_booking_date_time,'%d-%m-%y') DESC LIMIT 1";

    $query = $this->db->query($sql);
    if ($query != false) {
       
        $settle_date = $query->result_array();
        return $settle_date['0']['created_date'];

    }

    else {
        
        return false;
    }
    



    }


    public function get_filter_uploaded_csv_by_ses_year_ses($session_year,$session)   
    {

        $query = $this->db->select('*')
                          ->from('semester_fee_offline_upload_details')
                          ->where('session_year',$session_year) 
                          ->where('session',$session)
                          ->get();

        return $query->result_array();

    }


    public function delete_uploaded_csv_data_by_id($id)
    {
        // $query = $this->db->select('*')
        //                   ->from('semester_fee_offline_upload_details')
        //                   ->where('id',$id)
        //                   ->get();

        // return $query->result_array();

        $this->db->where('id', $id);
        $this->db->delete('semester_fee_offline_upload_details');

    }


    public function get_session_year()
    {

        $query = $this->db->select('session_year')
                      ->from('mis_session_year')
                      ->order_by('id','desc')
                      ->get();

                      return $query->result_array();

    }

    public function get_session()
    {

        $query = $this->db->select('session')
                      ->from('mis_session')
                      ->order_by('id','desc')
                      ->get();

                      return $query->result_array();

    }


    public function get_course_name($course)
    {

        $query = $this->db->select('name')
                 ->from('cbcs_courses')
                 ->where('id',$course)
                 ->get();


        $course_array = $query->result_array();

        return $course_array[0]['name'];


    }


    public function get_branch_name($branch)
    {

        $query = $this->db->select('name')
                 ->from('cbcs_branches')
                 ->where('id',$branch)
                 ->get();


        $course_array = $query->result_array();

        return $course_array[0]['name'];


    }

    public function get_student_email($admn_no)
        {

            $sql = "select domain_name from emaildata where admission_no = ?";
            $query = $this->db->query($sql,array($admn_no));
            $email_array = $query->result_array();
            return $email_array[0]['domain_name'];


        }


        public function update_reason_for_pending($id,$error_insert_db)
        {

            $data = array(

                'reason_for_pending' => $error_insert_db
            );

            $this->db->where('id',$id)
                     ->update('semester_fee_offline_upload_details',$data);

        }


    function get_student_details($id){


                
        $sql = "SELECT UPPER(a.id) AS admn_no,b.enrollment_year, UPPER(CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)) AS stu_name,
        CASE b.course_id WHEN  'exemtech' THEN b.course_id /*'M.TECH 3 YR'*/ ELSE b.course_id END as course_id,b.branch_id,c.name, 
        UPPER(CONCAT(CASE b.course_id WHEN  'exemtech' THEN 'M.TECH 3 YR' ELSE b.course_id END,' ( ',c.name,' ) ')) AS discipline,
        a.photopath
        , d.name as cname,c.name as bname
        FROM user_details a
        INNER JOIN stu_academic b ON b.admn_no=a.id
        inner join cs_courses d on d.id=b.course_id
        INNER JOIN cs_branches c ON c.id=b.branch_id
        WHERE a.id=?";


        
//         $sql = "SELECT UPPER(a.id) AS admn_no, UPPER(CONCAT_WS(' ',a.first_name,a.middle_name,a.last_name)) AS stu_name,
// CASE b.course_id WHEN  'exemtech' THEN b.course_id /*'M.TECH 3 YR'*/ ELSE b.course_id END as course_id,b.branch_id,c.name, UPPER(CONCAT(CASE b.course_id WHEN  'exemtech' THEN 'M.TECH 3 YR' ELSE b.course_id END,' ( ',c.name,' ) ')) AS discipline,a.photopath
// FROM user_details a
// INNER JOIN stu_academic b ON b.admn_no=a.id
// INNER JOIN cs_branches c ON c.id=b.branch_id
// WHERE a.id=?";

        $query = $this->db->query($sql,array($id));
        //echo $this->db->last_query();die();
        if ($this->db->affected_rows() >= 0) {
            return $query->row();
        } else {
            return false;
        }

    }


    public function semester_fee_upload()
    {
        $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year')
                           ->from('semester_fee_offline_upload_details')
                           ->get(); 

                           return $query->result_array();
    }

    public function semester_fee_upload_with_sink()
    {
        $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details')
                           ->from('semester_fee_offline_upload_details')
                           ->where('sink_details','sink_done')
                           ->get(); 

                           return $query->result_array();
    }

    public function semester_fee_upload_without_sink()
    {
        $query = $this->db->select('id,category_name,payment_mode,transaction_id,transaction_date,amount,student_name,admn_no,session,session_year,sink_details,reason_for_pending')
                           ->from('semester_fee_offline_upload_details')
                           ->where('sink_details','')
                           ->get(); 

                           //echo $this->db->last_query(); die();

                           return $query->result_array();
    }

    public function get_regular_form_id($admn_no,$session_year,$session)
    {

        $sql = "select form_id from reg_regular_form where admn_no = ? and session_year = ? and session = ? and hod_status = ? and acad_status = ? order by form_id desc limit 1";
        //SELECT * FROM reg_regular_form WHERE session_year = '2020-2021' AND SESSION = 'Monsoon' AND admn_no = '18je0029' AND hod_status = '1' AND acad_status = '1'
        $query = $this->db->query($sql,array($admn_no,$session_year,$session,'1','1'));

        $array_formid =  $query->result_array();
        if ($array_formid != '') {
       
        return $array_formid[0]['form_id'];
        }

        else {
            
            return false;
        }

    }

    public function update_reg_regular_fee($form_id,$admn_no,$data)
    {

        $this->db->where('form_id',$form_id)
                 ->where('admn_no',$admn_no)
                 ->update('reg_regular_fee',$data);

    }


    public function semester_fee_sink_with_reg_regular_fee($transaction_id,$date_of_payment,$amount,$admn_no,$session,$session_year)
    {

        $date_of_payment = date('Y-m-d',strtotime($date_of_payment));
        $form_id = $this->get_regular_form_id($admn_no,$session_year,$session); //exit;
        //$get_mis_reg_regular_id = $this->fee_payment_model->get_mis_reg_regular_id($admn_no,$form_id);

        //echo $form_id; exit;
       

        $data = array(

            "fee_amt" =>  $amount,
            "fee_date" => $date_of_payment,
            "transaction_id" => $transaction_id,
            "receipt_path" => ''
        );

        $this->update_reg_regular_fee($form_id,$admn_no,$data); // later to use the form_id and admn_no to store payment receipt

        return $form_id;

       

    }

    public function get_payment_details_reg_regular_form($form_id,$admn_no)
    {

        $sql = "select * from reg_regular_fee where form_id = ? and admn_no = ?";
        $query = $this->db->query($sql,array($form_id,$admn_no));
        return $query->result_array();


    }

    public function update_payment_receipt_mis_reg_fee_students($data_payment_receipt,$form_id,$admn_no){

        $this->db->where('admn_no',$admn_no)
                 ->where('form_id',$form_id)
                 ->update('reg_regular_fee',$data_payment_receipt);

    }

    public function update_payment_receipt_parent_students($data_payment_receipt,$parentid)
    {

        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->parentbeta, TRUE);
        $this->db2->where('id',$parentid)
                ->update('bank_fee_details',$data_payment_receipt);

    }


    public function update_payment_receipt_mis_students($data_payment_receipt,$get_mis_id)
    {

        // $CI = &get_instance();
        // $this->db2 = $CI->load->database($this->parentbeta, TRUE);
        $this->db->where('id',$get_mis_id)
                ->update('bank_fee_details',$data_payment_receipt);

    }


    public function update_payment_sink_details($data,$id)
    {

        $this->db->where('id',$id)
                 ->update('semester_fee_offline_upload_details',$data);

                 return 1;

    }





    public function get_student_details_pbeta($get_mis_id)
    {

        $sql = "select * from bank_fee_details where id = ?";
        $query = $this->db->query($sql,array($get_mis_id));
        //echo $this->db->last_query();
        return $query->result_array();

    }


    public function mis_pay_offline($data,$get_mis_id)
    {

        $query = $this->db->where('id',$get_mis_id)
                           ->update('bank_fee_details',$data);


    }


    public function get_mis_id($admn_no,$session_year,$session)
    {

        // $CI = &get_instance();
        // $this->db2 = $CI->load->database($this->misdev, TRUE);

        $sql = "select id from bank_fee_details where admn_no = ? and session_year = ? and session = ? order by id desc limit 1";
        $query = $this->db->query($sql,array($admn_no,$session_year,$session));
        //echo $this->db->last_query();
        $amount_array = $query->result_array();
        if (!empty($amount_array)) {
           
            return $amount_array['0']['id'];

        }
        else{

            return false;
        }
        


    }


    public function check_already_paid_mis($get_mis_id)
    {

        // $CI = &get_instance();
        // $this->db2 = $CI->load->database($this->misdev, TRUE);

        $sql = "select * from bank_fee_details where id = ? and verification_status = 1 and payment_status IN (0,2) order by id desc limit 1";
        $query = $this->db->query($sql,array($get_mis_id));
        //$amount_array = $query->result_array();
        //echo 'mis'.$query->num_rows();
        if ($query->num_rows() > 0) {
           
            return 1;

        }
        else{

            return 0;
        }
        


    }






    public function get_parent_id($admn_no,$session_year,$session)
    {

        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->parentbeta, TRUE);

        $sql = "select id from bank_fee_details where admn_no = ? and session_year = ? and session = ? order by id desc limit 1";
        $query = $this->db2->query($sql,array($admn_no,$session_year,$session));
        $amount_array = $query->result_array();
        if (!empty($amount_array)) {
           
            return $amount_array['0']['id'];

        }

        else {
            
            return false;
        }
        


    }


    public function check_already_paid_parent($parentid)
    {
        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->parentbeta, TRUE);

        $sql = "select * from bank_fee_details where id = ? and verification_status = 1 and payment_status IN (0,2) order by id desc limit 1";
        $query = $this->db2->query($sql,array($parentid));
        //echo $this->db2->last_query();
        //$amount_array = $query->result_array();

        //echo 'parent'.$query->num_rows(); 
        if ($query->num_rows() > 0) {
           
            return 1;

        }
        else{

            return 0;
        }
        


    }



    public function parent_update_details($data,$pbetaid)
    {

        // update pbeta 

        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->parentbeta, TRUE);

        $query = $this->db2->where('id',$pbetaid)
                           ->update('bank_fee_details',$data);
        


    }



    public function nfr_fee_sink_with_application($transaction_id)
    {

        $CI = &get_instance();
        $this->db2 = $CI->load->database($this->nfrbeta, TRUE);

        $query = $this->db2->select('app_no')
                          ->from('nfr_emp_application_test')
                          ->where('transaction_id',$transaction_id)
                          ->get();

         if($query->num_rows() > 0) {

              $application_no = $query->result_array();
              $data = array(

                'status' => 'success'

              );

              $this->db2->where('app_no',$application_no[0]['app_no'])
                        ->update('nfr_emp_application_test',$data);

                        $data = array(

                            'sink_details' => 'sink_done'
                        );

                        $this->db->where('transaction_id',$transaction_id)
                        ->update('nfr_admin_rec_fee_details',$data);

                        $this->db2->where('transaction_id',$transaction_id)
                        ->update('nfr_admin_rec_fee_details',$data);

                        return 1;
         }

         else {

            return 0;
         }


    }

    public function dynamic_entry_upload_fee($data)
    {

       $admission_no_error = 2; 

       $transaction_id = $data['transaction_id']; //exit;
       $admn_no = $data['admn_no'];
       $session_year = $data['session_year'];
       $session = $data['session'];

       $check_trans_already_exists = $this->check_trans_already_exists($transaction_id);

       $check_admn_no_already_exists = $this->check_admn_no_already_exists($admn_no,$session_year,$session);

       //$check_trans_already_exists_nfr = $this->check_trans_already_exists_nfr($transaction_id);

    //    if ($check_trans_already_exists >= 1 ) {
          
    //        //return 0;
    //        return array(
               
            
    //         'trans_exists' =>  $check_trans_already_exists,
    //         'admn_no' => '',
        
        
    //     );
              
    //    }

    //    if ($check_admn_no_already_exists >= 1 ) {
           
    //         //$count_admn_error = $admission_no_error++;
    //         //return 'admn_no_error'.$count_admn_error;
    //         return array($check_admn_no_already_exists);
           
    //    }

       if ($check_trans_already_exists >= 1 || $check_admn_no_already_exists >= 1) {
         
        return array(
            
           'trans_exists' => $check_trans_already_exists, 
            
           'admn_no_exists' =>  $check_admn_no_already_exists
        
        );
           
       }

       if ($check_trans_already_exists == 0 && $check_admn_no_already_exists == 0) {
           # code...
      
           
           $this->db->insert('semester_fee_offline_upload_details',$data);

        //    $CI = &get_instance();
        //    $this->db2 = $CI->load->database($this->nfrbeta, TRUE);


        //    $this->db2->insert('nfr_admin_rec_fee_details',$data);



           return 1;
       }

    }


    public function check_admn_no_already_exists($admn_no,$session_year,$session)
    {

        $query = $this->db->select('*')
                          ->from('semester_fee_offline_upload_details')
                          ->where('admn_no',$admn_no)
                          ->where('session',$session)
                          ->where('session_year',$session_year)
                          ->get();

                          //echo $this->db->last_query(); 

        if ($query->num_rows() > 0) {
            
            return 1;

        }

        else{

            return 0;
        }

        
    }

    public function check_trans_already_exists($transaction_id)
    {
        
             $query = $this->db->select('*')
                            ->from('semester_fee_offline_upload_details')
                            ->where('transaction_id',$transaction_id)
                            ->get();

                            return $query->num_rows();
        
    }


    // public function check_trans_already_exists_nfr($transaction_id)
    // {

    //     $CI = &get_instance();
    //     $this->db2 = $CI->load->database($this->nfrbeta, TRUE);
        
    //          $query = $this->db2->select('*')
    //                         ->from('nfr_admin_rec_fee_details')
    //                         ->where('transaction_id',$transaction_id)
    //                         ->get();

    //                         return $query->num_rows();
        
    // }


    public function get_semester_fee_details(){

        // $CI = &get_instance();
        // $this->db2 = $CI->load->database($this->nfrbeta, TRUE);

        $query = $this->db->select('*')
                          ->from('semester_fee_offline_upload_details')
                          //->where('status','Pending')
                          ->get();
        //echo $this->db->last_query();
        if ($query != '') {
            
            return $query->result_array();
        }

        else {
           
         return false;

        }

    }

    // public function get_all_sbi_transaction_details()
    // {

    //     $query = $this->db->select('*')
    //                       ->from('sbi_all_transactions')
    //                       ->where('status','sync_done')
    //                       ->get();
    //     //echo $this->db->last_query();
    //     return $query->result_array();

    // }

}
    

    ?>