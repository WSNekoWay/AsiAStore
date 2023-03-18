<?php
include_once("user_controller.php");
include_once("product_controller.php");
include_once("main_controller.php");
include_once("shop_controller.php");




session_start();
if (!isset($_SESSION["role"])) {
    header("Location: index.php");
}
if (!isset($_POST["listid"])) {
    header("Location: shopcart.php");
}
if (!isset($_POST["userid"])) {
    header("Location: shopcart.php");
}
$userstatuson = false;
$adminstatuson = false;
$userstatusoff = false;
$adminstatusoff = false;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
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
                        <h3>Item List</h3>
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
                                </tr>
                            </thead>
                            <?php


                            $result = getUIDhistory($_POST["userid"]);
                            $a = 1;
                            foreach ($result as $datarow) {


                                if ($datarow["listid"] == $_POST["listid"]) {

                            ?>

                                    <tbody>
                                        <tr>
                                            <td class="tdtext"><?= $a ?></td>
                                            <td class="tdtext"><?= $datarow["name"] ?></td>
                                            <td class="tdtext">
                                                <?= $datarow["quantity"] ?>
                                            </td>
                                            <td class="tdtext">Rp <?= $datarow["price"] ?></td>
                                            <td class="tdtext">Rp <?= $datarow["pricetotal"] ?></td>
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

                        <form action="shopcart.php" method="POST">
                            <button type="submit" class="btn btn-danger btn-lg">Back</button>
                        </form>
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

    <div class="h-25 bg-dark d-flex justify-content-center " style="margin-top:500px" width="1000">
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