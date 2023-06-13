<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $price, $pid]);

   $message[] = '';

   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'images size is too large!';
      }else{
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('../uploaded_img/'.$old_image);
         $message[] = 'New menu product updated successfully';
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
   <title>Update Menu</title>

   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- update product section starts  -->

<section class="update-product">

   <h1 class="heading">Update Menu</h1>

   <?php
      $update_id = $_GET['update'];
      $show_products = $conn->prepare("SELECT * FROM products WHERE id = ?");
      $show_products->execute([$update_id]);
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <span>Update Menu Name</span>
      <input type="text" required placeholder="Enter New Menu Name" name="name" maxlength="100" class="box" value="<?= $fetch_products['name']; ?>">
      <span>Update Menu Price</span>
      <input type="number" min="0" max="9999999999" required placeholder="Enter New Menu Price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box" value="<?= $fetch_products['price']; ?>">
      <span>Update Menu Category</span>
      <select name="category" class="box" required>
         <option value="" disabled selected>--Select Category--</option>
         <option value="Starter Packs">Starter Packs</option>
		 <option value="Main Dish">Main Dishes</option>
		 <option value="Desserts">Desserts</option>
         <option value="Drinks">Drinks</option>
      </select>
      <span>Update Menu Image</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <div class="flex-btn">
         <input type="submit" value="update" class="btn" name="update" required>
         <a href="products.php" class="option-btn">Back To Menu</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">No new menu product, was added.</p>';
      }
   ?>
</section>


<?php
if(isset($message)){
   foreach($message as $message){
      echo '
         <script>
            swal({
               title: "Update Menu Product",
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