<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $error_message = 'Menu product already exists.';
    } else {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $error_message = 'Invalid file format. Only JPG, JPEG, PNG, and WebP images are allowed.';
        } elseif ($image_size > 2000000) {
            $error_message = 'Image size is too large. Please upload an image below 2MB.';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);

            $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image) VALUES(?,?,?,?)");
            $insert_product->execute([$name, $category, $price, $image]);

            $message = 'New menu product was added successfully.';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $delete_product_image->execute([$delete_id]);
    $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
    unlink('../uploaded_img/' . $fetch_delete_image['image']);
    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
    $delete_cart->execute([$delete_id]);
    header('location:products.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add A New Menu</title>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- bootstrap cdn link  -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6J
        xm"
        crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KyZXEAg3QhqLMpG8r+UyPvD5IDM6p7dL3z3weD6i2p2cS+3gK2j2gUv2Q4yD7k4v"
        crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

  <?php
    if (isset($error_message)) {
        echo '<script>
                swal("Error", "' . $error_message . '", "error");
              </script>';
    }

    if (isset($message)) {
        echo '<script>
                swal("Success", "' . $message . '", "success");
              </script>';
    }
    ?>

    <?php include '../components/admin_header.php' ?>

    <!-- add products section starts  -->

    <section class="add-products">

        <form action="" method="POST" enctype="multipart/form-data">
            <h3>Add a New Menu</h3>
            <input type="text" required placeholder="Menu Name" name="name" maxlength="100" class="box">
            <input type="number" step="0.01" min="0" max="9999999999" required placeholder="Menu Price" name="price"
                class="box">
            <select name="category" class="box" required>
                <option value="" disabled selected>--Select Category--</option>
                <option value="Starter Packs">Starter Packs</option>
                <option value="Main Dishes">Main Dishes</option>
                <option value="Desserts">Desserts</option>
                <option value="Drinks">Drinks</option>
            </select>
            <input type="file" name="image" class="box"
                accept="image/jpg, image/jpeg, image/png, image/webp" required>
            <input type="submit" value="Add The Menu" name="add_product" class="btn">
        </form>

    </section>

    <!-- add products section ends -->

    <!-- show products section starts  -->

    <section class="show-products" style="padding-top: 0;">

        <div class="box-container">

            <?php
            $show_products = $conn->prepare("SELECT * FROM `products`");
            $show_products->execute();
            if ($show_products->rowCount() > 0) {
                while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="box">
                <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                <div class="flex">
                    <div class="price"><span>₱</span><?= $fetch_products['price']; ?><span></span></div>
                    <div class="category"><?= $fetch_products['category']; ?></div>
                </div>
                <div class="name"><?= $fetch_products['name']; ?></div>
                <div class="overlay">
                    <a href="?delete=<?= $fetch_products['id']; ?>" class="btn">Remove</a>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<h2>No menu products available.</h2>';
            }
            ?>

        </div>

    </section>

    <!-- show products section ends -->

      <!-- Display success or error message -->
    <?php if (isset($error_message) || isset($message)) : ?>
        <div class="container mt-4">
            <div class="alert <?php echo isset($error_message) ? 'alert-danger' : 'alert-success'; ?>">
                <?php echo isset($error_message) ? $error_message : $message; ?>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>
