<?php 


include_once("main_controller.php");



function readProduct(){
    $allData = array();
    $conn = my_connectDB();

    if($conn!=null){
        $sql_query = "SELECT * FROM `product`";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if($result ->num_rows > 0){
            while($row = $result -> fetch_assoc()){
                $data['id'] = $row["product_id"];
                $data['name'] = $row["product_name"];
                $data['picture'] = $row["product_picture"];
                $data['stock'] = $row["product_stock"];
                $data['type'] = $row["product_type"];
                $data['description'] = $row["product_description"];
                $data['price'] = $row["product_price"];
                $data['review'] = $row["product_review"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}



function createProduct($name, $img, $type, $stock,$desc, $price){
    
        $conn = my_connectDB();
        $namafile = $_FILES['imgfile']['name'];
        $ukuranfile = $_FILES['imgfile']['size'];
        $error = $_FILES['imgfile']['error'];
        $tmpName = $_FILES['imgfile']['tmp_name'];

        if($error === 4){
        echo " <script>
        alert('Pilih gambar terlebih dahulu!!');

        </script>";
        return false;
        }

        $validpicupload= ['jpg','jpeg','png'];
        $picextension = explode('.',$namafile);
        $picextension = strtolower(end($picextension));
        if(!in_array( $picextension, $validpicupload)){


    echo " <script>
        alert('Upload gambar yang benar!!');

        </script>";
        return false;
    }
    $newfilename = uniqid();
    $newfilename .= '.';
    $newfilename .= $picextension;
    move_uploaded_file($tmpName, 'images/productimg/' . $newfilename);
    
    $query = "INSERT INTO product(product_id, product_name, product_picture, product_type, product_stock, product_description, product_price,product_review) VALUES(NULL, '$name', '$newfilename', '$type', '$stock','$desc','$price', '0')";
    $result = mysqli_query($conn, $query);
    return $result;


}
function getProduct($ID){
    $allData = array();
    if($ID>0){
        $conn = my_connectDB();
        $sql_query = "SELECT * FROM product WHERE product_id =$ID";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if($result ->num_rows > 0){
            while($row = $result -> fetch_assoc()){
                $data['id'] = $row["product_id"];
                $data['name'] = $row["product_name"];
                $data['picture'] = $row["product_picture"];
                $data['stock'] = $row["product_stock"];
                $data['type'] = $row["product_type"];
                $data['description'] = $row["product_description"];
                $data['price'] = $row["product_price"];
                $data['review'] = $row["product_review"];
                array_push($allData, $data);
            }
        }
        my_closeDB($conn);
        return $data;
    }

}
function updateReview($id, $review){
    $conn = my_connectDB();
    $sql_query = "UPDATE `product` SET `product_review` = '$review' WHERE product_id=$id";



    $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

    my_closeDB($conn);

}

function updateProduct($id, $name, $img, $type, $stock,$desc, $price){
    if($name!=""&& $type!=""&& $stock!="" && $desc!=""){
    
        $conn = my_connectDB();
        if ($_FILES['imgfile']['error'] === 4){
            
            $sql_query = "UPDATE product SET `product_name` = '$name', `product_type` = '$type', `product_stock` = '$stock', `product_description` = '$desc', `product_price` = '$price' WHERE `product_id`='$id'";
     
        
          }
        
        
        
        else {
        $oldimg = mysqli_query($conn, "SELECT product_picture FROM product WHERE product_id=$id");
        while($row = $oldimg -> fetch_assoc()){
        echo $row["product_picture"];
        unlink("images/productimg/".$row["product_picture"]);
        }
        $namafile = $_FILES['imgfile']['name'];
        $ukuranfile = $_FILES['imgfile']['size'];
        $error = $_FILES['imgfile']['error'];
        $tmpName = $_FILES['imgfile']['tmp_name'];

        

        $validpicupload= ['jpg','jpeg','png'];
        $picextension = explode('.',$namafile);
        $picextension = strtolower(end($picextension));
        if(!in_array( $picextension, $validpicupload)){


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
        return $result;
        my_closeDB($conn);
    }

}
function readsearchProduct($searchkey){
    $allData = array();
    $conn = my_connectDB();

    if($conn!=null){
        $sql_query = "SELECT * FROM `product` WHERE product_name LIKE '%".$searchkey."%'";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if($result ->num_rows > 0){
            while($row = $result -> fetch_assoc()){
                $data['id'] = $row["product_id"];
                $data['name'] = $row["product_name"];
                $data['picture'] = $row["product_picture"];
                $data['stock'] = $row["product_stock"];
                $data['type'] = $row["product_type"];
                $data['description'] = $row["product_description"];
                $data['price'] = $row["product_price"];
                $data['review'] = $row["product_review"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}
function readcategoryProduct($categorykey){
    $allData = array();
    $conn = my_connectDB();

    if($conn!=null){
        $sql_query = "SELECT * FROM `product` WHERE product_type LIKE '%".$categorykey."%'";
        $_SESSION["category"]="$categorykey";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));
        
        if($result ->num_rows > 0){
            while($row = $result -> fetch_assoc()){
                $data['id'] = $row["product_id"];
                $data['name'] = $row["product_name"];
                $data['picture'] = $row["product_picture"];
                $data['stock'] = $row["product_stock"];
                $data['type'] = $row["product_type"];
                $data['description'] = $row["product_description"];
                $data['price'] = $row["product_price"];
                $data['review'] = $row["product_review"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}
function readsearchCatProduct($categorykey, $searchkey){
    $allData = array();
    $conn = my_connectDB();

    if($conn!=null){
        $sql_query = "SELECT * FROM `product` WHERE product_name LIKE '%".$searchkey."%' AND  product_type LIKE '%".$categorykey."%' ";
        $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

        if($result ->num_rows > 0){
            while($row = $result -> fetch_assoc()){
                $data['id'] = $row["product_id"];
                $data['name'] = $row["product_name"];
                $data['picture'] = $row["product_picture"];
                $data['stock'] = $row["product_stock"];
                $data['type'] = $row["product_type"];
                $data['description'] = $row["product_description"];
                $data['price'] = $row["product_price"];
                $data['review'] = $row["product_review"];
                array_push($allData, $data);
            }
        }
    }
    return $allData;
}
function updateStock($id,$stock){
    $conn = my_connectDB();
    $sql_query = "UPDATE `product` SET `product_stock` = '$stock' WHERE product_id=$id";



    $result = mysqli_query($conn, $sql_query) or die(mysqli_error($conn));

    my_closeDB($conn);
}

?>