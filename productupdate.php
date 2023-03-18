<?php
include_once("user_controller.php");
include_once("product_controller.php");
include_once("main_controller.php");
include_once("review_controller.php");
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: index.php");
} else {
    $_SESSION["state"] = "products";
}

if (isset($_POST["id_product"])) {
    $_SESSION["productid"] = $_POST["id_product"];
}

if (isset($_POST["logoutBtn"])) {
    session_destroy();
    header("Location: index.php");
}

if (isset($_POST["update_submit"])) {
    $id = $_SESSION["productid"];
    $name = $_POST["productname"];
    $type = $_POST["producttype"];
    $stock = $_POST["productstock"];
    $desc = $_POST["productdesc"];
    $price = $_POST["productprice"];
    $_FILES["imgfile"]["name"];
    if ($name != "" && $type != "" && $stock != "" && $desc != "") {

        $conn = my_connectDB();
        if ($_FILES['imgfile']['error'] === 4) {

            $sql_query = "UPDATE product SET `product_name` = '$name', `product_type` = '$type', `product_stock` = '$stock', `product_description` = '$desc', `product_price` = '$price' WHERE `product_id`='$id'";
            header("Location: productupdate.php");
        } else {
            $oldimg = mysqli_query($conn, "SELECT product_picture FROM product WHERE product_id=$id");
            while ($row = $oldimg->fetch_assoc()) {
                echo $row["product_picture"];
                unlink("images/productimg/" . $row["product_picture"]);
            }
            $namafile = $_FILES['imgfile']['name'];
            $ukuranfile = $_FILES['imgfile']['size'];
            $error = $_FILES['imgfile']['error'];
            $tmpName = $_FILES['imgfile']['tmp_name'];



            $validpicupload = ['jpg', 'jpeg', 'png'];
            $picextension = explode('.', $namafile);
            $picextension = strtolower(end($picextension));
            if (!in_array($picextension, $validpicupload)) {


                echo " <script>
        alert('Upload gambar yang benar!!');

        </script>";
                return false;
            }
            $newfilename = uniqid();
            $newfilename .= '.';
            $newfilename .= $picextension;
            move_uploaded_file($tmpName, 'images/productimg/' . $newfilename);
            $sql_query = "UPDATE product SET `product_name` = '$name', `product_picture` = '$newfilename', `product_type` = '$type', `product_stock` = '$stock', `product_description` = '$desc' ,`product_price` = '$price' WHERE `product_id`='$id'";
        }


        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));
        header("Location: productupdate.php");
        return $result;
        my_closeDB($conn);
    }
}
if (isset($_POST["deleteRBtn"])) {
    deleteReviewProduct($_POST["delete_id"]);

    $datarow = getProduct($_SESSION["productid"]);
    updateReview($_SESSION["productid"], $datarow['review'] - 1);
    header("Location: productupdate.php");
}
if (isset($_POST["deletePBtn"])) {
    $conn = my_connectDB();
    $id = $_SESSION["productid"];
    $sql_quer = "DELETE FROM `review` WHERE product_id = '$id'";
    $result = mysqli_query($conn, $sql_quer) or die(mysqli_error($conn));
    $img = mysqli_query($conn, "SELECT product_picture FROM product WHERE product_id=$id");
    while ($row = $img->fetch_assoc()) {
        echo $row["product_picture"];
        unlink("images/productimg/" . $row["product_picture"]);
    }
    $sql_query = "DELETE FROM `product` WHERE product_id = '$id'";
    $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));
    header("Location: products.php");
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store's Update</title>
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
                                <i class="fs-4 bi-grid text-danger"></i> <span class="ms-1 d-none d-sm-inline text-white">Store's Product</span></a>

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
            <div class="col ">
                <div class="container">
                    <div class="row">
                        <div class="col-12">

                            <div>
                                <h3>Store's Product</h3>
                                <hr>
                            </div>
                            <?PHP


                            $productrow = getProduct($_SESSION["productid"]);


                            ?>
                            <div>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="  mx-auto mt-0 mb-0">
                                        <div class=" align-items-center p-2 text-center  card mt-3" style="height: 400px;">
                                            <img name="productpicture" src="images/productimg/<?= $productrow["picture"]; ?>" alt="" class="rounded" width="120" height="100">
                                            <span>Select Image To Upload:<br></span>
                                            <span class="pl-5"><input type="file" name="imgfile"><br></span>
                                            <input type="text" class="text-dark" name="productname" value="<?= $productrow["name"] ?>">
                                            <div class="class mt-3 info">
                                                <span class="d-block">Type: <input type="text" name="producttype" class="text-dark" value="<?= $productrow["type"] ?>"></span>
                                                <span class="d-block">Stock: <input type="text" name="productstock" class="text-dark" value="<?= $productrow["stock"] ?>"></span>
                                                <span class="d-block">Description: <input type="text" name="productdesc" class="text-dark" value="<?= $productrow["description"] ?>"></span>

                                            </div>
                                            <div class="cost mt-3 text-dark">
                                                <span>Rp <input type="text" name="productprice" value="<?= $productrow["price"] ?>"></span>
                                            </div>
                                        </div>
                                        <div class="gap-3 d-md-flex justify-content-center text-center"><button type="submit" class="btn btn-danger btn-lg mt-2 mb-3" name="update_submit">Update Product</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="tab-pane fade active show" id="pills-reviews" role="tabpanel" aria-labelledby="pills-reviews-tab">


                            <div class="bg-white rounded shadow-sm p-4 pt-0 mb-4 ">
                                <h5 class="mb-1 text-dark">Ratings and Reviews</h5>
                                <div style="overflow-x:auto;">

                                    <table id="data" class=" table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="th-sm dtext">No</th>
                                                <th class="th-sm dtext">Username</th>
                                                <th class="th-sm ">Rate</th>
                                                <th class="th-sm ">Review</th>
                                                <th class="th-sm ">Delete</th>
                                            </tr>
                                        </thead>
                                        <?php

                                        $a = 0;
                                        $result = readReview();
                                        foreach ($result as $datarow) {
                                            if ($datarow["productid"] === $productrow["id"]) {
                                                $a++;
                                        ?>

                                                <tbody>
                                                    <tr>
                                                        <td class="dtext"><?= $a ?></td>
                                                        <td class="dtext"><?= $datarow["name"] ?></td>
                                                        <td class="dtext"><?= $datarow["rate"] ?></td>
                                                        <td class="dtext"><?= $datarow["review"] ?></td>
                                                        <td>
                                                            <form action="" method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="delete_id" value="<?= $datarow["id"] ?>">
                                                                <input type="submit" name="deleteRBtn" value="Delete Review">
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                        <?PHP
                                            }
                                        }

                                        ?>


                                    </table>

                                </div>

                            </div>
                            <div class="gap-3 d-md-flex justify-content-center text-center">

                                <form action="" method="POST">
                                    <button type="submit" class="btn btn-danger btn-lg mt-2 mb-3" name="deletePBtn">Delete Product</button>
                                </form>
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