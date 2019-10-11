<?php
$ACHIEVEMENTS = [
    'meeting',
    'INVITOR',
    'bugReport',
    'message_1',
    'taskOverdue_1',
    'taskPostpone_1',
    'taskDoneWithCoworker_1',
    'selfTask_1',
    'taskDone_1',
    'taskDone_10',
    'taskDone_50',
    'taskDone_100',
    'taskDone_200',
    'taskDone_500',
    'taskDonePerMonth_500',
    'taskCreate_10',
    'taskCreate_50',
    'taskCreate_100',
    'taskCreate_200',
    'taskCreate_500',
    'comment_1000',
    'taskOverduePerMonth_0',
    'taskDonePerMonth_leader',
    'taskInwork_20',
];

/**Возвращает массив с названиями достижений, имеющихся у пользователя
 * Если достижений не найдено, то будет возвращен пустой массив
 * @param $userId int id пользователя
 * @return array
 */
function getUserAchievements($userId)
{
    global $pdo;
    $query = $pdo->prepare('SELECT achievement FROM user_achievements WHERE user_id = :userId');
    $query->execute(array(':userId' => $userId));
    $result = $query->fetchAll(PDO::FETCH_COLUMN);
    if (!is_array($result)) {
        $result = [];
    }
    return $result;
}

function getUserAchievementsForRender($userId)
{
    global $pdo;
    $query = $pdo->prepare('SELECT achievement, datetime FROM user_achievements WHERE user_id = :userId');
    $query->execute(array(':userId' => $userId));
    $result = $query->fetchAll(PDO::FETCH_COLUMN);
    if (!is_array($result)) {
        $result = [];
    }
    return $result;
}

function getUserNonMultipleAchievements($userId)
{
    global $pdo;
    $query = $pdo->prepare('SELECT ua.achievement, ua.datetime FROM user_achievements ua LEFT JOIN achievement_rules ar ON ua.achievement = ar.achievement_name WHERE ua.user_id = :userId ORDER BY ua.datetime DESC');
    $query->execute(array(':userId' => $userId));
    $achievements = $query->fetchAll(PDO::FETCH_ASSOC);
    $result = [];
    foreach ($achievements as $a) {
        $result[$a['achievement']] = [
            'datetime' => $a['datetime'],
            'got' => true,
            ];
    }
    return $result;
}

