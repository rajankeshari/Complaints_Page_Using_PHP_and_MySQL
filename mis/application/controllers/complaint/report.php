<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report extends MY_Controller
{
	public function __construct()
	{
		parent::__construct(array('spvr_cc','spvr_ups','spvr_pc','spvr_contingency','spvr_civil','spvr_ee','spvr_mess','spvr_snt','spvr_phone','adis','adcm','adhm'));
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
	}
		
			public function index($id='')
			{
					$this->addJS("complaint/print.js");
                                        
                                        if(in_array("spvr_cc",$this->session->userdata('auth')))
                                        {
                                            if($id=="cc")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Internet');
                                          
                                        }
										// Added to include PC and UPS related report
										 if(in_array("spvr_pc",$this->session->userdata('auth')))
                                        {
                                            if($id=="pc")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('PC');
                                          
                                        }
										 if(in_array("spvr_ups",$this->session->userdata('auth')))
                                        {
                                            if($id=="ups")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('UPS');
                                          
                                        }
										if(in_array("adis",$this->session->userdata('auth')))
                                        {
                                            if($id=="cc")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Internet');
                                          
                                        }
                                        if(in_array("spvr_civil",$this->session->userdata('auth')))
                                        {
                                           if($id=="civ")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Civil');
                                          
                                        }
										if(in_array("adcm",$this->session->userdata('auth')))
                                        {
                                           if($id=="civ")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Civil');
                                          
                                        }
                                        if(in_array("spvr_ee",$this->session->userdata('auth')))
                                        {
                                            if($id=="ee")
                                          $data['show_list']=$this->complaints->get_all_complaints_byType('Electrical');
                                          
                                        }
										if(in_array("adcm",$this->session->userdata('auth')))
                                        {
                                            if($id=="ee")
                                          $data['show_list']=$this->complaints->get_all_complaints_byType('Electrical');
                                          
                                        }
                                        if(in_array("spvr_mess",$this->session->userdata('auth')))
                                        {
                                          if($id=="mess")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Mess');
                                          
                                        }
										if(in_array("adhm",$this->session->userdata('auth')))
                                        {
                                          if($id=="mess")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Mess');
                                          
                                        }
                                        if(in_array("spvr_snt",$this->session->userdata('auth')))
                                        {
                                          if($id=="snt")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Sanitary');
                                        }
										 if(in_array("adcm",$this->session->userdata('auth')))
                                        {
                                          if($id=="snt")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Sanitary');
                                        }
                                        if(in_array("spvr_phone",$this->session->userdata('auth')))
                                        {
                                          if($id=="phone")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Telephone');
                                        }
										 if(in_array("adis",$this->session->userdata('auth')))
                                        {
                                          if($id=="phone")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('Telephone');
                                        }
                                        if(in_array("dev",$this->session->userdata('auth')))
                                        {
                                          if($id=="mis")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('MIS');
                                          
                                        }
										
										if(in_array("spvr_contingency",$this->session->userdata('auth')))
                                        {
                                          if($id=="contingency")
                                            $data['show_list']=$this->complaints->get_all_complaints_byType('contingency');
                                        }
                                        
                                        
                                       
					$this->drawHeader("Online Complaint Report");
					$this->load->view('complaint/view_report',$data);
					$this->drawFooter();
				
			}
			
                        function view_result()
                        {
                           if (isset($_POST['b_sub'])) {
                               
                               $this->show_result();
                               
                            }
                            elseif (isset($_POST['p_sub'])) {
                                $this->print_report1();
                            }
                            
                           
                            
                           
                        }
                        
                        function show_result()
                        {
                           $this->addJS("complaint/print.js");
                           $fdate= date('Y-m-d',strtotime($this->input->post('from')));
                           $tdate= date('Y-m-d',strtotime($this->input->post('to')));
                           $status=$this->input->post('selstatus');
                           if($status=="none")
                           {
                               $status='';
                           }
                           $type=$this->input->post('seltype');
                           if($type=="none")
                           {
                               $type='';
                           }
                           $loc=$this->input->post('selloc');
                           if($loc=="none")
                           {
                               $loc='';
                           }
                           
                          
                       
                            $data['show_list']=$this->complaints->get_all_complaints_rpt($fdate,$tdate,$status,$type,$loc);
			  $this->drawHeader("Online Complaint Report");
			   $this->load->view('complaint/view_report',$data);
			    $this->drawFooter();
                        }
                        function print_report1()
                        {
                            $fdate= date('Y-m-d',strtotime($this->input->post('from')));
                           $tdate= date('Y-m-d',strtotime($this->input->post('to')));
                           $status=$this->input->post('selstatus');
                           if($status=="none")
                           {
                               $status='';
                           }
                           $type=$this->input->post('seltype');
                           if($type=="none")
                           {
                               $type='';
                           }
                           $loc=$this->input->post('selloc');
                           if($loc=="none")
                           {
                               $loc='';
                           }
                           
                            
                            $data['show_list']=$this->complaints->get_all_complaints_rpt($fdate,$tdate,$status,$type,$loc);
							if(sizeof($data['show_list'][0])==0)
							{
								echo '<script type="text/javascript">'; 
								echo 'window.location.href ="index";';
								echo '</script>';
							}
							else{
								$this->load->helper(array('dompdf', 'file'));
			    $ff= $this->load->view('complaint/print_report_sup.php',$data,TRUE);
					//pdf_create($ff, 'admin'.$data['show_list'][0]->po_refno);
                            pdf_create($ff, 'supervisor');
							}
                            
                        }
	
	
	
	
	
}
?>

