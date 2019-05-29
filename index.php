<?php
session_start();
ob_start();
include 'conf.php';
if (!isIsFiredColumnExists())
{
    global $pdo;
    $sql = $pdo->prepare('alter table `users` add `is_fired` int default 0 null');
    $sql->execute();

//    $sql = $pdo->prepare('UPDATE users SET is_fired = 0');
//    $sql->execute();

}
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
            inc('other', $folder);
        }
    }
}
// Проверка на страницу восстановления пароля
if (isset($_GET['restore']) && isset($_GET['code']))
{
    inc('other', 'restore-password');
}
// Проверка на страницу активации аккаунта
if (isset($_GET['activate']) && isset($_GET['code']))
{
    inc('other', 'activate');
}
if (isset($_GET['join']))
{
    inc('other', 'join');
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

function isColumnAuthorExist()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `uploads`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'author') {
            return true;
        }
    }
    return false;
}
function addColumnAuthor()
{
    global $pdo;
    $sql = 'alter table `uploads` add `author` text null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

if (!isColumnAuthorExist()) {
    addColumnAuthor();
}

function isTaskCoworkersTableExists()
{
    global $pdo;
    $query = "SHOW TABLES LIKE 'task_coworkers'";
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetch();
    return $result;
}

if (!isTaskCoworkersTableExists()) {
    global $pdo;
    $query = 'create table task_coworkers
(
    task_coworker_id int auto_increment,
	task_id int null,
	worker_id int null,
	constraint task_coworkers_pk
		primary key (task_coworker_id)
)';
    $sql = $pdo->prepare($query);
    $sql->execute();

    $query = "INSERT INTO task_coworkers(task_id, worker_id) SELECT id, worker FROM tasks";
    $sql = $pdo->prepare($query);
    $sql->execute();
}

function isViewStatusColumnExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `tasks`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'view_status') {
            return true;
        }
    }
    return false;
}

if (!isViewStatusColumnExists())
{
    global $pdo;
    $sql = 'alter table tasks add view_status text null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isViewStatusColumnInCommentsExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `comments`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'view_status') {
            return true;
        }
    }
    return false;
}

if (!isViewStatusColumnInCommentsExists())
{
    global $pdo;
    $sql = 'alter table comments add view_status text null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isViewStatusColumnInMailExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `mail`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'view_status') {
            return true;
        }
    }
    return false;
}

if (!isViewStatusColumnInMailExists())
{
    global $pdo;
    $sql = 'alter table mail add view_status tinyint default 0 null;';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function iPasswordRestoreTableExists()
{
    global $pdo;
    $query = "SHOW TABLES LIKE 'password_restore'";
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetch();
    return $result;
}

if (!iPasswordRestoreTableExists()) {
    global $pdo;
    $query = 'create table password_restore
(
    pr_id int auto_increment,
	user_id int not null,
	code text not null,
	constraint password_restore_pk
		primary key (pr_id)
)';
    $sql = $pdo->prepare($query);
    $sql->execute();
}

function isActivatedColumnInCompanyExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `company`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'activated') {
            return true;
        }
    }
    return false;
}

if (!isActivatedColumnInCompanyExists())
{
    global $pdo;
    $sql = 'alter table company add activated int default 0 not null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $sql = 'update company set activated=1';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isCompanyActivationTableExists()
{
    global $pdo;
    $query = "SHOW TABLES LIKE 'company_activation'";
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetch();
    return $result;
}

if (!isCompanyActivationTableExists()) {
    global $pdo;
    $query = 'create table company_activation
(
    ca_id int auto_increment,
	company_id int not null,
	code text not null,
	constraint company_activation_pk
		primary key (ca_id)
)';
    $sql = $pdo->prepare($query);
    $sql->execute();
}

$sql = 'update company set premium = 0 where premium <> :premium';
$sql = $pdo->prepare($sql);
$sql->execute(array(':premium' => 1));

if (isCompanyTableInOldState())
{
    global $pdo;
    $sql = $pdo->prepare('alter table company change currency full_company_name text null');
    $sql->execute();
    $sql = $pdo->prepare('alter table company modify site text null');
    $sql->execute();
    $sql = $pdo->prepare('alter table company change plugins description text null');
    $sql->execute();
}

function isCompanyTableInOldState()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `company`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'currency') {
            return true;
        }
    }
    return false;
}

function isPhoneColumnNotNull()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'phone' && $column['Null'] == 'NO') {
            return true;
        }
    }
    return false;
}

if (isPhoneColumnNotNull())
{
    global $pdo;
    $sql = 'alter table users modify phone text null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isNameColumnNotNull()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'name' && $column['Null'] == 'NO') {
            return true;
        }
    }
    return false;
}

if (isNameColumnNotNull())
{
    global $pdo;
    $sql = 'alter table users modify name text null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isSurnameColumnNotNull()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'surname' && $column['Null'] == 'NO') {
            return true;
        }
    }
    return false;
}

if (isSurnameColumnNotNull())
{
    global $pdo;
    $sql = $pdo->prepare('alter table users modify surname text null');
    $sql->execute();
}

