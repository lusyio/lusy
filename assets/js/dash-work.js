$(document).ready(function(){
loadLog();
LogUp();
loadTaskTotal();

var timerId = setInterval(function() {
updateLog();
}, 1000);

function LogUp() {
	$(".timeline").animate({
    	bottom: '0px'
	}, "slow");
}


function loadLog() {
	$.post("/ajax.php", {ajax: 'log' },loadLogSuccess);
	function loadLogSuccess(data) {
		$('.timeline').html(data).fadeIn();
	}
}

function loadTaskTotal() {
	$.post("/ajax.php", {ajax: 'task-total' },loadTaskTotal);
	function loadTaskTotal(data) {
		$('#totaltasks').html(data).fadeIn();
	}
}

function updateLog() {
	$.post("/ajax.php", {param1: $a, ajax: 'log-newelement' },loadElement);
	function loadElement(data) {
		$new = data;
		if ($a != $new) {
	  		$('.timeline').prepend($new);	
	  		$a = $(".newitem").attr("id");
		  	$(".newitem").animate({
		    	opacity: 1,
		    	height: "150px",
		    	margin: "20px 0px"
			}, "slow");
			$('.newitem').removeAttr('id');	
			loadTaskTotal();
			updateNav();
		}
	
		
	}
}

function updateNav() {
	$.post("/ajax.php", {ajax: 'nav-update' },loadTaskTotal);
	function loadTaskTotal(data) {
		$('#nav-tasks').html(data).fadeIn();
	}	
}

}); 

