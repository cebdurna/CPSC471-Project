<html>
	<body>
		<?php
            echo '<p style="text-align: left;">';
            echo '<a href="landingPage.php">Landing Page</a>';
			echo '&emsp;&emsp;<a href="hotelemployeep.php">Employee Dashboard</a>';
            echo '<span style="float: right;">';
            echo '<a href="hotelemployeep.php">Logged in as'. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
            echo '<a href="logoutlink">Logout</a>';
            echo '</span>';
            echo '</p>';

			echo "<table border='1'> <tr>
            <th>Booking Number</th>
            <th>Check In Date</th>
            <th>Check Out Date</th>
            <th>Room Number</th>
            <th>Customer ID</th>
            <th>Invoice ID</th>
			<th>Credit Card Number</th>
            </tr>";

			$customerId = $_COOKIE["userID"];
			$hotelId = "1";
            $bookingsURL = "http://localhost:8000/employee/bookings?hotelID=" . $hotelId;
            $curl = curl_init($bookingsURL);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            // stub response for thesting
            $response = array(
                'bookings' => array(
					array("booking_no" => 'booking_no', "check-in" => 'check-in',
                    "check-out" => 'check-out', "room_no" => 'room_no',
                    "customerID" => 'customerID', "invoiceID" => 'invoiceID',"cc_no" => 'cc_no'),
                )
            );
            $response = json_encode($response);
            // if($httpcode == 200){
                $response = json_decode($response);
                    foreach ($response->bookings as $booking){
                        $booking_no =  $booking->booking_no;
                        $check_in =  $booking->check-in;
                        $check_out =  $booking->check-out;
                        $room_no =  $booking->room_no;
                        $customerID =  $booking->customerID;
                        $invoiceID =  $booking->invoiceID;
                        $cc_no =  $booking->cc_no;
                        echo "<tr>
                        <td>" . $booking_no . "</td>
                        <td>" . $check_in . "</td>
                        <td>" . $check_out . "</td>
                        <td>" . $room_no . "</td>
                        <td>" . $customerID . "</td>
                        <td>" . $invoiceID . "</td>
                        <td>" . $cc_no . "</td>
						<td>
						<form action='editBooking.php' method='post'>
							<input type='hidden' name='booking_no' value = '".$booking_no."'>
							<input type='hidden' name='check_in' value = '". $check_in ."'>
							<input type='hidden' name='check_out' value = '" . $check_out ."'>
							<input type='hidden' name='room_no' value = '" . $room_no . "'>
							<input type='hidden' name='customerID' value = '" . $customerID . "'>
							<input type='hidden' name='invoiceID' value = '" . $invoiceID . "'>
							<input type='hidden' name='cc_no' value = '" . $cc_no . "'>
							<input type='submit' value='Edit'>
						</form>
						</td>
                        </tr>";
                        
                    }
            // else{
            //       echo "<br><font color='red'>Unable to display services</font>" . 'HTTP code: ' . $httpcode;
            // }

			echo '<form action="addBooking.php" method="post">
			Customer ID: <input type="text" name="cust_id" placeholder = "23283223"><br>
			Room Number: <input type="text" name="room_no" placeholder = "111"><br>
			Check in date: <input type="date" name="check_in" placeholder = "yyyy-mm-dd"><br>
			Check out date: <input type="date" name="check_out" placeholder = "yyyy-mm-dd"><br>
			CC Number: <input type="text" name="cc_no" placeholder = ""><br>
			CC Name: <input type="text" name="cc_name" placeholder = "Full Name"><br>
			CC Expiry: <input type="date" name="cc_expiry" placeholder = "yyyy-mm-dd"><br>
			CVV: <input type="number" name="cvv" placeholder = "yyyy-mm-dd"><br>
			CC Address: <input type="text" name="cc_address" placeholder = " Queens Drive"><br>
			Postal Code: <input type="text" name="postal_code" placeholder = "H7K 0P2"><br>
			<input type="submit" value="Create New Booking">
			</form><br><br>';

			
                
        ?>
		
  	</body>
</html>