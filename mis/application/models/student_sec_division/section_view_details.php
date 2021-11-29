<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Section_view_details extends MY_Controller
{
	function __construct()
	{
		parent::__construct(array('acad_ar'));
		$this->load->model('student_sec_division/sec_view_model');
        $this->addCSS("student_sec_division/section_view.css");	
        $this->addJS("student_sec_division/section_div.js");
	}

	public function index()
	{
		$this->drawHeader("View section Details");
		$this->load->view('student_sec_division/view_section');
		$this->drawFooter();			
	}	
	function setSessionyear()
	{
		$data['session_year'] = $this->sec_view_model->getSessionYear();
		var_dump($data);
		$this->load->view('student_sec_division/load_session', $data);
	}

	function viewDetails()
	{
		$data['session_year'] = $this->input->post('session_year');
		$this->drawHeader('Student Section Details');
		$this->load->view('student_sec_division/view_student_sec', $data);
		$this->drawFooter();
	}

	function loadStudentsDetails($session_year)
	{
		$data = array();
		$data['student'] = $this->sec_view_model->getStudents($session_year);
		$this->load->view('student_sec_division/load_student', $data);
	}


	function downloadSection()
	{
		$session_year = $this->input->post('year');
		$this->load->view('student_sec_division/view_section');
		$data['student'] = $this->sec_view_model->getStudents($session_year);
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
