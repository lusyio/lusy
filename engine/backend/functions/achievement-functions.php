<?php
$ACHIEVEMENTS = [
    'MEETING',
    'INVITOR',
    'BUGREPORT',
    'MESSAGE_1',
    'TASKOVERDUE_1',
    'TASKPOSTPONE_1',
    'TASKDONEWITHCOWORKER_1',
    'SELFTASK_1',
    'TASKDONE_1',
    'TASKDONE_10',
    'TASKDONE_50',
    'TASKDONE_100',
    'TASKDONE_200',
    'TASKDONE_500',
    'TASKDONEPERMONTH_500',
    'TASKCREATE_10',
    'TASKCREATE_50',
    'TASKCREATE_100',
    'TASKCREATE_200',
    'TASKCREATE_500',
    'COMMENT_1000',
    'TASKOVERDUEPERMONTH_0',
    'TASKDONEPERMONTH_LEADER',
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
    $query = $pdo->prepare('SELECT ar.output_name FROM user_achievements ua LEFT JOIN achievement_rules ar ON ua.achievement = ar.achievement_name WHERE ua.user_id = :userId');
    $query->execute(array(':userId' => $userId));
    $result = $query->fetchAll(PDO::FETCH_COLUMN);
    if (!is_array($result)) {
        $result = [];
    }
    return $result;
}

function getUserProgress($userId)
{
    global $pdo;

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

    $inviteSentQuery = $pdo->prepare("SELECT COUNT(*) FROM events WHERE action = 'sentinvite' AND recipient_id = :userId");
    $inviteSentQuery->execute(array(':userId' => $userId));
    if ($inviteSentQuery->fetch(PDO::FETCH_COLUMN) > 0) {
        $inviteSent = true;
    }

    $messageQuery = $pdo->prepare("SELECT COUNT(*) FROM mail WHERE recipient = :userId");
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
        'comment' => $taskCreateToday,
    ];
    return $result;
}

function getAchievementConditions()
{
    global $pdo;
    $query = $pdo->prepare("SELECT achievement_name, multiple, conditions, output_name FROM achievement_rules WHERE periodic = 0");
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
    $query = $pdo->prepare("SELECT achievement_name, multiple, conditions, output_name FROM achievement_rules WHERE periodic = 1");
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

function getMonthlyDoneTaskInCompany($companyId, $firstDay)
{
    global $pdo;

    $taskDonePerMonthLeaderQuery = $pdo->prepare("SELECT COUNT(*) AS count, worker AS userId FROM tasks t LEFT JOIN events e ON t.id = e.task_id WHERE status = 'done' AND idcompany = :companyId AND e.action = 'workdone' AND e.datetime > :firstDay GROUP BY worker ORDER BY count DESC");
    $taskDonePerMonthLeaderQuery->execute(array(':companyId' => $companyId, ':firstDay' => $firstDay));
    $doneTasks = $taskDonePerMonthLeaderQuery->fetchAll(PDO::FETCH_ASSOC);
    return $doneTasks;
}
function getMonthlyOverdueWorkersInCompany($companyId, $firstDay)
{
    global $pdo;

    $taskOverduePerMonthLeaderQuery = $pdo->prepare("SELECT COUNT(*) AS count, worker AS userId FROM tasks t LEFT JOIN events e ON t.id = e.task_id WHERE idcompany = :companyId AND e.action = 'overdue' AND e.datetime > :firstDay GROUP BY worker ORDER BY count DESC");
    $taskOverduePerMonthLeaderQuery->execute(array(':companyId' => $companyId, ':firstDay' => $firstDay));
    $overdueTasks = $taskOverduePerMonthLeaderQuery->fetchAll(PDO::FETCH_ASSOC);
    $workers = [];
    foreach ($overdueTasks as $tasks) {
        $workers[] = $tasks['userId'];
    }
    return $workers;
}

function checkTaskDoneLeaderAchievementsInCompany($companyId, $firstDay)
{
    $taskLeaders = getMonthlyDoneTaskInCompany($companyId, $firstDay);
    $hasMoreThanOneLeader = false;
    if (count($taskLeaders) > 1 && $taskLeaders[0]['count'] == $taskLeaders[1]['count']) {
        $hasMoreThanOneLeader = true;
    }
    if (!$hasMoreThanOneLeader && count($taskLeaders) > 0 && $taskLeaders[0]['count'] > 0) {
        addAchievement('TASKDONEPERMONTH_LEADER', $taskLeaders[0]['userId']);
    }
    foreach ($taskLeaders as $tasks) {
        if ($tasks['count'] >= 500) {
            addAchievement('TASKDONEPERMONTH_500', $taskLeaders[0]['userId']);
        }
    }
}

function checkTaskDonePerMonthInCompany($companyId, $firstDay)
{
    $TASK_GOAL = 500;
    $doneTasks = getMonthlyDoneTaskInCompany($companyId, $firstDay);
    foreach ($doneTasks as $tasks) {
        if ($tasks['count'] >= $TASK_GOAL) {
            addAchievement('TASKDONEPERMONTH_500', $tasks['userId']);
        }
    }
}

function checkTaskOverduePerMonthInCompany($companyId, $firstDay)
{
    $companyUsers = DBOnce('id', 'users' , 'idcompany = ' . $companyId . ' AND is_fired = 0');
    $overdueWorkers = getMonthlyOverdueWorkersInCompany($companyId, $firstDay);
    foreach ($companyUsers as $user) {
        if (!in_array($user, $overdueWorkers)) {
            addAchievement('TASKOVERDUEPERMONTH_0', $user);
        }
    }
}

function checkRule($property, $rule)
{
    if ($rule['condition'] == 'more') {
        return $property > $rule['value'];
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