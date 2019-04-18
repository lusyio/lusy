<?php 
session_start();
include 'conf.php'; 
include 'engine/backend/other/header.php'; 
include 'engine/frontend/other/header.php';


function isColumnStatusExist()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `comments`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'status') {
            return true;
        }
    }
    return false;
}

if(!isColumnStatusExist())
{
    addColumnStatus();
    fillColumnStatus();
}

function addColumnStatus()
{
    global $pdo;
    $sql = 'alter table `comments` add `status` text not null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function fillColumnStatus()
{
    global $pdo;
    $sql = 'update `comments` set `status` = "comment"';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}


// проверка на страницы логина и подобные
if (!empty($_GET['folder'])) {
$folder = $_GET['folder'];
if (!empty($folder)) {
	if (in_array($folder, $pages)) {
		inc('other',$folder);
	}
}
}

include 'engine/backend/other/footer.php'; 
include 'engine/frontend/other/footer.php';