<?php 
	function main_nav() {
	foreach ($GLOBALS["menu"] as $elem2) {
					     foreach ($elem2 as $elem)
						 	{ if (key($elem2) == 'tasks') {
							 	
							 	
							 	include 'engine/backend/nav/tasks.php';
					            						 	
							 	
							 	} else {
							    echo '<li class="nav-item pb-2"><a class="nav-link" href="'.$elem[0].'"><i class="'.$elem[2].' mr-2"></i> '.$elem[1].'</a></li>';
					            }
			              }
				     }
	}