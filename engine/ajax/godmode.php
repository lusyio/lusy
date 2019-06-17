<?php
global $pdo;
global $idc;
if ($idc != 1) {
    exit;
}
$uploadDir = '../public_html/public/upload/';

if ($_POST['module'] == 'addArticle') {

    if (!realpath($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imgSmallName = '';
    if (!empty($_FILES['imgSmall'])) {
        $imgSmallName = $_FILES['imgSmall']['name'];
        $filePath = $uploadDir . $imgSmallName;
        move_uploaded_file($_FILES['imgSmall']['tmp_name'], $filePath);
    }

    $imgFullName = '';
    if (!empty($_FILES['imgFull'])) {
        $imgFullName = $_FILES['imgFull']['name'];
        $filePath = $uploadDir . $imgFullName;
        move_uploaded_file($_FILES['imgFull']['tmp_name'], $filePath);
    }

    $query = $pdo->prepare('INSERT INTO blog(url, language, article_name, article_text, category, description, publish_date, img_small, img_full) values (:url, :language, :articleName, :articleText, :category, :description, :publishDate, :imgSmall, :imgFull)');
    $data = [
        ':url' => $_POST['articleUrl'],
        ':language' => 'ru',
        ':articleName' => $_POST['articleTitle'],
        ':articleText' => $_POST['articleText'],
        ':category' => $_POST['articleCategory'],
        ':description' => $_POST['articleDescription'],
        ':publishDate' => strtotime($_POST['articleDate']),
        ':imgSmall' => $imgSmallName,
        ':imgFull' => $imgFullName,
    ];
    if ($query->execute($data)) {
        echo '1';
    }
}

if ($_POST['module'] == 'updateArticle') {

    if (!realpath($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imgSmallName = DBOnce('img_small', 'blog', 'article_id=' . (int)$_POST['articleId']);
    if (!empty($_FILES['imgSmall'])) {
        $imgSmallName = $_FILES['imgSmall']['name'];
        $filePath = $uploadDir . $imgSmallName;
        move_uploaded_file($_FILES['imgSmall']['tmp_name'], $filePath);
    }

    $imgFullName = DBOnce('img_full', 'blog', 'article_id=' . (int)$_POST['articleId']);
    if (!empty($_FILES['imgFull'])) {
        $imgFullName = $_FILES['imgFull']['name'];
        $filePath = $uploadDir . $imgFullName;
        move_uploaded_file($_FILES['imgFull']['tmp_name'], $filePath);
    }

    $query = $pdo->prepare('UPDATE blog SET url = :url, language = :language, article_name = :articleName, article_text = :articleText,
                category = :category, description = :description, publish_date = :publishDate, img_small = :imgSmall, img_full = :imgFull WHERE article_id = :articleId');
    $data = [
        ':articleId' => $_POST['articleId'],
        ':url' => $_POST['articleUrl'],
        ':language' => 'ru',
        ':articleName' => $_POST['articleTitle'],
        ':articleText' => $_POST['articleText'],
        ':category' => $_POST['articleCategory'],
        ':description' => $_POST['articleDescription'],
        ':publishDate' => strtotime($_POST['articleDate']),
        ':imgSmall' => $imgSmallName,
        ':imgFull' => $imgFullName,
    ];
    if ($query->execute($data)) {
        echo '1';
    }
}
