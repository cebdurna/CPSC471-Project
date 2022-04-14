<html>
	<body>
		<?php
            echo '<p style="text-align: left;">';
            echo '<a href="landingPage.php">Homepage</a>';
			echo '&emsp;&emsp;<a href="hotelemployeep.php">Employee Dashboard</a>';
            echo '<span style="float: right;">';
            echo '<a href="hotelemployeep.php">Logged in as '. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
            echo '<a href="Logout.php">Logout</a>';
            echo '</span>';
            echo '</p>';
			echo "<br><h1>Invoices</h1><br>";

			echo "<table border='1'> <tr>
				<th>Invoice ID</th>
				<th>Format</th>
				<th>Date Created</th>
				<th>Date Due</th>
				<th>Booking No</th>
				<th>Total</th>
				<th>Total Paid</th>
				</tr>";

			$url = "http://localhost:8000/employee/invoice";
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_GET, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($curl);
			curl_close($curl);

			$response = json_decode($response);
        	foreach ($response as $invoice){
				$Invoice_ID =  $invoice->Invoice_ID;
				$Format =  $invoice->Format;
				$Date_created =  $invoice->Date_created;
				$Date_due =  $invoice->Date_due;
				$booking_no =  $invoice->booking_no;
				$total =  $invoice->total;
				$total_paid =  $invoice->total_paid;
				echo "<tr>
				<td>" . $Invoice_ID . "</td>
				<td>" . $Format . "</td>
				<td>" . $Date_created . "</td>
				<td>" . $Date_due . "</td>
				<td>" . $booking_no . "</td>
				<td>" . "$".round($total) . "</td>
				<td>" . "$".round($total_paid) . "</td>
				<td>
					<form action='InvoiceDetail.php' method='post'>
						<input type='hidden' name='invoice_id' value = '".$Invoice_ID."'>
						<input type='hidden' name='Format' value = '". $Format ."'>
						<input type='hidden' name='Date_created' value = '" . $Date_created ."'>
						<input type='hidden' name='Date_due' value = '" . $Date_due . "'>
						<input type='hidden' name='booking_no' value = '" . $booking_no . "'>
						<input type='hidden' name='total' value = '" . $total . "'>
						<input type='hidden' name='total_paid' value = '" . $total_paid . "'>
						<input type='submit' value='View/Edit'>
					</form>
				</td>
				</tr>";
			}
		?>

	</body>
</html>