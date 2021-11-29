<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mis_report extends MY_Controller
{
	public function __construct()
	{
		parent::__construct(array('spvr_cc','spvr_civil','spvr_ee','spvr_mess','spvr_snt','emp','stu','dev_feed','dev_att','dev_grade','dev_hall','dev_info','dev_login','dev_other','dev_semreg','dev_salary','dev_examacad','mis_admin','dev','adis','adhm','adcm')); // below lines copied from report
		// Pl use all auth_id so that each auth can see the details of complaint otherwise they may be logged out from the code
		// The system treats as unauthorized person for taking any further action on the complaint.
				$this->load->model('complaint/complaints','',TRUE);
                $this->load->model('user/user_details_model','',TRUE);
                 //============================================================================================
                                $this->addJS("exam_control/standard/jquery.dataTables.buttons.min.js");        
                                $this->addJS("exam_control/standard//buttons.flash.min.js");                
                                $this->addJS("exam_control/standard//jszip.min.js");
                                $this->addJS("exam_control/standard//pdfmake.min.js");
                                $this->addJS("exam_control/standard//vfs_fonts.js");
                                $this->addJS("exam_control/standard//buttons.html5.min.js");
                                $this->addJS("exam_control/standard//buttons.print.min.js");
                                $this->addJS("exam_control/standard/buttons.colVis.min.js");
                                $this->addCSS("exam_control/standard//buttons.dataTables.min.css");
                                
                //==================================================================================================
		$this->addJS("complaint/print1.js");	
	}
		
			public function index($id='')
			{
				
                                        if(in_array("mis_admin",$this->session->userdata('auth')))
                                        {
                                          if($id=="All")
                                            $data['show_list']=$this->complaints->get_total_mis_complaints('All'); 
                                        }
                                        
                                        if(in_array("dev_att",$this->session->userdata('auth')))
                                        {
                                          if($id=="Attendance")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('Attendance'); 
                                        }
                                        if(in_array("dev_feed",$this->session->userdata('auth')))
                                        {
                                          if($id=="Feedback")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('Feedback'); 
                                        }
                                        if(in_array("dev_grade",$this->session->userdata('auth')))
                                        {
                                          if($id=="Grade_Sheet")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('Grade_Sheet'); 
                                        }
                                        if(in_array("dev_hall",$this->session->userdata('auth')))
                                        {
                                          if($id=="Hall_Ticket")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('Hall_Ticket'); 
                                        }
                                        if(in_array("dev_info",$this->session->userdata('auth')))
                                        {
                                          if($id=="Personal_Details")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('Personal_Details'); 
                                          
                                        }
                                        if(in_array("dev_login",$this->session->userdata('auth')))
                                        {
                                          if($id=="Login")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('Login'); //
                                        }
                                        if(in_array("dev_other",$this->session->userdata('auth')))
                                        {
                                          if($id=="Others")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('Others'); //
                                        }
                                        if(in_array("dev_semreg",$this->session->userdata('auth')))
                                        {
                                          if($id=="Semester_Registration")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('Semester_Registration'); 
                                        }
                                        if(in_array("dev_att",$this->session->userdata('auth')))
                                        {
                                          if($id=="Attendance")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('Attendance'); 
                                        }
										if(in_array("dev_salary",$this->session->userdata('auth')))
                                        {
                                          if($id=="Salary")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('salary'); 
                                        }
										if(in_array("dev_examacad",$this->session->userdata('auth')))
                                        {
                                          if($id=="examacad")
                                            $data['show_list']=$this->complaints->get_all_mis_complaints_byType('examacad'); 
                                        }
                                        $data['supervisor']=$id;
										
					$this->drawHeader("MIS Complaint Report");
					$this->load->view('complaint/view_mis_report',$data);
					$this->drawFooter();
				
			}
			
                        function view_result($supervisor)
                        {

                           if (isset($_POST['b_sub'])) {
                               
                               $this->show_result($supervisor);
                               
                            }
                            elseif (isset($_POST['p_sub'])) {
                                $this->print_report1($supervisor);
                            }
                            
                           
                            
                           
                        }
                        
                        function show_result($supervisor)
                        {
                            $this->addJS("complaint/print1.js");
                           $fdate= date('Y-m-d',strtotime($this->input->post('from')));
                           $tdate= date('Y-m-d',strtotime($this->input->post('to')));
                           $status=$this->input->post('selstatus');
                           if($status=="none")
                           {
                               $status='';
                           }
                           $category=$this->input->post('selcat');

                           if($category=="none")
                           {
                               $category='';
                           }
                           
                            $data['show_list']=$this->complaints->get_all_mis_complaints_rpt($fdate,$tdate,$status,$category);
                            $data['supervisor']=$supervisor;  
			  $this->drawHeader("Online Complaint Report");
			   $this->load->view('complaint/view_mis_report',$data);
			    $this->drawFooter();
                        }
                        function print_report1($supervisor)
                        {
                            $fdate= date('Y-m-d',strtotime($this->input->post('from')));
                           $tdate= date('Y-m-d',strtotime($this->input->post('to')));
                           $status=$this->input->post('selstatus');
                           if($status=="none")
                           {
                               $status='';
                           }
                           $category=$this->input->post('selcat');
                           if($category=="none")
                           {
                               $category='';
                           }
                           
                            $data['show_list']=$this->complaints->get_all_mis_complaints_rpt($fdate,$tdate,$status,$category);
						//print_r($data);
							if(sizeof($data['show_list'][0])==0)
							{

                 // echo '<script> alert("No Records To Print") </script>';
								$this->index($supervisor);
							}
							else{
								$this->load->helper(array('dompdf', 'file'));
			    $ff= $this->load->view('complaint/print_mis_report_sup.php',$data,TRUE);
					//pdf_create($ff, 'admin'.$data['show_list'][0]->po_refno);
                            pdf_create($ff, 'supervisor');
							}
							
                        }
	
	
	
	
	
}
?>

