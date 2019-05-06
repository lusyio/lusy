$(document).ready(function(){

//создание новой задачи
	$( "#createTask" ).click(function() {
		var name = $("#name").val();
		var description = $("#description").val();
		var datedone = $("#datedone").val();
		var worker = $("#worker").val();
		var attachedFile = $('input[type=file]').prop('files')[0];
		var fd = new FormData();
		fd.append('file', attachedFile);
		fd.append('module', 'createTask');
		fd.append('name', name);
		fd.append('description', description);
		fd.append('datedone', datedone);
		fd.append('worker', worker);
		fd.append('usp', $usp);
		fd.append('ajax', 'task-control');
		if (name != null && description != null && datedone != null && worker != null) {
			$.ajax({
				url: '/ajax.php',
				type: 'POST',
				cache: false,
				processData: false,
				contentType: false,
				data: fd,
				success: function(data){
					location.href = '/task/' + data + '/'
				},
			});
		} else {
				$("#reportarea").addClass('border-danger');
			}
		});
});