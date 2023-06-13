<header class="header">

   <section class="flex">

      <a href="#" class="logo">Pinoy Foodie</a>

      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="menu.php">Our Menu</a>
         <a href="orders.php">My Orders</a>
      </nav>

      <div class="icons">
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
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
            <p class="name">No account, yet?</p>
            <a href="register.php" class="btn">Sign up</a>
            <a href="menuGuest.php" class="btn">Guest</a>
            
         <?php
          }
         ?>
      </div>

   </section>

</header>

