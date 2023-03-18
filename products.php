<?php
include_once("user_controller.php");
include_once("product_controller.php");
include_once("main_controller.php");
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: index.php");
} else {
    $_SESSION["state"] = "products";
}

if (isset($_POST["logoutBtn"])) {
    session_destroy();
    header("Location: index.php");
}
if (isset($_POST["createnew"])) {
    createProduct($_POST["productname"], $_POST["imgfile"], $_POST["producttype"], $_POST["productstock"], $_POST["productdesc"], $_POST["productprice"],);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store's Product</title>
    <link rel="stylesheet" href="stylewebsite.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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

        .submitLink {
            background-color: transparent;
            text-decoration: none;
            border: none;
            color: #fbc02d;
            cursor: pointer;
            outline: none;
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
                                <input type="checkbox" autocomplete="off" id="click">
                                <span class="slider round"></span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    </div>

    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li>
                            <a href="manages.php" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-people text-danger"></i> <span class="ms-1 d-none d-sm-inline text-white">Manage Accounts</span> </a>
                        </li>
                        <li>
                            <a href="products.php" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-grid text-danger"></i> <span class="ms-1 d-none d-sm-inline text-white">Store's Products</span></a>

                        <li>
                            <a href="orders.php" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-table text-danger"></i> <span class="ms-1 d-none d-sm-inline text-white">Orders</span></a>
                        </li>
                        <li>
                            <a href="inbox.php" class="nav-link px-0 align-middle">
                                <i class="fs-4 bi-inbox text-danger"></i> <span class="ms-1 d-none d-sm-inline text-white">Inbox</span></a>
                        </li>
                        <li>
                            <a href="admin.php" class="nav-link align-middle px-0">
                                <i class="fs-4 bi-person text-danger"></i> <span class="ms-1 d-none d-sm-inline text-white">Account</span>
                            </a>
                        </li>
                    </ul>
                    <hr>

                </div>
            </div>
            <div class="col py-3">
                <div class="container">
                    <div class="row">
                        <div class="col-12">

                            <div class="my-4">
                                <h3>Store's Product</h3>
                                <hr>
                            </div>
                            <div class="container mb-5 mt-5 ">
                                <div class="row">
                                    <?PHP


                                    $result = readProduct();


                                    foreach ($result as $datarow) {
                                    ?>

                                        <div class="col-md-3 col-6" data-aos="fade-up">
                                            <div class="card mt-3">
                                                <div class="product-1 align-items-center p-2 text-center" style="height: 500px;">
                                                    <img src="images/productimg/<?= $datarow["picture"]; ?>" alt="" class="rounded" width="120" height="100">
                                                    <h5><?= $datarow["name"] ?></h5>
                                                    <div class="class mt-3 info">
                                                        <span class="text1 d-block">Type: <?= $datarow["type"] ?></span>
                                                        <span class="text1 d-block">Stock: <?= $datarow["stock"] ?></span>
                                                        <div class="height:300"> <span class="text1"><?= $datarow["description"] ?></span></div>

                                                    </div>
                                                    <div class="cost mt-3 text-dark">
                                                        <span>Rp <?= $datarow["price"] ?></span>
                                                        <div class="star mt-3 align-items-center">
                                                            <form action="productupdate.php" method="POST" enctype="multipart/form-data">
                                                                <i class="fa fa-star"></i>
                                                                <input type="hidden" name="id_product" value="<?= $datarow["id"] ?>">
                                                                <input type="submit" class="submitLink" value="Review">
                                                                (<?= $datarow["review"] ?>)
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="p-3 buy text-center text-white mt-3 cursor">
                                                    <form action="productupdate.php" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="id_product" value="<?= $datarow["id"] ?>">
                                                        <input type="submit" class="submitLink text-white" value="Edit">

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!--card end here-->
                                    <?php }
                                    ?>
                                    <div class=" col-14" data-aos="fade-up">
                                        <div class="card mt-3">
                                            <form action="" method="POST" enctype="multipart/form-data">
                                                <div class="product-1 align-items-center p-2 text-center">
                                                    <span style="font-size:25px;" class="text-dark">Create Product<br></span>
                                                    <span class="text-dark">Select Image To Upload:<br></span>
                                                    <span class="pl-5"><input type="file" name="imgfile"><br></span>
                                                    <table class="mx-auto">
                                                        <tr class="text-dark">
                                                            <td>Product Name:
                                                            <td><input type="text" class="text-dark" name="productname"></td>
                                                            </td>
                                                        <tr class="text-dark">
                                                            <td class="d-block">Type:
                                                            <td><input type="text" name="producttype" class="text-dark"></td>
                                                            </td>
                                                        <tr class="text-dark">
                                                            <td class="d-block">Stock:
                                                            <td><input type="text" name="productstock" class="text-dark"></td>
                                                            </td>
                                                        <tr class="text-dark">
                                                            <td class="d-block">Description:
                                                            <td><input type="text" name="productdesc" class="text-dark"></td>
                                                            </td>
                                                        <tr class="text-dark">
                                                            <td>Rp
                                                            <td><input type="text" name="productprice"></td>
                                                            </td>

                                                    </table>
                                                </div>
                                                <div class="p-3 buy text-center text-white mt-3 cursor" style="bottom: 0;">
                                                    <input type="submit" class="submitLink text-white" name="createnew" value="Create">

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>










    <div class="container w-100 mt-5 ">

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