<?php
session_start();
ob_start();
include 'conf.php';

addTimeZoneToCompany();
makeTimeStampInComments();
makeTimeStampInCompany();
makeTimeStampInEvents();
makeTimeStampInInvitations();
makeTimeStampInMail();
makeTimeStampInUsers();
makeTimeStampInActivityUsers();
makeTimeStampInTasks();

include 'engine/backend/other/header.php'; 
include 'engine/frontend/other/header.php';

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

function addTimeZoneToCompany()
{
    global $pdo;
    $sql = $pdo->prepare('SHOW COLUMNS FROM `comments`');
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'timezone') {
            return;
        }
    }
    $addColumnQuery = $pdo->prepare('alter table company add timezone text null;');
    $addColumnQuery->execute();
    $fillColumnQuery = $pdo->prepare('UPDATE company SET timezone = :timezone');
    $fillColumnQuery->execute(array(':timezone' => 'Europe/Moscow'));
}

function makeTimeStampInComments()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `comments`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'datetime') {
            $getDateTimeQuery = $pdo->prepare('SELECT id, datetime FROM comments');
            $getDateTimeQuery->execute();
            $commentsDateTime = $getDateTimeQuery->fetchAll(PDO::FETCH_ASSOC);
//            $deleteQuery = $pdo->prepare('alter table comments drop column datetime');
//            $deleteQuery->execute();
            $createQuery = $pdo->prepare('alter table comments add datetimett int');
            $createQuery->execute();
            $updateQuery = $pdo->prepare('update comments set datetimett=:datetime WHERE id=:id');
            foreach ($commentsDateTime as $comment) {
                $updateQuery->execute(array(':id' => $comment['id'], ':datetime' => strtotime($comment['datetime'])));
            }
            break;
        }
    }
}

function makeTimeStampInCompany()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `company`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'datareg' && $column['Type'] != 'int(11)') {
            $getDataregQuery = $pdo->prepare('SELECT id, datareg FROM company');
            $getDataregQuery->execute();
            $companyDatareg = $getDataregQuery->fetchAll(PDO::FETCH_ASSOC);
//            $deleteQuery = $pdo->prepare('alter table company drop column datareg');
//            $deleteQuery->execute();
            $createQuery = $pdo->prepare('alter table company add dataregtt int');
            $createQuery->execute();
            $updateQuery = $pdo->prepare('update company set dataregtt=:datetime WHERE id=:id');
            foreach ($companyDatareg as $company) {
                $updateQuery->execute(array(':id' => $company['id'], ':datetime' => strtotime($company['datareg'])));
            }
            break;
        }
    }
}

function makeTimeStampInEvents()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `events`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'datetime' && $column['Type'] != 'int(11)') {
            $getDatetimeQuery = $pdo->prepare('SELECT event_id, datetime FROM events');
            $getDatetimeQuery->execute();
            $eventDatetime = $getDatetimeQuery->fetchAll(PDO::FETCH_ASSOC);
//            $deleteQuery = $pdo->prepare('alter table events drop column datetime');
//            $deleteQuery->execute();
            $createQuery = $pdo->prepare('alter table events add datetimett int');
            $createQuery->execute();
            $updateQuery = $pdo->prepare('update events set datetimett=:datetime WHERE event_id=:id');
            foreach ($eventDatetime as $event) {
                $updateQuery->execute(array(':id' => $event['event_id'], ':datetime' => strtotime($event['datetime'])));
            }
            break;
        }
    }
}

function makeTimeStampInInvitations()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `invitations`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'invite_date' && $column['Type'] != 'int(11)') {
            $getDatetimeQuery = $pdo->prepare('SELECT invite_id, invite_date FROM invitations');
            $getDatetimeQuery->execute();
            $inviteDatetime = $getDatetimeQuery->fetchAll(PDO::FETCH_ASSOC);
//            $deleteQuery = $pdo->prepare('alter table invitations drop column invite_date');
//            $deleteQuery->execute();
            $createQuery = $pdo->prepare('alter table invitations add invite_datett int');
            $createQuery->execute();
            $updateQuery = $pdo->prepare('update invitations set invite_datett=:datetime WHERE invite_id=:id');
            foreach ($inviteDatetime as $invite) {
                $updateQuery->execute(array(':id' => $invite['invite_id'], ':datetime' => strtotime($invite['invite_date'])));
            }
            break;
        }
    }
}

