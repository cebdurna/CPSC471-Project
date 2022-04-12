<!DOCTYPE html>
<html>
	<body>
<?php
	if (isset($_COOKIE["userName"]))
	{	
		// Employee case
		if ($_COOKIE["userID"] == "3000")
		{
			echo '<p style="text-align: left;">';
			echo '&nbsp; &nbsp; &nbsp';
			echo '<a href="landingPage.php">Landing Page</a>';
			echo '<span style="float: right;">';
			echo '<a href="hotelemployeep.php">Logged in as'. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
			echo '<a href="hotelemployeep.php">Employee Dashboard</a>';
			echo '</span>';
			echo '</p>';
		}
		
		// Customer case
		else
		{
			echo '<p style="text-align: left;">';
			echo '<a href="landingPage.php">Landing Page</a>';
			echo '&nbsp; &nbsp; &nbsp';
			echo '<a href="Services.php">Services Offerred</a>';
			echo '<span style="float: right;">';
			echo '<a href="hotelcustomerp.php">Logged in as'. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
			echo '<a href="hotelcustomerp.php">Customer Page</a>';
			echo '</span>';
			echo '</p>';
		}
	}
	else
	{
		echo '<p style="text-align: left;">';
		echo '<a href="landingPage.php">Landing Page</a>';
		echo '&nbsp; &nbsp; &nbsp';
		echo '<span style="float: right;">';
		echo '<a href="customer-login.php">Customer Login</a>&nbsp; &nbsp; &nbsp';
		echo '<a href="employee-login.php">Employee Login</a>';
		echo '</span>';
		echo '</p>';
	}
?>

<?php
	echo "<h1>SERVICES</h1>";
	echo "<table border='1'> <tr>
	<th>DESCRIPTION</th>
	<th>PRICE</th>
	</tr>";
	// $servicesURL = "http://localhost:8000/services";
	// $curl = curl_init($servicesUrl);
	//     curl_setopt($curl, CURLOPT_URL, $url);
	//     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	//     $response = curl_exec($curl);
	//     $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$response = array(
		'services' => array(
			array("description" => 'Description1', "price" => '14.4'),
		)
	);

	$response = json_encode($response);
	// if($httpcode == 200){
		$response = json_decode($response);
			foreach ($response->services as $service){
				$description =  $service->description;
				$price =  $service->price;
				echo "<tr>
				<td>" . $description . "</td>
				<td>" . $price . "</td>
				</tr>";
			}
	// else{
	//       echo "<br><font color='red'>Unable to display services</font>" . 'HTTP code: ' . $httpcode;
	// }
		

?>

  	</body>
</html>