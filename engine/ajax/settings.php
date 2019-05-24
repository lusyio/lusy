<?php
global $pdo;
global $datetime;
global $id;
global $idc;

require_once 'engine/backend/functions/settings-functions.php';

if ($_POST['module'] == 'changeAvatar') {
    if (isset($_FILES['avatar'])) {
        uploadAvatar();
    }
}
