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
	
	// // отчет о завершении
	$( "#done" ).click(function() {
		$('#status-block').addClass('d-none');
	// 	$('#report-block').removeClass('d-none');
	});

	
	// на рассмотрение
	$( "#sendonreview" ).click(function() {
		var text = $("#reportarea").val();
		if (text) {
			$.post("/ajax.php", {module: 'sendonreview', text: text, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
			}
		} else {
			$("#reportarea").addClass('border-danger');
		}
	});

	// Перенос срока
	$( "#sendRequest" ).click(function() {
		var text = $("#reportarea").val();
		if (text) {
			$.post("/ajax.php", {module: 'sendRequest', text: text, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
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

	//Отмена таска
	$( "#cancelTask" ).click(function() {
		$.post("/ajax.php", {module: 'cancelTask', usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
		function controlUpdate(data) {
			location.reload();
		}
	});

// 	Памятка
// 	<a href="delete.php?id=22" class="confirmation">Link</a>
// ...
// 	Include jQuery - see http://jquery.com
// 		<script type="text/javascript">
// 		$('.confirmation').on('click', function () {
// 			return confirm('Are you sure?');
// 		});
// </script>

	// $("#done").attr('onclick', 'this.style.opacity = "0.4"; return false;');

	// $("#done").click(function () {
	// 	$("#done").attr("disabled", true);
	// });

	$("#backbutton").click(function () {
		$("#status-block").removeClass('d-none');
	});

	$("#backbutton1").click(function () {
		$("#status-block").removeClass('d-none');
	});


	$( "#return-manager" ).click(function() {
		$('#status-block').addClass('d-none');
	});

	$( "#changeDate" ).click(function() {
		$('#status-block').addClass('d-none');
	});

	$("#datepicker-button").click(function () {
		$("#datepicker-button").addClass("datepicker-here");
	});

	$("#confirmDate").click(function () {
		$.post("/ajax.php", {module: 'confirmDate', usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
		function controlUpdate(data) {
			location.reload();
		}
	});

	$("#cancelDate").click(function () {
		$.post("/ajax.php", {module: 'cancelDate', usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
		function controlUpdate(data) {
			location.reload();
		}
	});

});