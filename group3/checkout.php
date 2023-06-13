<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      if($email == ''){
         $message[] = '';
      }else{
         
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name,  email, method, total_products, total_price) VALUES(?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $email, $method, $total_products, $total_price]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         $message[] = 'Your order successfully placed';
      }
      
   }else{
      $message[] = 'Please wait, until your order is arrived';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="checkout">

   <h1 class="title">Order Summary</h1>

<form action="" method="post">

   <div class="cart-items">
      <h3>Dish Orders</h3>
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
      <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">₱<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">Looks like your cart is empty. Please, Order now.</p>';
         }
      ?>
      <p class="grand-total"><span class="name">Total Due:</span><span class="price">₱<?= $grand_total; ?></span></p>
      <a href="cart.php" class="btn">View My Orders</a>
   </div>

   <input type="hidden" name="total_products" value="<?= $total_products; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
   <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
   <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">

   <div class="user-info">
      <h3>My Profile Information</h3>
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
      <select name="method" class="box" required>
         <option value="" disabled selected>--Select Payment Method--</option>
         <option value="Cash on Hand">Cash on Hand</option>
         <option value="Card">Card</option>
         <option value="Gcash">Gcash</option>
         <option value="Paypal">Paypal</option>
      </select>
      <input type="submit" value="place order" class="btn" name="submit">
   </div>

</form>
   
</section>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
         <script>
            swal({
               title: "Thank you",
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