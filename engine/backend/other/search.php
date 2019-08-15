<?php
require_once __ROOT__ . '/engine/backend/functions/search-functions.php';

if (isset($_GET['request'])) {
    $request = trim(mb_strtolower(filter_var($_GET['request'], FILTER_SANITIZE_STRING)));
    $result = mainSearch($request);
}
