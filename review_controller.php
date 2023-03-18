<?php
include_once("product_controller.php");

function readReview(){
    $allData = array();
    $conn = my_connectDB();

    if($conn!=null){
        $sql_query = "SELECT * FROM `review`";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if($result ->num_rows > 0){
            while($row = $result -> fetch_assoc()){
                $data['id'] = $row["review_id"];
                $data['name'] = $row["guest_name"];
                $data['productid'] = $row["product_id"];
                $data['review'] = $row["guest_review"];
                $data['rate'] = $row["product_rating"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}
function deleteReview($name){
    $conn = my_connectDB();

    if($conn!=null){
        $sql_query = "DELETE FROM `review` WHERE guest_name = '$name'";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));     
    }
    
}
function deleteReviewProduct($id){
    $conn = my_connectDB();

    if($conn!=null){
        $sql_query = "DELETE FROM `review` WHERE review_id = '$id'";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));   
      
    }
    
}
   

function deleteReviewAccount($name){
    $conn = my_connectDB();
    $allData = array();
    if($conn!=null){
        $sql_query = "SELECT * FROM `review` WHERE guest_name = '$name'";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));    
        
        if($result ->num_rows > 0){
            while($row = $result -> fetch_assoc()){
                $data['id'] = $row["review_id"];
                $data['name'] = $row["guest_name"];
                $data['productid'] = $row["product_id"];
                $data['review'] = $row["guest_review"];
                $data['rate'] = $row["product_rating"];
                array_push($allData, $data);
                $datarow = getProduct($data['productid']);
                updateReview( $data['productid'], $datarow['review']-1);
            }
        }
    }
    
  }




?>