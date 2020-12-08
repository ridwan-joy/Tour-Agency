<?php
  session_start();
  require "includes/db_connect.inc.php";
  $i = 0;
  $j = 10;
  $count = 0;
  $userID = $_SESSION["userID"];
  $userType = $_SESSION["userType"];
  $headButton = $headButtonLink = "";
  if (empty($_SESSION["userID"])) {
    header("Location: login.php");
  }

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
  $name = $email = $phone = $old_password = $new_password = "";
  $regSuccess = $userNameErr = $packageAdded ="";

  if($_SERVER["REQUEST_METHOD"]=="POST"){

    if(isset($_POST['profile'])){
      header("Location: profile.php");
    }

    if(isset($_POST['packages'])){
      header("Location: agent_packages.php");
    }

    if(isset($_POST['update_package'])){
      header("Location: agent_update_package.php");
    }
    if(isset($_POST['logout'])){
      session_unset();
      session_destroy();
      header("Location: login.php");
    }
//admin
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
//customer
    if(isset($_POST['purchase_history'])){
      header("Location: customer_purchase_history.php");
    }

    if(isset($_POST['profile'])){
      header("Location: profile.php");
    }

    if(isset($_POST['request_package'])){
      header("Location: request_package.php");
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


    if(isset($_POST['update_button'])){
      $sql = "UPDATE user_info SET name='$name', email='$email', phone=$phone WHERE user_id=$userID";
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
      $sql = "UPDATE user_info SET password='$new_passwordH' WHERE user_id=$userID";
      if (mysqli_query($conn, $sql)) {
        $packageAdded = "Profile Updated successfully";
        //header("Location: LOGIN.php");
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
    }
  }

  $sql = "select name, email, phone from user_info where user_id = $userID;";
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
  <body onload="<?php echo $userType ?>()">
    <script type="text/javascript">
    function agent() {
      document.getElementById("admin").remove();
      document.getElementById("customer").remove();
    }
    function admin() {
      document.getElementById("agent").remove();
      document.getElementById("customer").remove();
    }
    function customer() {
      document.getElementById("admin").remove();
      document.getElementById("agent").remove();
    }
    </script>
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
              <td class="td" colspan="4">Profile</td>
            </tr>
          </table>
        </form>
      </div>
    </section>

    <section class="" id="agent">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table class="search_again">
            <tr class="" align="center">
              <td><button class="button" type="submit" name="add_package">Add Package</button></td>
              <td><button class="button" type="submit" name="packages">My package</button></td>
              <td><button class="button" type="submit" name="update_package">Update Package</button></td>
              <td><button class="button" type="submit" name="logout">Log Out</button></td>
            </tr>
          </table>
        </form>
      </div>
    </section>

    <section class="" id="admin">
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

    <section class="" id="customer">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table class="search_again">
            <tr class="" align="center">
              <td><button class="button" type="submit" name="purchase_history">Purchase History</button></td>
              <td><button class="button" type="submit" name="request_package">Request Package</button></td>
              <td><button class="button" type="submit" name="profile">Profile</button></td>
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
                        <h2>Update Profile</h2>
                        <div class="success">
                          <?php echo $packageAdded ?>
                        </div>
                        <?php while($row = $result->fetch_assoc()){ ?>
                        <input type="text" name="name" placeholder="Name" value="<?php echo $row['name']; ?>" class="input_box"> <br>
                        <input type="text" name="email" placeholder="Email" value="<?php echo $row['email']; ?>" class="input_box"> <br>
                        <input type="int" name="phone" placeholder="Phone" value="<?php echo $row['phone']; ?>" class="input_box"> <br>
                        <input type="submit" name="update_button" value="Update" class="button"> <br>
                      <?php } ?>
                        </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <h3> <br> <br> Change Password</h3>
                        <div class="success">
                          <?php echo $packageAdded ?>
                        </div>
                        <input type="text" name="old_pass" placeholder="Old Password" value="" class="input_box"> <br>
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
