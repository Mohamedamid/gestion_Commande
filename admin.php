<?php

include("./classes/product.php");
include("./classes/command.php");
include("./classes/user.php");
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarché en ligne</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style.css?v=1.2">
    <style>
        table * {
            text-align: center !important;
            border: 1px solid black;

        }
        .idproduit{
            display: none; 
        }
        td{
            padding: 15px;
        }
    </style>
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
                <li class="hovered">
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
                <li>
                    <a href="gestionProduit.php">
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
                            $total = new Product(null, null, null, null, null);
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
                            $total->affichagetotal(conn: $conn);
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
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Recent Orders</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <td class="idproduit">id</td>
                                <td>Name</td>
                                <td>image</td>
                                <td>description</td>
                                <td>price</td>
                                <td style="width: 90px;">quantity</td>
                                <td class="idproduit">action</td>
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