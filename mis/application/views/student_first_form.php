<script type="text/javascript" src="<? echo base_url(); ?>/assets/js/new_student_admission/student_first_form.js"></script>
<script type="text/javascript">
//var $boxbody = $("#boxbody");
alert('Greetings!\nWelcome to Management Information System!!');

</script>
<script>

$(document).ready(function(){
   
    $('#blood_group').val('<?php echo $student_details['blood_group']; ?>');
	$('#marital_status').val('<?php echo $user_details['marital_status']; ?>');
	$('#religion').val('<?php echo $user_other_details['religion']; ?>');
	$('#category').val('<?php echo $user_details['category']; ?>');
	
    
});

</script>

<?php
    $ui = new UI();

	$boxbody=$ui->box()
			->uiType('primary')
			->id('boxbody')
			->background('grey')
			->title('Student Details Form ('.$name.')')
			->open();
			
			
			$modal_row = $ui->row()->classes('modal fade')->id('data_dialog_fade')->open();
			$modal_col = $ui->col()->classes('modal-dialog modal-lg')->id('data_dialog_dialog')->open();
//			$modal_box = $ui->box()->classes('modal-content')->id('data_dialog_content')->open();

			?>
            <div class="modal-content" id='data_dialog_content'>

		        <div class="modal-header bg-green">
                
                <center><strong>
        		  <h4 class="modal-title">Computer Assets and Information Technology (usage) Policy<br />ISM Dhanbad</h4></strong></center>
		        </div>
        		
                <div class="modal-body">
		                <p style="text-align:center; font-size:15px"><b><u>Mandatory Undertaking</u></b></p>
		          <p style="text-align:center"><b>By signing-up this declaration, the signatory user will hereby adopt and enact the Institute’s “Computer Assets and Information Technology (usage) Policy” along with the following explicit Undertaking.</b></p>
                  <hr>
                  <div  style="overflow-y:scroll; height:240px;">
                  
                  <ol style="font-size:12px">
                  <li>
                  <b>[My Computer]</b> I understand that the term “My Computer” binds me with all the IT resource for which I am responsible. I shall be responsible for all of my usage and activities on Institute’s IT resource. I shall bear full responsibility for all the content on my personally owned IT resource (computer(s), mobile, tabs etc.) which I operate within IT resource prerogatives of the Institute. Also, I will own similar responsibility, on all the IT resources as allotted to me by the Institute, including its stored and shared content (for example: file storage area, web pages, stored/ archived emails, compute and storage nodes, NAS and SAN etc.). 
                  </li>
                  <br />
                  <li>
                  <b>[My Software]</b> I will be responsible for all the Software as installed, copied and operated on ‘My Computer’. I will also NOT infringe with the copyright and licensing policy of each software as present in my computer. I will also NOT indulge in any unauthorized duplication, distribution or use of computer software than the license allows, or install software onto multiple computers or a server which has been licensed for one computer only. I will also NOT aid to piracy by providing unauthorized access to software by way of providing serial numbers used to register software. I also understand that the Institute is committed to run legally licensed software, and that the institute does not support software copyright infringement in any form.
                  </li>
                  <br />
                  
                  <li>
                  <b>[My Network]</b> I will hold responsibility for all the network traffic generated from “my computer”. I will not attempt to physically tamper or access remotely any network connection(s)/ equipment(s), send disruptive signals, or over use of network resources. I understand that repeated abuse as indicated in this policy document could result in permanent termination of my IT resource access privileges disconnection of network services. I shall not act as a forwarder on/ masquerade any network connection for anyone else and would access the IT resources for my own individual use.
                  </li>
                  <br />
                  <li>
                  <b>[My Communication]</b> I shall also not use Institute IT resources to threaten, intimidate, or harass others or to send wasteful broadcasts and malicious mail broadcasts. I shall also not attempt to deceive and spoof my identity while using IT resources. 
                  </li>
                  <br />
                  <li>
				<b>[Principles of Use]</b> I understand that the institutional IT resource is for academic and research purpose only. I shall not use it for any other purpose including any commercial or data hosting services for other people or groups, both on local and global network. I shall also not host shared files or information that might be otherwise considered objectionable or illegal under prevailing IT Act and other Cyber Laws. 
                  </li>
                  <br />
                  <li>
                  <b>[Privacy Rights]</b> I shall respect privacy rights of all users. By any means, I shall not indulge into or attempt to gain unauthorized access of any IT resource belonging to other user(s) and without their knowledge and explicit consent. This includes any attempt to hack other user’s computers, accounts, files, data, programs or any other information resource. I also understand that ‘forgery’ or other misrepresentation of one's identity via electronic or any other form of communication is a ‘Fundamental Standard violation’ and may attract severe legal actions. 
                  </li>
                  <br />
                  
                  <li>
                  <b>[IT Resource Monitoring]</b> I understand that the all IT resources of Institute are subject to monitoring as per the Institute policy. The monitoring may include aggregate bandwidth usage, monitoring of traffic content etc. in response to compliance of any national or institutional policy or due to request from law enforcement agency. I understand that the Institute has authority to perform network vulnerability and port scans on my systems (without any prior notice), as and when needed, to ensure integrity and optimal utilization of IT resources. 
                  </li>
                  <br />
                  <li>
                  <b>[Protection from Viruses]</b> I understand that viruses may severely degrade the performance of IT resources and it is my responsibility to keep my computer updated, by using available virus detection software and operating system updates. 
                  </li>
                  <br />
                  <li>
                  <b>[Prohibition in File Sharing]</b> I understand that sharing and hosting of any copyrighted or obscene material is strictly prohibited. I also understand that the electronic resources under IT resources such as e-journals, e-books, databases etc. are for personal academic use only. Bulk download or printing of complete book or downloading complete issue of any journal is strictly prohibited and may infringe with the policy of the library or terms of use of the publishers. 
                  </li>
                  <br />
                  <li>
                  <b>[Security Compliance]</b> I understand that any attempt to endanger the security and stability of the IT resource is strictly prohibited. I undertake that by any means, deliberate or unknowingly, I shall not attempt to bypass firewalls and access rules as configured. I will not attempt to set-up any unauthorized server(s) and client(s) of any kind (e.g. vpn, proxy, mail, web or hub etc.) both on local or global network by misusing institutional IT resource. I understand that any such careless act may lead to suspension or permanent loss of IT resources access privileges along with other suitable disciplinary action(s) etc.
                  </li>
                  <br />
                  <li>
                  <b>[Consequences of Non-compliance]</b> I understand that any abuse to and noncompliance of Computer Assets and IT (Usage) (CAIT) Policy and any other act that constitutes a violation of Institutional Rules & Regulations could result in administrative or disciplinary procedures. 
                  </li>
                  </ol>
                  </div>
                  
        		</div>
		        <div class="modal-footer">
                <p style="font-size:12px"><b>
                I hereby undertake to abide by the CAIT Policy and other rules and regulations of the Institute and adopt and enact this with immediate effect.</b>
                </p><br />
                <?
				

				$ui->button()
				->uiType('success')
				->value('I Agree')
				->icon($ui->icon('check'))
				->extras('data-dismiss="modal"')
				->show();

				echo "&nbsp;&nbsp;&nbsp;";
				
                ?><a href="<?= site_url('login/logout') ?>"><?
				$ui->button()
				->uiType('danger')
				->value('I Do Not Agree')
				->icon($ui->icon('close'))
				->show();
				?></a>
				
    	    	</div>

            </div>
            <?
			
