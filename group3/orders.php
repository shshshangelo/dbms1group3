<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="orders">

   <h1 class="title">Your Total Orders</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">Pleasem login to see your total orders.</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Customer ID: <span><?= $fetch_orders['user_id']; ?></span> </p>
      <p>Date/Time Placed On: <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Full Name: <span><?= $fetch_orders['name']; ?></span></p>
	  <p>Your Orders: <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Total Due: <span>₱<?= $fetch_orders['total_price']; ?></span></p>
      <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
      <p>Order Status: <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">No orders placed yet.</p>';
      }
      }
   ?>

   </div>

</section>



<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>