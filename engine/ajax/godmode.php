<?php
global $pdo;
global $idc;

if ($_POST['module'] == 'addArticle') {
    if ($idc != 2) {
        exit;
    } else {
        $query = $pdo->prepare('INSERT INTO blog(url, language, article_name, article_text, category, description, publish_date) values (:url, :language, :articleName, :articleText, :category, :description, :publishDate)');
        $data = [
            ':url' => $_POST['articleUrl'],
            ':language' => 'ru',
            ':articleName' => $_POST['articleTitle'],
            ':articleText' => $_POST['articleText'],
            ':category' => $_POST['articleCategory'],
            ':description' => $_POST['articleDescription'],
            ':publishDate' => strtotime($_POST['articleDate']),
        ];
        if ($query->execute($data)) {
            echo '1';
        }
    }
}