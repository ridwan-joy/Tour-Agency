<?php
  session_start();
  require "includes/db_connect.inc.php";
  $i = 0;
  $j = 10;
  $count = 0;
  $userID = $_SESSION["userID"];
  $userType = $_SESSION["userType"];

  $headButton = $headButtonLink = $destinationErr = $apertureErr = $dateErr = $seatErr = $priceErr = $detailsERR = "";
  $destination = $aperture = $date = $seat = $price = $details = "";
  $regSuccess = $userNameErr = $packageAdded ="";

  if(empty($userID)){
    $headButton = "Login / Registration";
  }else{
    $headButton = "Dashboard";
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

    if(empty($_POST['destination'])){
      $destinationErr = "Destination cannot be empty!";
    }else{
      $destination = $_POST['destination'];
    }

    if(empty($_POST['price'])){
      $priceErr = "Price cannot be empty!";
    }else{
      $price = $_POST['price'];
    }

    if(empty($_POST['details'])){
      $detailsERR = "Details cannot be empty!";
    }else{
      $details = $_POST['details'];
    }

    if(empty($_POST['aperture'])){
      $apertureErr = "Aperture cannot be empty!";
    }else{
      $aperture = $_POST['aperture'];
    }

    if(empty($_POST['date'])){
      $dateErr = "Date cannot be empty!";
    }else{
      $date = $_POST['date'];
    }

    if(empty($_POST['package_limit'])){
      $seatErr = "Package limit cannot be empty!";
    }else{
      $seat = $_POST['package_limit'];
    }

    $sql = "INSERT INTO package (package_id, destination, aperture_from, date, price, package_limit, package_details, agent_id)
    VALUES ( NULL, '$destination', '$aperture', '$date', $price, $seat, '$details', $userID)";
    if (mysqli_query($conn, $sql)) {
      $packageAdded = "New package created successfully";
      //header("Location: LOGIN.php");
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
  }

  $sql = "select name, email, phone, user_type from user_info where user_id = $userID;";
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
              <td class="td" colspan="4">Profile</td>
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
              <td><button class="button" type="submit" name="add_package">Add Package</button></td>
              <td><button class="button" type="submit" name="packages">My package</button></td>
              <td><button class="button" type="submit" name="update_package">Update Package</button></td>
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
                        <select class="select_box">
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
                          <?php echo $packageAdded ?>
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
