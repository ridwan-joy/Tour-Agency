<?php
  $packageAdded = "";
  session_start();
  $userID = $_SESSION["userID"];
  $userType = $_SESSION["userType"];
  $headButton = $headButtonLink = "";
  if(empty($userID)){
    $headButton = "Login / Registration";
  }else{
    $headButton = "My Account";
  }

  if (empty($_SESSION["userID"])) {
    header("Location: login.php");
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

  require "includes/db_connect.inc.php";

  $destinationErr = $apertureErr = $dateErr = $seatErr = $priceErr = $detailsERR = $vehicleERR = $hotelERR = "";
  $destination = $aperture = $date = $seat = $price = $details = $vehicle = $hotel = "";
  $regSuccess = $userNameErr = "";

  if($_SERVER["REQUEST_METHOD"]=="POST"){

    if(isset($_POST['purchase_history'])){
      header("Location: customer_purchase_history.php");
    }

    if(isset($_POST['profile'])){
      header("Location: profile.php");
    }

    if(isset($_POST['req_history'])){
      header("Location: customer_request_history.php");
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

    if(empty($_POST['vehicle'])){
      $vehicle = "Agent choice";
    }else{
      $vehicle = $_POST['vehicle'];
    }

    if(empty($_POST['hotel'])){
      $hotel = "Agent choice";
    }else{
      $hotel = $_POST['hotel'];
    }

    if(isset($_POST['add_button'])){
      $sql = "INSERT INTO pack_request (req_id, customer_id, destination, aperture_from, date, price, person, vehicle, hotel, details)
      VALUES ( NULL, $userID, '$destination', '$aperture', '$date', $price, $seat, '$vehicle', '$hotel', '$details')";
      if (mysqli_query($conn, $sql)) {
        $packageAdded = "Package requested successfully";
        //header("Location: LOGIN.php");
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
      header("Location: customer_request_history.php");
    }

  }

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
              <td class="td" colspan="4">Request A Package</td>
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
              <td><button class="button" type="submit" name="purchase_history">Purchase History</button></td>
              <td><button class="button" type="submit" name="req_history">Request History</button></td>
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
                        <h2>Request Package</h2>
                        <div class="success">
                          <?php echo $packageAdded ?>
                        </div>
                        <input type="text" name="destination" placeholder="Destination" value="" class="input_box"> <br>
                        <input type="text" name="aperture" placeholder="Aperture From" value="" class="input_box"> <br>
                        <input type="text" onfocus="(this.type='date')" name="date" placeholder="Date" value="" class="input_box"> <br>
                        <input type="int" name="price" placeholder="Price" value="" class="input_box"> <br>
                        <input type="int" name="package_limit" placeholder="Total Seat" value="" class="input_box"> <br>
                        <input type="text" name="vehicle" placeholder="Vehicle type" value="" class="input_box"> <br>
                        <input type="text" name="hotel" placeholder="Hotel" value="" class="input_box"> <br>
                        <textarea name="details" rows="8" cols="80" placeholder="Package details..." class="input_text_box" value=""></textarea> <br>
                        <input type="submit" name="add_button" value="Add Package" class="button"> <br>
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
