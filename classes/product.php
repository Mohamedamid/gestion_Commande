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
        $sql = "SELECT id_product, name, description, price, quantity ,delet_at FROM product WHERE delet_at = 'null' ";
        $stmt = $conn->query($sql);
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($produits as $produit) {
            if ($produit['quantity'] < 20) {
                $color = 'red';
            } elseif ($produit['quantity'] >= 20 && $produit['quantity'] < 60) {
                $color = 'orange';
            } else {
                $color = 'green';
            }

            echo '<tr>';
            echo '<td class="idproduit idp">' . $produit['id_product'] . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($produit['name']) . '</td>';
            echo '<td>' . htmlspecialchars($produit['description']) . '</td>';
            echo '<td style="width:100px">' . htmlspecialchars($produit['price']) . ' DH</td>';
            echo '<td style="background-color: ' . $color . ';">' . htmlspecialchars($produit['quantity']) . '</td>';
            echo '<td class="idproduit">
                <a href="gestionProduit.php?Edit='.$produit["id_product"].'" class="edit">Edit</a>
                <a href="gestionProduit.php?Delet='.$produit["id_product"].'" class="delete">Delet</a>
            </td>';
            echo '</tr>';
        }
    }

    function affichagetotal($conn){
        $query = "SELECT COUNT(*) AS total_produits FROM product where delet_at= 'null'";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalProduits = $row['total_produits'];
        echo $totalProduits;
    }

    function ajouterProduit($conn) {
        $query = "INSERT INTO product (name, description, price, quantity) 
                    VALUES (:name, :description, :price, :quantity)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':name' => $this->name,
            ':description' => $this->description,
            ':price' => $this->price,
            ':quantity' => $this->quantity
        ]);
    }

    function editProduit($conn ,$id) {
        $id = $_GET["Edit"];
        $updateQuery = "UPDATE product SET name = :name, description = :description, price = :price, quantity = :quantity WHERE id_product = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([
            ':name' => $this->name,
            ':description' => $this->description,
            ':price' => $this->price,
            ':quantity' => $this->quantity,
            ':id' => $id
        ]);
    }

    function deletProduit($conn, $id) {
        $date = date('Y-m-d H:i:s');
        $query = "UPDATE `product` SET delet_at = :delet_at WHERE id_product = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':delet_at', $date, PDO::PARAM_STR);
        $stmt->execute();
    }
}
?>