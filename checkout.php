
<?php
  session_start();
  require "includes/db_connect.inc.php";
  $i = 0;
  $j = 10;
  $count = 0;
  $cond = "a";
  $totseat = 0;
  $packID = $_SESSION["purspackID"];
  if (empty($_SESSION["userID"])) {
    header("Location: login.php");
  }
  if (empty($_SESSION["purspackID"])) {
    header("Location: home.php");
  }
  $userID = $_SESSION["userID"];
  $userType = $_SESSION["userType"];
  $seat = $_SESSION["packSeat"];
  $price = $_SESSION["price"];
  $total_price = $seat * $price;

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
  $destination = $aperture = $date = $price = $details = "";
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

    if(isset($_POST['update_button'])){
      $cond = "";
    }

    if(isset($_POST['pay_confirm_button'])){
      $date = date('Y-m-d H:i:s');
      $sqlin = "INSERT INTO purchase (purchase_id, user_id, package_id, seat, total_price, purchase_date) VALUES (NULL, '$userID', '$packID', '$seat', '$total_price', '$date')";

      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);

    }

  }

  }

  $sql = "select * from package where package_id = $packID;";
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
  <body onload="toggle<?php echo $cond ?>Popup()">

    <script>
    function togglePopup(){
      document.getElementById("popup-warning").classList.toggle("active");
    }
    function toggledonePopup(){
      document.getElementById("popup-done").classList.toggle("active");
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
                              <td class="package_info" > Destination </td>
                              <td class="package_info" > Price </td>
                              <td class="package_info" > Available Seat </td>
                              <td class="package_info" > Total Price </td>
                              <td class="package_info" > Vehicle Type </td>
                              <td class="package_info" > Hotel </td>
                            </tr>
                            <tr>
                              <td class="package_info" > <?php echo $row['destination']; ?> </td>
                              <td class="package_info" >BDT <?php echo $row['price']; ?> </td>
                              <td class="package_info" > <?php echo $_SESSION["packSeat"]; ?> </td> <?php $totseat = $row['package_limit']; ?>
                              <td class="package_info" >BDT <?php echo $total_price ?></td>
                              <td class="package_info" >BDT <?php echo $row['vehicle']; ?> </td>
                              <td class="package_info" >BDT <?php echo $row['hotel']; ?> </td>
                            </tr>

                            <tr>
                              <td colspan="6" align="right" class="checkout"> <br><br> <input type="submit" name="update_button" value="Pay Now" class="button"></td>
                            </tr>
                        <?php } ?>
                        </table>

                        </td>
                    </tr>
                  </table>
              </td>
            </tr>
          </table>
          <div class="popup" id="popup-warning">
            <div class="overlay"></div>
            <div class="content">
              <div class="close-btn" onclick="togglePopup()">&times;</div>
              <h1>Payment</h1> <br>
              <img src="media/pay.png" width="80px" alt=""> <br> <br>
              <p> Choose Payment Method </p>
              <button class="button" type="submit" name="pay_confirm_button">bKash</button>
              <button class="button" type="submit" name="pay_confirm_button">Card</button>
            </div>
          </div>

          <div class="popup" id="popup-done">
            <div class="overlay"></div>
            <div class="content">
              <div class="close-btn" onclick="togglePopup()">&times;</div>
              <h1>Done!</h1> <br>
              <img src="media/check.png" width="80px" alt=""> <br> <br>
              <p> Payment Received </p>
              <button class="button" type="submit" name="ok">OK</button>
            </div>
          </div>
        </form>
      </div>

    </section>

  </body>
</html>
