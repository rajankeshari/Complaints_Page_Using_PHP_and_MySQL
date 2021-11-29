
$(document).ready(function(){

	var no_of_guests = parseInt($('select[name="group_guests"]').val());

	$('select[name="group_guests"]').change(function(){
		no_of_guests = parseInt($(this).val());
	});

	$('#app_detail_box').click(function(){
		$('#app_info').slideToggle();
	});


	//uncheck all checkboxes on changing #guests
	$('[name="guest_count"]').change(function(){
		$('input[type=checkbox]:checked').iCheck("uncheck");
	});

	//on submit
	$('#ckbox_btn').click(function(){
		var total_selected = 0;
		var ckbox = $('input[type=checkbox]:checked');
		for(var i = 0; i < ckbox.length; i++) {
			if(ckbox[i].getAttribute('data-room_type') === 'AC Suite')
				select_value = 1 - ckbox[i].getAttribute('data-checked');
			else
				select_value = 2 - ckbox[i].getAttribute('data-checked');

			total_selected += select_value;
		}
		if(total_selected < parseInt($('[name="guest_count"]').val())) {
			alert("Adjust no. of guests to match free space");
			return false;
		}
		if(!ckbox.length){
			alert("Select atleast one room!");
			return false;
		}
	});


	$('#drpdwn_btn').click(function(){
		if(!$('[name="rooms"]').val()){
			alert("Select atleast one room!");
			return false;
		}
	});
});
