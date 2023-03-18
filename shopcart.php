<?php
include_once("user_controller.php");
include_once("product_controller.php");
include_once("main_controller.php");
include_once("shop_controller.php");




session_start();
$userstatuson = false;
$adminstatuson = false;
$userstatusoff = false;
$adminstatusoff = false;
if(isset($_SESSION["role"])){
    if($_SESSION["role"]==="admin"){
        header("Location: orders.php");
    }
}

if (isset($_POST["loginBtn"])) {

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $conn = my_connectDB();
        $uppercase = preg_match('@[A-Z]@', $_POST["password"]);
        $lowercase = preg_match('@[a-z]@', $_POST["password"]);
        $number    = preg_match('@[0-9]@', $_POST["password"]);
        if (empty($_POST["email"])) {
            $emailempty = true;
        }
        if ($_POST['password'] != '') {
            if (!$uppercase || !$lowercase || !$number || !strlen($_POST["password"]) >= 8) {
                $errorpassw = true;
            } else {
                $email = $_POST["email"];
                $password = $_POST["password"];
                $result = mysqli_query($conn, "SELECT * FROM user WHERE user_email= '$email'");

                if (mysqli_num_rows($result) === 1) {

                    $row = mysqli_fetch_assoc($result);
                    if (password_verify($password, $row["user_password"])) {

                        $_SESSION["name"] = $row["user_name"];
                        $_SESSION["id"] = $row["user_id"];
                        $_SESSION["email"] = $row["user_email"];
                        if ($row["user_status"] === 'user') {
                            $userstatuson = true;
                        } else if ($row["user_status"] === 'admin') {
                            $adminstatuson = true;
                        }
                        updatelogin($row["user_id"]);
                    } else {
                        $errorpss = true;
                    }
                } else {
                    $error = true;
                }
            }
        } else {
            $passempty = true;
        }
    } else {
        $emailempty = true;
        $passempty = true;
    }
}
$errorpass = false;
$errorpass2 = false;
$errorconfirm = false;
$errorinputusername = false;
$errorinputemail = false;
$erroremail = false;
if (isset($_POST["register"])) {

    if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password2"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password2 = $_POST["password2"];
        $errorpass = false;
        $errorconfirm = false;
        $errorinputusername = false;
        $errorinputemail = false;
        $uppercase = preg_match('@[A-Z]@', $_POST["password"]);
        $lowercase = preg_match('@[a-z]@', $_POST["password"]);
        $number    = preg_match('@[0-9]@', $_POST["password"]);
        if (empty($username)) {
            $errorinputusername = true;
        }
        if (empty($email)) {
            $errorinputemail = true;
        }
        if (empty($password2)) {
            $errorpass2 = true;
        }
        if ($password !== $password2 && !empty($password)) {
            $errorconfirm = true;
        } elseif ($password == $password2) {

            if (!$uppercase || !$lowercase || !$number || !strlen($_POST["password"]) >= 8) {
                $errorpass = true;
            } else {



                $result = getUser();
                $a = false;
                foreach ($result as $datarow) {
                    if ($datarow["email"] == $email) {
                        $a = true;
                    }
                }
                if ($a === false && $errorinputusername === false && $errorinputemail === false) {
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $status = 'user';
                    createUser($username, $email, $password, $status);

                    header("Location: shopcart.php");
                } else {
                    $erroremail = true;
                }
            }
        }
    }
}
if ($userstatuson === true) {
    $_SESSION["role"] = 'user';
}
if ($adminstatuson === true) {
    $_SESSION["state"] = 'account';
    $_SESSION["role"] = 'admin';
}



if (isset($_POST["logoutBtn"])) {
    session_destroy();
    header("Location: index.php");
}

