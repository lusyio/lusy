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
}
