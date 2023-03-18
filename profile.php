<?php
include_once("user_controller.php");
include_once("product_controller.php");
include_once("main_controller.php");
include_once("review_controller.php");
session_start();
if ($_SESSION["role"] !== "user") {
  header("Location: index.php");
}

if (isset($_POST["logoutBtn"])) {
  session_destroy();
  header("Location: index.php");
}
if (isset($_POST["updateprofile"])) {
  
$tempname= $_SESSION["name"];
if ($_POST["username"] !==$tempname ) {
  $name = $_POST["username"];
  if (!empty($_POST["oldpass"]) && !empty($_POST["pass1"]) && !empty($_POST["pass2"])) {
    $uppercase = preg_match('@[A-Z]@', $_POST["pass1"]);
    $lowercase = preg_match('@[a-z]@', $_POST["pass1"]);
    $number    = preg_match('@[0-9]@', $_POST["pass1"]);
    if (!$uppercase || !$lowercase || !$number || !strlen($_POST["pass1"]) >= 8 || $_POST["pass1"] !== $_POST["pass2"]) {
      $errorpass = true;
    } else {
      $conn = my_connectDB();
      $oldpass = $_POST["oldpass"];
      $password = $_POST["pass1"];
      $id = $_SESSION["id"];
      
      $result = mysqli_query($conn, "SELECT * FROM user WHERE user_id= '$id' ");

      if (mysqli_num_rows($result) === 1) {

        $row = mysqli_fetch_assoc($result);
        if (password_verify($oldpass, $row["user_password"])) {
          $password = password_hash($password, PASSWORD_DEFAULT);
          $_SESSION["name"] = $name;
          updateuser($id, $name, $password);
        } else {
          $errorold = true;
        }
      }
    }
  } else {
    $id = $_SESSION["id"];
    $_SESSION["name"] = $name;
    updateusernameonly($id, $name);
  }
} else {
  if (!empty($_POST["oldpass"]) && !empty($_POST["pass1"]) && !empty($_POST["pass2"])) {
    $uppercase = preg_match('@[A-Z]@', $_POST["pass1"]);
    $lowercase = preg_match('@[a-z]@', $_POST["pass1"]);
    $number    = preg_match('@[0-9]@', $_POST["pass1"]);
    if (!$uppercase || !$lowercase || !$number || !strlen($_POST["pass1"]) >= 8 || $_POST["pass1"] !== $_POST["pass2"]) {
      $errorpass = true;
    } else {
      $conn = my_connectDB();
      $oldpass = $_POST["oldpass"];
      $password = $_POST["pass1"];
      $id = $_SESSION["id"];
      
      $result = mysqli_query($conn, "SELECT * FROM user WHERE user_id= '$id' ");

      if (mysqli_num_rows($result) === 1) {

        $row = mysqli_fetch_assoc($result);
        if (password_verify($oldpass, $row["user_password"])) {
          $password = password_hash($password, PASSWORD_DEFAULT);
          updateuserpass($id, $password);
        } else {
          $errorold = true;
        }
      }
    }
  }
}
}



if (isset($_POST["deleteprofile"])) {


  if (isset($_SESSION["id"])) {
    deleteReviewAccount($_SESSION["name"]);
    deleteReview($_SESSION["name"]);
    deleteUser($_SESSION["id"]);
    session_destroy();
    header("Location: index.php");

  }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
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
    } .btn-link {
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

                ?><li class="ml-2"> <form action="category.php" method="post">
                <button type="submit" name="categorykey" value="<?= $datarow["type"] ?>" class="btn-link"><?= $datarow["type"] ?></button>
              </form></li>

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
                }?>
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
                

  <div class="container">
    <div class="row">
      <div class="col-12">

        <div class="my-4">
          <h3>My Profile</h3>
          <hr>
        </div>
        <form action="" method="POST">
          <div class="row mb-5 gx-5">

            <div class="row mb-5 gx-5">
              <div class="col-xxl-6 mb-5 mb-xxl-0">
                <div class="bg-secondary-soft px-4 py-5 rounded">
                  <div class="row g-3">
                    <h4 class="mb-4 mt-4">User Detail</h4>

                    <div class="col-md-10">
                      <label class="form-label"> User Name *</label>
                      <input type="text" class="form-control" placeholder="" name="username" value="<?php echo $_SESSION["name"] ?>">
                    </div>

                    <div class="col-md-10">
                      <label class="form-label">Email *</label>
                      <input type="text" readonly class="form-control" placeholder="" value="<?php echo $_SESSION["email"] ?>">
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-xxl-6">
                <div class="bg-secondary-soft px-4 py-5 rounded">
                  <div class="row g-3">
                    <h4 class="my-4">Change Password</h4>
                    <div class="col-md-6">
                      <label for="exampleInputPassword1" class="form-label">Old password *</label>
                      <input type="password" class="form-control" id="exampleInputPassword1" name="oldpass">
                    </div>
                    <div class="col-md-6">
                      <label for="exampleInputPassword2" class="form-label">New password *</label>
                      <input type="password" class="form-control" id="exampleInputPassword2" name="pass1">
                    </div>
                    <div class="col-md-12">
                      <label for="exampleInputPassword3" class="form-label">Confirm Password *</label>
                      <input type="password" class="form-control" id="exampleInputPassword3" name="pass2">
                    </div>
                    <div class="col-md-12">
                      <?php if (isset($errorold)) : ?>
                        <p style="color: red; font-style:italic;"> Old Password is wrong!</p>
                      <?php endif; ?>
                      <?php if (isset($errorpass)) : ?>
                        <p style="color: red; font-style:italic;"> Password must match with confirm password and must have uppercase, lowercase, and number!</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="gap-3 d-md-flex justify-content-md-end text-center">

              <button type="submit" class="btn btn-danger btn-lg" name="updateprofile">Update profile</button>
        </form>
        <form action="" method="POST">
          <button type="submit" class="btn btn-danger btn-lg" value="deleteprofile" name="deleteprofile">Delete profile</button>
        </form>

      </div>
    </div>
  </div>
  </div>










  <div class="container w-100 mt-5 ">

    <div class="col-12 col-lg-5 d-flex justify-content-center align-items-center">
                        <br>
    </div>
    <div class="col-12 col-lg-7 ">
    <br><br>
    <br>
    </div>
    <br>
  </div>
  </div>

  <div class="h-25 bg-dark d-flex justify-content-center " width="1000">
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