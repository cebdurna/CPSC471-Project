<!DOCTYPE html>
<html>

<head>
  <title>Hotel Service</title>
</head>

<body>

<a href="landingPage.php">Return to Landing Page</a>

<hr />

<H2>Customer Login</H2>

<form action="customer-login.php" method="post">
   Username: <input type="text" name="user_name" placeholder = "Username"><br>
   Password: <input type="text" name="password" placeholder="Password"><br>
   <input type="submit" value="Login">
</form>

<a href="customer-register.php">
  <button type="submit">Create an account</button>
</a>
<?php
  if(isset($_POST['user_name']) && isset($_POST['password']))
  {
    $username = $_POST["user_name"];
    $password = $_POST["password"];
    $url = "http://localhost:8000/customer/login?username=" . $username ."&password=" . $password;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if($httpcode == 200){
      $response = json_decode($response);
      $customerID = $response->customerID;
      setcookie("userID", $customerID, time()+3600, "/");
      setcookie("userName", $username, time()+3600, "/");
      header("Location: hotelcustomerp.php");
      exit();
    }
    else{
      echo "<br><font color='red'>Unable to log in, </font>" . 'HTTP code: ' . $httpcode;
    }
    curl_close($curl);
  }
  

?>
</body>

</html>