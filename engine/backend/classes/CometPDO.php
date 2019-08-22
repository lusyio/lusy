<?php

class CometPDO extends PDO
{
    public function __construct($dsn, $username, $passwd)
    {
        parent::__construct($dsn, $username, $passwd);
    }

    public function sendLogMessage($userId, $data = [])
    {
        $stmt = $this->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $stmt->execute([':id' => $userId, ':type' => json_encode($data)]);
    }

    public function multipleSendLogMessage($dataArray)
    {
        $stmt = $this->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        foreach ($dataArray as $userId => $data) {
            $stmt->execute([':id' => $userId, ':type' => json_encode($data)]);
        }
    }

    public function multipleSendNewMailMessage($dataArray)
    {
        $stmt = $this->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'new', :type)");
        foreach ($dataArray as $userId => $data) {
            $stmt->execute([':id' => $userId, ':type' => json_encode($data)]);
        }
    }

    public function sendNewChatMessage($channelName, $data)
    {
        $stmt = $this->prepare("INSERT INTO pipes_messages (name, event, message) VALUES (:channelName, 'newChat', :data)");
        $stmt->execute([':channelName' => $channelName, ':data' => json_encode($data)]);
    }

    public function getOnlineUsers($channelName)
    {
        $stmt = $this->prepare('SELECT * FROM users_in_pipes WHERE name = :channelName');
        $stmt->execute([':channelName' => $channelName]);
        $onlineUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_column($onlineUsers, 'user_id');
    }
}