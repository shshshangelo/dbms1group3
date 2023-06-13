<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'This email is already existed';
   }else{
      if($pass != $cpass){
         $message[] = 'Confirm password is not matched';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
         $select_user->execute([$email, $pass]);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:login.php');
         }
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
   <title>Sign up</title>

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

<br><br><br><br><br><br><br><br><br><br><br><br><br>

<section class="form-container">

   <form action="" method="post">
      <h3>Sign up for discounts, rewards and vouchers.</h3>
      <input type="text" name="name" required placeholder="Full Name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="Email Address" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your Password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" name="submit" class="btn">
      <p>Loyal customer? <a href="login.php">Login Now</a></p>
   </form>

</section>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
         <script>
            swal({
               title: "Please try again",
               text: "'.$message.'",
			   icon: "warning",
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