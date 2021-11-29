
//-------------------------------------------medical history---------------------------
$(document).ready(function(){

	$("#epilepsy").hide();
	$('#epi').parent().parent().click(function(){
		clicker('#epi','#epilepsy');
	});

	$('#epi').next().click(function(){
		clicker('#epi','#epilepsy');
	});



	
	$("#asthma").hide();
	$('#ast').parent().parent().click(function(){
		clicker('#ast','#asthma');
	});
	$('#ast').parent().parent().click(function(){
		clicker('#ast','#asthma');
	});



	$("#heart").hide();
	$('#hrt').parent().parent().click(function(){
		clicker('#hrt','#heart');
	});
	$('#hrt').next().click(function(){
		clicker('#hrt','#heart');
	});



	$("#diabetes").hide();
	$('#dib').parent().parent().click(function(){
		clicker('#dib','#diabetes');
	});

	$('#dib').next().click(function(){
		clicker('#dib','#diabetes');
	});



	$("#hypertension").hide();
	$('#hyp').parent().parent().click(function(){
		clicker('#hyp','#hypertension');
	});
	$('#hyp').next().click(function(){
		clicker('#hyp','#hypertension');
	});



	$("#psychiatric").hide();
	$('#psy').parent().parent().click(function(){
		clicker('#psy','#psychiatric');
	});
	$('#psy').next().click(function(){
		clicker('#psy','#psychiatric');
	});
	
	$("#eye_infection").hide();
	$('#eye').parent().parent().click(function(){
		clicker('#eye','#eye_infection');
	});
	$('#eye').next().click(function(){
		clicker('#eye','#eye_infection');
	});


	$("#ear_infection").hide();
	$('#ear').parent().parent().click(function(){
		clicker('#ear','#ear_infection');
	});
	$('#ear').next().click(function(){
		clicker('#ear','#ear_infection');
	});



	$("#skin_disease").hide();
	$('#skin').parent().parent().click(function(){
		clicker('#skin','#skin_disease');
	});
	$('#skin').next().click(function(){
		clicker('#skin','#skin_disease');
	});
	
	//------------------------------------text area--------------------------
	
	$('.help-block').css('font-weight', 'bold');
	$('.message').keyup(function () {
		max = this.getAttribute("maxlength");
		var len = $(this).val().length;
		if (len >= max) {
			$(this).next().text(' you have reached the limit');
			$(this).next().css('color', 'red');
		} else {
			$(this).next().css('color', 'green');
			var char = max - len;
			$(this).next().text(char + ' characters left');
		}
	});

});

function clicker(e1,e2){
	if($(e1).parent().attr('aria-checked')=='true'){
		console.log('hh');
		$(e2).show();
		$(e2).prop('required',true);
	}
	else
	{	console.log('hh');
		$(e2).hide();
		$(e2).prop('required',false);
	}

}

//------------------------------------instruction model --------------------------------------------

$(document).ready(function(){
	$("#close").hide();
	$('#modelterm').parent().parent().click(function(){
		showHide('#modelterm','#close');
	});

	$('#modelterm').next().click(function(){
		showHide('#modelterm','#close');
	});

	$("#instructionModal").modal({
		backdrop: 'static',
		keyboard: false
	});

});
function showHide(e1,e2) {

	if($(e1).parent().attr('aria-checked')=='true')
	{   //alert('hi');
		$(e2).show();
		
	}
	else
	{	
		$(e2).hide();
	}
}

//----------------------------student applly button---------------------------------------------

$(document).ready(function(){
	$("#apply").hide();
	$('#term').parent().parent().click(function(){
		showHide('#term','#apply');
	});


	$('#term').next().click(function(){
		showHide('#term','#apply');
	});
});

//----------------------------Employee Family member  fetch details------------------------------

$(document).ready(function(){
	$('#mem_id').prop('selectedIndex',0);
	$('#Apply').prop('disabled','true');


	$('#mem_id').on('change',function(){
		var sno = $('#mem_id').val();
		var url = base_url()+'index.php/swimming/ajax/get_dep_details';
		console.log(url);

		$.ajax({
			url:url,
			type:'POST',
			data:{
				sno: sno,
			},
			success:function(text){
				console.log(text);
				var ret = JSON.parse(text);
				console.log(ret);
				$('#relation').prop('value',ret['relation']);
				$('#age').prop('value',ret['age']);
				$('#sex').prop('value',ret['sex']);

				var total = ret['total'];
				var reg_status = ret['reg_status'];
				var filled = ret['filled'];
				$('#fill_total').prop('value',filled+'/'+total);

				if(reg_status==1)
				{
					console.log(reg_status+'hi');
					$('#error-close').show();
					$('#Apply').prop('disabled',true);
				}
				else if(total<filled){
					$('#error').show();
					$('#Apply').prop('disabled',true);
				}
				else{
					$('#error').hide();
					$('#Apply').prop('disabled',false);

				}
			}
		});
	});
});

//-------------------------------swimming section application approval------------------------
$(document).ready(function(){
	$('#reject').click(function(){
		$("#remarks").prop('required',true);
	});
	$('#approve').click(function(){
		$("#remarks").prop('required',false);
	});
});


//------------------id card-------------------------------------------------------------------

$(document).ready(function(){
		$("#print").on("click",function(){
			//alert('hi');
			$("#print").hide();
			$("#download").hide();
			$(".card").css({
				'margin-right':'24%',
				'margin-left':'24%'
			});
			window.print();
			$("#print").show();
			$("#download").show();
			$(".card").css({
				'margin-right':'0',
				'margin-left':'0'
			});
		});
	});
	
//------------------------------student slot allocation--------------------------------------
$(function() {
		$("#candidate").dataTable({
			"bPaginate": true,
			"bLengthChange": true,
			"lengthMenu": [ 5,10,15,20,25,30,35,40,45,50 ],
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true
		});
	});

	$(document).ready(function(){
		$("#group_code").on("change",function(){
			var group_code=$("#group_code").val();
			var url=base_url()+'index.php/swimming/swimming_ajax/get_slot_by_group_code';
			//console.log(url);
			$.ajax({
				url: url,
				type: 'POST',
				data: {
					code : group_code,
				},      
				success: function(result)
				{	
					//alert(result);
					$("#slot_id").html(result);
				}      
	       });

		});
	});
//-------------------------------employee slot allocation---------------------------------------
$(function() {
		$("#employee").dataTable({
			"bPaginate": true,
			"bLengthChange": true,
			"lengthMenu": [ 5,10,15,20,25,30,35,40,45,50 ],
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bAutoWidth": true
		});
	});

	$(document).ready(function(){
		$("#group_code").on("change",function(){
			var group_code=$("#group_code").val();
			var url=base_url()+'index.php/swimming/swimming_ajax/get_slot_by_group_code';
			//console.log(url);
			$.ajax({
				url: url,
				type: 'POST',
				data: {
					code : group_code,
				},
				success: function(result)
				{
					//alert(result);
					$("#slot_id").html(result);
				}
	       });

		});
	});

	