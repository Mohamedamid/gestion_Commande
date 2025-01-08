<?php

class user
{
    private $name;
    private $email;
    private $password;

    function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getName()
    {
        return $this->name;
    }
    public function getemail()
    {
        return $this->email;
    }

    function affichage($conn)
    {
        $sql = "SELECT id , name, email ,status FROM users WHERE type = 'cleint'";
        $stmt = $conn->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            echo '<tr>';
            echo '<td>' . $user['name'] . '</td>';
            echo '<td>' . $user['email'] . '</td>';
            if ($user['status'] == 'active') {
                echo '<td>
                    <a href="cleint.php?idEdit=' . $user['id'] . '&status=disactive" class="status-link disactive-link">Disactive</a>
                </td>';
            } else {
                echo '<td>
                    <a href="cleint.php?idEdit=' . $user['id'] . '&status=active" class="status-link active-link">Active</a>
                </td>';
            }
            echo '</tr>';
        }
    }

    function affichagetotal($conn)
    {
        $query = "SELECT COUNT(*) AS total_users FROM users WHERE type = 'cleint'";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totaluser = $row['total_users'];
        echo $totaluser;
    }

    function updateStatus($conn, $id, $status)
    {
        $userId = $id;
        $newStatus = $status;
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':status' => $newStatus,
            ':id' => $userId
        ]);
    }
}
?>