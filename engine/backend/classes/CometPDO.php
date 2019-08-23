<?php

class CometPDO
{
    private $db;

    public function __construct($dsn, $username, $passwd)
    {
        try {
            $this->db = new PDO($dsn, $username, $passwd);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->db = false;
        }
    }

    public function sendLogMessage($userId, $data = [])
    {
        if (!$this->db) return;
        $stmt = $this->db->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $stmt->execute([':id' => $userId, ':type' => json_encode($data)]);
    }

    public function multipleSendLogMessage($dataArray)
    {
        if (!$this->db) return;
        $stmt = $this->db->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        foreach ($dataArray as $userId => $data) {
            $stmt->execute([':id' => $userId, ':type' => json_encode($data)]);
        }
    }

    public function multipleSendNewMailMessage($dataArray)
    {
        if (!$this->db) return;
        $stmt = $this->db->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'new', :type)");
        foreach ($dataArray as $userId => $data) {
            $stmt->execute([':id' => $userId, ':type' => json_encode($data)]);
        }
    }

    public function sendNewChatMessage($channelName, $data)
    {
        if (!$this->db) return;
        $stmt = $this->db->prepare("INSERT INTO pipes_messages (name, event, message) VALUES (:channelName, 'newChat', :data)");
        $stmt->execute([':channelName' => $channelName, ':data' => json_encode($data)]);
    }

    public function getOnlineUsers($channelName)
    {
        if (!$this->db) {
            global $pdo;
            global $idc;
            $lastVisitTime = time() - 180;
            $stmt = $pdo->prepare("SELECT id FROM users WHERE idcompany = :companyId AND activity > :lastVisitTime");
            $stmt->execute([':companyId' => $idc, ':lastVisitTime' => $lastVisitTime]);
            $onlineUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $onlineUsers;
        } else {
            $stmt = $this->db->prepare('SELECT * FROM users_in_pipes WHERE name = :channelName');
            $stmt->execute([':channelName' => $channelName]);
            $onlineUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_column($onlineUsers, 'user_id');
        }
    }

    public function clearMessages($userId)
    {
        if (!$this->db) return;
        $stmt = $this->db->prepare("DELETE FROM users_messages WHERE id =:id");
        $stmt->execute(array(':id' => $userId));
    }

    public function authorizeUser($userId, $hash)
    {
        if (!$this->db) return;
        $stmt = $this->db->prepare("INSERT INTO users_auth (id, hash )VALUES (:id, :hash)");
        $stmt->execute(array(':id' => $userId, ':hash' => $hash));
    }

    public function getStatus() {
        if (!$this->db) {
            return false;
        }
        return true;
    }
}