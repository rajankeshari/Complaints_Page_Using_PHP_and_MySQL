$(document).ready(function(){
	$('#a_total_charges').on('keyup',function(){
          var a=this.value;
          var b=(a*(24.5))/100;
          var c=(a*(3.5))/100;
          var d=(a*(1.75))/100;
          var e=$('#a_services_tax').val();
          var f=a-(-e);
          
          $('#b_institue_support_charges').val(b);
          $('#b_department_devlopment_fund').val(c);
          $('#b_professional_devlopment_fund').val(c);
          $('#b_benevolent_fund').val(d);
          $('#b_central_administrative_charges').val(d);
          $('#a_total_amount').val(f);
          //$('#b_total_credit').val(e);
          
    });
    $('#a_services_tax').on('keyup',function(){
          var e=this.value;
          //var i=0;
          //i=this.value('#a_total_charges');
          i=$('#a_total_charges').val();
          $('#b_services_tax').val(e);
          if(i!='')
          {
               var b=i-(-e);
               $('#a_total_amount').val(b);
          }
          
    });
    $('#a_actual_expenditure').on('keyup',function(){
          var f=$('#a_total_amount').val();
          var c=this.value;
          if(f!='')
          {
               var b=f-c;
               $('#a_balance_available').val(b);
          }

    });
    /*************************service tax******************************/
    $('#b_services_tax').on('keyup',function(){
          var a=this.value;
          //var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /************************institue support charge*************************/
    $('#b_institue_support_charges').on('keyup',function(){
          var b=this.value;
          var a=$('#b_services_tax').val();
          //var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /**********************department development fund*****************************/
    $('#b_department_devlopment_fund').on('keyup',function(){
          var c=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          //var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /*****************professional development************************/
    $('#b_professional_devlopment_fund').on('keyup',function(){
          var d=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          //var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /*******************benevolent_fund**************************/
    $('#b_benevolent_fund').on('keyup',function(){
          var e=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          //var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /*********************central admin***********************/
    $('#b_central_administrative_charges').on('keyup',function(){
          var f=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          //var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /*****************edc dev***********************/
    $('#b_edc_development_fund').on('keyup',function(){
          var g=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          //var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /*******************edc lodging***********************/
    $('#b_edc_lodging_boarding').on('keyup',function(){
          var h=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          //var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /*****************edc_xeroxing**************************/
    $('#b_edc_xeroxing').on('keyup',function(){
          var i=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          //var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /*********************ism vech***********************************/
    $('#b_ism_vehicle').on('keyup',function(){
          var j=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          //var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /******************alumni fund**************************************/
    $('#b_alumni_fund').on('keyup',function(){
          var k=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          //var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /**********************equip charge**********************************/
    $('#b_equipment_charges').on('keyup',function(){
          var l=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          //var l=$('#b_equipment_charges').val();
          var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /********************************************************/
    $('#b_other_payments').on('keyup',function(){
          var m=this.value;
          var a=$('#b_services_tax').val();
          var b=$('#b_institue_support_charges').val();
          var c=$('#b_department_devlopment_fund').val();
          var d=$('#b_professional_devlopment_fund').val();
          var e=$('#b_benevolent_fund').val();
          var f=$('#b_central_administrative_charges').val();
          var g=$('#b_edc_development_fund').val();
          var h=$('#b_edc_lodging_boarding').val();
          var i=$('#b_edc_xeroxing').val();
          var j=$('#b_ism_vehicle').val();
          var k=$('#b_alumni_fund').val();
          var l=$('#b_equipment_charges').val();
          //var m=$('#b_other_payments').val();
          var n=a-(-b)-(-c)-(-d)-(-e)-(-f)-(-g)-(-h)-(-i)-(-j)-(-k)-(-l)-(-m);
          $('#b_total_credit').val(n);
          //$('#c_institue_development').val(d);
          //$('#c_dept_development').val(d);
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
    });
    /************************************************************************/
    /*$('#b_total_credit').on('keyup',function(){
          var g=this.value;
          var a1=$('#a_balance_available').val();
          var b1=a1-n;
          $('#c_total_credit').val(n);
          $('#c_balance_available').val(a1);
          $('#c_net_amount').val(b1);
          
    });*/
    $('#c_amount_released').on('keyup',function(){
          var a=this.value;
          var b=$('#c_net_amount').val();
          var c=b-a;
          var d=c/2;
          $('#c_net_savings').val(c);
          $('#c_institue_development').val(d);
          $('#c_dept_development').val(d);
    });
    
});