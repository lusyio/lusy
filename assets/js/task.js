$(document).ready(function(){
	
	
	// при загрузке обновляем комменты
	
	updateComments();
	
	// отправляем данные в функцию комментов
	$("#comment").click(function(){
		var $comment = $("#comin").val();
		if ($comment != '') {
			addComment($comment);
		} else {
			alert('Введите комментарий');
		}
		$('#comin').val('');
		
	});
	
	
	// функция загрузки комментариев
	function updateComments() {
		$.post("/ajax.php", {usp: $usp, it: $it,ajax: 'task-comments' },onCommentSuccess);
		function onCommentSuccess(data) {
			$('#comments').html(data).fadeIn();
		}
	}
	
	// функция добавления комментария
	function addComment(text) {
		$.post( "/ajax.php", { text: text, usp: $usp, it: $it, ajax: 'task-comments-new' })
		.done(function() {
			$("#comin").attr("disabled", true);
			$('#comment').html('<i class="fas fa-spinner fa-spin"></i>');
			$('#comments').fadeOut();
			setTimeout(function () {
				updateComments();
		    }, 200); 
		    setTimeout(function () {
			    $('#comment').html('<i class="fas fa-paper-plane"></i>');
			    $("#comin").attr("disabled", false);
			}, 500);
		});
		
	}
	
	 	
	// управление задачами
	
	// перенос срока задачи
	$("#postpone").click(function() {
		$('#status-block').addClass('d-none');
		$('#postpone-block').removeClass('d-none');
		
	});
	
	// отчет о завершении
	$( "#done" ).click(function() {
		$('#status-block').addClass('d-none');
		$('#report-block').removeClass('d-none');
	});
	
	// на рассмотрение
	$( "#sendonreview" ).click(function() {
		var report = $("#reportarea").val();
		if (report) {
			$.post("/ajax.php", {module: 'sendonreview', report: report, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
			}
		} else {
			$("#reportarea").addClass('border-danger');
		}
	});
	
	// перенос
	$( "#sendpostpone" ).click(function() {
		var datepostpone = $("#datepostpone").val();
		$.post("/ajax.php", {module: 'sendpostpone', datepostpone: datepostpone, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
			}
	});
}); 