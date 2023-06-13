
<header class="header">

   <section class="flex">

      <a href="#" class="logo">HeadChef</a>

      <nav class="navbar">
	     <a href="dashboard.php" class="logo">Dashboard</a>
         <a href="products.php">Add a New Menu</a>
         <a href="placed_orders.php">Customer Order Lists</a>
      </nav>
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         <div class="flex-btn">
         </div>
         <a href="../components/admin_logout.php" class="delete-btn">Sign out</a>
      </div>

   </section>

</header>