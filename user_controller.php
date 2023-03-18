<?php
include_once("main_controller.php");




function getUser()
{
    $allData = array();
    $conn = my_connectDB();

    if ($conn != null) {
        $sql_query = "SELECT * FROM `user`";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['id'] = $row["user_id"];
                $data['name'] = $row["user_name"];
                $data['email'] = $row["user_email"];
                $data['password'] = $row["user_password"];
                $data['status'] = $row["user_status"];
                $data['lastdate'] = $row["user_last_login"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}
function deleteUser($id)
{
    $conn = my_connectDB();

    if ($conn != null) {
        $id = $conn->real_escape_string($id);
        $sql_query = "DELETE FROM `user` WHERE user_id = $id";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));
    }
}

function createUser($name, $email, $password, $status)
{
    $conn = my_connectDB();

    if ($conn != null) {
        $sql_query = "INSERT INTO `user` (`user_id`, `user_name`, `user_email`, `user_password`, `user_status`, `user_last_login`) VALUES ('null', '$name', '$email', '$password', '$status', CURRENT_TIMESTAMP)";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        // echo $sql_query;


    }
}
function readUser($ID){
    $allData = array();
    if ($ID > 0) {
        $conn = my_connectDB();
        $sql_query = "SELECT * FROM user WHERE user_id =$ID";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['id'] = $row["user_id"];
                $data['name'] = $row["user_name"];
                $data['email'] = $row["user_email"];
                $data['password'] = $row["user_password"];
                $data['status'] = $row["user_status"];
                $data['lastdate'] = $row["user_last_login"];
                array_push($allData, $data);
            }
        }
        my_closeDB($conn);
        return $data;
    }
}

function updateuser($id, $name, $password)
{
    if ($id != "" && $name != "" && $password != "") {
        $conn = my_connectDB();
        $sql_query = "UPDATE `user` SET `user_name` = '$name', `user_password` = '$password', `user_last_login` = CURRENT_TIMESTAMP WHERE user_id=$id";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    }
}

function updateusernameemail($id, $name, $email,$status)
{
    if ($id != "" && $name != "" && $email != "") {
        $conn = my_connectDB();
        $sql_query = "UPDATE `user` SET `user_name` = '$name', `user_email` = '$email', `user_status` = '$status', `user_last_login` = CURRENT_TIMESTAMP WHERE user_id=$id";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    }
}


function adminupdateuser($id, $name,$email, $password,$status)
{
    if ($id != "" && $name != "" && $password != "") {
        $conn = my_connectDB();
        $sql_query = "UPDATE `user` SET `user_name` = '$name', `user_email` = '$email', `user_password` = '$password', `user_status` = '$status', `user_last_login` = CURRENT_TIMESTAMP WHERE user_id=$id";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    }
}
function updateuserpass($id, $password)
{
    if ($id != ""  && $password != "") {
        $conn = my_connectDB();
        $sql_query = "UPDATE `user` SET  `user_password` = '$password', `user_last_login` = CURRENT_TIMESTAMP WHERE user_id=$id";



        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    }
}
function updateusernameonly($id, $name)
{
    if ($id != "" && $name != "" ) {
        $conn = my_connectDB();
        $sql_query = "UPDATE `user` SET `user_name` = '$name', `user_last_login` = CURRENT_TIMESTAMP WHERE user_id=$id";



        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    }
}
function updatelogin($id)
{
    
        $conn = my_connectDB();
        $sql_query = "UPDATE `user` SET  `user_last_login` = CURRENT_TIMESTAMP WHERE user_id=$id";



        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    
}