//			$modal_box->close();
			$modal_col->close();
			$modal_row->close();
			
//	$padcol1=$ui->col()->width(1)->m_width(0)->t_width(0)->open();
//	$padcol1->close();
	$bodycol=$ui->col()->width(12)->m_width(12)->t_width(12)->open();
        $coress_recv = false;
        $form=$ui->form()
                 ->action('new_student_admission/student_first_login/update_all_details/'.$stu_id.'/'.$coress_recv)
                 ->multipart()
                 ->id('form_submit')
                 ->open();

            $student_details_row = $ui->row()
                                  ->open();

                $student_details_box = $ui->box()
                                          ->uiType('primary')
                                          ->solid()
                                          ->title('Personal Details')
                                          ->open();


                    $student_personal_details_row_1 = $ui->row()
                                                         ->open();

						if(in_array('sex',$user_details)) $w=3;
						else $w=4;

                            $ui->input()
                               ->label('पूरा नाम हिन्दी में')
                               ->id('stud_name_hindi')
                               ->name('stud_name_hindi')
                               ->value()//$stu_basic_details->name_in_hindi)
                               ->width($w)
                               ->show();

                       // $column3->close();
						//if(in_array('sex ',$user_details))
						//{
                        	$column_gender = $ui->col()
                                      ->width(3)
                                      ->open();
                            echo '<label> Gender <span style= "color:red;"> *</span></label>';

                if($user_details['sex']=='m')
				{
                           $ui->radio()
                               ->name('sex')
                               ->label('Male')
							   ->checked()
                               ->value('m')
                               ->show();

                            $ui->radio()
                               ->name('sex')
                               ->label('Female')
                               ->value('f')
                               ->show();

                            $ui->radio()
                               ->name('sex')
                               ->label('Others')
                               ->value('o')
                               ->show();
			   }
			   else if($user_details['sex']=='f')
			   {
			        $ui->radio()
                               ->name('sex')
                               ->label('Male')
                               ->value('m')
                               ->show();

                            $ui->radio()
                               ->name('sex')
                               ->label('Female')
                               ->value('f')
							   ->checked()
                               ->show();

                            $ui->radio()
                               ->name('sex')
                               ->label('Others')
                               ->value('o')
                               ->show();
			   }		
			   else
			   {
			          $ui->radio()
                               ->name('sex')
                               ->label('Male')
                               ->value('m')
                               ->show();

                            $ui->radio()
                               ->name('sex')
                               ->label('Female')
                               ->value('f')
                               ->show();

                            $ui->radio()
                               ->name('sex')
                               ->label('Others')
                               ->value('o')
							    ->checked()
                               ->show();
			   }		   

                        $column_gender->close();
						//}
						
						
                            $ui->datePicker()
                               ->label('Date of Birth <span style= "color:red;"> *</span>')
                               ->width($w)
                               ->name('dob')
                               ->value(date('d-m-Y'))
                               ->dateFormat('dd-mm-yyyy')
                               ->show();

                            $ui->input()
                               ->label('Place of Birth <span style= "color:red;"> *</span>')
                               ->name('pob')
                               ->required()
                               ->value()
                               ->width($w)
                               ->show();

                    $student_personal_details_row_1->close();

                    $student_personal_details_row_2 = $ui->row()
                                                         ->open();

                        $column_pd = $ui->col()
                                            ->width(3)
                                            ->open();
                            echo '<label>Physically Challenged <span style= "color:red;"> *</span> </label>';
							
                if($user_details['physically_challenged']=='yes')
				{
                            $ui->radio()
                               ->name('pd')
                               ->label('Yes')
                               ->value('yes')
                               ->checked()
                               ->show();

                            $ui->radio()
                               ->name('pd')
                               ->label('No')
                               ->value('no')
                               //->checked()
                               ->show();
				}
				else
				{
				    $ui->radio()
                               ->name('pd')
                               ->label('Yes')
                               ->value('yes')
                              // ->checked()
                               ->show();

                            $ui->radio()
                               ->name('pd')
                               ->label('No')
                               ->value('no')
                               ->checked()
                               ->show();
				}			   

                        $column_pd->close();

                        $ui->select()
                           ->name('blood_group')
						   ->id('blood_group')
                           ->width(3)
                           ->label('Blood Group <span style= "color:red;"> *</span>')
                           ->options(array($ui->option()->value('A+')->text('A+'),
                                           $ui->option()->value('A-')->text('A-'),
                                           $ui->option()->value('B+')->text('B+'),
                                           $ui->option()->value('B-')->text('B-'),
                                           $ui->option()->value('O+')->text('O+'),
                                           $ui->option()->value('O-')->text('O-'),
                                           $ui->option()->value('AB+')->text('AB+'),
                                           $ui->option()->value('AB-')->text('AB-')))
                           ->show();

                        $column_ki = $ui->col()
                                        ->width(3)
                                        ->open();
                            echo '<label>Kashmiri Immigrant <span style= "color:red;"> *</span> </label>';

                if($user_other_details['kashmiri_immigrant']=='yes')
				{

                            $ui->radio()
                               ->name('kashmiri')
                               ->label('Yes')
                               ->value('yes')
                               ->checked()
                               ->show();

                            $ui->radio()
                               ->name('kashmiri')
                               ->label('No')
                               ->value('no')
                               //->checked()
                               ->show();
				}
				else
				{
				     $ui->radio()
                               ->name('kashmiri')
                               ->label('Yes')
                               ->value('yes')
                               //->checked()
                               ->show();

                            $ui->radio()
                               ->name('kashmiri')
                               ->label('No')
                               ->value('no')
                               ->checked()
                               ->show();
				}
						   

                        $column_ki->close();

                        $ui->select()
                           ->name('mstatus')
						   ->id('marital_status')
                           ->width(3)
                           ->label('Marital Status<span style= "color:red;"> *</span> ')
                           ->options(array($ui->option()->value('unmarried')->text('Unmarried'),
                                           $ui->option()->value('married')->text('Married'),
                                           $ui->option()->value('widow')->text('Widow'),
                                           $ui->option()->value('Widower')->text('Widower'),
                                           $ui->option()->value('divorcee')->text('Divorcee'),
                                           $ui->option()->value('separated')->text('Separated')))
                           ->show();

                    $student_personal_details_row_2->close();



			if(in_array('category',$user_details) && in_array('nationality',$user_other_details))
			{
				       $student_personal_details_row_3 = $ui->row()
                                                         ->open();
														 
														 
                        /*$ui->select()
                           ->name('category')
						   ->id('category')
                           ->width(3)
                           ->label('Category<span style= "color:red;"> *</span>')
                           ->options(array($ui->option()->value('General')->text('GEN'),
                                           $ui->option()->value('OBC')->text('OBC'),
                                           $ui->option()->value('SC')->text('SC'),
                                           $ui->option()->value('ST')->text('ST'),
                                           $ui->option()->value('Others')->text('OTHERS')))
                           ->show();*/
						$ui->input()
                           ->label('Category')
                           ->name('category')
                           ->value($user_details['category'])
                           ->disabled()
                           ->width(1)
                           ->show(); 
						 
					   $ui->input()
                           ->label('Allocated Category')
                           ->name('allocated_category')
                           ->value($user_details['allocated_category'])
                           ->disabled()
                           ->width(2)
                           ->show(); 	     


			            $ui->input()
                           ->label('Nationality<span style= "color:red;"> *</span>')
                           ->name('nationality')
                           ->value($user_other_details['nationality'])
                           ->required()
                           ->width(3)
                           ->show();


                        $ui->select()
                           ->name('religion')
						   ->id('religion')
                           ->width(3)
                           ->label('Religion<span style= "color:red;"> *</span>')
                           ->options(array($ui->option()->value('HINDU')->text('HINDU'),
                                           $ui->option()->value('CHRISTIAN')->text('CHRISTIAN'),
                                           $ui->option()->value('MUSLIM')->text('MUSLIM'),
                                           $ui->option()->value('SIKH')->text('SIKH'),
                                           $ui->option()->value('BAUDHH')->text('BAUDHH'),
                                           $ui->option()->value('JAIN')->text('JAIN'),
                                           $ui->option()->value('PARSI')->text('PARSI'),
                                           $ui->option()->value('YAHUDI')->text('YAHUDI'),
                                           $ui->option()->value('OTHERS')->text('OTHERS')))
                           ->show();


                        $ui->input()
                           ->label('Aadhaar Card No.')
                           ->id('aadhaar_no')
                           ->name('aadhaar_no')
                           ->value()//$stu_other_details->aadhaar_card_no)
                           ->width(3)
                           ->show();

                    $student_personal_details_row_3->close();

                    $student_personal_details_row_4 = $ui->row()
                                                         ->open();

                        $ui->input()
                           ->label('Identification Mark<span style= "color:red;"> *</span>')
                           ->name('identification_mark')
                           ->required()
                           ->value($student_details['identification_mark'])
                           ->width(12)
                           ->show();

                    $student_personal_details_row_4->close();
			}
			else if(in_array('category',$user_details) || in_array('nationality',$user_other_details))
			{
				       $student_personal_details_row_3 = $ui->row()
                                                         ->open();
														 
					if(in_array('category',$user_details))
					{
                        /*$ui->select()
                           ->name('category')
                           ->width(3)
                           ->label('Category<span style= "color:red;"> *</span>')
                           ->options(array($ui->option()->value('General')->text('GEN'),
                                           $ui->option()->value('OBC')->text('OBC'),
                                           $ui->option()->value('SC')->text('SC'),
                                           $ui->option()->value('ST')->text('ST'),
                                           $ui->option()->value('Others')->text('OTHERS')))
                           ->show();*/
						   
						   $ui->input()
                           ->label('Category')
                           ->name('category')
                           ->value($user_details['category'])
                           ->disabled()
                           ->width(1)
                           ->show(); 
						   
						   $ui->input()
                           ->label('Allocated Category')
                           ->name('allocated_category')
                           ->value($user_details['allocated_category'])
                           ->disabled()
                           ->width(2)
                           ->show();
					}
					else if(in_array('nationality',$user_other_details))
					{
						                        $ui->input()
                           ->label('Nationality<span style= "color:red;"> *</span>')
                           ->name('nationality')
                           ->value()//$user_other_details->nationality)
                           ->required()
                           ->width(3)
                           ->show();
					}

                        $ui->select()
                           ->name('religion')
                           ->width(3)
                           ->label('Religion<span style= "color:red;"> *</span>')
                           ->options(array($ui->option()->value('HINDU')->text('HINDU'),
                                           $ui->option()->value('CHRISTIAN')->text('CHRISTIAN'),
                                           $ui->option()->value('MUSLIM')->text('MUSLIM'),
                                           $ui->option()->value('SIKH')->text('SIKH'),
                                           $ui->option()->value('BAUDHH')->text('BAUDHH'),
                                           $ui->option()->value('JAIN')->text('JAIN'),
                                           $ui->option()->value('PARSI')->text('PARSI'),
                                           $ui->option()->value('YAHUDI')->text('YAHUDI'),
                                           $ui->option()->value('OTHERS')->text('OTHERS')))
                           ->show();


                        $ui->input()
                           ->label('Aadhaar Card No.')
                           ->id('aadhaar_no')
                           ->name('aadhaar_no')
                           ->value()//$stu_other_details->aadhaar_card_no)
                           ->width(6)
                           ->show();

                    $student_personal_details_row_3->close();

                    $student_personal_details_row_4 = $ui->row()
                                                         ->open();

                        $ui->input()
                           ->label('Identification Mark<span style= "color:red;"> *</span>')
                           ->name('identification_mark')
                           ->required()
                           ->value($student_details['identification_mark'])
                           ->width(12)
                           ->show();

                    $student_personal_details_row_4->close();
			}
			else
			{
				$student_personal_details_row_3 = $ui->row()
                                                         ->open();	
				
                        $ui->select()
                           ->name('religion')
                           ->width(3)
                           ->label('Religion<span style= "color:red;"> *</span>')
                           ->options(array($ui->option()->value('HINDU')->text('HINDU'),
                                           $ui->option()->value('CHRISTIAN')->text('CHRISTIAN'),
                                           $ui->option()->value('MUSLIM')->text('MUSLIM'),
                                           $ui->option()->value('SIKH')->text('SIKH'),
                                           $ui->option()->value('BAUDHH')->text('BAUDHH'),
                                           $ui->option()->value('JAIN')->text('JAIN'),
                                           $ui->option()->value('PARSI')->text('PARSI'),
                                           $ui->option()->value('YAHUDI')->text('YAHUDI'),
                                           $ui->option()->value('OTHERS')->text('OTHERS')))
                           ->show();

                        $ui->input()
                           ->label('Aadhaar Card No.')
                           ->id('aadhaar_no')
                           ->name('aadhaar_no')
                           ->value()//$stu_other_details->aadhaar_card_no)
                           ->width(3)
                           ->show();

                        $ui->input()
                           ->label('Identification Mark<span style= "color:red;"> *</span>')
                           ->name('identification_mark')
                           ->required()
                           ->value($student_details['identification_mark'])
                           ->width(6)
                           ->show();

                    $student_personal_details_row_3->close();
			}

                $student_details_box->close();

                $student_family_details_box = $ui->box()
                                                 ->uiType('primary')
                                                 ->solid()
                                                 ->title('Family Details')
                                                 ->open();

                    $family_details_row = $ui->row()
                                             ->open();

                    $family_father = $ui->col()
                                         ->width(4)
                                         ->open();

                        $student_father_details_box = $ui->box()
                                                         ->uiType('primary')
                                                        // ->solid()
                                                         ->title('Father\'s Details')
                                                         ->open();

						//if(in_array('father_name',$user_other_details))
						//{
                            $ui->input()
                               ->label('Father\'s Name<span style= "color:red;"> *</span>')
							   ->required()
                               ->id('father_name')
                               ->name('father_name')
                               ->value($user_other_details['father_name'])
                               ->show();
						//}

                            $ui->input()
                               ->label('Father\'s Occupation<span style= "color:red;"> *</span>')
                               ->id('father_occupation')
							   ->required()
                               ->name('father_occupation')
                               ->value($student_other_details['fathers_occupation'])
                               ->show();

                            $ui->input()
                               ->label('Father\'s Gross Annual Income<span style= "color:red;"> *</span>')
                               ->id('father_gross_income')
							   ->required()
                               ->name('father_gross_income')
                               ->value($student_other_details['fathers_annual_income'])
                               ->show();

                        $student_father_details_box->close();


                    $family_father->close();

                    $family_mother = $ui->col()
                                         ->width(4)
                                         ->open();

                        $student_mother_details_box = $ui->box()
                                                         ->uiType('primary')
                                                         //->solid()
                                                         ->title('Mother\'s Details')
                                                         ->open();

						//if(in_array('mother_name',$user_other_details))
						//{
                            $ui->input()
                               ->label('Mother\'s Name<span style= "color:red;"> *</span>')
                               ->id('mother_name')
							   ->required()
                               ->name('mother_name')
                               ->value($user_other_details['mother_name'])
                               ->show();
						//}

                            $ui->input()
                               ->label('Mother\'s Occupation<span style= "color:red;"> *</span>')
                               ->id('mother_occupation')
                               ->name('mother_occupation')
							   ->required()
                               ->value($student_other_details['mothers_occupation'])
                               ->show();

                            $ui->input()
                               ->label('Mother\'s Gross Annual Income<span style= "color:red;"> *</span>')
                               ->id('mother_gross_income')
                               ->name('mother_gross_income')
							   ->required()
                               ->value($student_other_details['mothers_annual_income'])
                               ->show();

                        $student_mother_details_box->close();

                    $family_mother->close();

                    $family_guardian = $ui->col()
                                         ->width(4)
                                         ->open();

                        $student_guardian_details_box = $ui->box()
                                                           ->uiType('primary')
                                                           //->solid()
                                                           ->title('Guardian\'s Details')
                                                           ->open();

                            ?><input type='checkbox' id ="depends_on"  name="depends_on" unchecked /> <?php

                            echo '<label> Fill Guardian Details</label>';


                            $ui->input()
                               ->label('Guardian\'s Name<span style= "color:red;"> *</span>')
                               ->id('guardian_name')
							   ->required()
                               ->name('guardian_name')
                               ->value($student_other_details['guardian_name'])
                               ->disabled()
                               ->show();

                            $ui->input()
                               ->label('Relationship<span style= "color:red;"> *</span>')
                               ->id('guardian_relation_name')
							   ->required()
                               ->name('guardian_relation_name')
                               ->value($student_other_details['guardian_relation'])
                               ->disabled()
                               ->show();

                        $student_guardian_details_box->close();

                    $family_guardian->close();

                    $family_details_row->close();

                    $family_contact_details_row = $ui->row()
                                                     ->open();

                        $ui->input()
                           ->label('Parent/Guardian Mobile No<span style= "color:red;"> *  (Only numeric digits 0-9)</span>')
                           ->id('parent_mobile')
                           ->required()
                           ->value($student_details['parent_mobile_no'])
                           ->width(6)
                           ->name('parent_mobile')
                           ->show();

                       
                        $ui->input()
                           ->label('Parent/Guardian Landline No<span style= "color:red;"> (Only numeric digits 0-9)</span>')
                           ->id('parent_landline')
                           ->width(6)
                           ->value('')
                           ->name('parent_landline')
                           ->show();

                    $family_contact_details_row->close();

                $student_family_details_box->close();

                $student_admission_details_box = $ui->box()
                                                 ->uiType('primary')
                                                 ->solid()
                                                 ->title('Admission Details')
                                                 ->open();

                    $admission_details_row_1 = $ui->row()
                                                ->open();

                        $ui->input()
                           ->label('Migration Certificate No.')
                           ->width(6)
                           ->name('migration_cert')
                           ->value()
                           ->show();

                        $ui->input()
                           ->label('Roll No.')
                           ->id('roll_no')
                           ->name('roll_no')
                           ->placeholder('eg. IIT-JEE enrollment no.')
                           ->value()
                           ->width(6)
                           ->show();

                    $admission_details_row_1->close();

                $student_admission_details_box->close();

                $student_bank_details_box = $ui->box()
                                                   ->uiType('primary')
                                                   ->solid()
                                                   ->title('Bank Details')
                                                   ->open();

                    $bank_details_row_1 = $ui->row()
                                             ->open();

                        $ui->input()
                           ->label('Bank Name<span style= "color:red;"> *</span>')
                           ->name('bank_name')
                           ->value()
                           ->required()
                           ->width(6)
                           ->show();

                        $ui->input()
                           ->label('Bank Account No.<span style= "color:red;"> *</span>')
                           ->name('bank_account_no')
                           ->value()
                           ->required()
                           ->width(6)
                           ->show();

                    $bank_details_row_1 ->close();

                $student_bank_details_box->close();

                $student_fee_details_box = $ui->box()
                                              ->uiType('primary')
                                              ->solid()
                                              ->title('Details of Fees Payment at the time of Admission')
                                              ->open();

                    $fee_details_row_1 = $ui->row()
                                            ->open();

                        $ui->select()
                           ->label('Mode of Payment<span style= "color:red;"> *</span>')
                           ->name('fee_paid_mode')
						   ->id('payment_mode')
                           ->width(4)
                           ->options(array($ui->option()->value('dd')->text('DD'),
										   $ui->option()->value('cheque')->text('CHEQUE'),
                                           $ui->option()->value('online')->text('ONLINE TRANSFER'),
										   $ui->option()->value('challan')->text('CHALLAN')))
                           ->show();

                        $ui->datePicker()
                           ->label('Fees Paid Date<span style= "color:red;"> *</span>')
                           ->width(4)
						   ->required()
                           ->name('fee_paid_date')
                           ->value(date('d-m-Y'))
                           ->dateFormat('dd-mm-yyyy')
                           ->show();
						?><div id="fee_receipt_no"><?
                        $ui->input()
                           ->label('DD/CHEQUE/ONLINE/CASH NO.<span style= "color:red;"> *</span>')
                           ->name('fee_paid_dd_chk_onlinetransaction_cashreceipt_no')
                           ->id('fee_paid_dd_chk_onlinetransaction_cashreceipt_no')
                           ->value()
						   ->required()
                           ->width(4)
                           ->show();
						?></div><?
                        $ui->input()
                           ->label('Fees Paid Amount<span style= "color:red;"> *</span>')
                           ->name('fee_paid_amount')
                           ->id('fee_paid_amount')
						   ->required()
                           ->value('')
                           ->width(4)
                           ->show();

					   $ui->input()
							->type('file')
							->label('Fee Reciept<span style= "color:red;"> *</span>')
							->id('slip')
							->width(8)
							->name('slip')
							->required()
							->show();

                    $fee_details_row_1 ->close();

                $student_fee_details_box->close();

                $student_address_details_box = $ui->box()
                                                  ->uiType('primary')
                                                  ->solid()
                                                  ->title('Address Details')
                                                  ->open();

                    $state1_array = array();
                    foreach ($states as $row)
                    {
                        $state1_array[] = $ui->option()->value($row->state_name)->text(ucwords($row->state_name));
                        $state1_array = array_values($state1_array);
                    }

                    $state2_array = array();
					
					if($permanent_addr->state!='')
					{
                    	foreach ($states as $row)
                    	{
                        	$state2_array[] = $ui->option()->value($row->state_name)->text(ucwords($row->state_name))->selected($permanent_addr->state == $row->state_name);
                        	$state2_array = array_values($state2_array);
                    	}
					}
					else
					{
						foreach ($states as $row)
                    	{
                        	$state2_array[] = $ui->option()->value($row->state_name)->text(ucwords($row->state_name));
                        	$state2_array = array_values($state2_array);
                    	}
					}

                    $address_details_row_1 = $ui->row()
                                                ->open();

                        $address_col_1 = $ui->col()
                                          ->width(6)
                                          ->open();

                        $present_address_details_box = $ui->box()
                                                          ->uiType('primary')
                                                          //->solid()
                                                          ->title('Present Address')
                                                          ->open();

                            $ui->input()
                               ->label('Address Line 1<span style= "color:red;"> *</span>')
                               ->name('line11')
                               ->required()
                               ->show();

                            $ui->input()
                               ->label('Address Line 2')
                               ->name('line21')
                               ->show();

                            $ui->input()
                               ->label('City<span style= "color:red;"> *</span>')
                               ->name('city1')
                               ->required()
                               ->show();

                            $ui->select()
                               ->label('State<span style= "color:red;"> *</span>')
                               ->name('state1')
                               ->options($state1_array)
                               ->show();

                            $ui->input()
                               ->label('Pincode<span style= "color:red;"> * (Only numeric digits 0-9)</span>')
                               ->name('pincode1')
                               ->id('pincode1')
                               ->required()
                               ->show();

                            $ui->input()
                               ->label('Country<span style= "color:red;"> *</span>')
                               ->name('country1')
                               ->required()
                               ->show();

                            $ui->input()
                               ->label('Contact No.<span style= "color:red;"> * (Only numeric digits 0-9)</span>')
                               ->name('contact1')
                               ->id('contact1')
                               ->required()
                               ->show();

                        $present_address_details_box->close();

                        $address_col_1->close();

                        $address_col_2 = $ui->col()
                                          ->width(6)
                                          ->open();

                        $permanent_address_details_box = $ui->box()
                                                          ->uiType('primary')
                                                          //->solid()
                                                          ->title('Permanent Address')
                                                          ->open();

                            $ui->input()
                               ->label('Address Line 1<span style= "color:red;"> *</span>')
                               ->name('line12')
                               ->value($permanent_addr->line1)
                               ->required()
                               ->show();

                            $ui->input()
                               ->label('Address Line 2')
                               ->name('line22')
                               ->value($permanent_addr->line2)
                               ->show();

                            $ui->input()
                               ->label('City<span style= "color:red;"> *</span>')
                               ->name('city2')
                               ->value($permanent_addr->city)
                               ->required()
                               ->show();

                            $ui->select()
                               ->label('State<span style= "color:red;"> *</span>')
                               ->name('state2')
                               ->options($state2_array)
                               ->show();

						if($permanent_addr->pincode=='0')	$pin='';
						else $pin=$permanent_addr->pincode;

                            $ui->input()
                               ->label('Pincode<span style= "color:red;"> * (Only numeric digits 0-9)</span>')
                               ->name('pincode2')
                               ->id('pincode2')
                               ->value($pin)
                               ->required()
                               ->show();

                            $ui->input()
                               ->label('Country<span style= "color:red;"> *</span>')
                               ->name('country2')
                               ->value($permanent_addr->country)
                               ->required()
                               ->show();

                            $ui->input()
                               ->label('Contact No.<span style= "color:red;"> * (Only numeric digits 0-9)</span>')
                               ->name('contact2')
                               ->id('contact2')
							   ->value($mobile_no)
                               ->required()
                               ->show();

                        $permanent_address_details_box->close();

                        $address_col_2->close();

                    $address_details_row_1 ->close();

                    $address_details_row_2 = $ui->row()
                                                ->open();

                        $check_corr_address_col_0 = $ui->col()
                                                       ->width(3)
                                                       ->open();
                        $check_corr_address_col_0->close();

                        /*$check_corr_address_col_1 = $ui->col()
                                                       ->width(1)
                                                       ->open();

                            $ui->checkbox()
                               ->name('correspondence_addr')
                               ->id('correspondence_addr')
                               ->checked(!$coress_recv)
                               ->show();

                        $check_corr_address_col_1->close();*/

                        $check_corr_address_col_2 = $ui->col()
                                                       ->width(7)
                                                       ->open();

                            ?><input type='checkbox' id ="correspondence_addr"  name="correspondence_addr" checked ?><?php

                            echo '<label>Correspondence address same as Permanent address.</label>';

                        $check_corr_address_col_2->close();

                    $address_details_row_2 ->close();

                    ?><div id="corr_addr_visibility"><?php

                    $address_details_row_3 = $ui->row()
                                                ->open();

                        $corr_address_col_1 = $ui->col()
                                                 ->width(3)
                                                 ->open();

                        $corr_address_col_1->close();

                        $corr_address_col_2 = $ui->col()
                                                 ->width(6)
                                                 ->open();

                            $correspondence_address_details_box = $ui->box()
                                                          ->uiType('primary')
                                                          ->solid()
                                                          ->title('Correspondence Address')
                                                          ->open();

								$state3_array = array();
                                foreach ($states as $row)
                                {
                                    $state3_array[] = $ui->option()->value($row->state_name)->text(ucwords($row->state_name));
                                    $state3_array = array_values($state3_array);
                                }
								
                                $ui->input()
                                   ->label('Address Line 1<span style= "color:red;"> *</span>')
                                   ->name('line13')
								   ->id('line13')
                                   ->value()
                                   ->show();

                                $ui->input()
                                   ->label('Address Line 2')
								   ->id('line23')
                                   ->name('line23')
                                   ->value()
                                   ->show();

                                $ui->input()
                                   ->label('City<span style= "color:red;"> *</span>')
								   ->id('city3')
                                   ->name('city3')
                                   ->value()
                                   ->show();

                                $ui->select()
                                   ->label('State<span style= "color:red;"> *</span>')
								   ->id('state3')
                                   ->name('state3')
                                   ->options($state3_array)
                                   ->show();

                                $ui->input()
                                   ->label('Pincode<span style= "color:red;"> * (Only numeric digits 0-9)</span>')
                                   ->name('pincode3')
                                   ->id('pincode3')
                                   ->value()//$correspondence_address->pincode)
                                   ->show();

                                $ui->input()
                                   ->label('Country<span style= "color:red;"> *</span>')
								   ->id('country3')
                                   ->name('country3')
                                   ->value()//$correspondence_address->country)
                                   ->show();

                                $ui->input()
                                   ->label('Contact No.<span style= "color:red;"> * (Only numeric digits 0-9)</span>')
                                   ->name('contact3')
                                   ->id('contact3')
                                   ->value()//$correspondence_address->contact_no)
                                   ->show();
                                

                        $correspondence_address_details_box->close();

                        $corr_address_col_2->close();

                    $address_details_row_3 ->close();

                    ?></div><?php

                $student_address_details_box->close();

            $student_details_row->close();

            $student_educational_details_row = $ui->row()
                                ->open();
								

        $student_educational_details_box = $ui->box()
                                                      ->uiType('primary')
                                                      ->solid()
                                                      ->title('Educational Details<span style= "color:red;"> *</span>')
                                                      ->open();

                    $ui->input()
                       ->type('hidden')
                       ->value()//$stu_basic_details->type)
                       ->id('student_type')
                       ->show();

                  $educational_details_row = $ui->row()
                                                ->open();
                  $col = $ui->col()->width(12)->open();
                    $educational_details_row_1 = $ui->row()
                                                    ->open();


                        $table = $ui->table()
                                    ->responsive()
                                    ->id('tableid')
                                    ->hover()
                                    ->width(12)
                                    ->bordered()
                                    ->open();


                            echo '
                            <tr>
                                <th>S no.</th>
                                <th>Examination</th>
                                <th>Branch/Specialization</th>
                                <th>School/College/University/Institute</th>
                                <th>Year</th>
                                <th>Percentage/Grade</th>
                                <th>Class/Division</th>
                            </tr>';

                            $i=1;
              foreach($student_education_details as $row)
              {
                $year_array = array();
                              $year = 1926;
                              $present_year = date('Y');
                              while ($year <= $present_year)
                              {
                                if($row->year == $year)
                                  $year_array[] = $ui->option()->value($year)->text($year)->selected();
                                else
                                    $year_array[] = $ui->option()->value($year)->text($year);
                                  $year_array = array_values($year_array);
                                  $year++;
                              }

                              $class_div_array = array();

                              if($row->division=="first")
                                $class_div_array[] = $ui->option()->value('first')->text('FIRST')->selected();
                              else
                                $class_div_array[] = $ui->option()->value('first')->text('FIRST');

                              $class_div_array = array_values($class_div_array);

                              if($row->division=="second") 
                                $class_div_array[] = $ui->option()->value('second')->text('SECOND')->selected();
                              else
                                $class_div_array[] = $ui->option()->value('second')->text('SECOND');

                              $class_div_array = array_values($class_div_array);

                            if($row->division=="third") 
                                $class_div_array[] = $ui->option()->value('third')->text('THIRD')->selected();
                              else
                                $class_div_array[] = $ui->option()->value('third')->text('THIRD');

                              $class_div_array = array_values($class_div_array);

                            if($row->division=="na") 
                                $class_div_array[] = $ui->option()->value('na')->text('NA')->selected();
                              else
                                $class_div_array[] = $ui->option()->value('na')->text('NA');
                echo '
                              <tr name="row[]" id="addrow" align="center">
                                  <td id="sno">'.$i.'</td>
                                  <td>';$ui->input()
                                           ->name('exam4[]')
                                           ->value(strtoupper($row->exam))
                                           ->show();echo'</td>
                                  <td>';$ui->input()
                                           ->name('branch4[]')
                                           ->value(strtoupper($row->specialization))
                                           ->show();echo'</td>
                                  <td>';$ui->input()
                                           ->name('clgname4[]')
                                           ->value(strtoupper($row->institute))
                                           ->show();echo'</td>
                                  <td>';$ui->select()
                                           ->name('year4[]')
                                           ->options($year_array)
                                           ->show();echo'</td>
                                  <td>';$ui->input()
                                           ->name('grade4[]')
                                           ->value(strtoupper($row->grade))
                                           ->show();echo'</td>
                                  <td>';$ui->select()
                                           ->name('div4[]')
                                           ->options($class_div_array)
                                           ->show();echo'</td>
                              </tr>';
                              $i++;
                          }

                        $table->close();
                    

                    $educational_details_row_1->close();

                    $educational_details_row_2 = $ui->row()
                                                    ->open();

                        $educational_detail_col_1 = $ui->col()
                                                       ->width(4)
                                                       ->open();
                        $educational_detail_col_1->close();

                        $educational_detail_col_2 = $ui->col()
                                                       ->width(2)
                                                       ->open();

                            $ui->button()
                               ->block()
                               ->value('Add More')
                               ->id('add')
                               ->name('add')
                               ->show();

                        $educational_detail_col_2->close();
						
						$educational_detail_col_3 = $ui->col()
                                                       ->width(2)
                                                       ->open();

                            $ui->button()
                               ->block()
                               ->value('Remove Last Row')
                               ->id('remove')
                               ->name('remove')
                               ->show();

                        $educational_detail_col_3->close();

                    $educational_details_row_2->close();