function getUserProgress($userId)
{
    global $pdo;
    $idc = DBOnce('idcompany', 'users', 'id = ' . $userId);
    $isProfileFilled = false;
    $inviteSent = false;

    $doneQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE worker = :userId AND status = 'done'");
    $doneQuery->execute(array(':userId' => $userId));
    $taskDone = $doneQuery->fetch(PDO::FETCH_COLUMN);

    $doneWithCoworkerQuery = $pdo->prepare("SELECT COUNT(*) FROM task_coworkers tc LEFT JOIN tasks t ON tc.task_id = t.id WHERE t.worker = :userId AND t.status = 'done'");
    $doneWithCoworkerQuery->execute(array(':userId' => $userId));
    $taskDoneWithCoworker = $doneWithCoworkerQuery->fetch(PDO::FETCH_COLUMN);

    $createQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE manager = :userId");
    $createQuery->execute(array(':userId' => $userId));
    $taskCreate = $createQuery->fetch(PDO::FETCH_COLUMN);

    $overdueQuery = $pdo->prepare("SELECT COUNT(*) FROM events e LEFT JOIN tasks t on e.task_id = t.id WHERE e.action = 'overdue' AND t.worker = :userId");
    $overdueQuery->execute(array(':userId' => $userId));
    $taskOverdue = $overdueQuery->fetch(PDO::FETCH_COLUMN);

    $postponeQuery = $pdo->prepare("SELECT COUNT(*) FROM events e LEFT JOIN tasks t on e.task_id = t.id WHERE e.action = 'confirmdate' AND t.worker = :userId");
    $postponeQuery->execute(array(':userId' => $userId));
    $taskPostpone = $postponeQuery->fetch(PDO::FETCH_COLUMN);

    $userDataQuery = $pdo->prepare("SELECT name, surname, idcompany FROM users WHERE id = :userId");
    $userDataQuery->execute(array(':userId' => $userId));
    $userData = $userDataQuery->fetch(PDO::FETCH_ASSOC);
    $avatarPath = 'upload/avatar/' . $userData['idcompany'] . '/' . $userId . '.jpg';
    if (!empty($userData['name']) && !empty($userData['surname']) && file_exists($avatarPath)) {
        $isProfileFilled = true;
    }

    $inviteSentQuery = $pdo->prepare("SELECT COUNT(*) FROM events WHERE action = 'newuser' AND recipient_id = :userId");
    $inviteSentQuery->execute(array(':userId' => $userId));
    if ($inviteSentQuery->fetch(PDO::FETCH_COLUMN)) {
        $inviteSent = true;
    }

    $messageQuery = $pdo->prepare("SELECT COUNT(*) FROM mail WHERE recipient = :userId AND sender > 1");
    $messageQuery->execute(array(':userId' => $userId));
    $message = $messageQuery->fetch(PDO::FETCH_COLUMN);

    $selfTaskQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE worker = :userId AND manager = :userId");
    $selfTaskQuery->execute(array(':userId' => $userId));
    $selfTask = $selfTaskQuery->fetch(PDO::FETCH_COLUMN);

    $taskCreateTodayQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE manager = :userId AND datecreate > :startTime");
    $startTime = strtotime(date('Y-m-d'));
    $taskCreateTodayQuery->execute(array(':userId' => $userId, ':startTime' => $startTime));
    $taskCreateToday = $taskCreateTodayQuery->fetch(PDO::FETCH_COLUMN);

    $commentQuery = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE iduser = :userId AND status = 'comment'");
    $commentQuery->execute(array(':userId' => $userId));
    $comment = $commentQuery->fetch(PDO::FETCH_COLUMN);

    $taskInworkQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE worker = :userId AND status = 'inwork'");
    $taskInworkQuery->execute(array(':userId' => $userId));
    $taskInwork = $taskInworkQuery->fetch(PDO::FETCH_COLUMN);

    $firstDay = strtotime(date('Y-m-01'));

    $taskDonePerMonthQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks t LEFT JOIN events e ON t.id = e.task_id WHERE t.worker = :userId AND e.action = 'workdone' AND e.datetime > :firstDay");
    $taskDonePerMonthQuery->execute(array(':userId' => $userId, ':firstDay' => $firstDay));
    $taskDonePerMonth = $taskDonePerMonthQuery->fetch(PDO::FETCH_COLUMN);
    $taskOverduePerMonthQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks t LEFT JOIN events e ON t.id = e.task_id WHERE t.worker = :userId AND e.action = 'overdue' AND e.datetime > :firstDay");
    $taskOverduePerMonthQuery->execute(array(':userId' => $userId, ':firstDay' => $firstDay));
    $taskOverduePerMonth = $taskOverduePerMonthQuery->fetch(PDO::FETCH_COLUMN);

    $taskDoneChartQuery = $pdo->prepare("SELECT COUNT(*) AS tasks, t.worker FROM tasks t LEFT JOIN events e ON t.id = e.task_id WHERE e.action = 'workdone' AND t.idcompany = :companyId AND e.datetime > :firstDay GROUP BY t.worker ORDER BY tasks DESC");
    $taskDoneChartQuery->execute(array(':companyId' => $idc, ':firstDay' => $firstDay));
    $taskDoneChart = $taskDoneChartQuery->fetchAll(PDO::FETCH_ASSOC);
    $position = count($taskDoneChart);
    foreach ($taskDoneChart as $key => $value) {
        if ($value['worker'] == $userId) {
            $position = $key + 1;
        }
    }
    $result = [
        'taskDone' => $taskDone,
        'taskCreate' => $taskCreate,
        'profileFilled' => $isProfileFilled,
        'inviteSent' => $inviteSent,
        'taskOverdue' => $taskOverdue,
        'taskPostpone' => $taskPostpone,
        'taskDoneWithCoworker' => $taskDoneWithCoworker,
        'message' => $message,
        'bugReport' => 0,
        'selfTask' => $selfTask,
        'taskCreateToday' => $taskCreateToday,
        'comment' => $comment,
        'taskInwork' => $taskInwork,
        'taskDonePerMonth' => $taskDonePerMonth,
        'taskOverduePerMonth' => $taskOverduePerMonth,
        'taskDonePerMonthPlace' => $position,
    ];
    return $result;
}

