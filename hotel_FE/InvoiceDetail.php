<html>
	<body>
		<?php
			$invoice_id =  $_POST['invoice_id'];
			$Format =  $_POST['Format'];
			$Date_created =  $_POST['Date_created'];
			$Date_due =  $_POST['Date_due'];
			$booking_no =  $_POST['booking_no'];
			$total =  $_POST['total'] + $_POST['price'];
			$total_paid =  $_POST['total_paid'] + $_POST['amount'];

			if(isset($_POST['date_due2']))
			{
				$curl = curl_init($url);

				$invoice_id =  curl_escape($curl, $_POST['invoice_id']);
				$Format =  curl_escape($curl, $_POST['Format']);
				$Date_created =  curl_escape($curl, $_POST['Date_created']);
				$Date_due =  curl_escape($curl, $_POST['date_due2']);

				$url = "localhost:8000/employee/invoice_detail?invoice_id={$invoice_id}&form={$Format}&date_created={$Date_created}&date_due={$Date_due}";
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_PUT, 1);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($response);
			}

			if(isset($_POST['charge_time']))
			{
				$curl = curl_init($url);
				$invoice_id =  curl_escape($curl, $_POST['invoice_id']);
				$description =  curl_escape($curl, $_POST['description']);
				$price =  curl_escape($curl, $_POST['price']);
				$charge_time =  curl_escape($curl, $_POST['charge_time']);

				$url = "localhost:8000/employee/invoice_detail/charge?invoice_id={$invoice_id}&description={$description}&price={$price}&charge_time={$charge_time}";
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($response);
			}

			if(isset($_POST['date']))
			{
				$curl = curl_init($url);
				
				$invoice_id =  curl_escape($curl, $_POST['invoice_id']);
				$cc_number =  curl_escape($curl, $_POST['cc_number']);
				$amount =  curl_escape($curl, $_POST['amount']);
				$date =  curl_escape($curl, $_POST['date']);

				$url = "localhost:8000/employee/invoice_detail/payment?invoice_id={$invoice_id}&cc_no={$cc_number}&amount={$amount}&date={$date}";
				$curl = curl_init($url);
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($response);
			}
            echo '<p style="text-align: left;">';
            echo '<a href="landingPage.php">Homepage</a>';
			echo '&emsp;&emsp;<a href="Invoices.php">Go back to invoices</a>';
            echo '<span style="float: right;">';
            echo '<a href="hotelemployeep.php">Logged in as '. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
            echo '<a href="Logout.php">Logout</a>';
            echo '</span>';
            echo '</p>';
			echo "<br><h1>Invoice Detail</h1><br>";

			$url = "http://localhost:8000/employee/invoice_detail?invoice_id={$invoice_id}";
			$curl = curl_init($url);
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
				<form action='invoiceDetail.php' method='post'>
					<input type='hidden' name='invoice_id' value = '$invoice_id'>
					<input type='hidden' name='Date_created' value = '$Date_created'>
					<input type='hidden' name='booking_no' value = '$booking_no'>
					<input type='hidden' name='total' value = '$total'>
					<input type='hidden' name='total_paid' value = '$total_paid'>
					<td>" . $invoice_id . "</td>
					<td><input type='text' name='Format' value = '$Format'></td>
					<td>" . $Date_created . "</td>
					<td><input type='text' name='date_due2' value = '$Date_due'></td>
					<td>" . $booking_no . "</td>
					<td>" . "$".round($total,2) . "</td>
					<td>" . "$".round($total_paid) . "</td>
					<td><input type='submit' value='Update'></td>
				</form>
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
			echo "</table>";
			
			echo "<br><table border='1'> <tr>
				<th>Description</th>
				<th>Price</th>
				<th>Date</th>
				</tr>";
			echo "<tr>
					<form action='invoiceDetail.php' method='post'>
						<input type='hidden' name='invoice_id' value = '".$invoice_id."'>
						<input type='hidden' name='Format' value = '".$Format."'>
						<input type='hidden' name='Date_created' value = '".$Date_created."'>
						<input type='hidden' name='Date_due' value = '".$Date_due."'>
						<input type='hidden' name='booking_no' value = '".$booking_no."'>
						<input type='hidden' name='total' value = '".$total."'>
						<input type='hidden' name='total_paid' value = '".$total_paid."'>
						<td><input type='text' name='description' placeholder = 'Description'></td>
						<td><input type='text' name='price' placeholder = '$111'></td>
						<td><input type='date' name='charge_time' placeholder = '202X-XX-XXT09:09:09'></td>
						<td><input type='submit' value='Add new charge'></td>
					</form>
				</tr>";

			echo "</table>";

			echo "<br><h1>Payments</h1><br>";
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
			echo "<br><table border='1'> <tr>
				<th>CC Number</th>
				<th>Amount</th>
				<th>Date</th>
				</tr>";
			echo "<tr>
					<form action='invoiceDetail.php' method='post'>
						<input type='hidden' name='invoice_id' value = '".$invoice_id."'>
						<input type='hidden' name='Format' value = '".$Format."'>
						<input type='hidden' name='Date_created' value = '".$Date_created."'>
						<input type='hidden' name='Date_due' value = '".$Date_due."'>
						<input type='hidden' name='booking_no' value = '".$booking_no."'>
						<input type='hidden' name='total' value = '".$total."'>
						<input type='hidden' name='total_paid' value = '".$total_paid."'>
						<td><input type='text' name='cc_number' placeholder = '4874 8748 4949 2821' maxlength='19'></td>
						<td><input type='text' name='amount' placeholder = '$111'></td>
						<td><input type='date' name='date' placeholder = '202X-XX-XX'></td>
						<td><input type='submit' value='Add new payment'></td>
					</form>
				</tr>";

			echo "</table>";
		?>

	</body>
</html>