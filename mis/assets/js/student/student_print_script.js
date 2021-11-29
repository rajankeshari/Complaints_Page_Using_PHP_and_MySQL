function printContent(id, style) {
	str=document.getElementById(id).innerHTML;
	newwin=window.open('','printwin','left=100,top=100,width=400,height=400');
	newwin.document.write('<HTML>\n<HEAD>\n');
	newwin.document.write('<TITLE>Print Page</TITLE>\n');
	newwin.document.write('<script>\n');
	newwin.document.write('function chkstate(){\n');
	newwin.document.write('if(document.readyState=="complete"){\n');
	newwin.document.write('window.close()\n');
	newwin.document.write('}\n');
	newwin.document.write('else{\n');
	newwin.document.write('setTimeout("chkstate()",2000)\n');
	newwin.document.write('}\n');
	newwin.document.write('}\n');
	newwin.document.write('function print_win(){\n');
	newwin.document.write('window.print();\n');
	newwin.document.write('chkstate();\n');
	newwin.document.write('}\n');
	newwin.document.write('<\/script>\n');
	newwin.document.write('<style type="text/css">'+style+'</style>');
	newwin.document.write('</HEAD>\n');
	newwin.document.write('<BODY onload="print_win()">\n');
	newwin.document.write(str);
	newwin.document.write('</BODY>\n');
	newwin.document.write('</HTML>\n');
	newwin.document.close();
}

$(document).ready(function() {
	$('#print_btn').click(function() {
		window.print();
		//printContent('print');
	});

	$('.pending, .rejected, #pending_photo, #pending_stu_education_details').hide();

	//for both pending and rejected
	$('#pending_pic').click(function(){
		$('#pending_photo').toggle();
		$('#pending_pic').html(($('#pending_pic').html() == 'Show')?	'Hide':'Show');
	});
	//for both pending and rejected
	$('#pending_basic').click(function(){
		$('.pending, .rejected').toggle();
		$('.pending').css("color", "#3A87AD");
		$('.rejected').css("color", "#d9534f");
		$('#pending_basic').html(($('#pending_basic').html() == 'Show')?	'Hide':'Show');
	});

	$('#pending_education').click(function(){
		$('#pending_stu_education_details').toggle();
		$('#pending_education').html(($('#pending_education').html() == 'Show')?	'Hide':'Show');
	});

	$('#rejected_education').click(function(){
		$('#pending_stu_education_details').toggle();
		$('#rejected_education').html(($('#rejected_education').html() == 'Show')?	'Hide':'Show');
	});

});