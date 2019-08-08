<?php
global $idc;
global $pdo;

if ($idc != 1) {
    header('Location: /');
}

$knowledgeArticlesQuery = $pdo->prepare("SELECT article_id, article_name, article_text FROM knowledge_articles");
$knowledgeArticlesQuery->execute();
$knowledgeArticles = $knowledgeArticlesQuery->fetchAll(PDO::FETCH_ASSOC);
