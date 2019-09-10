<?php

class EventList
{
    private $query;
    private $viewStatusCondition = '';
    private $periodCondition = '';
    private $limitString = '';
    private $offsetIdString = '';
    private $orderString = 'ORDER BY e.view_status, e.datetime DESC ';
    private $queryArgs = [];

    public function __construct($userId, $companyId)
    {
        $this->query = "SELECT e.event_id, e.action, e.task_id, t.name AS taskName, e.author_id, u.name, u.surname, e.comment AS comment, c.comment AS commentText, e.datetime, e.view_status, t.worker FROM events e
  LEFT JOIN tasks t ON t.id = e.task_id
  LEFT JOIN users u on u.id = e.author_id
  LEFT JOIN comments c on c.id = e.comment                                                                              
  WHERE (e.recipient_id = :userId OR (e.recipient_id = 0 AND e.company_id = :companyId)) ";

        $this->queryArgs = [
            ':userId' => $userId,
            ':companyId' => $companyId,
        ];
    }

    public function setEventsPeriod($days)
    {
        $startTime = strtotime('-' . $days . ' days midnight');
        $this->periodCondition = ' AND (e.datetime >= ' . $startTime . ' OR e.view_status = 0) ';
    }

    public function setLimit($limit)
    {
        $this->limitString = 'LIMIT ' . $limit . ' ';
    }

    public function setOffsetId($eventId)
    {
        $this->offsetIdString = 'AND e.eventId < :eventId ';
        $this->queryArgs[':eventId'] = $eventId;
    }

    public function setViewStatus($viewStatus)
    {
        if ($viewStatus == 1) {
            $this->viewStatusCondition = 'AND e.view_status = 1 ';
        } else {
            $this->viewStatusCondition = 'AND e.view_status = 0 ';
        }
    }

    public function getEvents()
    {
        global $pdo;
        $fullQuery = $this->query . $this->viewStatusCondition . $this->periodCondition . $this->offsetIdString . $this->orderString . $this->limitString;
        $stmt = $pdo->prepare($fullQuery);
        $stmt->execute($this->queryArgs);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $events;
    }
}