if (isset($_POST["btnminus"])) {
    $max = $_POST["prmax"];
    $min = 1;
    $price = $_POST["price"];
    $pricetotal = $_POST["prit"];
    $id = $_POST["prid"];
    $quantity = $_POST["prq"] - 1;
    $change = $pricetotal - $price;
    if ($quantity >= $min && $quantity <= $max) {
        updatequantity($id, $quantity, $change);
    }
}
if (isset($_POST["btnplus"])) {
    $max = $_POST["prmax"];
    $min = 1;
    $id = $_POST["prid"];
    $price = $_POST["price"];
    $pricetotal = $_POST["prit"];
    $quantity = $_POST["prq"] + 1;
    $change = $pricetotal + $price;
    if ($quantity >= $min && $quantity <= $max) {
        updatequantity($id, $quantity, $change);
    }
}
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] === "user") {
        $result = getUID("0");
        $resut = getList();

        foreach ($result as $datarow) {
            $id = $datarow["id"];
            $uid = $_SESSION["id"];
            $pid = $datarow["pid"];

            $a = false;
            foreach ($resut as $orderow) {
                $list = $orderow["id"];
                if ($orderow["userid"] == $uid) {
                    if ($orderow["pid"] == $pid) {

                        $a = true;



                        $y = $orderow['quantity'] + $datarow["quantity"];
                        $z = $orderow["max"];

                        if ($z > $y) {

                            $change = $orderow["pricetotal"] + $datarow["pricetotal"];
                            updatequantity($list, $y, $change);
                            deleteList($id);
                        } else {
                            $change = $orderow["price"] * $z;
                            $y = $datarow["stock"];
                            updatequantity($list, $y, $change);
                            deleteList($id);
                        }
                    }
                }
            }
            if ($a === false) {
                updateUID($id, $uid);
            }
        }
    } else {
        $result = getUID("0");
        foreach ($result as $datarow) {
            $id = $datarow["id"];
            deleteList($id);
        }
    }
}
if (isset($_POST["deleteBtn"])) {

    deleteList($_POST["delete_id"]);
}
if (isset($_POST["orderBtn"])) {
    if (isset($_SESSION["role"])) {
        if ($_SESSION["role"] === "user") {
            $cantbuy=false;
          
           
            $money=0;
            $haveorder=false;
            $result = getUID($_SESSION["id"]);
            foreach ($result as $daterow) {
                    $resut=readProduct();
                    foreach($resut as $datrow){
                        if($daterow["pid"]===$datrow["id"]){
                            if($datrow["stock"]>=$daterow["quantity"]){
                                $stoc=$datrow["stock"]-$daterow["quantity"];
                            updateStock($datrow["id"],$stoc);
                            }else{
                                $cantbuy=true;
                            }
                            
                        }
                    }
                    $money=$money+$daterow["pricetotal"];
                    $haveorder=true;

                
                
            }
            $a = 0;
            $resul = getHistory();
            foreach ($resul as $datarow) {
                $a = $datarow["id"];
            }
            
            $a = $a + 1;
            if($haveorder===true&&$cantbuy===false){

                addHistory($a,$_SESSION["id"],$money);
                moveList($_SESSION["id"],$a);
                
            }elseif($cantbuy===true){
                echo '<script>alert("We can not give you what you want because the number of orders exceeds our stock")</script>';
            }
           
            
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Cart</title>
    <link rel="stylesheet" href="stylewebsite.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.datatables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min."></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css" integrity="sha384-zIaWifL2YFF1qaDiAo0JFgsmasocJ/rqu7LKYH8CoBEXqGbb9eO+Xi3s6fQhgFWM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .card {
            outline: none;
            background-color: #fff;
            border-radius: 20px;
            transition: transform .3s;
        }

        .card:hover {
            transform: translateY(-15px);
            transition: transform .3s;
        }

        .navbar {
            top: 0px;
            position: sticky;
            z-index: 100;
        }

        .btn-link {
            border: none;
            outline: none;
            background-color: white;
            cursor: pointer;
            color: inherit;
            padding: 0;
            text-decoration: none;
            font-family: inherit;
            font-size: inherit;
        }

        body {
            width: 100vw;
            min-width: 100vw;
            max-width: 100vw;

            overflow-x: hidden
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark m-0">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php" class="asia">
                <h1 style=font-size:50px><span style="color:#dc3545">AsiA</span><em>Store</em></h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <div class="ms-auto">
                    <ul class=" navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" style="margin-top:5px" href="index.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle " style="margin-top:5px" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Category
                            </a>
                            <ul class="dropdown-menu " style="margin-top:5px" aria-labelledby="navbarDropdown">
                                <?PHP


                                $result = readProduct();

                                $arrcat = array();
                                foreach ($result as $datarow) {
                                    $a = false;
                                    foreach ($arrcat as $i) {
                                        if ($datarow === $i) {
                                            $a = true;
                                        }
                                        if ($a === false) {

                                ?><li class="ml-2">
                                                <form action="category.php" method="post">
                                                    <button type="submit" name="categorykey" value="<?= $datarow["type"] ?>" class="btn-link"><?= $datarow["type"] ?></button>
                                                </form>
                                            </li>

                                <?php
                                            $a = true;
                                        }
                                    }
                                    array_push($arrcat, $datarow["type"]);
                                }
                                ?>

                            </ul>
                        </li>
                        <li class="nav-item " style="margin-top:5px">
                            <a class="nav-link" href="aboutus.php" aria-current="page">About Us</a>
                        </li>
                        <li class="nav-item " style="margin-top:5px">
                            <a class="nav-link" href="contact.php">Contact Us</a>
                        </li>
                        <li class="nav-item " style="margin-top:5px">
                            <form action="" role="search" method="POST">
                                <?php
                                if (isset($_POST["searchkey"])) {
                                ?><input class="form-control me-2" type="search" placeholder="Search" name="searchkey" value="<?= $_POST["searchkey"] ?>" aria-label="Search"><?php
                                                                                                                                                                            } else {
                                                                                                                                                                                ?><input class="form-control me-2" type="search" placeholder="Search" name="searchkey" value="" aria-label="Search"><?php
                                                                                                                                                                                                                                                                                                } ?>
                        </li>
                        <li class="nav-item me-2 " style="margin-top:5px">
                            <button class="btn btn-danger  bi bi-search" type="submit"></button>
                            </form>
                        </li>
                        <li class="nav-item me-2 " style="margin-top:5px">
                            <form method="POST" action="shopcart.php">
                                <button class="btn btn-danger  bi bi-cart" type="submit"></button>
                            </form>
                        </li>
                        <?php if (!isset($_SESSION["role"])) { ?>
                            <li class="nav-item me-2 " style="margin-top:5px">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#displayLogin">Login</button>
                            </li>
                            <li class="nav-item " style="margin-top:5px">
                                <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#displayRegister">Register</button>
                            </li>
                        <?php } else { ?>

                            <li class="nav-item me-2 " style="margin-top:5px">
                                <form method="POST" action="">
                                    <button type="submit" class="btn btn-danger" name="logoutBtn">Logout</button>
                                </form>
                            </li>
                            <?php if ($_SESSION["role"] === "user") { ?>
                                <li class="nav-item me-2 " style="margin-top:5px">
                                    <form action="profile.php" class="action">
                                        <button type="submit" class="btn btn-danger">Profile</button>
                                    </form>
                                </li>
                            <?php } elseif ($_SESSION["role"] === "admin") { ?>
                                <li class="nav-item me-2 " style="margin-top:5px">
                                    <form action="admin.php" class="action">
                                        <button type="submit" class="btn btn-danger">Admin</button>
                                    </form>
                                </li>
                            <?php } ?>


                        <?php } ?>
                        <li class="nav-item me-2 " style="margin-top:4px">
                            <label class="switch">
                                <input type="checkbox" name="togglecolor" autocomplete="off" id="click">
                                <span class="slider round"></span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    </div>

    <div class="modal fade" id="displayLogin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  text-dark" id="exampleModalLabel ">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div>

                        </div>
                        <div class="mb-3">
                            <label for="email " class="text-dark">Email Address :</label>
                            <input type="email" class="form-control" placeholder="Enter email" name="email">
                        </div>
                        <div class="mb-3">
                            <?php if (isset($error)) : ?>
                                <p style="color: red; font-style:italic;"> Email is wrong!</p>
                            <?php endif; ?>
                            <?php if (isset($emailempty)) : ?>
                                <p style="color: red; font-style:italic;"> Please fill your email!</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="text-dark">Password :</label>
                            <input type="password" class="form-control" placeholder="Password" name="password">
                        </div>
                        <div class="mb-3">
                            <?php if (isset($errorpassw)) : ?>
                                <p style="color: red; font-style:italic;"> Password must have uppercase, lowercase, and number!</p>
                            <?php endif; ?>
                            <?php if (isset($error) || isset($errorpss)) : ?>
                                <p style="color: red; font-style:italic;"> Password is wrong!</p>
                            <?php endif; ?>
                            <?php if (isset($passempty)) : ?>
                                <p style="color: red; font-style:italic;"> Please fill your password!</p>
                            <?php endif; ?>
                        </div>
                        <button type="submit" name="loginBtn" class="btn btn-danger mt-3">Login</button>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="displayRegister" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  text-dark" id="exampleModalLabel ">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label text-dark">Username</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username">
                        </div>
                        <div class="mb-3">
                            <?php
                            if ($errorinputusername === true) : ?>
                                <p style="color: red; font-style:italic;"> Please fill the username</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-dark">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <?php if ($errorinputemail === true && $erroremail === false) : ?>
                                <p style="color: red; font-style:italic;"> Please fill the email</p>
                            <?php endif; ?>
                            <?php if ($erroremail === true) : ?>
                                <p style="color: red; font-style:italic;"> Use other email!</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="password1" class="form-label  text-dark">Password</label>
                            <input type="password" class="form-control" id="password1" placeholder="Password" name="password">
                        </div>
                        <div class="mb-3">
                            <?php if ($errorpass === true) : ?>
                                <p style="color: red; font-style:italic;"> Password must have uppercase, lowercase, number and at least 8 characters</p>
                            <?php endif; ?>
                            <?php if ($errorconfirm === true && $errorpass === false) : ?>
                                <p style="color: red; font-style:italic;"> Password and password confirmation must be the same!</p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="password2" class="form-label  text-dark">Password Confirmation</label>
                            <input type="password" class="form-control" id="password2" placeholder="Rewrite Password" name="password2">
                        </div>
                        <div class="mb-3">
                            <?php if ($errorconfirm === true || $errorpass2 === true) : ?>
                                <p style="color: red; font-style:italic;"> Password and password confirmation must be the same!</p>
                            <?php endif; ?>
                            <?php if ($errorpass === true && $errorconfirm === false) : ?>
                                <p style="color: red; font-style:italic;"> Wrong password format!</p>
                            <?php endif; ?>
                        </div>
                        <button type="submit" name="register" value="register" class="btn btn-danger">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="col py-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="my-4">
                        <h3>Buying List</h3>
                        <hr>
                    </div>
                    <div style="overflow-x:auto; ">

                        <table id="datax" class=" table table-striped table-bordered table-sm mb-5" cellspacing="0" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm tdtext">No</th>
                                    <th class="th-sm tdtext">Product</th>
                                    <th class="th-sm tdtext">Quantity</th>
                                    <th class="th-sm tdtext">Price</th>
                                    <th class="th-sm tdtext">Total Price</th>
                                    <th class="th-sm tdtext" style="width:20%">Remove From List</th>
                                </tr>
                            </thead>
                            <?php


                            $result = getList();
                            $a = 1;
                            foreach ($result as $datarow) {
                                $id = 0;
                                if (isset($_SESSION["role"])) {
                                    $id = $_SESSION["id"];
                                }
                                if ($datarow["userid"] === "$id") {
                            ?>

                                    <tbody>
                                        <tr>
                                            <td class="tdtext"><?= $a ?></td>
                                            <td class="tdtext"><?= $datarow["name"] ?></td>
                                            <td class="tdtext w-25">
                                                <form action="" method="POST"><input type="text" hidden name="prid" value="<?= $datarow["id"] ?>"><input type="text" hidden name="price" value="<?= $datarow["price"] ?>"><input type="text" hidden name="prit" value="<?= $datarow["pricetotal"] ?>"><input type="text" hidden name="prq" value="<?= $datarow["quantity"] ?>"><input type="text" hidden name="prmax" value="<?= $datarow["max"] ?>"><button name="btnminus" class="btnminus floating"><i class="fa fa-minus minus"></i></button></form><input type="number" style="width:33%" readonly value="<?= $datarow["quantity"] ?>" min="1" max="<?= $datarow["max"] ?>" name="quantity" class="floating">
                                                <form action="" method="POST"><input type="text" hidden name="prid" value="<?= $datarow["id"] ?>"><input type="text" hidden name="price" value="<?= $datarow["price"] ?>"><input type="text" hidden name="prit" value="<?= $datarow["pricetotal"] ?>"><input type="text" hidden name="prq" value="<?= $datarow["quantity"] ?>"><input type="text" hidden name="prmax" value="<?= $datarow["max"] ?>"><button name="btnplus" class=" floating"><i class="fa fa-plus"></i></button></form>
                                            </td>
                                            <td class="tdtext">Rp <?= $datarow["price"] ?></td>
                                            <td class="tdtext">Rp <?= $datarow["pricetotal"] ?></td>
                                            <td>
                                                <form action="" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="delete_id" value="<?= $datarow["id"] ?>">
                                                    <input type="submit" name="deleteBtn" value="X">
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                            <?PHP

                                    $a++;
                                }
                            }

                            ?>


                        </table>
                    </div>
                    <div class="gap-3 d-md-flex justify-content-md-end text-center mt-3 mb-5">

                        <form action="" method="POST">
                            <?php if (isset($_SESSION["role"])) { ?>
                                <?php if ($_SESSION["role"] === "user") : ?>

                                    <button type="submit" class="btn btn-danger btn-lg" name="orderBtn">Order</button>
                                <?php endif;
                                if ($_SESSION["role"] === "admin") : ?>
                                    <button type="submit" disabled class="btn btn-danger btn-lg" name="adminsubmit">Order</button>
                                <?php endif; ?>
                            <?php } ?>
                            <?php if (!isset($_SESSION["role"])) : ?>
                                <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#displayLogin">Order</button>
                            <?php endif; ?>
                        </form>


                    </div>
                </div>
            </div>
        </div>




    </div>
    </div>
    </div>
    </div>
    <div class="col py-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="my-4">
                        <h3>History List</h3>
                        <hr>
                    </div>
                    <div style="overflow-x:auto; ">

                        <table id="data" class=" table table-striped table-bordered table-sm mb-5 mt-5" cellspacing="0" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-sm tdtext">No</th>
                                    <th class="th-sm tdtext">Total</th>
                                    <th class="th-sm tdtext">Time</th>
                                    <th class="th-sm tdtext">Check</th>
                                </tr>
                            </thead>
                            <?php
                            if(isset($_SESSION["role"])){
                            $result = getHistoryUser($_SESSION["id"]);
                            $a = 1;
                            foreach ($result as $datarow) {

                               
                            ?>

                                    <tbody>
                                        <tr>
                                            <td class="tdtext"><?= $a ?></td>
                                            <td class="tdtext">Rp<?= $datarow["total"] ?></td>
                                            <td class="tdtext"><?= $datarow["time"] ?></td>                                         
                                            <td>
                                                <form action="historylist.php" method="POST">
                                                    <input type="hidden" name="listid" value="<?= $datarow["id"] ?>">
                                                    <input type="hidden" name="userid" value="<?= $datarow["uid"] ?>">
                                                    <input type="submit" name="checksubmit" value="Check">
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                            <?PHP
                                $a++; }
                               
                            }
                        
                            ?>


                        </table>
                    </div>

                </div>
            </div>
        </div>




    </div>
    </div>
    </div>
    </div>











    <div class="container w-100 mt-5 mb-5">

        <div class="col-12 col-lg-5 d-flex justify-content-center align-items-center">

        </div>


    </div>
    </div>

    <div class="h-25 bg-dark d-flex justify-content-center " style="margin-top:  -50px;" width="1000">
        <div class="m-3">
            <div>
                <a href="index.php" class="link-light me-3">Product</a>
                <a href="aboutus.php" class="link-light me-3">About us</a>
                <a href="contact.php" class="link-light me-3">Contact us</a>

            </div>
            <h6 class="link-light text-center mt-3">Copyright AsiAStore</h6>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="functionaljquery.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

</body>

</html>