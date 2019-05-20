<?php

require_once 'engine/backend/functions/search-functions.php';

if (isset($_POST['request'])) {
    $request = mb_strtolower(filter_var($_POST['request'], FILTER_SANITIZE_STRING));
    $result = mainSearch($request);
    echo json_encode($result);
}