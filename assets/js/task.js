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
	$( "#sendpostpone" ).click(function() {
		var datepostpone = $("#example-date-input").val();
		var text = $("#reportarea1").val();
		if (text) {
			$.post("/ajax.php", {module: 'sendpostpone', text: text, datepostpone: datepostpone, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
				location.reload();
			}
		} else {
			$("#reportarea1").addClass('border-danger');
		}
	});

	// Манагер ставит дату
	$( "#sendDate" ).click(function() {
		var sendDate = $("#example-date-input").val();
		if (sendDate) {
			$.post("/ajax.php", {module: 'sendDate', sendDate: sendDate, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
			}
		} else {
			$("#example-date-input").addClass('border-danger');
		}
	});

	// Манагер принимает дату
	$("#confirmDate").click(function () {
		$.post("/ajax.php", {module: 'confirmDate', usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
		function controlUpdate(data) {
			location.reload();
		}
	});

	// Манагер отменят дату
	$("#cancelDate").click(function () {
		$.post("/ajax.php", {module: 'cancelDate', usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
		function controlUpdate(data) {
			location.reload();
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
		// var datepostpone = $("#example-date-input").val();
		var text = $("#reportarea").val();
		if (text) {
			$.post("/ajax.php", {module: 'workreturn', text: text, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
			function controlUpdate(data) {
				location.reload();
			}
		} else {
			$("#reportarea").addClass('border-danger');
		}
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


	//Отмена таска
	$( "#cancelTask" ).click(function() {
		$.post("/ajax.php", {module: 'cancelTask', usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
		function controlUpdate(data) {
			location.reload();
		}
	});


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


	var dateControl = document.querySelector('input[type="date"]');
	var d1 = new Date();
	var curr_date = d1.getDate();
	var curr_month = d1.getMonth() + 1;
	var curr_year = d1.getFullYear();
	if(curr_month<10) {
		curr_month = '0' + curr_month
	}
	if(curr_date<10) {
		curr_date = '0' + curr_date
	}
	dated = curr_year + "-" + curr_month + "-" + curr_date;
	dateControl.min = dated;
	dateControl.value = dated;


});