function makeTimeStampInMail()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `mail`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'datetime' && $column['Type'] != 'int(11)') {
            $getDatetimeQuery = $pdo->prepare('SELECT message_id, datetime FROM mail');
            $getDatetimeQuery->execute();
            $mailDatetime = $getDatetimeQuery->fetchAll(PDO::FETCH_ASSOC);
//            $deleteQuery = $pdo->prepare('alter table mail drop column datetime');
//            $deleteQuery->execute();
            $createQuery = $pdo->prepare('alter table mail add datetimett int');
            $createQuery->execute();
            $updateQuery = $pdo->prepare('update mail set datetimett=:datetime WHERE message_id=:id');
            foreach ($mailDatetime as $mail) {
                $updateQuery->execute(array(':id' => $mail['message_id'], ':datetime' => strtotime($mail['datetime'])));
            }
            break;
        }
    }
}

function makeTimeStampInUsers()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'register_date' && $column['Type'] != 'int(11)') {
            $getDatetimeQuery = $pdo->prepare('SELECT id, register_date FROM users');
            $getDatetimeQuery->execute();
            $usersDatetime = $getDatetimeQuery->fetchAll(PDO::FETCH_ASSOC);
//            $deleteQuery = $pdo->prepare('alter table users drop column register_date');
//            $deleteQuery->execute();
            $createQuery = $pdo->prepare('alter table users add register_datett int');
            $createQuery->execute();
            $updateQuery = $pdo->prepare('update users set register_datett=:datetime WHERE id=:id');
            foreach ($usersDatetime as $user) {
                $updateQuery->execute(array(':id' => $user['id'], ':datetime' => strtotime($user['register_date'])));
            }
            break;
        }
    }
}

function makeTimeStampInActivityUsers()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `users`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'register_date' && $column['Type'] != 'int(11)') {
            $deleteQuery = $pdo->prepare('alter table users drop column activity');
            $deleteQuery->execute();
            $createQuery = $pdo->prepare('alter table users add activity int');
            $createQuery->execute();
            break;
        }
    }
}

function makeTimeStampInTasks()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `tasks`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] == 'datecreate' && $column['Type'] != 'int(11)') {
            $getTaskDatesQuery = $pdo->prepare('SELECT id, datecreate, datepostpone, datedone FROM tasks');
            $getTaskDatesQuery->execute();
            $taskDates = $getTaskDatesQuery->fetchAll(PDO::FETCH_ASSOC);

//            $deleteQuery = $pdo->prepare('alter table tasks drop column datecreate');
//            $deleteQuery->execute();
//            $deleteQuery = $pdo->prepare('alter table tasks drop column datepostpone');
//            $deleteQuery->execute();
//            $deleteQuery = $pdo->prepare('alter table tasks drop column datedone');
//            $deleteQuery->execute();

            $createQuery = $pdo->prepare('alter table tasks add datecreatett int');
            $createQuery->execute();
            $createQuery = $pdo->prepare('alter table tasks add datepostponett int');
            $createQuery->execute();
            $createQuery = $pdo->prepare('alter table tasks add datedonett int');
            $createQuery->execute();

            $updateCreateQuery = $pdo->prepare('update tasks set datecreatett=:datetime WHERE id=:id');
            $updatePostponeQuery = $pdo->prepare('update tasks set datepostponett=:datetime WHERE id=:id');
            $updateDoneQuery = $pdo->prepare('update tasks set datedonett=:datetime WHERE id=:id');
            foreach ($taskDates as $task) {
                if (!is_null($task['datecreate'])) {
                    $updateCreateQuery->execute(array(':id' => $task['id'], ':datetime' => strtotime($task['datecreate'])));
                }
                if (isset($task['datepostpone']) && !is_null($task['datepostpone']) && $task['datepostpone'] != '' && $task['datepostpone'] != '0000-00-00') {
                    $updatePostponeQuery->execute(array(':id' => $task['id'], ':datetime' => strtotime($task['datepostpone'])));
                }
                if (!is_null($task['datedone'])) {
                    $updateDoneQuery->execute(array(':id' => $task['id'], ':datetime' => strtotime($task['datedone'])));
                }
            }
            break;
        }
    }
}


include 'engine/backend/other/footer.php';
include 'engine/frontend/other/footer.php';