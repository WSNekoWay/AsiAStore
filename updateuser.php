<?php
include_once("user_controller.php");
include_once("product_controller.php");
include_once("main_controller.php");
session_start();
if ($_SESSION["role"] !== "admin") {
  header("Location: index.php");
}

$_SESSION["state"] = "manage";
$errorpass = false;
$errorpass2 = false;
$errorconfirm = false;
$errorinputusername = false;
$errorinputemail = false;
$erroremail = false;

if (isset($_POST["logoutBtn"])) {
  session_destroy();
  header("Location: index.php");
}

if (isset($_POST["updateaccount"])) {
    $id=  $_SESSION["updateid"];
    $username = $_POST["username"];
    $oldemail=$_SESSION["oldemail"];
    $email = $_POST["email"];
    $status = $_POST["status"];
if(empty($username)){
    $errorinputusername = true;
}
if(empty($email)){
    $errorinputemail = true;
}
if (!empty($username) && !empty($email)) {
    if (!empty($_POST["password"]) && !empty($_POST["password2"])) {
    $uppercase = preg_match('@[A-Z]@', $_POST["password"]);
    $lowercase = preg_match('@[a-z]@', $_POST["password"]);
    $number    = preg_match('@[0-9]@', $_POST["password"]);
    if (!$uppercase || !$lowercase || !$number || !strlen($_POST["password"]) >= 8 || $_POST["password"] !== $_POST["password2"]) {
      $errorpass = true;
    } else {
      $conn = my_connectDB();
      $password = $_POST["password"];
     
      
      $result = mysqli_query($conn, "SELECT * FROM user WHERE user_id= '$id' ");

      if (mysqli_num_rows($result) === 1) {

        $row = mysqli_fetch_assoc($result);
        
        $resut = getUser();
        $a = false;
        foreach ($resut as $datarow) {
          if ($datarow["email"] == $email) {
            if($datarow["email"] == $oldemail) {
                
            }else{
                $a = true;
            }
           
          }
        }
        if ($a === false ) {
          $password = password_hash($password, PASSWORD_DEFAULT);
          
          adminupdateuser($id,$username, $email, $password, $status);
          header("Location: manages.php");
        } else {
          $erroremail = true;
        }
       
      }
    }
  } else {
    if ($erroremail!==true ) {
    updateusernameemail($id, $username,$email,$status);
    header("Location: manages.php");
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
  <title>Update User</title>
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
                <h3>Edit Account</h3>
                <hr>
              </div>
              <div>
                <form method="POST" action="updateuser.php">
                  <div class="row mb-5 gx-5">
                    <div class=" mb-5 mb-xxl-0">
                      <div class="bg-secondary-soft px-4 py-5 rounded">
                        <div class="row g-3">
                          <h4 class="mb-4 mt-4">User Detail</h4>
                          <?php 
                          if(isset($_POST["userid"])){
                            $_SESSION["updateid"]=$_POST["userid"];
                          }
                          
                          $datarow = readUser($_SESSION["updateid"]);   
                          $_SESSION["oldemail"]=$datarow['email'];        ?>
                          <input type="text" hidden name="update_id" value="<?=$datarow['id']?>"><br>
                          <div class="col-md-14">
                            <label class="form-label"> User Name *</label>
                            <input type="text" class="form-control" name="username" value="<?=$datarow['name']?>">
                          </div>
                          <div class="col-md-14">
                            <?php
                            if ($errorinputusername === true) : ?>
                              <p style="color: red; font-style:italic;"> Please fill the username</p>
                            <?php endif; ?>
                          </div>

                          <div class="col-md-14">
                            <label class="form-label">Email *</label>
                            <input type="text" class="form-control" value="<?=$datarow['email']?>" name="email">
                          </div>

                          <div class="col-md-14">
                            <?php if ($errorinputemail === true && $erroremail === false) : ?>
                              <p style="color: red; font-style:italic;"> Please fill the email</p>
                            <?php endif; ?>
                            <?php if ($erroremail === true) : ?>
                              <p style="color: red; font-style:italic;"> Use other email!</p>
                            <?php endif; ?>
                          </div>

                          <div class="col-md-14">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" placeholder="Password" name="password">
                          </div>

                          <div class="col-md-14">
                            <?php if ($errorpass === true) : ?>
                              <p style="color: red; font-style:italic;"> Password must have uppercase, lowercase, number and at least 8 characters</p>
                            <?php endif; ?>
                            <?php if ($errorconfirm === true && $errorpass === false) : ?>
                              <p style="color: red; font-style:italic;"> Password and password confirmation must be the same!</p>
                            <?php endif; ?>
                          </div>

                          <div class="col-md-14">
                            <label class="form-label">Password Confirmation *</label>
                            <input type="password" class="form-control" placeholder="Password Confirmation" name="password2">
                          </div>

                          <div class="col-md-14">
                            <?php if ($errorconfirm === true || $errorpass2 === true) : ?>
                              <p style="color: red; font-style:italic;"> Password and password confirmation must be the same!</p>
                            <?php endif; ?>
                            <?php if ($errorpass === true && $errorconfirm === false) : ?>
                              <p style="color: red; font-style:italic;"> Wrong password format!</p>
                            <?php endif; ?>
                          </div>

                          <div class="col-md-14">
                            <label class="form-label">Status *</label>
                            <?php if ($datarow['status']==="user") : ?>
                                <select class="form-select" id="inputGroupSelect01" name="status" >
                              <option selected value="user">User</option>
                              <option value="admin">Admin</option>
                            </select>
                            <?php endif; ?>
                            <?php if ($datarow['status']==="admin") : ?>
                                <select class="form-select" id="inputGroupSelect01" name="status" >
                              <option value="user">User</option>
                              <option selected value="admin">Admin</option>
                            </select>
                            <?php endif; ?>
                           

                          </div>

                        </div>
                      </div>
                    </div>

                    <div class="gap-3 d-md-flex justify-content-md-end text-center">

                      <button type="submit" class="btn btn-danger btn-lg" name="updateaccount">Update User/Admin</button>
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







 