function isPointsColumnNotNull()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'points' && $column['Null'] == 'NO') {
            return true;
        }
    }
    return false;
}

if (isPointsColumnNotNull())
{
    global $pdo;
    $sql = $pdo->prepare('alter table users modify points text');
    $sql->execute();
    $sql = $pdo->prepare('alter table users modify points text null');
    $sql->execute();
}

function isActivityColumnNotNull()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'activity' && $column['Null'] == 'NO') {
            return true;
        }
    }
    return false;
}

if (isActivityColumnNotNull())
{
    global $pdo;
    $sql = $pdo->prepare('alter table users modify activity text');
    $sql->execute();
    $sql = $pdo->prepare('alter table users modify activity text null');
    $sql->execute();
}

function isInvitationsTableExists()
{
    global $pdo;
    $query = "SHOW TABLES LIKE 'invitations'";
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetch();
    return $result;
}

if (!isInvitationsTableExists()) {
    global $pdo;
    $query = 'create table invitations
(
    invite_id int auto_increment,
    company_id int not null,
    invitee_name int not null,
	code text not null,
	invite_date datetime not null,
	status tinyint not null,
	email text null,
	invitee_position text null,
	constraint invitations_pk
		primary key (invite_id)
)';
    $sql = $pdo->prepare($query);
    $sql->execute();
}
allToNullAndTextInUsersTable();

function allToNullAndTextInUsersTable()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] != 'id' && $column['Type'] != 'text') {
            $changeToTextQuery = $pdo->prepare('alter table users modify ' . $column['Field'] . ' text');
            $changeToTextQuery->execute();
        }
        if ($column['Field'] != 'id' && $column['Null'] == 'NO') {
            $changeToNotNullQuery = $pdo->prepare('alter table users modify ' . $column['Field'] . ' text null');
            $changeToNotNullQuery->execute();
        }

    }
    return false;
}

if (isInviteeNameColumnInInvitationsExists())
{
    global $pdo;
    $sql = 'alter table invitations drop column invitee_name';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isInviteeNameColumnInInvitationsExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `invitations`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'invitee_name') {
            return true;
        }
    }
    return false;
}

function isAuthorColumnExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `tasks`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'author') {
            return true;
        }
    }
    return false;
}

if (!isAuthorColumnExists())
{
    global $pdo;
    $sql = 'alter table tasks add author text null;';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $sql = 'UPDATE tasks SET author = manager;';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isEventsTableExists()
{
    global $pdo;
    $query = "SHOW TABLES LIKE 'events'";
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetch();
    return $result;
}

if (!isEventsTableExists()) {
    global $pdo;
    $query = 'create table events
(
    event_id int auto_increment,
	action text null,
	task_id int null,
	author_id int null,
	recipient_id int null,
	company_id int null,
	comment text null,
	datetime datetime null,
	view_status tinyint default 0 null,
	constraint events_pk
		primary key (event_id)
)';
    $sql = $pdo->prepare($query);
    $sql->execute();
}

function isRegisterDateColumnExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'register_date') {
            return true;
        }
    }
    return false;
}

if (!isRegisterDateColumnExists())
{
    global $pdo;
    $sql = 'alter table users add register_date date null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $sql = 'UPDATE tasks SET author = manager;';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isSocialNetworksColumnExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'social_networks') {
            return true;
        }
    }
    return false;
}

if (!isSocialNetworksColumnExists())
{
    global $pdo;
    $sql = 'alter table users add social_networks text null;';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isAboutColumnExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'about') {
            return true;
        }
    }
    return false;
}

if (!isAboutColumnExists())
{
    global $pdo;
    $sql = 'alter table users add about int null';
    $sql = $pdo->prepare($sql);
    $sql->execute();
}

function isIsFiredColumnExists()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'is_fired') {
            return true;
        }
    }
    return false;
}



//connection to comet-server

$cometUser = '2553';
$cometPass = 'ywg03ajXvYGrtoCp6pvy7GEFf4hFGW5IeXQMBtF8i53S7ZgfXHVyWmIVwqPGel8r';
$cometDsn = 'mysql:host=app.comet-server.ru;dbname=CometQL_v1';
$cometPdo = new PDO($cometDsn, $cometUser, $cometPass);
$cometPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//debug send e-mail
//require_once 'engine/phpmailer/LusyMailer.php';
//
//$mail = new \PHPMailer\PHPMailer\LusyMailer();
//
//$mail->addAddress('garifianov@gmail.com'); // Еще один email, если нужно.
//
//// Письмо
//$mail->isHTML(true);
//$mail->Subject = "Заголовок test"; // Заголовок письма
//$bodyArgs = [
//    'head1' => 'Заголовок 1',
//    'head2' => 'Заголовок 2, после 1',
//    'd' => 'DOLOR',
//];
//$mail->setMessageContent('testing', $bodyArgs); // Текст письма
//// Результат
//$mail->send();

include 'engine/backend/other/footer.php';
include 'engine/frontend/other/footer.php';