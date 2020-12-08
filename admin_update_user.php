<?php
  session_start();
  require "includes/db_connect.inc.php";
  $i = 0;
  $j = 10;
  $count = 0;
  $userID = $_SESSION["userID"];
  $userType = $_SESSION["userType"];
  $editUserID = $_SESSION["edit_user_id"];
  if (empty($_SESSION["userID"])) {
    header("Location: login.php");
  }
  if ($userType!="admin") {
    header("Location: error-access.php");
  }

  $headButton = $headButtonLink = "";
  if(empty($userID)){
    $headButton = "Login / Registration";
  }else{
    $headButton = "My Account";
  }

  if(empty($userType)){
    $headButtonLink = "login.php";
  }
  elseif ($userType=="admin") {
    $headButtonLink = "admin_home.php";
  }
  elseif ($userType=="agent") {
    $headButtonLink = "agent_home.php";
  }
  else {
    $headButtonLink = "customer_home.php";
  }



  $nameErr = $emailErr = $phoneErr = $old_passwordErr = $new_passwordErr = "";
  $name = $email = $user_type = $phone = $old_password = $new_password = "";
  $regSuccess = $userNameErr = $pass_updated = $packageAdded ="";

  if($_SERVER["REQUEST_METHOD"]=="POST"){

    if(isset($_POST['all_packages'])){
      header("Location: admin_all_packages.php");
    }

    if(isset($_POST['profile'])){
      header("Location: profile.php");
    }

    if(isset($_POST['users'])){
      header("Location: admin_users.php");
    }

    if(isset($_POST['logout'])){
      session_unset();
      session_destroy();
      header("Location: login.php");
    }

    if(empty($_POST['name'])){
      $nameErr = "Name cannot be empty!";
    }else{
      $name = $_POST['name'];
    }

    if(empty($_POST['email'])){
      $emailErr = "Email cannot be empty!";
    }else{
      $email = $_POST['email'];
    }

    if(empty($_POST['phone'])){
      $phoneErr = "Phone cannot be empty!";
    }else{
      $phone = $_POST['phone'];
    }

    if(empty($_POST['userType'])){
      $user_type = "User type cannot be empty!";
    }else{
      $user_type = $_POST['userType'];
    }


    if(isset($_POST['update_button'])){
      $sql = "UPDATE user_info SET name='$name', email='$email', phone=$phone, user_type='$user_type' WHERE user_id=$editUserID";
      if (mysqli_query($conn, $sql)) {
        $packageAdded = "Profile Updated successfully";
        //header("Location: LOGIN.php");
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
    }

    if(isset($_POST['update_pass_button'])){
      if(empty($_POST['old_pass'])){
        $old_passwordErr = "Enter Old Password";
      }else{
        $old_password = $_POST['old_pass'];
      }
      if(empty($_POST['new_pass'])){
        $new_passwordErr = "Enter New Password";
      }else{
        $new_password = $_POST['new_pass'];
      }

      $new_passwordH = hash('sha512', $new_password);
      $sql = "UPDATE user_info SET password='$new_passwordH' WHERE user_id=$editUserID";
      if (mysqli_query($conn, $sql)) {
        $pass_updated = "Password Updated successfully";
        //header("Location: LOGIN.php");
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
    }
  }

  $sql = "select name, email, phone, user_type from user_info where user_id = $editUserID;";
  $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style/search.css">
    <title></title>
  </head>
  <body>
    <header>
      <a class="logo" href="/projects/web_tech_final_project/"><img src="media/logo.png" alt="logo" width="120px"></a>
      <nav>
          <ul class="nav__links">
              <li><a href="/projects/web_tech_final_project/">Home</a></li>
              <li><a href="/projects/web_tech_final_project/">Find Package</a></li>
              <li><a href="#">About</a></li>
          </ul>
      </nav>
      <a class="cta" href="/projects/web_tech_final_project/<?php echo $headButtonLink; ?>"><?php echo $headButton; ?></a>
    </header>


    <section class="search_top">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
          <table>
            <tr class="top_section_head" align="center">
              <td class="td" colspan="4">Update User</td>
            </tr>
          </table>
        </form>
      </div>
    </section>

    <section class="">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table class="search_again">
            <tr class="" align="center">
              <td><button class="button" type="submit" name="users">Users</button></td>
              <td><button class="button" type="submit" name="profile">Profile</button></td>
              <td><button class="button" type="submit" name="all_packages">Packages</button></td>
              <td><button class="button" type="submit" name="logout">Log Out</button></td>
            </tr>
          </table>
        </form>
      </div>
    </section>

    <section class="white_section">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
          <table align="center" class="update_package" cellspacing="0" cellpadding="0">
            <tr cellspacing="0" cellpadding="0">

              <td class="package_form" align="center" cellspacing="0" cellpadding="0">
                  <table>
                    <tr>
                      <td align="center">
                        <h2>Update User</h2>
                        <div class="success">
                          <?php echo $packageAdded ?>
                        </div>
                        <?php while($row = $result->fetch_assoc()){ ?>
                        <input type="text" name="name" placeholder="Name" value="<?php echo $row['name']; ?>" class="input_box"> <br>
                        <input type="text" name="email" placeholder="Email" value="<?php echo $row['email']; ?>" class="input_box"> <br>
                        <input type="int" name="phone" placeholder="Phone" value="<?php echo $row['phone']; ?>" class="input_box"> <br>
                        <select class="select_box" id="userType" name="userType">
                          <option value="<?php echo $row['user_type']; ?>" disabled selected><?php echo $row['user_type']; ?></option>
                          <option value="admin">admin</option>
                          <option value="agent">agent</option>
                          <option value="customer">customer</option>
                        </select> <br>
                        <input type="submit" name="update_button" value="Update" class="button"> <br>
                      <?php } ?>
                        </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <h3> <br> <br> Change Password</h3>
                        <div class="success">
                          <?php echo $pass_updated ?>
                        </div>
                        <input type="text" name="new_pass" placeholder="New Password" value="" class="input_box"> <br>
                        <input type="submit" name="update_pass_button" value="Change Password" class="button"> <br>
                        </td>
                    </tr>
                  </table>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </section>

  </body>
</html>
