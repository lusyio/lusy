<?php
$idcom = str_replace("#", "", $_POST['ic']);
$sql = "DELETE from comments where id='".$idcom."'";
$sql = $pdo->prepare($sql);
$sql->execute();
?>