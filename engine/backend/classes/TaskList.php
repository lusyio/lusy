<?php

abstract class TaskList
{
    protected $tasks = [];
    protected $query;
    protected $queryArgs;
    protected $queryStatusFilterString = '';
    protected $subTaskFilterString = '';
    protected $parentTaskNullFilterString = '';
    protected $tasksQueryResult = [];
    protected $tasksQueryLimitString = '';
    protected $tasksQueryOffsetString = '';
    protected $tasksQueryOrderString = '';

    public function __construct()
    {
        require_once __ROOT__ . '/engine/backend/classes/Task.php';
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public abstract function executeQuery();

    public function setParentTaskNullFilterString($is)
    {
        $appendix = ' AND t.parent_task IS';
        if ($is) {
            $appendix .= ' NULL ';
        } else {
            $appendix .= ' NOT NULL ';
        }
        $this->parentTaskNullFilterString = $appendix;
    }

    public function setQueryStatusFilter($status, $in = true)
    {
        $appendix = ' AND t.status';
        if ($in) {
            $appendix .= ' IN ';
        } else {
            $appendix .= ' NOT IN ';
        }
        if (is_array($status)) {
            $appendix .= "('" . implode("', '", $status) . "')";
        } else {
            $appendix .= "('" . $status . "')";
        }
        $this->queryStatusFilterString = $appendix;
    }

    public function setSubTaskFilterString($status, $in = true)
    {
        $appendix = ' AND t.status';
        if ($in) {
            $appendix .= ' IN ';
        } else {
            $appendix .= ' NOT IN ';
        }
        if (is_array($status)) {
            $appendix .= "('" . implode("', '", $status) . "')";
        } else {
            $appendix .= "('" . $status . "')";
        }
        $this->subTaskFilterString = $appendix;
    }

    public function instantiateTasks()
    {
        $tasksResult = $this->tasksQueryResult;
        foreach ($tasksResult as $taskData) {
            $this->tasks[] = new Task($taskData['id'], $taskData, $this->subTaskFilterString);
        }
    }

    public function renderList()
    {
        foreach ($this->tasks as $task) {
            $task->renderCard();
        }
    }

    public function sortActualTasks()
    {
        usort($this->tasks, ['TaskList', 'compareActual']);
    }

    public static function compareActual(Task $a, Task $b)
    {
        $aSubTasks = $a->get('subTasks');
        $bSubTasks = $b->get('subTasks');
        if (count($aSubTasks) && count($bSubTasks)) {
            $minCount = min(count($aSubTasks), count($bSubTasks));
            for ($i = 0; $i < $minCount; $i++) {
                if (self::compareWithoutSubTasks($aSubTasks[$i], $bSubTasks[$i]) != 0) {
                    return self::compareWithoutSubTasks($aSubTasks[$i], $bSubTasks[$i]);
                }
            }
            return 0;
        } elseif (count($aSubTasks)) {
            if (self::compareWithoutSubTasks($aSubTasks[0], $a) > 0 && self::compareWithoutSubTasks($a, $b) != 0) {
                return self::compareWithoutSubTasks($a, $b);
            } else {
                foreach ($aSubTasks as $aSubTask) {
                    if (self::compareWithoutSubTasks($aSubTask, $b) != 0) {
                        return self::compareWithoutSubTasks($aSubTask, $b);
                    }
                }
                return 0;
            }
        } elseif (count($bSubTasks)) {
            if (self::compareWithoutSubTasks($bSubTasks[0], $b) > 0 && self::compareWithoutSubTasks($a, $b) != 0) {
                return self::compareWithoutSubTasks($a, $b);
            } else {
                foreach ($bSubTasks as $bSubTask) {
                    if (self::compareWithoutSubTasks($a, $bSubTask) != 0) {
                        return self::compareWithoutSubTasks($a, $bSubTask);
                    }
                }
                return 0;
            }
        } else {
            return self::compareWithoutSubTasks($a, $b);
        }
    }

    public static function compareWithoutSubTasks(Task $a, Task $b)
    {
        $statusOrder = [
            'overdue' => 1,
            'postpone' => 2,
            'pending' => 3,
            'returned' => 4,
            'new' => 4,
            'inwork' =>4,
            'planned' => 5,
            'canceled' => 6,
            'done' => 7,
        ];
        if ((key_exists($a->get('status'), $statusOrder)) && (key_exists($b->get('status'), $statusOrder)) && $statusOrder[$a->get('status')] != $statusOrder[$b->get('status')]) {
            return $statusOrder[$a->get('status')] - $statusOrder[$b->get('status')];
        } else {
            if ($a->get('viewStatus') != $b->get('viewStatus')) {
                return $a->get('viewStatus') - $b->get('viewStatus');
            } else {
                return $a->get('datedone') - $b->get('datedone');
            }
        }
    }

    public function countTasks()
    {
        return count($this->tasksQueryResult);
    }

    public function setTasksQueryLimitString($limit)
    {
        $this->tasksQueryLimitString = ' LIMIT ' . $limit;
    }
    public function setTasksQueryOffsetString($offset)
    {
        $this->tasksQueryOffsetString = ' OFFSET ' . $offset;
    }

    public function addOrderByDate($asc = true)
    {
        if ($asc) {
            $this->tasksQueryOrderString = ' ORDER BY t.datedone';
        } else {
            $this->tasksQueryOrderString = ' ORDER BY t.datedone DESC';
        }
    }
}