function getAchievementConditions($withPeriodic = false)
{
    global $pdo;
    if ($withPeriodic) {
        $query = $pdo->prepare("SELECT achievement_name, multiple, conditions, hidden FROM achievement_rules");

    } else {
        $query = $pdo->prepare("SELECT achievement_name, multiple, conditions, hidden FROM achievement_rules WHERE periodic = 0");
    }
    $query->execute();
    $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
    $result = [];
    foreach ($queryResult as $achievement) {
        $result[$achievement['achievement_name']] = $achievement;
    }
    return $result;
}

function getPeriodicAchievementConditions()
{
    global $pdo;
    $query = $pdo->prepare("SELECT achievement_name, multiple, conditions, hidden FROM achievement_rules WHERE periodic = 1");
    $query->execute();
    $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
    $result = [];
    foreach ($queryResult as $achievement) {
        $result[$achievement['achievement_name']] = $achievement;
    }
    return $result;
}

function checkAchievements($userId)
{
    $earnedAchievements = getUserAchievements($userId);
    $userProgress = getUserProgress($userId);
    $achievementsList = getAchievementConditions();

    foreach ($achievementsList as $ach => $props) {
        $conditions = json_decode($props['conditions'], true);
        if ($props['multiple'] == 1 || !in_array($ach, $earnedAchievements)) {
            $conditionsSatisfied = true;
            foreach ($conditions as $property => $condition) {
                if (!checkRule($userProgress[$property], $condition)) {
                    $conditionsSatisfied = false;
                    break;
                }
            }
            if ($conditionsSatisfied) {
                addAchievement($ach, $userId);
            }
        }
    }
}

function checkPeriodicAchievements($userId)
{
    $earnedAchievements = getUserAchievements($userId);
    $userProgress = getUserProgress($userId);
    $achievementsList = getAchievementConditions();

    foreach ($achievementsList as $ach => $props) {
        $conditions = json_decode($props['conditions'], true);
        if ($props['multiple'] == 1 || !in_array($ach, $earnedAchievements)) {
            $conditionsSatisfied = true;
            foreach ($conditions as $property => $condition) {
                if (!checkRule($userProgress[$property], $condition)) {
                    $conditionsSatisfied = false;
                    break;
                }
            }
            if ($conditionsSatisfied) {
                addAchievement($ach, $userId);
            }
        }
    }
}

function getMonthlyDoneTaskInCompany($companyId, $firstDay, $lastDay = null)
{
    global $pdo;
    if (is_null($lastDay)) {
        $lastDay = time();
    }

    $taskDonePerMonthLeaderQuery = $pdo->prepare("SELECT COUNT(*) AS count, worker AS userId FROM tasks t LEFT JOIN events e ON t.id = e.task_id WHERE status = 'done' AND idcompany = :companyId AND e.action = 'workdone' AND e.datetime > :firstDay AND e.datetime < :lastDay GROUP BY worker ORDER BY count DESC");
    $taskDonePerMonthLeaderQuery->execute(array(':companyId' => $companyId, ':firstDay' => $firstDay, ':lastDay' => $lastDay));
    $doneTasks = $taskDonePerMonthLeaderQuery->fetchAll(PDO::FETCH_ASSOC);
    return $doneTasks;
}

function getMonthlyOverdueWorkersInCompany($companyId, $firstDay, $lastDay = null)
{
    global $pdo;
    if (is_null($lastDay)) {
        $lastDay = time();
    }

    $taskOverduePerMonthLeaderQuery = $pdo->prepare("SELECT COUNT(*) AS count, worker AS userId FROM tasks t LEFT JOIN events e ON t.id = e.task_id WHERE idcompany = :companyId AND e.action = 'overdue' AND e.datetime > :firstDay AND e.datetime < :lastDay GROUP BY worker ORDER BY count DESC");
    $taskOverduePerMonthLeaderQuery->execute(array(':companyId' => $companyId, ':firstDay' => $firstDay, ':lastDay' => $lastDay));
    $overdueTasks = $taskOverduePerMonthLeaderQuery->fetchAll(PDO::FETCH_ASSOC);
    $workers = [];
    foreach ($overdueTasks as $tasks) {
        $workers[] = $tasks['userId'];
    }
    return $workers;
}

