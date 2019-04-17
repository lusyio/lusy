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

// Кнопка принять для worker'a (в статусе "на рассмотрении""), переводит в статус done - завершен

$( "#workdone" ).click(function() {
		// var report = $("#reportarea").val();
		// if (report) {
			$.post("/ajax.php", {module: 'workdone', usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
			}
		// } else {
		// 	$("#reportarea").addClass('border-danger');
		// }
	});

// Кнопка "принять" для worker'a (в статусе "на рассмотрении"")

$( "#workreturn" ).click(function() {
		// var report = $("#reportarea").val();
		// if (report) {
			$.post("/ajax.php", {module: 'workreturn', usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
			}
		// } else {
		// 	$("#reportarea").addClass('border-danger');
		// }
	});

// Кнопка "В работу" для worker'a (на странице "возвращен")

$( "#inwork" ).click(function() {
		// var report = $("#reportarea").val();
		// if (report) {
			$.post("/ajax.php", {module: 'inwork', usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
			}
		// } else {
		// 	$("#reportarea").addClass('border-danger');
	});

// Минимальная дата
	$('#minMaxExample').datepicker({
		minDate: new Date()
	});


// перенос
	$( "#okdatapicker" ).click(function() {
		var newDated = window.dated;
		$.post("/ajax.php", {module: 'sendpostpone', datepostpone: newDated, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
			}
		if(newDated === undefined || newDated === null || newDated.length > 10) {

			$("input.datepicker-here").css("border-color", "#dc3545");
			// alert('Введите дату');
		}else {
			$("input.datepicker-here").css("border-color", "#28a745");
		}
		$("#okdatapicker").off('click');
	});


//Подсказка статус-бар
	$(".icon-title-status-bar").on("click", function () {
		alert("xad");
		// var statustitle = $(this).parent().find('.statustitle');
		// statustitle.fadeIn(500);
		//
		// $('.icon-title-status-bar').on('mouseleave', function () {
		// 	statustitle.fadeOut(300);
		// });
	});

}); 