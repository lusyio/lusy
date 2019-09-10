<?php

class TaskListStrategy
{
    public static function createTaskList($userId, $companyId, $isCeo)
    {
        if ($isCeo) {
            require_once __ROOT__ . '/engine/backend/classes/CeoTaskList.php';
            return new CeoTaskList($userId, $companyId);
        } else {
            require_once __ROOT__ . '/engine/backend/classes/EmployeeTaskList.php';
            return new EmployeeTaskList($userId, $companyId);
        }
    }

    public static function getAvailableTasks($userId, $companyId, $isCeo)
    {
        if ($isCeo) {
            require_once __ROOT__ . '/engine/backend/classes/CeoTaskList.php';
            return CeoTaskList::getAvailableTasksId($userId, $companyId);
        } else {
            require_once __ROOT__ . '/engine/backend/classes/EmployeeTaskList.php';
            return EmployeeTaskList::getAvailableTasksId($userId, $companyId);
        }
    }
}
