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

if ($_POST['module'] == 'addPromocode') {

    $query = $pdo->prepare('INSERT INTO promocodes(promocode_name, days_to_add, is_multiple, valid_until, used) values (:promocodeName, :daysToAdd, :isMultiple, :validUntil, :used)');
    $data = [
        ':promocodeName' => mb_strtolower($_POST['promocodeName']),
        ':daysToAdd' => $_POST['promocodeDays'],
        ':isMultiple' => $_POST['promocodeMultiple'],
        ':validUntil' => strtotime($_POST['promocodeDate']),
        ':used' => $_POST['promocodeUsed'],
    ];
    if ($query->execute($data)) {
        echo '1';
    }
}

if ($_POST['module'] == 'updatePromocode') {

    $query = $pdo->prepare('UPDATE promocodes SET promocode_name = :promocodeName, days_to_add = :daysToAdd, is_multiple = :isMultiple, valid_until = :validUntil, used = :used WHERE promocode_id = :promocodeId');
    $data = [
        ':promocodeName' => mb_strtolower($_POST['promocodeName']),
        ':daysToAdd' => $_POST['promocodeDays'],
        ':isMultiple' => $_POST['promocodeMultiple'],
        ':validUntil' => strtotime($_POST['promocodeDate']),
        ':used' => $_POST['promocodeUsed'],
        ':promocodeId' => (int) $_POST['promocodeId'],
    ];
    if ($query->execute($data)) {
        echo '1';
    }
}
if ($_POST['module'] == 'activatePromocode') {

    require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';

    $companyId = $_POST['companyId'];
    $promocode = mb_strtolower($_POST['promocodeName']);

    if ($companyId == 0) {
        $companiesListQuery = $pdo->prepare("SELECT id FROM company");
        $companiesListQuery->execute();
        $companiesList = $companiesListQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($companiesList as $company) {
            activatePromocode($company['id'], $promocode);
        }
    } else {
        activatePromocode($companyId, $promocode);
    }
}

if ($_POST['module'] == 'addKnowledgeArticle') {

    $query = $pdo->prepare('INSERT INTO knowledge_articles(article_name, article_text) values (:articleName, :articleText)');
    $data = [
        ':articleName' => $_POST['articleTitle'],
        ':articleText' => $_POST['articleText'],
    ];
    if ($query->execute($data)) {
        echo '1';
    }
}

if ($_POST['module'] == 'updateKnowledgeArticle') {

    $query = $pdo->prepare('UPDATE knowledge_articles SET article_name = :articleName, article_text = :articleText WHERE article_id = :articleId');
    $data = [
        ':articleId' => $_POST['articleId'],
        ':articleName' => $_POST['articleTitle'],
        ':articleText' => $_POST['articleText'],
    ];
    if ($query->execute($data)) {
        echo '1';
    }
}

if ($_POST['module'] == 'sendMail') {
    if (!isset($_POST['email']) || $_POST['email'] == '') {
        exit;
    }
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $template = filter_var($_POST['template'], FILTER_SANITIZE_STRING);
    $template = preg_replace('~.php~', '', $template);
    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';

    $mailSender = new \PHPMailer\PHPMailer\LusyMailer();
    try {
        $mailSender->addAddress($email);
        $mailSender->isHTML();
        $unsubscribeCode = generateUnsubscribeCode(1);
        $unsubscribeLink = '1/' . $unsubscribeCode . '/';
        $args = [
            'achievementName' => generateRandomString(rand(1,2)),
            'achievementText' => generateRandomString(rand(8,16)),
            'taskName' => generateRandomString(rand(2,6)),
            'commentText' => generateRandomString(rand(5,20)),
            'messageText' => generateRandomString(rand(5,20)),
            'report' => generateRandomString(rand(5,20)),
            'commentId' => rand(25000,30000),
            'email' => $email,
            'password' => generateRandomString(1),
            'activationLink' => generateRandomString(1),
            'restoreLink' => generateRandomString(1),
            'authorName' => generateRandomString(2),
            'managerName' => generateRandomString(2),
            'companyName' => generateRandomString(rand(1,2)),
            'taskId' => rand(15000,20000),
            'tariffName' => array_rand(['Стартовый', 'Уверенный', 'Босс']),
            'cardDigits' => rand(4000,5000) . rand(1000,9999) . rand(1000,9999) . rand(1000,9999),
            'subscribeUntil' => date('d.m.Y', strtotime('+' . rand(1,12) . ' month')),
            'nextChargeDate' => date('d.m.Y', strtotime('+' . rand(1,12) . ' month')),
            'request' => date('d.m.Y', strtotime('+' . rand(1,12) . ' day')),
            'freeDays' => rand(5, 30),
            'inviteLink' => generateRandomString(1),
            'unsubscribeLink' => $unsubscribeLink,
        ];

            $mailSender->Subject = "Тестовое письмо";
            $mailSender->setMessageContent($template, $args);

        $mailSender->send();
    } catch (Exception $e) {
        return;
    }
}
