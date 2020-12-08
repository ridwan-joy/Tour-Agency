<?php
  session_start();
  require "includes/db_connect.inc.php";
  $i = 0;
  $j = 10;
  $count = 0;
  if (empty($_SESSION["userID"])) {
    header("Location: login.php");
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


if($_SERVER["REQUEST_METHOD"] == "POST"){

  if(isset($_POST['prev_btn'])){
    $i = $_POST['prev_btn'];
  }elseif(isset($_POST['next_btn'])){
    $i = $_POST['next_btn'];
  }elseif(isset($_POST['pg_btn'])){
    $i = $_POST['pg_btn'];
  }elseif (isset($_POST['perpg_btn'])) {
    $_SESSION['per_page'] = $_POST['records_per_page'];
  }

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
}

  $sqlPrs = "select * from pack_request where customer_id = $userID LIMIT $i, $j;";
  $resultPurchase = mysqli_query($conn, $sqlPrs);

  /*
  $sqlPkg = "select * from purchase where package_id = $packageID;";
  $resultPackage = mysqli_query($conn, $sqlPkg);
  */

  $sqlTotalRows = "select count(*) as total_rows from pack_request where customer_id = $userID;";
  $rowCount = mysqli_fetch_assoc(mysqli_query($conn, $sqlTotalRows));
  $pgNmbr = ceil($rowCount['total_rows'] / $j);
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
              <td class="td" colspan="4">Request History</td>
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
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table class="packages" align="center" cellspacing="30">
          <tr>
            <td class="package_info" > Destination </td>
            <td class="package_info" > Person </td>
            <td class="package_info" > Total Price </td>
            <td class="package_info" > Date </td>
            <td class="package_info" > Vehicle </td>
            <td class="package_info" > Hotel </td>
            <td class="package_info" > Status </td>
          </tr>
            <?php while($row = mysqli_fetch_assoc($resultPurchase)){ ?>
              <tr>
                <td class="package_info" > <?php echo $row['destination']; ?> </td>
                <td class="package_info" > <?php echo $row['person']; ?> </td>
                <td class="package_info" >BDT <?php echo $row['price']; ?> </td>
                <td class="package_info" > <?php echo $row['date']; ?></td>
                <td class="package_info" > <?php echo $row['vehicle']; ?></td>
                <td class="package_info" > <?php echo $row['hotel']; ?></td>
                <td class="package_info" > <?php if($row['agent_id']==0){echo "Pending";} else{echo "Accepted";}; ?></td>
              </tr>
            <?php } ?>
        </table>
        <div class="pagination_btns" align="center">

        </div>
      </form>

      </div>
    </section>

    <section class="">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <table class="pagination_section">
            <tr class="" align="center">
              <td colspan="3">
                <?php if($i > 0){ ?>
                  <button type="submit" class="pagination_button" name="prev_btn" value="<?php echo ($i-$j); ?>" >Previous</button>
                <?php } ?>

                <?php if($pgNmbr > 1) { for ($p=0; $p<$pgNmbr; $p++) { ?>
                  <button type="submit" class="pagination_button" name="pg_btn" value="<?php echo ($p*$j); ?>" ><?php echo ($p+1); ?></button>
                <?php } } ?>

                <?php if($i < ($rowCount['total_rows']-$j)){ ?>
                  <button type="submit" class="pagination_button" name="next_btn" value="<?php echo ($i+$j); ?>" >Next</button>
                <?php } ?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </section>

  </body>
</html>
