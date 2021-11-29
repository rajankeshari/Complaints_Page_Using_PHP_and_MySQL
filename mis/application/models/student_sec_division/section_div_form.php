<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Section_div_form extends MY_Controller
{
	function __construct()
    {
        parent::__construct(array('acad_ar'));       
        $this->load->model('student_sec_division/stu_sec_model');
        $this->load->model('student_sec_division/insert_model');
        $this->addCSS("student_sec_division/section_view.css");
    }

	function index()
	{
		
		$status = $this->stu_sec_model->getStatus();
		$flag = true;
		if($status==false)
		{
        	$data['admn_no'] = $this->stu_sec_model->getNewRegistered();
			$data['new_entrance'] = count($data['admn_no']);
			if($data['new_entrance']==0)
				$flag = false;
			$data['prep_admn'] = $this->stu_sec_model->getPreparatory();
			$data['preparatory'] = count($data['prep_admn']);
		}
		if(!$status && $flag)
		{
			$this->drawHeader('Section Division Data');
			$this->load->view('student_sec_division/section_division', $data);
			$this->drawFooter();
		}
		else
		{
			$this->session->set_flashdata('flashInfo','Already divide students into section and group.');
			redirect('student_sec_division/section_div_form/showConfirmGroupSection');
		}
			
	}

	function stu_section_initializer()
	{
		$data = array();
		$data['count'] = $this->input->post('section_size');
		$data['sec_init'] = $this->input->post('section_box');

		if($data['count']==0 || $data['count']=='')
		{
			$this->drawheader('ERROR');
			$this->drawfooter();
			$this->session->set_flashdata('flashError','Error: No of Sections Either Zero or NULL.');
			redirect('student_sec_division/section_div_form');
		}
		else if($data['sec_init']=='')
		{
			$this->drawheader('ERROR');
			$this->drawfooter();
			$this->session->set_flashdata('flashError','Error: Please Select Section Initializer .');
			redirect('student_sec_division/section_div_form');
		}
		else
		{
			$data['preparatory'] = $this->input->post('preparatory');
			$data['new_entrance'] = $this->input->post('new_entrance');
			if($data['preparatory']=='')
				$data['preparatory'] = 0;
			$this->drawHeader('Section Data');
			$this->load->view('student_sec_division/section_name_initiator',$data);
			$this->drawFooter();
		}
	}

	
	function loadStudents() 
	{
		$session = $this->insert_model->getSessionYear();
		$sesion_year;
		foreach($session as $row)
		{
			$session_year = $row['sess'];
		}
		$data = array();
		$data['student'] = $this->stu_sec_model->getConfirmDetails($session_year);
		$this->load->view("student_sec_division/show_student", $data);
	}


	function stu_section_generation()
	{
		$data['count'] = $this->input->post('count');
		$data['preparatory'] =  $this->input->post('preparatory');
		$data['new_entrance'] = $this->input->post('new_entrance');
		$temp_new = 0;
		$temp_pre = 0;

		if($data['count']==0 || $data['count']=='null')
			redirect('student_sec_division/section_div_form');

		for($i=0; $i<$data['count']; $i++)
		{
			$data[$i]['sec_val'] = $this->input->post('sec_val'.$i);
			$data[$i]['new_ent'] = $this->input->post('new_ent'.$i);
			$data[$i]['pre_ent'] = $this->input->post('pre_ent'.$i);
			$data[$i]['total_ent'] = $this->input->post('total_ent'.$i);
			$temp_new += $data[$i]['new_ent'];
			$temp_pre += $data[$i]['pre_ent'];
		}
		if($temp_new == $data['new_entrance'] && $temp_pre == $data['preparatory'])
		{
			$curr_year = date("Y");
			$session_year = $curr_year.'-'.($curr_year+1);
			$data['new_stu'] = $this->stu_sec_model->getNewRegistered();
			$this->stu_sec_model->deletePendingDetails();
			$data['pre_stu'] = $this->stu_sec_model->getPreparatory();
			
			$this->db->trans_start();

			$this->db->trans_start();

			$i=0;
			$k=0;
			for($j=0;$j<count($data['new_stu']); $j++)
			{
				if($data[$i]['new_ent']==$k)
				{	
					$i++;
				 	$k=0;
				}
				$admn_no = $data['new_stu'][$j]->admn_no;
				$section = $data[$i]['sec_val'];
				$sec_details = array('admn_no'=>$admn_no, 'section'=>$section, 'session_year'=>$session_year);
				$this->stu_sec_model->pending_insert($sec_details);
				$k++;
			}


			$k=0;
			$i=0;
			for($j=0;$j<count($data['pre_stu']); $j++)
			{
				if($data[$i]['pre_ent']==$k)
				{	
					$i++;
				 	$k=0;
				}
				$admn_no = $data['pre_stu'][$j]->admn_no;
				$section = $data[$i]['sec_val'];
				$sec_details = array('admn_no'=>$admn_no, 'section'=>$section, 'session_year'=>$session_year);
				$this->stu_sec_model->pending_insert($sec_details);
				$k++;
			}

			$this->db->trans_complete();

			redirect('student_sec_division/section_div_form/section_set');

		}
		else
		{
			$this->drawheader('ERROR');
			$this->drawfooter();
			$this->session->set_flashdata('flashError','Error: Total No of Students doesn\'t Match with registered Students.');
			redirect('student_sec_division/section_div_form');
		}
	}

	function section_set()
	{
		
		$data['pending_stu'] = $this->stu_sec_model->getPendingStudent();
		$this->drawheader('Preview Section Table');
		$this->load->view('student_sec_division/preview_section',$data);
		$this->drawfooter();
		
	}

	function updateSection()
	{
		$curr_year = date("Y");
		$session_year = $curr_year.'-'.($curr_year+1);
		$admn_no = $_POST['admn_no'];
		$section = $_POST['section'];
		$sec_details = array('section'=>$section);
		$this->db->trans_start();	
		$this->stu_sec_model->updatePending($sec_details, $admn_no);
		$this->db->trans_complete();
	}

	function createGroup()
	{
		$result = ($this->stu_sec_model->getSection());
		$count = count($result);
		$var = array();
		for($i=0; $i<$count; $i++)
			array_push($var, $result[$i]->section);
		$data['sec'] = $var;
		$data['sec_count'] = $count;
		$this->drawheader('Create Group');
		$this->load->view('student_sec_division/groupform',$data);
		$this->drawfooter();
	}

	function showSectionGroup()
	{
		$this->db->trans_start();
		$this->stu_sec_model->deletePendingGroup();
		$data['sect_name'] = $_POST['sec_name'];
		$data['group_count'] =$_POST['group_count'];
		$data['sec_count'] = $_POST['section_count'];
		$sec_per_grp = (int)($data['sec_count']/$data['group_count']);
		$rem = $data['sec_count']%$data['group_count'];
		$grp_no = 1;
		$sec_count = $sec_per_grp;
		$curr_year = date("Y");
		$session_year = $curr_year.'-'.($curr_year+1);
		if($rem)
		{
			$sec_count++;
			$rem--;
		}
		$k = 0;
		$val = count($data['sect_name']);

		for($i=0; $i<$val; $i++)
		{
			if($k == $sec_count)
			{
				$grp_no++;
				if($rem)
				{
					$sec_count = $sec_per_grp+1;
					$rem--;
				}
				else
					$sec_count = $sec_per_grp;
				$k = 0;
			}
			$section = $data['sect_name'][$i];
			$groupsec = array('section'=>$section, 'group'=>$grp_no, 'session_year'=>$session_year);
			$this->stu_sec_model->setPendingGroup($groupsec);
			$k++;				
		}
		$data['group'] = $this->stu_sec_model->getPendingGroup();
		$this->db->trans_complete();
		$this->load->view('student_sec_division/showgroupsec', $data);
	}

	function updateGroup()
	{
		$group = $_POST['group'];
		$section = $_POST['section'];	
		$curr_year = date("Y");
		$session_year = $curr_year.'-'.($curr_year+1);
		$this->db->trans_start();
		$this->stu_sec_model->updatePendingGroup($group, $section, $session_year);
		$this->db->trans_complete();
	}

	function confirmation()
	{
		$this->db->trans_start();
		$this->stu_sec_model->setConfirmSection();
		$this->stu_sec_model->setConfirmGroup();

	//	$this->stu_sec_model->setPreparatory();
		$this->stu_sec_model->setStatus();
		$this->stu_sec_model->setGroupInRegistration();

		$this->db->trans_complete();
		
		$curr_year = date("Y");
			$session_year = $curr_year.'-'.($curr_year+1);
		$data['student'] = $this->stu_sec_model->getConfirmDetails($session_year);

		foreach($data['student'] as $row)
		{
			$name = $row->first_name;
			if($row->middle_name)
				$name = $name.' '.$row->middle_name;
			if($row->last_name)
				$name = $name.' '.$row->last_name;
			$description = "Dear ".$name." admission no ".$row->admn_no.", You are in section ".$row->sec." under Group ".$row->grp;
			$title = "Section Allotment Noticce";
			$link = "student_sec_division/greetings/showGreetings/".$row->admn_no.'/'.$row->grp.'/'.$row->sec;
			$this->notification->notify($row->admn_no,"stu",$title,$description,$link,"");
		}

		redirect('student_sec_division/section_div_form/showConfirmGroupSection');
	}

	function showConfirmGroupSection()
	{
		$this->drawheader('Confirm Section Group Matrix');
		$this->load->view('student_sec_division/confirm_sec_view');
		$this->drawfooter();
	}

	function downloadSection()
	{
		$session = $this->insert_model->getSessionYear();
		$session_year;
		foreach($session as $row)
		{
			$session_year = $row['sess'];
		}
		$data['student'] = $this->stu_sec_model->getConfirmDetails($session_year);
		$this->load->helper('download');
		$this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
                    ->setTitle("Section Group Info")
                    ->setDescription("first year group");

        $sno = 1;
        $i = 1;


        $styleArray = array('alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                ),
        					);
       	$styleArray1 = array('borders' => array(
                            		'allborders' => array(
                            		'style' => PHPExcel_Style_Border::BORDER_THIN
                        				)
                    				)
                				); 

       	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(11);
       	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
       	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
       	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
       	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);

       	$i=1;
       	$k=1;
        foreach($data['student'] as $row)
        {

        	if($k%40==1)
        	{
        		$i++;
        		$objPHPExcel->setActiveSheetIndex()
                        ->setCellValue('A'.$i, 'INDIAN SCHOOL OF MINES, DHANBAD');
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':E'.$i);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setBold(true)->setSize(16);

                $i = $i+1;
                $objPHPExcel->setActiveSheetIndex()
                        ->setCellValue('A'.$i, 'SECTION GROUP LIST FOR FIRST YEAR STUDENTS');
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':E'.$i);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setBold(true)->setSize(14);

                $i = $i+1;
                $objPHPExcel->setActiveSheetIndex()
                        ->setCellValue('A'.$i, 'SESSION YEAR: '.$session_year);
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':E'.$i);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setBold(true)->setSize(14);

                $i = $i+1;
                $objPHPExcel->setActiveSheetIndex()
                        ->setCellValue('D'.$i, 'DATE: '.date("d-m-Y"));
                $objPHPExcel->getActiveSheet()->mergeCells('D'.$i.':E'.$i);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setBold(true)->setSize(12);
                
                $i = $i+1;

                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, 'SL. NO')
                        ->setCellValue('B'.$i, 'Admission No')
                        ->setCellValue('C'.$i, 'Name Of Student')
                        ->setCellValue('D'.$i, 'Section')
                        ->setCellValue('E'.$i, 'Group');
            	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($styleArray1);
            	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->applyFromArray($styleArray);
            	$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':E'.$i)->applyFromArray($styleArray);
            	$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getFont()->setBold(true);
       			$i++;

        	}

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $sno++)
                        ->setCellValue('B'.$i, $row->admn_no)
                        ->setCellValue('C'.$i, substr($row->first_name." ".
                                    	$row->middle_name." ".$row->last_name,0,32))
                        ->setCellValue('D'.$i, $row->sec)
                        ->setCellValue('E'.$i, $row->grp);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($styleArray1);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$i.':E'.$i)->applyFromArray($styleArray);
       		$i++;
       		$k++;
        }


        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

        if(!is_dir('./assets/student_section/'))
        {
            mkdir('./assets/student_section/', 0777, true);
        }

   		$output_file='./assets/student_section/'.'section'.$session_year.'.xls';
		echo $output_file;

   		$objWriter->save($output_file);
     	$output_file1=base_url().'../assets/student_section/'.'section'.$session_year.'.xls';
	  	echo $output_file1;


		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime =  finfo_file($finfo, $output_file);
		finfo_close($finfo);

		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment;  filename="section.xls"');
		header('Content-Length: '.filesize($output_file));
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

		ob_get_clean();
		echo file_get_contents($output_file);
		ob_end_flush();

	}
}
?>