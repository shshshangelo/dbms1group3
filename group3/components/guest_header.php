<?php
if(isset($message)){
   foreach($message as $message){
      echo '
         <script>
            swal({
               title: "Message",
               text: "'.$message.'",
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

<header class="header">

   <section class="flex">

      <a href="menuGuest.php" class="logo">Pinoy Foodie - Guest Mode</a>

         <div class="icons">
            <?php
               $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $count_cart_items->execute([$user_id]);
               $total_cart_items = $count_cart_items->rowCount();
            ?>
            
         </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p class="name"><?= $fetch_profile['name']; ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">My Profile</a>
            <a href="components/user_logout.php" class="delete-btn">Sign out</a>
         </div>
         <?php
            }else{
         ?>
            <p class="name">No, account yet?</p>
            <a href="register.php" class="btn">Sign up.</a>
         <?php
          }
         ?>
      </div>

   </section>

</header>

