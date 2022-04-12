<!DOCTYPE html>
<html>

<head>
  <title>Booking</title>
</head>

<body>

    <a href="landingPage.php">Return to Landing Page</a>

    <hr />

    <H2>Customer Room Booking</H2>

    <p>Please select a check-in and check-out date for your reservation:</p>
	
	<form action="booking.php" method="post">
		Check-in Date: <input type="date" name="startdate" placeholder = "yyyy-mm-dd"><br>
		Check-out Date: <input type="date" name="enddate" placeholder = "yyyy-mm-dd"><br>
		<br>
		<input type="submit" value="Check Availability">
	</form>

    <button type="submit">Confirm Selected Room(s)</button>

    <br></br>
    
    <label>Total price: $400</label><br>
    
    <label>Credit Card #: </label>
    <input type="text" name="cc#" placeholder = "XXXX XXXX XXXX XXXX"><br>
    
    <label>Cardholder's Full Name: </label>
    <input type="text" name="cardName" placeholder = "John Smith"><br>
    
    <label>Expiry date: </label>
    <input type="date" name="expiry" placeholder = "yyyy-mm-dd"><br>
    
    <label>CVV: </label>
    <input type="text" name="cvv" placeholder = "XXX"><br>
    
    <label>Billing Address Street: </label>
    <input type="text" name="street" placeholder = ""><br>
    
    <label>Billing Address Postal Code: </label>
    <input type="text" name="postalcode" placeholder = ""><br>
    <button type="submit">Complete Booking</button>
    
    
    <br></br>

    <label>The following rooms are available during the selected time period:</label>
	</br>
	<?php
		echo "<table border='1'> <tr>
		<th>Room Number</th>
		<th>Room Type</th>
		<th>Number of Beds</th>
		<th>Floor</th>
		<th>Furniture</th>
		<th>Capacity</th>
		<th>Orientation</th>
		<th>Rate</th>
		</tr>";
	?>
	<?php
	if(isset($_POST['startdate']) && isset($_POST['enddate']))
	{
		$startdate = $_POST["startdate"];
		$enddate = $_POST["enddate"];
		
		$url = "http://localhost:8000/customer/availability?checkInDate=" . $startdate ."&checkOutDate=" . $enddate;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		// stub response for thesting
		$response = array(
			'rooms' => array(
				array("roomNumber" => 'roomNumber', "type" => 'type',
				"beds" => 'beds', "floor" => 'floor',
				"furniture" => 'furniture', "capacity" => 'capacity',
				"orientation" => 'orientation', 'rate' => 'rate'),
			)
		);
		$response = json_encode($response);

		// if($httpcode == 200){
			$response = json_decode($response);
			foreach ($response->rooms as $room){
				$roomNumber =  $room->roomNumber;
				$type =  $room->type;
				$beds =  $room->beds;
				$floor =  $room->floor;
				$furniture =  $room->furniture;
				$capacity =  $room->capacity;
				$orientation =  $room->orientation;
				$rate =  $room->rate;
				echo "<tr>
				<td>" . $roomNumber . "</td>
				<td>" . $type . "</td>
				<td>" . $beds . "</td>
				<td>" . $floor . "</td>
				<td>" . $furniture . "</td>
				<td>" . $capacity . "</td>
				<td>" . $orientation . "</td>
				<td>" . $rate . "</td>
				</tr>";
				
			}
		// }
		// else{
		//       echo "<br><font color='red'>Unable to display rooms</font>" . 'HTTP code: ' . $httpcode;
		// }
		
	}
	?>	

</body>

</html