$col->close();
                  $educational_details_row->close();

                $student_educational_details_box->close();
         
          $student_educational_details_row->close();

          $studenteditable_details = $ui->row()
                                  ->open();

                 $student_editable_details_box = $ui->box()
                                                  ->uiType('primary')
                                                  ->solid()
                                                  ->title('Editable Details')
                                                  ->open();

                    $editable_details_row_1 = $ui->row()
                                                 ->open();

                        $ui->input()
                           ->label('Email<span style= "color:red;"> *</span>')
                           ->name('email')
                           ->type('email')
                           ->required()
                           ->value($email)//$user_details->email)
                           ->width(3)
                           ->show();

                        $ui->input()
                           ->label("Parent's Email<span style= 'color:red;'> *</span>")
                           ->name('alternate_email_id')
                           ->id('alternate_email_id')
                           ->type('email')
                           ->value()
						   ->required()
                           ->width(3)
                           ->show();

                        $ui->input()
                           ->label('Mobile No.<span style= "color:red;"> * (Only numeric digits 0-9)</span>')
                           ->name('mobile')
                           ->id('mobile')
                           ->required()
                           ->value($mobile_no)
                           ->width(3)
                           ->show();



                        $ui->input()
                           ->label('Alternate Mobile No.<span style= "color:red;"> (Only numeric digits 0-9)</span>')
                           ->name('alternate_mobile')
                           ->id('alternate_mobile')
                           ->value('')//$alternate_mobile_number)
                           ->width(3)
                           ->show();


                    $editable_details_row_1 ->close();

                    $editable_details_row_2 = $ui->row()
                                                 ->open();

                        $ui->input()
                           ->label('Hobbies')
                           ->name('hobbies')
                           ->width(3)
                           ->id('hobbies')
                           ->value()//$user_other_details->hobbies)
                           ->show();

                        $ui->input()
                           ->label('Favourite Pass Time')
                           ->name('favpast')
                           ->id('favpast')
                           ->value()//$user_other_details->fav_past_time)
                           ->width(3)
                           ->show();

                        $ui->input()
                           ->label('Extra-Curricular Activities ( if any):')
                           ->name('extra_activity')
                           ->id('extra_activity')
                           ->value()//$stu_other_details->extra_curricular_activity)
                           ->width(3)
                           ->show();

                        $ui->input()
                           ->label('Any other relevant information')
                           ->name('any_other_information')
                           ->id('any_other_information')
                           ->value()//$stu_other_details->other_relevant_info)
                           ->width(3)
                           ->show();


                    $editable_details_row_2 ->close();

                $student_editable_details_box->close();

            $studenteditable_details->close();

            $photedit_row_0 = $ui->row()
                                 ->open();

                $photoedit_box_1 = $ui->box()
                                      ->title('Profile Pic<span style= "color:red;"> *</span>')
                                      ->solid()
                                      ->uiType('primary')
                                      ->open();

                    $photoedit_row_4 = $ui->row()
                                          ->open();

                        $photoedit_col_4_1 = $ui->col()
                                                ->width(4)
                                                ->open();

                        $photoedit_col_4_1->close();

                        $photoedit_col_4_2 = $ui->col()
                                                ->width(4)
                                                ->open();

                            
                                echo '<img src="'.base_url().'assets/images/student/noProfileImage.png" id="view_photo" width="145" height="150"/>';
                            

                        $photoedit_col_4_2->close();

                    $photoedit_row_4->close();

                    $photoedit_row_1 = $ui->row()
                                          ->open();

                        $upload_img = $ui->imagePicker()
                                         ->id('photo')
                                         ->name('photo')
                                         ->width(12)
                                         ->show();

                    $photoedit_row_1->close();

                $photoedit_box_1->close();

            $photedit_row_0->close();

            $student_details_row_2 = $ui->row()
                                          ->open();

                    $student_details_2_1 = $ui->col()
                                              ->width(4)
											  ->m_width(0)
											  ->t_width(0)
                                              ->open();

                        $student_details_2_1->close();
				$col_buttn_full=$ui->col()->width(4)->m_width(12)->t_width(12)->open();
					$col_buttn1=$ui->col()->width(3)->m_width(7)->t_width(7)->open();
                        $ui->button()
                           ->submit(true)
                           ->value('Submit')
                           ->id('submit_button_id')
						   ->uiType('primary')
						   ->icon($ui->icon('check'))
                           ->width(12)
                           ->show();
						   $col_buttn1->close();
						   
						   $col_buttn2=$ui->col()->width(4)->m_width(5)->t_width(5)->open();
						   ?><a href="<?= site_url('login/logout') ?>"><?
                        $ui->button()
                           ->value('Logout without submittion')
                           ->uiType('danger')
						   ->icon($ui->icon('times'))
                           ->id('logout')
                           ->width(12)
                           ->show();
						?></a><?
						$col_buttn2->close();
                $student_details_row_2->close();
			$col_buttn_full->close();
		$form->close();
$bodycol->close();
$boxbody->close();

//$padcol2=$ui->col()->width(1)->m_width(0)->t_width(0)->open();
//	$padcol2->close();
?>