function checkTaskDoneLeaderAchievementsInCompany($companyId, $firstDay, $lastDay = null)
{
    if (is_null($lastDay)) {
        $lastDay = time();
    }
    $taskLeaders = getMonthlyDoneTaskInCompany($companyId, $firstDay, $lastDay);
    $hasMoreThanOneLeader = false;
    if (count($taskLeaders) > 1 && $taskLeaders[0]['count'] == $taskLeaders[1]['count']) {
        $hasMoreThanOneLeader = true;
    }
    if (!$hasMoreThanOneLeader && count($taskLeaders) > 0 && $taskLeaders[0]['count'] > 0) {
        addAchievement('taskDonePerMonth_leader', $taskLeaders[0]['userId']);
    }
}

function checkTaskDonePerMonthInCompany($companyId, $firstDay)
{
    $TASK_GOAL = 500;
    $doneTasks = getMonthlyDoneTaskInCompany($companyId, $firstDay);
    foreach ($doneTasks as $tasks) {
        if ($tasks['count'] >= $TASK_GOAL) {
            addAchievement('taskDonePerMonth_500', $tasks['userId']);
        }
    }
}

function checkTaskOverduePerMonthInCompany($companyId, $firstDay, $lastDay)
{
    global $pdo;
    $companyUsersQuery = $pdo->prepare("SELECT id FROM users WHERE idcompany = :companyId AND is_fired = 0");
    $companyUsersQuery->execute(array(':companyId' => $companyId));
    $companyUsers = $companyUsersQuery->fetchAll(PDO::FETCH_COLUMN);
    $overdueWorkers = getMonthlyOverdueWorkersInCompany($companyId, $firstDay, $lastDay);
    $doneCount = getMonthlyTaskDoneByWorkerOrManagerInCompany($companyId, $firstDay, $lastDay);
    foreach ($companyUsers as $user) {
        if (!in_array($user, $overdueWorkers) && key_exists($user, $doneCount) && $doneCount[$user] >= 5) {
            addAchievement('taskOverduePerMonth_0', $user);
        }
    }
}

function checkRule($property, $rule)
{
    if ($rule['condition'] == 'more') {
        return $property > $rule['value'];
    } elseif ($rule['condition'] == 'moreOrEqual') {
        return $property >= $rule['value'];
    } elseif ($rule['condition'] == 'less') {
        return $property < $rule['value'];
    } else {
        return $property == $rule['value'];
    }
}

function addAchievement($achievement, $userId)
{
    global $pdo;
    $query = $pdo->prepare("INSERT INTO user_achievements (user_id, achievement, datetime) VALUES (:userId, :achievement, :datetime)");
    $query->execute(array(':userId' => $userId, ':achievement' => $achievement, ':datetime' => time()));

    addEvent('newachievement', '0', $achievement, '$userId');
}

function getNonPathAchievements()
{
    global $pdo;
    $pathAchievements = [
        'taskDone_10',
        'taskDone_50',
        'taskDone_100',
        'taskDone_200',
        'taskDone_500',
        'taskCreate_10',
        'taskCreate_50',
        'taskCreate_100',
        'taskCreate_200',
        'taskCreate_500'];
    $achievementsQuery = $pdo->prepare("SELECT achievement_name FROM achievement_rules");
    $achievementsQuery->execute();
    $achievements = $achievementsQuery->fetchAll(PDO::FETCH_COLUMN);
    $result = array_diff($achievements, $pathAchievements);
    return $result;
}

function getMonthlyTaskDoneByWorkerOrManagerInCompany($companyId, $firstDay, $lastDay = null)
{
    global $pdo;
    if (is_null($lastDay)) {
        $lastDay = time();
    }

    $taskDonePerMonthQuery = $pdo->prepare("SELECT e.recipient_id, COUNT(DISTINCT e.task_id) AS count FROM events e LEFT JOIN users u ON u.id = e.recipient_id WHERE u.idcompany = :companyId AND e.action = 'workdone' AND e.datetime > :firstDay AND e.datetime < :lastDay GROUP BY e.recipient_id");
    $taskDonePerMonthQuery->execute(array(':companyId' => $companyId, ':firstDay' => $firstDay, ':lastDay' => $lastDay));
    $taskDoneCount = $taskDonePerMonthQuery->fetchAll(PDO::FETCH_KEY_PAIR);
    return $taskDoneCount;
}
