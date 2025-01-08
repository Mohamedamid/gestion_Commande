<?php

class commande
{
    private $id_cleint;
    private $date;
    private $price_total;
    private $quantity;

    function __construct($id, $date, $price_total)
    {
        $this->id_cleint = $id;
        $this->date = $date;
        $this->price_total = $price_total;
    }

    public function getid_cleint()
    {
        return $this->id_cleint;
    }
    public function getdate()
    {
        return $this->date;
    }
    public function getPrice_total()
    {
        return $this->price_total;
    }

    function affichProduit($conn)
    {
        $sql = "SELECT * FROM product_management";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $product) {
            $image=base64_encode($product['image']);
            echo '<div class="col-12 col-md-6 col-lg-4 mb-4">';
            echo '<div class="card h-100">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">'. htmlspecialchars($product['name']) .'</h5>';
            echo '<img src="data:image/jpeg;base64,'.$image.'" width="80px">';
            echo '<p class="card-text">'. htmlspecialchars($product['description']) .'</p>';
            echo '</div>';
            echo '<div class="card-footer d-flex flex-wrap justify-content-between align-items-center p-3">';
            echo '<h3>'. htmlspecialchars($product['price']) .' DH</h3>';
            echo '<form action="index.php" method="post">';
            echo '<input type="hidden" name="price" value="'. $product["price"] .'">';
            echo '<div class="input-group" style="max-width: 150px;">';
            echo '<span class="input-group-text">Qty</span>';
            echo '<input type="number" name="quantity" class="form-control" min="1" max="100" value="1">';
            echo '</div>';
            echo '<input type="hidden" name="id_product" value="'. $product["id_product"] .'">';
            if (isset($_SESSION["user_email"]) && !empty($_SESSION["user_email"])) {
                echo '<button type="submit" name="buy_now" class="btn btn-primary mt-2">Buy Now</button>';
            } else {
                echo '<a href="login.php" class="btn btn-primary mt-2">Login to Buy</a>';
            }
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
    function affichage($conn)
    {

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

    function affichagetotal($conn)
    {
        $query = "SELECT COUNT(*) AS total_commandes FROM command";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalcommandes = $row['total_commandes'];
        echo $totalcommandes;
    }

    function ajouterCommand($conn, $idCl, $idPro, $quantity, $priceTotal) {
        $date = date('Y-m-d H:i:s');
        
        $conn->beginTransaction();
    
        if (!isset($_SESSION['idcommand'])) {
            $sql = 'INSERT INTO command (idCleint, date_cmd, price_total) VALUES (:idCl, :date_cmd, :price_total)';
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':idCl' => $idCl,
                ':date_cmd' => $date,
                ':price_total' => $priceTotal
            ]);
            
            $_SESSION['idcommand'] = $conn->lastInsertId();
        } else {
            $sql = "UPDATE `command` SET `price_total` = `price_total` + :prTotal WHERE `id_command` = :idcmd";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":prTotal" => $priceTotal,
                ":idcmd" => $_SESSION['idcommand']
            ]);
        }
    
        $sql1 = 'INSERT INTO product_management (quantity, idProduct, idCommand) VALUES (:quantity, :idProduct, :idCommand)';
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([
            ':quantity' => $quantity,
            ':idProduct' => $idPro,
            ':idCommand' => $_SESSION['idcommand']
        ]);

        $conn->commit();
    }
    
    
    
}
?>