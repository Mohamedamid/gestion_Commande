<?php
include("./classes/user.php");
include("./classes/product.php");
include("./classes/command.php");
include_once("./connexion/config.php");

// session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

$sql = "SELECT name, email, type FROM users WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->execute([':email' => $user_email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['type'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST["submit"])) {

    $name = $_POST["name"];
    $discription = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $image = $_FILES["image"]["tmp_name"];

    // if($_FILES["image"]["tmp_name"]){
    //     die ('image valide');
    // }else{
    //     die("error");
    // }
    $img = file_get_contents($image);

    $aj = new Product($name, $discription, $price, $quantity, $img);
    $aj->ajouterProduit($conn);
    header("location:gestionProduit.php");
}

if (isset($_GET["Edit"])) {
    $id = $_GET["Edit"];
    $query = "SELECT * FROM product WHERE id_product = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    $name = $product["name"];
    $description = $product["description"];
    $price = $product["price"];
    $quantity = $product["quantity"];
}

if (isset($_POST["Edit"])) {
    $id = $_GET["Edit"];
    $name = $_POST["name"];
    $discription = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $image = $_FILES["image"]["tmp_name"];

    $img = file_get_contents($image);

    $edit = new Product($name, $discription, $price, $quantity ,$img);
    $edit->editProduit($conn, $id);
    header("location:gestionProduit.php");
}

if (isset($_GET["Delet"])) {
    $id = $_GET["Delet"];
    $Delet = new Product(null, null, null, null ,null);
    $Delet->deletProduit($conn, $id);
}

if (isset($_POST["reset"])) {
    header("location:gestionProduit.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarché en ligne</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style.css?v=1.4">
    <link rel="stylesheet" href="assets/css/styleProduct.css?v=1.4">
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="storefront-outline"></ion-icon>
                        </span>
                        <span class="title">Supermarché en ligne</span>
                    </a>
                </li>

                <li>
                    <a href="admin.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Accueil</span>
                    </a>
                </li>

                <li>
                    <a href="cleint.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Les Clients</span>
                    </a>
                </li>

                <li>
                    <a href="command.php">
                        <span class="icon">
                            <ion-icon name="cart-outline"></ion-icon>
                        </span>
                        <span class="title">commande</span>
                    </a>
                </li>

                <li class="hovered">
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="cube-outline"></ion-icon>
                        </span>
                        <span class="title">Gestion des produits</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>
            </div>
            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $total = new product(null, null, null, null, null);
                            $total->affichagetotal($conn);
                            ?>
                        </div>
                        <div class="cardName">Les Produits</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cube-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $total = new user(null, null, null);
                            $total->affichagetotal($conn);
                            ?>
                        </div>
                        <div class="cardName">Les Cleints</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers">
                            <?php
                            $total = new commande(null, null, null);
                            $total->affichagetotal($conn);
                            ?>
                        </div>
                        <div class="cardName">Les Commande</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                </div>
            </div>

            <!-- ================ Order Details List ================= -->
            <div class="details">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Product Name:</label>
                            <input type="text" id="name" name="name"
                                value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" id="name" name="image" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" id="description" name="description"
                                value="<?php echo isset($description) ? htmlspecialchars($description) : ''; ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price (DH):</label>
                            <input type="number" id="price" name="price" step="any"
                                value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" id="quantity" name="quantity" min="1"
                                value="<?php echo isset($quantity) ? htmlspecialchars($quantity) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <?php
                            if (isset($_GET['Edit'])) {
                                echo '<input type="submit" name="Edit" value="Edit Order" class="btn">';
                            } else {
                                echo '<input type="submit" name="submit" value="Submit Order" class="btn">';
                            }
                            ?>
                        </div>
                    </div>
                </form>
                <form action="" method="post">
                    <input type="submit" value="Reset" name="reset">
                </form>
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Recent Orders</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <td class="idp">id</td>
                                <td>Name</td>
                                <td>image</td>
                                <td>description</td>
                                <td>price</td>
                                <td style="width: 90px;">quantity</td>
                                <td style="width: 100px;height: 40px;">action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $p = new Product(null, null, null, null, null);
                            $p->affichage($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js?v=1"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>