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

  $destinationErr = $apertureErr = $dateErr = $seatErr = $priceErr = $detailsERR = "";
  $destination = $aperture = $date = $seat = $price = $details = "";
  $regSuccess = $userNameErr = "";

  if($_SERVER["REQUEST_METHOD"]=="POST"){

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

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style/style.css">
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


    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
      <table align="center" class="section" cellspacing="0" cellpadding="0">
        <tr cellspacing="0" cellpadding="0">

          <td class="package_form" align="center" cellspacing="0" cellpadding="0">
              <table>
                <tr>
                  <td align="center">
                    <h2>Add Package</h2>
                    <div class="success">
                      <?php echo $packageAdded ?>
                    </div>
                    <input type="text" name="destination" placeholder="Destination" value="" class="pack_text_input"> <br>
                    <input type="text" name="aperture" placeholder="Aperture From" value="" class="pack_text_input"> <br>
                    <input type="text" onfocus="(this.type='date')" name="date" placeholder="Date" value="" class="pack_text_input"> <br>
                    <input type="int" name="price" placeholder="Price" value="" class="pack_text_input"> <br>
                    <input type="int" name="package_limit" placeholder="Total Seat" value="" class="pack_text_input"> <br>
                    <textarea name="details" rows="8" cols="80" placeholder="Package details..." class="pack_textarea_input" value=""></textarea> <br>
                    <input type="submit" name="add_button" value="Add Package" class="login_button"> <br>
                    </td>
                </tr>
              </table>
          </td>
        </tr>
      </table>
    </form>
  </body>
</html>
