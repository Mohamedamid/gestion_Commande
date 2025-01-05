<?php

class commande {
    private $id_cleint;
    private $date;
    private $price_total;
    private $quantity;

    function __construct($id ,$date ,$price_total){
        $this->id_cleint = $id;
        $this->date = $date;
        $this->price_total = $price_total;
    }

    public function getid_cleint(){
        return $this->id_cleint;
    }
    public function getdate(){
        return $this->date;
    }
    public function getPrice_total(){
        return $this->price_total;
    }

    function affichage($conn) {
        
        $sql = "SELECT command.id_command ,users.name ,command.date_cmd, command.price_total 
        FROM command 
        INNER JOIN users 
        ON command.idCleint = users.id;";
    
        $stmt = $conn->query($sql);
        $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($commandes as $command) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($command['id_command']) . '</td>'; 
            echo '<td>' . htmlspecialchars($command['name']) . '</td>'; 
            echo '<td>' . htmlspecialchars($command['date_cmd']) . '</td>'; 
            echo '<td>' . htmlspecialchars($command['price_total']) . ' DH</td>';
            echo '</tr>';
        }
    }
    
    function affichagetotal($conn){
        $query = "SELECT COUNT(*) AS total_commandes FROM command";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalcommandes = $row['total_commandes'];
        echo $totalcommandes;
    }
}
?>