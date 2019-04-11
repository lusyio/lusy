$(document).ready(function(){
	
	
	

//создание новой задачи
	$( "#createTask" ).click(function() {
			var name = $("#name").val();
			var description = $("#description").val();
			var datedone = $("#datedone").val();
			var worker = $("#worker").val();

			if (name != null && description != null && datedone != null && worker != null) {
				$.post("/ajax.php", {module: 'createTask', name: name, description: description, datedone: datedone, worker: worker, usp: $usp, ajax: 'task-control' },controlUpdate);
				function controlUpdate(data) {
				//	location.reload();
					location.href = '/task/' + data + '/';
				}
			} else {
				$("#reportarea").addClass('border-danger');
			}
		});



}); 