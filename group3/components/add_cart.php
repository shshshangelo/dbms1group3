<?php

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message1[] = '';
      }else{
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[] = 'Your order is successfully placed to cart';
         
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

	<!-- prompt message link -->
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
         <script>
            swal({
               text: "'.$message.'",
			   icon: "success",
               button: "Close",
            });
         </script>
      ';
      // echo '
      // <div class="message">
      //    <span>'.$message.'</span>
      //    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      // </div>
      // ';
   }
}
?>


<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>