<?php
session_start();
class Product
{
    private $name;
    private $description;
    private $price;
    private $quantity;
    private $image;

    function __construct($name, $description, $price, $quantity , $image)
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->image = $image;
    }

    public function getName()
    {
        return $this->name;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function getImage()
    {
        return $this->image;
    }

    function affichProduit($conn)
    {
        $sql = "SELECT * FROM product where delet_at = 'null'";
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
        $sql = "SELECT id_product, name, image, description, price, quantity ,delet_at FROM product WHERE delet_at = 'null' ";
        $stmt = $conn->query($sql);
        $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($produits as $produit) {
            $image=base64_encode($produit['image']);

            if ($produit['quantity'] < 20) {
                $color = 'red';
            } elseif ($produit['quantity'] >= 20 && $produit['quantity'] < 40) {
                $color = 'orange';
            } else {
                $color = 'green';
            }

            echo '<tr>';
            echo '<td class="idproduit idp">' . $produit['id_product'] . '</td>';
            echo '<td style="width:150px">' . htmlspecialchars($produit['name']) . '</td>';
            echo '<td style="width:150px"><img src="data:image/jpeg;base64,' . $image . '" width="80px" style="border:none;"></td>';
            echo '<td>' . htmlspecialchars($produit['description']) . '</td>';
            echo '<td style="width:100px">' . htmlspecialchars($produit['price']) . ' DH</td>';
            echo '<td style="background-color: ' . $color . ';">' . htmlspecialchars($produit['quantity']) . '</td>';
            echo '<td class="idproduit">
                <a href="gestionProduit.php?Edit=' . $produit["id_product"] . '" class="edit">Edit</a>
                <a href="gestionProduit.php?Delet=' . $produit["id_product"] . '" class="delete">Delet</a>
            </td>';
            echo '</tr>';
        }
    }

    function affichagetotal($conn)
    {
        $query = "SELECT COUNT(*) AS total_produits FROM product where delet_at= 'null'";
        $stmt = $conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalProduits = $row['total_produits'];
        echo $totalProduits;
    }

    function ajouterProduit($conn)
    {
        $query = "INSERT INTO product (name, image ,description, price, quantity ,delet_at) 
                    VALUES (:name, :image, :description, :price, :quantity ,'null')";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':name' => $this->name,
            ':image' => $this->image,
            ':description' => $this->description,
            ':price' => $this->price,
            ':quantity' => $this->quantity
        ]);
    }

    function editProduit($conn, $id)
    {
        $id = $_GET["Edit"];
        $updateQuery = "UPDATE product SET name = :name, image = :image,description = :description, price = :price, quantity = :quantity WHERE id_product = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([
            ':name' => $this->name,
            ':image'=> $this->image,
            ':description' => $this->description,
            ':price' => $this->price,
            ':quantity' => $this->quantity,
            ':id' => $id
        ]);
    }

    function deletProduit($conn, $id)
    {
        $date = date('Y-m-d H:i:s');
        $query = "UPDATE `product` SET delet_at = :delet_at WHERE id_product = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':delet_at', $date, PDO::PARAM_STR);
        $stmt->execute();
    }
}
?>