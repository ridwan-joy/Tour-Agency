<?php
  session_start();
  require "includes/db_connect.inc.php";
  $i = 0;
  $j = 10;
  $count = 0;
  $packID = $_SESSION["purspackID"];
  if (empty($_SESSION["userID"])) {
    header("Location: login.php");
  }
  if (empty($_SESSION["purspackID"])) {
    header("Location: home.php");
  }

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


  $destinationErr = $apertureErr = $dateErr = $seatErr = $priceErr = $detailsERR = "";
  $destination = $aperture = $date = $seat = $price = $details = "";
  $regSuccess = $userNameErr = $packageAdded ="";

  if($_SERVER["REQUEST_METHOD"]=="POST"){

    if(isset($_POST['update_user'])){
      header("Location: admin_users.php");
    }

    if(isset($_POST['profile'])){
      header("Location: profile.php");
    }

    if(isset($_POST['packages'])){
      header("Location: agent_packages.php");
    }

    if(isset($_POST['logout'])){
      session_unset();
      session_destroy();
      header("Location: login.php");
    }

    if(empty($_POST['seat'])){
      $_SESSION["packSeat"] = 1;
    }else{
      $_SESSION["packSeat"] = $_POST['seat'];
    }

    if(isset($_POST['update_button'])){
      $_SESSION["packSeat"];
      header("Location: checkout.php");
    }

  }

  $sql = "select destination, aperture_from, date, price, package_limit, package_details from package where package_id = $packID;";
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
              <td class="td" colspan="4">Checkout</td>
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
                        <h2></h2>
                        <div class="success">
                          <?php echo $packageAdded ?>
                        </div> <br> <br>
                        <table >
                          <?php while($row = $result->fetch_assoc()){ $_SESSION["packDestination"] = $row['destination']; $_SESSION["packPrice"] = $row['price'];?>
                            <tr>
                              <td class="checkout"><b> Destination: </b> <?php echo $row['destination']; ?></td>
                              <td class="checkout"><b> Aperture From: </b> <?php echo $row['aperture_from']; ?></td>
                            </tr>
                            <tr>
                              <td class="checkout"><b> Aperture Date: </b> <?php echo $row['date']; ?></td>
                              <td> </td>
                            </tr>
                            <tr>
                              <td class="checkout"><b> Price Per Seat: </b><?php $_SESSION["price"] = $row['price']; ?> <?php echo $row['price']; ?></td>
                              <td class="checkout"><b> Available Seat: </b> <?php echo $row['package_limit']; ?></td>
                            </tr>
                            <tr>
                              <td class="tourdetails" colspan="2"><b> Tour Details: </b> <?php echo $row['package_details']; ?> </td>
                            </tr>
                            <tr>
                              <td></td>
                              <td class="checkout"><input type="number" class="input_box_small" name="seat" value="" placeholder="No of Seat (1)"></td>

                            </tr>
                            <tr>
                              <td></td>
                              <td class="checkout"><input type="submit" name="update_button" value="Checkout" class="button"></td>
                            </tr>
                        <?php } ?>
                        </table>

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
