<?php

require_once 'engine/backend/functions/common-functions.php';

if ($_POST['module'] == 'count') {
    echo json_encode(countTopsidebar());
}