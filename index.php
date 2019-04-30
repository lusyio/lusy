<?php
session_start();
ob_start();
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

function isColumnCompanyIdExist()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `uploads`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'company_id') {
            return true;
        }
    }
    return false;
}

function addColumnCompanyId()
{
    global $pdo;
    $sql = 'alter table uploads add company_id int not null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}
//заполняет все строки значением '2'
function fillColumnCompanyId()
{
    global $pdo;
    $sql = 'update `uploads` set `company_id` = 2';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

if (!isColumnCompanyIdExist()) {
    addColumnCompanyId();
    fillColumnCompanyId();
}

function isColumnIsDeletedExist()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `uploads`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'is_deleted') {
            return true;
        }
    }
    return false;
}

function addColumnIsDeleted()
{
    global $pdo;
    $sql = 'alter table uploads add is_deleted int not null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}
//заполняет все строки значением '0'
function fillColumnIsDeleted()
{
    global $pdo;
    $sql = 'update `uploads` set `is_deleted` = 0';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

if (!isColumnIsDeletedExist()) {
    addColumnIsDeleted();
    fillColumnIsDeleted();
}

function isMailTableExists()
{
    global $pdo;
    $query = "SHOW TABLES LIKE 'mail'";
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetch();
    return $result;
}

if (!isMailTableExists()) {
    global $pdo;
    $query = 'create table mail (message_id int auto_increment primary key, mes text null, sender int null, recipient int null, datetime datetime null)';
    $sql = $pdo->prepare($query);
    $sql->execute();
}

function isuserSessionsTableExists()
{
    global $pdo;
    $query = "SHOW TABLES LIKE 'user_sessions'";
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetch();
    return $result;
}

if (!isuserSessionsTableExists()) {
    global $pdo;
    $query = 'create table user_sessions ( session_id int auto_increment, user_id int null, timestamp int(11) null, constraint user_sessions_pk primary key (session_id))';
    $sql = $pdo->prepare($query);
    $sql->execute();
}

//connection to comet-server

$cometUser = '2553';
$cometPass = 'ywg03ajXvYGrtoCp6pvy7GEFf4hFGW5IeXQMBtF8i53S7ZgfXHVyWmIVwqPGel8r';
$cometDsn = 'mysql:host=app.comet-server.ru;dbname=CometQL_v1';
$cometPdo = new PDO($cometDsn, $cometUser, $cometPass);
$cometPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


include 'engine/backend/other/footer.php';
include 'engine/frontend/other/footer.php';