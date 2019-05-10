<?php
global $pdo;
global $datetime;
global $id;
global $idc;
global $cometHash;

$messages = DB('*','mail','sender = '.$id.' or recipient = '.$id. ' ORDER BY `datetime` DESC');
$userList = DB('id, name, surname', 'users', 'idcompany = '. $idc . ' AND id !=' . $id);
$dialog = [];

foreach ($messages as $n) {
    if (in_array($n['sender'], $dialog)) {
    } else {
        if ($n['sender'] != $id) {
            $dialog[] = $n['sender'];
        }
    }
    if (in_array($n['recipient'], $dialog)) {
    } else {
        if ($n['recipient'] != $id) {
            $dialog[] = $n['recipient'];
        }
    }
}

function fiomess($iduser) {
	global $pdo;
	$fio = DBOnce('name','users','id='.$iduser) . ' ' . DBOnce('surname','users','id='.$iduser);
	echo $fio;
}

function lastmess($iduser) {
	global $pdo;
	global $id;
	$sql = DB('*','mail','sender = '.$iduser.' or recipient = '.$iduser.' and sender = '.$id.' or recipient = '.$id.' order by datetime DESC limit 1');
	foreach ($sql as $n) {
		if ($id == $n['sender']) {
			$author = 'Вы: ';
		} else {
			$author = '';
		}
		echo '<p>' . $author . $n['mes'] . '</p><small>' . $n['datetime'] . '</small>';
	}
}

function numberOfNewMessages($idSender)
{
    global $id;
    $count = DBOnce('COUNT(*)', 'mail', 'recipient='.$id.' AND sender='.$idSender.' AND view_status=0');
    return $count;
}