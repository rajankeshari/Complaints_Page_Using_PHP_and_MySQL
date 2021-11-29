<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Student_editable_by_student extends MY_Controller
{
	function __construct()
	{
		parent::__construct(array('stu'));
	}

	function index($error = '')
	{
		$stu_id = $this->session->userdata('id');
		$data['error'] = $error;
		$data['stu_id'] = $stu_id;
		$this->load->model('student/student_details_model');
		$this->load->model('student/student_other_details_model');
		$this->load->model('user/user_details_model');
		$this->load->model('user/user_other_details_model');
		$this->load->model('user/user_details_model','',TRUE);
		$this->load->model('student/student_academic_model','',TRUE);
		$res=$this->user_details_model->getUserById($stu_id);
		$data['photopath'] = ($res == FALSE)?	FALSE:$res->photopath;
		$data['stu_academic_details']=$this->student_academic_model->get_stu_academic_details_by_id($stu_id);
		$data['stu_detail'] = $this->student_details_model->get_student_details_by_id($stu_id);
		$data['stu_other_detail'] = $this->student_other_details_model->get_student_other_details_by_id($stu_id);
		$data['user_detail'] = $this->user_details_model->getUserById($stu_id);
		$data['user_other_detail'] = $this->user_other_details_model->getUserById($stu_id);

		$this->addJS('student/edit_my_details.js');

		$this->drawHeader('Edit Your Details');
		$this->load->view('student/edit/student_editable_by_student',$data);
		$this->drawFooter();
	}

	function update_my_details()
	{
		$stu_id = $this->session->userdata('id');
		$this->load->model('student/student_details_model');
		$this->load->model('student/student_other_details_model');
		$this->load->model('user/user_details_model');
		$this->load->model('user/user_other_details_model');
		
		$user_details=$this->user_details_model->getUserById($stu_id);
		
		$alternate_mobile_number = $this->input->post('alternate_mobile');
			
			$upload = $this->upload_image($stu_id,'photo');
			if($upload == FALSE)
			{
				/*var_dump('no image');
				$this->session->set_flashdata('flashError','Student '.$stu_id.' image upload failed.');
				redirect("");*/
				$image_path = $user_details->photopath;
			}
			else
			{
				//var_dump('image');
				$image_path = 'student/'.$stu_id.'/'.$upload['file_name'];
			}
		//var_dump($alternate_mobile_number);
		//return;

		$user_details = array(
			'email' => $this->authorization->strclean($this->input->post('email')),
			'photopath' => $image_path
		);

		$user_other_details = array(
			'hobbies' => strtolower($this->authorization->strclean($this->input->post('hobbies'))) ,
			'fav_past_time' => strtolower($this->authorization->strclean($this->input->post('favpast'))) ,
			'mobile_no' => $this->input->post('mobile') 
		);

		$stu_details = array(
			'alternate_mobile_no' => $alternate_mobile_number ,
			'alternate_email_id' => $this->input->post('alternate_email_id'),
			'admn_date' => date('Y-m-d',strtotime($this->input->post('entrance_date')))
		);
		
		$stu_academic = array(
			'enrollment_year' => date('Y',strtotime($this->input->post('entrance_date'))),
			'semester' => $this->input->post('semester')
		);

		$stu_other_details = array(
			'extra_curricular_activity' => strtolower($this->authorization->strclean($this->input->post('extra_activity'))) ,
			'other_relevant_info' => strtolower($this->authorization->strclean($this->input->post('any_other_information')))
		);

		$this->load->model('user/user_details_model','',TRUE);
		$this->load->model('user/user_other_details_model','',TRUE);
		$this->load->model('student/student_details_model','',TRUE);
		$this->load->model('student/student_other_details_model','',TRUE);
		$this->load->model('student/student_academic_model','',TRUE);

		$this->db->trans_start();

		$this->user_details_model->updateById($user_details,$stu_id);
		$this->user_other_details_model->updateById($user_other_details,$stu_id);
		$this->student_details_model->update_by_id($stu_details,$stu_id);
		$this->student_other_details_model->update_by_id($stu_other_details,$stu_id);
		$this->student_academic_model->update_by_id($stu_academic,$stu_id);

		$this->db->trans_complete();

		$this->session->set_flashdata('flashSuccess','Details successfully updated.');
		redirect();

	}
	
	function upload_image($stu_id = '', $name ='')
	{
		$config['upload_path'] = 'assets/images/student/'.strtolower($stu_id).'/';
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['max_size']  = '200';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';

		if(isset($_FILES[$name]['name']))
    	{
            if($_FILES[$name]['name'] == "")
        		$filename = "";
            else
			{
                $filename=$this->security->sanitize_filename(strtolower($_FILES[$name]['name']));
                $ext =  strrchr( $filename, '.' ); // Get the extension from the filename.
                $filename='stu_'.$stu_id.'_'.date('YmdHis').$ext;
            }
        }
        else
        {
        	//var_dump('failure');
	       	$this->index('ERROR: File Name not set.');
			return FALSE;
	    }

		$config['file_name'] = $filename;

		if(!is_dir($config['upload_path']))	//create the folder if it's not already exists
	    {
			mkdir($config['upload_path'],0777,TRUE);
    	}

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($name))		//do_multi_upload is back compatible with do_upload
		{
			$error = $this->upload->display_errors();
			$this->index('ERROR: '.$error);
			return FALSE;
		}
		else
		{
			$upload_data = $this->upload->data();
			return $upload_data;
		}
	}
}