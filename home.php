<?php
session_start();

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

  if($_SERVER["REQUEST_METHOD"]=="POST")
  {
    session_start();
    $_SESSION['destination'] = $_POST['destination'];
    $_SESSION['date'] = $_POST['date'];
    $_SESSION['budget'] = $_POST['budget'];
    header("Location: search_result.php");
  }
 ?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style/home.css">
    <title>Tour Agency</title>
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

    <section class="top_section">
      <div align="center">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
          <table>
            <tr class="top_section_head" align="center">
              <td class="td" colspan="4">Where do you want to go? </td>
            </tr>
            <tr class="top_section_input" align="center">
              <td>
                <input type="text" class="input_box" name="destination" placeholder="Destination, city" value="">
              </td>
              <td>
                <input type="text" onfocus="(this.type='date')" class="input_box" name="date" placeholder="Tour date" value="">
              </td>
              <td>
                <input type="number" class="input_box" name="budget" placeholder="Maximum Budget" value="">
              </td>
              <td>
                <input type="submit" class="button" name="search" value="Search">
              </td>
            </tr>
          </table>
        </form>
      </div>
    </section>

    <section class="white_section">
      <div align="center">
        <table  cellspacing="15">
          <tr class="section_head" align="center">
            <td class="td" colspan="4">Popular destinations</td>
          </tr>
          <tr class="" align="left">
            <td style="background-image: url(media/tokyo.jpg); background-size: cover; background-position: right;" class="position_bottom">
              <div class="image_box">
                Tokyo
              </div>
            </td>
            <td class="position_bottom" style="background-image: url(media/seoul.jpg); background-size: cover; background-position: center;">
              <div class="image_box">
                Seoul
              </div>
            </td>
            <td class="position_bottom" style="background-image: url(media/paris.jpg); background-size: cover; background-position: right;">
              <div class="image_box">
                Paris
              </div>
            </td>
            <td  class="position_bottom" style="background-image: url(media/london.jpg); background-size: cover; background-position: right;">
              <div class="image_box">
                London
              </div>
            </td>
          </tr>
        </table>
      </div>
    </section>

  </body>
</html>
