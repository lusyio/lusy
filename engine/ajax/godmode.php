<?php
global $pdo;
global $idc;

if ($_POST['module'] == 'addArticle') {
    if ($idc != 1) {
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
if ($_POST['module'] == 'updateArticle') {
    if ($idc != 1) {
        exit;
    } else {

        $query = $pdo->prepare('UPDATE blog SET url = :url, language = :language, article_name = :articleName, article_text = :articleText,
                category = :category, description = :description, publish_date = :publishDate WHERE article_id = :articleId');
        $data = [
            ':articleId' => $_POST['articleId'],
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
