<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HeadChef Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">
 <br><br><br><br><br>

   <div class="box-container">

  <div class="box">
     <br><br><br><br><br><br><br><br><br><br><br><br><br><br>

      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $numbers_of_products = $select_products->rowCount();
      ?>

      <h3><?= $numbers_of_products; ?></h3>
      <p>Total Menu Products</p>
      <a href="products.php" class="btn">See Menu Lists</a>
   </div>
   
   <div class="box">
     <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_pendings->execute(['Pending']);
         while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
            $checkrows = $select_pendings->rowCount();
            if($checkrows === 0 || $checkrows === null){
               $total_pendings = 0;
            } else {
               $total_pendings = $select_pendings->rowCount();
            }
            // $total_pendings += $fetch_pendings['total_price'];
         }
      ?>

      <h3><span></span><?= $total_pendings; ?><span></span></h3>
      <p>Total Pending Orders of Customer</p>
	  <a href="placed_orders.php" class="btn">See Total Pending Orders</a>

   </div>

   <div class="box">
     <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      <?php
         $total_completes = 0;
         $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completes->execute(['Completed']);
         while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
            $total_completes = $select_completes->rowCount();
            // $total_completes += $fetch_completes['total_price'];
         }
      ?>

      <h3><span></span><?= $total_completes; ?><span></span></h3>
      <p>Total Completed Orders of Customer</p>
	  <a href="placed_orders.php" class="btn">See Total Completed Orders</a>
   </div>
 </div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>