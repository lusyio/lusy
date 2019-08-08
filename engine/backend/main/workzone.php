<?php
$url = $GLOBALS["url"];

if (!empty($_GET['task'])) {
    $id_task = $_GET['task']; // принимаем id задачи
    if (!empty($id_task)) { //проверяем не пустой ли id, и если так, то показываем рабочий стол
        if ($id_task != 'new' && (!isset($_GET['edit']) || $_GET['edit'] != 1)) {
            inc('task', 'task');
        }
    }
    if (isset($_GET['edit'])) {
        inc('other', 'tasknew');
    }
}

if (!empty($_GET['tasks'])) {
    inc('other', 'tasks');
}

if (!empty($_GET['profile'])) {
    inc('other', 'profile');
}

if (!empty($_GET['storage'])) {
    inc('other', 'storage');
}


if (!empty($_GET['mail'])) {
    inc('other', 'conversation');
}
if (!empty($_GET['support'])) {
    inc('other', 'support-dialog');
}

if(!empty($url)) {
	inc('other',$url);

} else {
    inc('other', 'dashboard');
}