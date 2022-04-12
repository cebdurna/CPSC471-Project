<html>

<head>
  <title>Hotel Service</title>
</head>

<body>

<a href="landingPage.php">Return to Landing Page</a>

<hr />

<H2>Customer Registration</H2>

<form action="customer-register.php" method="post">
   Full Name: <input type="text" name="full_name" placeholder = "Full Name"><br>
   Birthday: <input type="date" name="birthday" placeholder = "yyyy-mm-dd"><br>
   Phone Number: <input type="text" name="phonenumber" placeholder = "XXX-XXX-XXXX"><br>
   Email: <input type="text" name="email" placeholder = "example@test.com"><br>
   Username: <input type="text" name="username" placeholder = "Username"><br>
   Password: <input type="password" name="password" placeholder="Password"><br>
   Confirm Password: <input type="password" name="confirm_password" placeholder="Password"><br>
   <input type="submit" value="Create account">
</form>

<?php
  if(isset($_POST['full_name']) && isset($_POST['birthday']))
  {
    $full_name = $_POST['full_name'];
    $birthday = $_POST['birthday'];
    $phone_number = $_POST['phonenumber'];
    $email = $_POST['email'];
    $user_name = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if($password != $confirm_password)
    {
      echo "<br><font color='red'>Paswords do not match</font>";
    }
    else
    {
      $post = ["username"=>$user_name,
      "password"=> $password,
      "name"=>$full_name,
      "birthdate"=>$birthday,
      "phone_no"=>$phone_number,
      "email"=>$email,
      ];

      $url = "http://localhost:8000/customer/registration";
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
      $response = curl_exec($curl);
      $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      
      
      if($httpcode == 200){
        $response = json_decode($response);
        $customerID = $response->customerID;
        setcookie("userID", $customerID, time()+3600, "/");
        header("Location: hotelcustomerp.php");
        exit();
      }
      else{
        echo "<br><font color='red'>Unable to register, </font>" . 'HTTP code: ' . $httpcode;
      }
      curl_close($curl);
    }
  }
  

?>

</body>

</html>