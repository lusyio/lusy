<?php
global $pdo;
global $id;
global $roleu;

if ($_POST['module'] == 'rateModal' && isset($_POST['answer']) && $roleu == 'ceo') {
    $answer = filter_var($_POST['answer'], FILTER_SANITIZE_NUMBER_INT);
    if ($answer === '1' || $answer === '-1') {
        $ratesCount = DBOnce('COUNT(*)', 'rates', 'user_id = ' . $id);
        if ($ratesCount > 0) {
            exit();
        }
        $insertRateQuery = $pdo->prepare("INSERT INTO rates (user_id, rate) VALUES (:userId, :rate)");
        if ($answer === '-1') {
            $rate = -1;
        } else {
            $rate = 1;
        }
        $insertRateQuery->execute([':userId' => $id, ':rate' => $rate]);
        exit();
    }
}