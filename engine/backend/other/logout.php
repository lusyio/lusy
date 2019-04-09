<?php
	$_SESSION['auth'] = null;
	session_destroy();
	echo "<script>document.location.href = '/login/'</script>";
?>