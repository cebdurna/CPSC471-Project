<html>
	<body>
		<?php
			$Invoice_ID =  $_POST['Invoice_ID'];
			$Format =  $_POST['Format'];
			$Date_created =  $_POST['Date_created'];
			$Date_due =  $_POST['Date_due'];
			$booking_no =  $_POST['booking_no'];
			$total =  $_POST['total'];
			$total_paid =  $_POST['total_paid'];
            echo '<p style="text-align: left;">';
            echo '<a href="landingPage.php">Homepage</a>';
			echo '&emsp;&emsp;<a href="hotelcustomerp.php">Customer Page</a>';
            echo '<span style="float: right;">';
            echo '<a href="hotelcustomerp.php">Logged in as '. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
            echo '<a href="Logout.php">Logout</a>';
            echo '</span>';
            echo '</p>';
			echo "<br><h1>Invoice Detail</h1><br>";

			$curl = curl_init();
            $Invoice_ID = curl_escape($curl,$_POST['Invoice_ID']);
			$url = "http://localhost:8000/customer/invoice_detail?invoiceID={$Invoice_ID}";
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_GET, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($curl);
			curl_close($curl);
			$response = json_decode($response);

			echo "<table border='1'> <tr>
				<th>Invoice ID</th>
				<th>Format</th>
				<th>Date Created</th>
				<th>Date Due</th>
				<th>Booking No</th>
				<th>Total</th>
				<th>Total Paid</th>
				</tr>";
			echo "<tr>
				<td>" . $Invoice_ID . "</td>
				<td>" . $Format . "</td>
				<td>" . $Date_created . "</td>
				<td>" . $Date_due . "</td>
				<td>" . $booking_no . "</td>
				<td>" . "$".round($total,2) . "</td>
				<td>" . "$".round($total_paid,2) . "</td>
				</tr></table>";

			echo "<br><h1>Charges</h1><br>";
			echo "<table border='1'> <tr>
				<th>Description ID</th>
				<th>Price</th>
				<th>Tax</th>
				<th>Date and Time</th>
				</tr>";
			
			echo "<br>";
			
			foreach ($response->Charges as $charge){
				$Description =  $charge->Description;
				$Price =  $charge->Price;
				$Tax =  $charge->Tax;
				$ChargeTime =  $charge->ChargeTime;
				echo "<tr>
				<td>" . $Description . "</td>
				<td>" . "$".$Price . "</td>
				<td>" . "$".$Tax . "</td>
				<td>" . $ChargeTime . "</td>
				</tr>";
			}
			

			echo "</table><br><h1>Payments</h1><br>";
			echo "<table border='1'> <tr>
			<th>Transaction #</th>
			<th>CC Number</th>
			<th>Amount</th>
			<th>Date</th>
			</tr>";
			foreach ($response->Payments as $payment){
				$Transaction_Number =  $payment->Transaction_Number;
				$CC_Number =  $payment->CC_Number;
				$Amount =  $payment->Amount;
				$Date =  $payment->Date;
				echo "<tr>
				<td>" . $Transaction_Number . "</td>
				<td>" . $CC_Number . "</td>
				<td>" . "$".$Amount . "</td>
				<td>" . $Date . "</td>
				</tr>";
			}
			echo "</table>";
		?>

	</body>
</html>