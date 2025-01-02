<?php

class Product{
    private $name;
    private $description;
    private $price;
    private $quantity;

    function __construct($name ,$description ,$price ,$quantity){
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getName(){
        return $this->name;
    }
    public function getDescription(){
        return $this->description;
    }
    public function getPrice(){
        return $this->price;
    }
    public function getQuantity(){
        return $this->quantity;
    }

    function affichage($conn){
        $sql = "SELECT name, description, price, quantity FROM product";
        $stmt = $conn->query($sql);
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($produits as $produit) {
            if ($produit['quantity'] < 20) {
                $color = 'red';
            } elseif ($produit['quantity'] >= 20 && $produit['quantity'] < 100) {
                $color = 'orange';
            } else {
                $color = 'green';
            }

            echo '<tr>';
            echo '<td style="width:150px">' . htmlspecialchars($produit['name']) . '</td>';
            echo '<td>' . htmlspecialchars($produit['description']) . '</td>';
            echo '<td style="width:100px">' . htmlspecialchars($produit['price']) . ' DH</td>';
            echo '<td style="background-color: ' . $color . ';">' . htmlspecialchars($produit['quantity']) . '</td>';
            echo '</tr>';
        }
    }
    function affichagetotal($conn){
        $query = "SELECT COUNT(*) AS total_produits FROM product";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalProduits = $row['total_produits'];

        echo $totalProduits;
    }
}
?>