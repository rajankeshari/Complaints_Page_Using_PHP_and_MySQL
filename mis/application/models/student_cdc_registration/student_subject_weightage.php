<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'controllers/exam_tabulation/exam_tabulation.php');
class Student_subject_weightage extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->addJS("attendance/show_subjects.js");
		$this->addJS("attendance/attendance.js");
    $this->load->model('student/stu_sub_weighage_model');
 		$this->load->model('attendance/student_attendance_model');
	}
	public function index()
	{
        $stu_id = $this->session->userdata('id');
				$data['stu_id']=$stu_id;
				$data['sessionYear']=$this->stu_sub_weighage_model->getSessionYear();
				$sessionYear = $this->input->post('selsyear');
				$session = $this->input->post('session');
      //  print_r($data['sessionYear'][0]->session_year);die();
			 $stu_details=$this->stu_sub_weighage_model->getStuDetails($stu_id,$sessionYear,$session);
			if(empty($sessionYear) || empty($session)){
			$sessionYear=$stu_details[0]->session_year;
			$session=$stu_details[0]->session;

			}

			 $form_id=$stu_details[0]->form_id;
			 $section=$stu_details[0]->section;
			 $course_id=$stu_details[0]->course_id;

			 $stu_details=$this->stu_sub_weighage_model->getStuDetails($stu_id,$sessionYear,$session);
 		 	//$form_id=$stu_details[0]->form_id;
			 $course_aggr_id=$stu_details[0]->course_aggr_id;
			 $branch_id=$stu_details[0]->branch_id;
			 $sessionYear = $this->input->post('selsyear');
			 $session = $this->input->post('session');
			 $data=array();
#echo"okdgpoim ". $stu_details[0]->section;
	$data['personal_details']=$this->stu_sub_weighage_model->get_student_latest_status($stu_id,$sessionYear,$session);

		 if (strpos($course_aggr_id, 'cbcs') !== false) {
			 //echo "kjsdfjsdilfilfjgil";
  			$data['stu_get_subject']=$this->stu_sub_weighage_model->get_stu_subject($form_id,$stu_id,$sessionYear,$session,$section,$course_aggr_id,$course_id,$branch_id);
			}else{


			 $data['stu_get_subject']=$this->stu_sub_weighage_model->get_stu_subject($form_id,$stu_id,$sessionYear,$session,$section,$course_aggr_id,$course_id,$branch_id);

		}
				$data['sessionYear']=$this->stu_sub_weighage_model->getSessionYear();
		$this->drawheader('Course Weightage');
		$this->load->view('student/stu_sub_weightage_view',$data);
		$this->drawfooter();
	}

	function ToObject($Array) {

	    // Clreate new stdClass object
	    $object = new stdClass();

	    // Use loop to convert array into object
	    foreach ($Array as $key => $value) {
	        if (is_array($value)) {
	            $value = ToObject($value);
	        }
	        $object->$key = $value;
	    }
	    return $object;
	}
       public function get_subjects_mapped()
    {

         $sessionYear = $this->input->post('selsyear');
         $session = $this->input->post('session');
         $stu_id = $this->session->userdata('id');
         $data['sessionYear']=$this->stu_sub_weighage_model->getSessionYear();
         $stu_details=$this->stu_sub_weighage_model->getStuDetails($stu_id,$sessionYear,$session);
         $form_id=$stu_details[0]->form_id;
         $section=$stu_details[0]->section;
         $data['stu_get_subject']=$this->stu_sub_weighage_model->get_stu_subject($form_id,$stu_id,$sessionYear,$session,$section);
     //   echo $data['stu_id']=$stu_id;

         $this->drawheader('Course Weightage');
        $this->load->view('student/stu_sub_weightage_view',$data);
        $this->drawfooter();

    }

		public function get_stu_marks(){
			$id = $this->input->post('id');
			$temp=explode('|', $id );
			$sub_code=$temp[0];
			$session_year=$temp[1];
			$session=$temp[2];
			$course_id=$temp[4];
			$branch_id=$temp[5];
			$data['stu_weightage']=$this->stu_sub_weighage_model->get_stu_marks($sub_code,$session_year,$session,$course_id,$branch_id);
			$data['stu_weightage_details']=$this->stu_sub_weighage_model->get_stu_marks($sub_code,$session_year,$session,$course_id,$branch_id);
		//  echo $this->db->last_query();die();
		if(empty($data['stu_weightage'])){
			echo "<span style='color:red;'>Data Not Found..</span>";
		}
			$this->load->view('student/student_marks_view',$data);

		}
		

    public function get_weightage(){
        $id = $this->input->post('id');
        $temp=explode('|', $id );
        //print_r($temp);
        //subject_id,sessionyear,session,empno,course,branch
        $data['stu_weightage']=$this->stu_sub_weighage_model->get_detail_weightage($temp[0],$temp[1],$temp[2],$temp[3],$temp[4],$temp[5],$temp[6],$temp[7]);
      //  echo $this->db->last_query();die();
			if(empty($data['stu_weightage'])){
				echo "<span style='color:red;'>Data Not Found..</span>";
			}
			//echo $temp[7];
        $this->load->view('student/stu_sub_weightage_view_details',$data);


    }
		public function get_download(){
        $id = $this->input->post('id');
        $temp=explode('|', $id );
        //print_r($temp);
        //subject_id,sessionyear,session,empno,course,branch
        $data['stu_weightage']=$this->stu_sub_weighage_model->get_detail_weightage_download($temp[0],$temp[1],$temp[2],$temp[3],$temp[4],$temp[5],$temp[6]);
				foreach ($data['stu_weightage'] as $dt) {
					$path=$data['stu_weightage'][0]->lecture_plan_path;
				}
				echo json_encode($path);


    }
}
?>
