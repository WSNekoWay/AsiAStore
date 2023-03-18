<?php


function getHistory()
{
    $allData = array();
    $conn = my_connectDB();

    if ($conn != null) {
        $sql_query = "SELECT * FROM `history`";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['id'] = $row["list_id"];
                $data['uid'] = $row["user_id"];
                $data['total'] = $row["total_price"];
                $data['time'] = $row["history_time"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}
function getHistoryUser($uid)
{
    $allData = array();
    $conn = my_connectDB();

    if ($conn != null) {
        $sql_query = "SELECT * FROM `history` WHERE `user_id`=$uid";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['id'] = $row["list_id"];
                $data['uid'] = $row["user_id"];
                $data['total'] = $row["total_price"];
                $data['time'] = $row["history_time"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}
function addHistory($id,$uid,$price){
    $conn = my_connectDB();
    if ($conn != null) {
        $sql_query = "INSERT INTO `history` (`list_id`, `user_id`, `total_price`, `history_time`) VALUES ('$id', '$uid', '$price', CURRENT_TIMESTAMP)";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        // echo $sql_query;


    }
}
function getList()
{
    $allData = array();
    $conn = my_connectDB();

    if ($conn != null) {
        $sql_query = "SELECT * FROM `orderlist`";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['id'] = $row["orderlist_id"];
                $data['pid'] = $row["product_id"];
                $data['name'] = $row["product_name"];
                $data['quantity'] = $row["product_quantity"];
                $data['price'] = $row["product_price"];
                $data['userid'] = $row["user_id"];
                $data['max'] = $row["product_max"];
                $data['pricetotal'] = $row["total_price"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}
function getUID($uid)
{
    $allData = array();
    $conn = my_connectDB();

    if ($conn != null) {
        $sql_query = "SELECT * FROM `orderlist` WHERE `user_id`=$uid";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['id'] = $row["orderlist_id"];
                $data['pid'] = $row["product_id"];
                $data['name'] = $row["product_name"];
                $data['quantity'] = $row["product_quantity"];
                $data['price'] = $row["product_price"];
                $data['userid'] = $row["user_id"];
                $data['max'] = $row["product_max"];
                $data['pricetotal'] = $row["total_price"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}


function createOrder($pid, $name, $quantity, $price,$userid,$max)
{
    $conn = my_connectDB();

    if ($conn != null) {
        $sql_query = "INSERT INTO `orderlist` (`orderlist_id`, `product_id`, `product_name`, `product_quantity`, `product_price`, `user_id`, `product_max`,`total_price`) VALUES ('null', '$pid', '$name', '$quantity', '$price', '$userid', '$max',$price)";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        // echo $sql_query;


    }
}
function updatequantity($id, $quantity,$change)
{
    
        $conn = my_connectDB();
        $sql_query = "UPDATE `orderlist` SET `product_quantity` = '$quantity' ,`total_price` = '$change' WHERE orderlist_id=$id";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    
}
function updateUID($id,$uid)
{
    
        $conn = my_connectDB();
        $sql_query = "UPDATE `orderlist` SET `user_id` = '$uid'  WHERE orderlist_id=$id";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    
}


function deleteList($id)
{
    $conn = my_connectDB();

    if ($conn != null) {
        $id = $conn->real_escape_string($id);
        $sql_query = "DELETE FROM `orderlist` WHERE orderlist_id = $id";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));
    }
}
function moveList($uid,$lid){
    $conn = my_connectDB();
    $resut=getUID($uid);
    foreach($resut as $datarow){
    if ($conn != null) {
        $id=$datarow["id"];
        $pid=$datarow['pid'] ;
        $name=$datarow['name'] ;
        $quantity=$datarow['quantity'] ;
        $price=$datarow['price'] ;
        $userid=$datarow['userid'] ;
        $pricetotal=$datarow['pricetotal'];
        $sql_query =  "INSERT `historylist` (`historyid`, `product_id`, `product_name`, `product_quantity`, `product_price`, `user_id`,`total_price`,`list_id`,`orderlist_id`) VALUES ('null', '$pid', '$name', '$quantity', '$price', '$userid','$pricetotal',$lid,$id)"; 
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        // echo $sql_query;

        deleteList($id);
    }}
   
}
function getUIDhistory($uid)
{
    $allData = array();
    $conn = my_connectDB();

    if ($conn != null) {
        $sql_query = "SELECT * FROM `historylist` WHERE `user_id`=$uid ";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['id'] = $row["orderlist_id"];
                $data['pid'] = $row["product_id"];
                $data['name'] = $row["product_name"];
                $data['quantity'] = $row["product_quantity"];
                $data['price'] = $row["product_price"];
                $data['userid'] = $row["user_id"];
                $data['pricetotal'] = $row["total_price"];
                $data['listid']= $row['list_id'];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}
function updateListID($id,$lid)
{
    
        $conn = my_connectDB();
        $sql_query = "UPDATE `historylist` SET `list_id` = '$lid'  WHERE user_id=$id";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    
}
function deleteID($lid)
{
    
        $conn = my_connectDB();
        $sql_query = "DELETE FROM `historylist` WHERE list_id=$lid";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));
        $sql_query = "DELETE FROM `history` WHERE list_id=$lid";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        my_closeDB($conn);
    
}












































?>