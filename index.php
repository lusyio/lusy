<?php 
session_start();
include 'conf.php'; 
include 'engine/backend/other/header.php'; 
include 'engine/frontend/other/header.php';

function isPostponedateColumnNotNull()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `tasks`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'datepostpone' && $column['Null'] == 'NO') {
            return true;
        }
    }
    return false;
}

if (isPostponedateColumnNotNull())
{
    global $pdo;
    $sql = 'alter table tasks modify datepostpone date null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

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

function isUploadsTableExists()
{
    global $pdo;
    $query = "SHOW TABLES LIKE 'uploads'";
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetch();
    return $result;
}
if (!isUploadsTableExists()) {
    global $pdo;
    $query = 'create table uploads( file_id int auto_increment primary key, file_name text not null, file_size int null, file_path text null, comment_id int null)';
    $sql = $pdo->prepare($query);
    $sql->execute();
}

function isColumnCommentTypeExist()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `uploads`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'comment_type') {
            return true;
        }
    }
    return false;
}

function addColumnCommentType()
{
    global $pdo;
    $sql = 'alter table `uploads` add `comment_type` text not null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}
function fillColumnCommentType()
{
    global $pdo;
    $sql = 'update `uploads` set `comment_type` = "comment"';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

if (!isColumnCommentTypeExist()) {
    addColumnCommentType();
    fillColumnCommentType();
}

include 'engine/backend/other/footer.php';
include 'engine/frontend/other/footer.php';