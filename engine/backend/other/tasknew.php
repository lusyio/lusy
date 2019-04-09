<?php
	if (!empty($_POST['name']) and !empty($_POST['description']) and !empty($_POST['worker'])) {
	    createTask($_POST['name'],$_POST['description'],$_POST['worker'],$_POST['datedone']);
	}