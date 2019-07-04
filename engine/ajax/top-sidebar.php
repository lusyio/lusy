<?php

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';

if ($_POST['module'] == 'count') {
    echo json_encode(countTopsidebar());
}