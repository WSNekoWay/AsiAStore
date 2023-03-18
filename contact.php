<?php
include_once("user_controller.php");
include_once("product_controller.php");
include_once("main_controller.php");
session_start();
$userstatuson = false;
$adminstatuson = false;
$userstatusoff = false;
$adminstatusoff = false;

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

                    header("Location: contact.php");
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
    header("Location: contact.php");
}
if (isset($_POST["adminsubmit"])) {

    $stopadmin = true;
}
if (isset($_POST["contactsubmit"])) {
    if (empty($_POST["contentask"])) {
        $errortext = true;
    }
    if (empty($_POST["typecontact"])) {
        $errorinput = true;
    }
    if (!empty($_POST["typecontact"]) && !empty($_POST["contentask"])) {
        $username = $_SESSION["name"];
        $id = $_SESSION["id"];
        $contenttype = $_POST["typecontact"];
        $contenttext = $_POST["contentask"];
        $conn = my_connectDB();
        $sql_query = "INSERT INTO `contact` (`contact_id`, `contact_username`, `contact_type`, `user_id`, `content_text`, `submit_time`) VALUES ('null', '$username', '$contenttype', '$id', '$contenttext', CURRENT_TIMESTAMP)";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
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
                            <a class="nav-link active" href="contact.php">Contact Us</a>
                        </li>
                        <li class="nav-item " style="margin-top:5px">
                            <form action="index.php" role="search" method="POST">
                                <?php
                                if (isset($_POST["searchkey"])) {
                                ?><input class="form-control me-2" type="search" placeholder="Search" name="searchkey" value="<?= $_POST["searchkey"] ?>" aria-label="Search"><?php
                                                                                                                                                                            } else {
                                                                                                                                                                                ?><input class="form-control me-2" type="search" placeholder="Search" name="searchkey" value="" aria-label="Search"><?php
                                                                                                                                                                            }
                                                                                                                                                    ?>

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
                            <?php } elseif ($_SESSION["role"] === "admin") {
                                if ($_SESSION["state"] === "account") {
                                    $go = "admin.php";
                                } elseif ($_SESSION["state"] === "orders") {
                                    $go = "orders.php";
                                } elseif ($_SESSION["state"] === "products") {
                                    $go = "products.php";
                                } elseif ($_SESSION["state"] === "manage") {
                                    $go = "manages.php";
                                } elseif ($_SESSION["state"] === "inbox") {
                                    $go="inbox.php";
                                  }?>
                                <li class="nav-item me-2 " style="margin-top:5px">
                                    <form action="<?= $go ?>" class="action">
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


    <div class="modal fade" id="displayLogin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  text-dark" id="exampleModalLabel ">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="contact.php">
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
                    <form method="POST" action="contact.php">
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
    <div class="container w-100 mt-5 ">
        <div class="row">
            <div class="col-12 col-lg-5 d-flex justify-content-center align-items-center">
                <img class="w-75 h-auto" src="https://i.pinimg.com/736x/06/48/6e/06486e82ceebc4890902625c408b26c2.jpg" alt="">
            </div>
            <div class="col-12 col-lg-7 ">
                <h2 class="text-lg-start text-center">Confused? Have some feedback or question?</h2>
                <p>
                    Fill the form here! Write your question or some feedback for our store! We will accept your complain or even your suggestion! Login first to send your question or feedback!
                </p>
            </div>
        </div>
    </div>
    <div class="h-auto rounded p-3" id="registerForm">
        <div class="registerTitle w-100 text-center mb-5 mt-2">
            <h1>Contact Us</h1>
        </div>

        <div class="d-flex">
            <div class="col-6 " id="ketRegister">
                <div class="regBox">
                    <img class="m-auto d-block w-75" src="https://smart2printpadang.com/wp-content/uploads/2016/12/fastservice.png" alt="">
                    <p class="ketRegisterText w-75 mx-auto">Fast respond and sevice are our speciality because you are special! </p>
                </div>
                <div class="regBox">
                    <img class="m-auto d-block w-50" src="https://cdn1.iconfinder.com/data/icons/organ-donation-filled-outline/64/life_saving-life-heart-sharing-give-512.png" alt="">
                    <p class="ketRegisterText w-75 mx-auto">Taking care of our customer is our number 1 principle!</p>
                </div>
                <div class="regBox">
                    <img class="m-auto d-block w-50" src="https://cdn-icons-png.flaticon.com/512/2037/2037819.png" alt="">
                    <p class="ketRegisterText w-75 mx-auto">100% will give you satisfication with your either feedback or question.</p>
                </div>

            </div>
            <div class="col-12 col-lg-6 registerContainer">
                <form method="POST" action="">
                    <?php if (isset($_SESSION["role"])) : ?>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Username</label>
                            <input type="text" readonly class="form-control" id="fullName" placeholder="Name" value="<?php echo $_SESSION["name"] ?>">
                        </div>
                    <?php endif; ?>
                    <?php if (!isset($_SESSION["role"])) : ?>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Username</label>
                            <input type="text" class="form-control" id="fullName" placeholder="Name">
                        </div>
                    <?php endif; ?>
                    <div class="input-group">
                        <label for="typeinput" class="form-label">Type</label>  

                    </div>
                    <div>
                        <?php if (isset($errorinput)) : ?>
                            <p style="color: red; font-style:italic;"> Please choose your form type!</p>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <input type="radio" id="feedback" name="typecontact" value="feedback">
                          <label for="feedback">Feedback</label><br>
                        <input type="radio" id="question" name="typecontact" value="question">
                          <label for="question">Question</label><br>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsiPekerjaan" class="form-label">Things you want to say or ask</label>
                        <?php if (isset($errortext)) : ?>
                            <p style="color: red; font-style:italic;"> The content must not be empty!</p>
                        <?php endif; ?>
                        <textarea class="form-control" id="deskripsiPekerjaan" rows="8" name="contentask"></textarea>
                        <?php if (isset($stopadmin)) : ?>
                            <p style="color: red; font-style:italic;"> Admin can't submit! Only for customer!</p>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($_SESSION["role"])) { ?>
                        <?php if ($_SESSION["role"] === "user") : ?>

                            <button type="submit" class="btn btn-danger btn-sm" name="contactsubmit">Submit Comment</button>
                        <?php endif;
                        if ($_SESSION["role"] === "admin") : ?>
                            <button type="submit" class="btn btn-danger btn-sm" name="adminsubmit">Submit Comment</button>
                        <?php endif; ?>
                    <?php } ?>
                    <?php if (!isset($_SESSION["role"])) : ?>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#displayLogin">Submit Comment</button>
                    <?php endif; ?>

                </form>
            </div>
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