<?php
include_once("user_controller.php");
include_once("product_controller.php");
include_once("main_controller.php");
include_once("review_controller.php");
session_start();
if ($_SESSION["role"] !== "admin") {
  header("Location: index.php");
}

$_SESSION["state"] = "manage";



if (isset($_POST["logoutBtn"])) {
  session_destroy();
  header("Location: index.php");
}

if (isset($_POST["deleteBtn"])) {


  if (isset($_POST["delete_id"])) {
    $id=$_POST["delete_id"];
    $name=$_POST["delete_name"];
    deleteReviewAccount($name);
    deleteReview($name);
    deleteUser($id);
    
    header("Location: manages.php");

  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Management</title>
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
    body{
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
                <h3>Manage Accounts</h3>
                <hr>
              </div>
              <div style="overflow-x:auto; ">
             
                <table id="data" class=" table table-striped table-bordered table-sm" cellspacing="0" style="width:100%">
                  <thead>
                    <tr>
                      <th class="th-sm tdtext">No</th>
                      <th class="th-sm tdtext">Username</th>
                      <th class="th-sm tdtext">Email</th>                     
                      <th class="th-sm tdtext">Status</th>
                      <th class="th-sm tdtext">Last Login</th>
                      <th class="th-sm tdtext" style="width:9%">Edit</th>
                      <th class="th-sm tdtext" style="width:10%">Delete</th>
                    </tr>
                  </thead>
                  <?php
                  $result = getUser();
                  $a = 0;
                  foreach ($result as $datarow) {

                    if ($datarow["id"] === "1" ||$datarow["id"] === $_SESSION["id"]) {
                    } else {
                  ?>

                      <tbody>
                        <tr>
                          <td class="tdtext" ><?= $a ?></td>
                          <td class="tdtext"><?= $datarow["name"] ?></td>
                          <td class="tdtext"><?= $datarow["email"] ?></td>                         
                          <td class="tdtext"><?= $datarow["status"] ?></td>
                          <td class="tdtext"><?= $datarow["lastdate"] ?></td>
                          <td>
                            <form action="updateuser.php" method="POST">
                              <input type="hidden" name="userid" value="<?= $datarow["id"] ?>">
                              <input type="submit" name="update_submits" value="Edit Account">
                            </form>
                          </td>
                          <td>
                            <form action="" method="POST" enctype="multipart/form-data">
                              <input type="hidden" name="delete_id" value="<?= $datarow["id"] ?>">
                              <input type="hidden" name="delete_name" value="<?= $datarow["name"] ?>">
                              <input type="submit" name="deleteBtn"value="Delete Account">
                            </form>
                          </td>
                        </tr>
                      </tbody>
                  <?PHP
                    }
                    $a++;
                  }

                  ?>


                </table>
              </div>
              <div class="gap-3 d-md-flex justify-content-md-end text-center mt-3">
                <form action="createuser.php">
                <button type="submit" class="btn btn-danger btn-lg" name="createaccount">Create User/Admin</button>
                </form>

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