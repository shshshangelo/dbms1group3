<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update_payment'])){

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);
   $message[] = 'Your order is on its way';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Customer Order Lists</title>

   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   <!-- bootstrap cdn link  -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- placed orders section starts  -->

<section class="placed-orders">
<br><br><br>
   <h1 class="heading">Customer Orders</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>

   <div class="box">
      <p> Customer ID: <span><?= $fetch_orders['user_id']; ?></span> </p>
      <p> Date/Time Placed On: <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Customer Name: <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Total Dish: <span><?= $fetch_orders['total_products']; ?></span>	   
	   <input type="checkbox" id="order_id" name="total_products" value="" <?php echo $fetch_orders['payment_status'] === 'Completed' ? 'checked disabled' : '';  ?> ></p>
      <p> Total Due: <span>₱<?= $fetch_orders['total_price']; ?></span> </p>
      <p> Payment Method: <span><?= $fetch_orders['method']; ?></span> </p>
	  
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="drop-down">
            <option value=" " selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="update" class="btn" name="update_payment">
            <!-- <a href="placed_orders.php?delete=<?//= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a> -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
               Delete
            </button>
         </div>
      </form>
   </div>

   <!-- Modal -->
   <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Warning!</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <p style="font-size: 20px;">Are you sure you want to remove this receipt?</p>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
               <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" type="button" class="btn btn-primary">Confirm</a>
            </div>
         </div>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty"></p>';
   }
   ?>
   </div>
   
</div>
</section>


<?php
if(isset($message)){
   foreach($message as $message){
      echo '
         <script>
            swal({
               title: "Thank you for your order",
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
<script src="../js/admin_script.js"></script>

</body>
</html>