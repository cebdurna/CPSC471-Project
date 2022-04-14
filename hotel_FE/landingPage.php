<!DOCTYPE html>
<html>
	<body>
	<?php
		setcookie("hotelID", "1", time()+3600, "/");
		if (isset($_COOKIE["userName"]))
		{	
			// Employee case
			if ($_COOKIE["userID"] == "3000")
			{
				echo '<p style="text-align: left;">';
				echo '&nbsp; &nbsp; &nbsp';
				echo '<a href="Services.php">Services Offerred</a>';
				echo '<span style="float: right;">';
				echo '<a href="hotelemployeep.php">Logged in as '. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
				echo '<a href="Logout.php">Logout</a>';
				echo '</span>';
				echo '</p>';
			}
			
			// Customer case
			else
			{
				echo '<p style="text-align: left;">';
				echo '<a href="hotelcustomerp.php">Book a Room</a>';
				echo '&nbsp; &nbsp; &nbsp';
				echo '<a href="Services.php">Services Offerred</a>';
				echo '<span style="float: right;">';
				echo '<a href="hotelcustomerp.php">Logged in as '. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
				echo '<a href="Logout.php">Logout</a>';
				echo '</span>';
				echo '</p>';
			}
		}
		else
		{
			echo '<p style="text-align: left;">';
			echo '<a href="customer-login.php">Book a Room</a>';
			echo '&nbsp; &nbsp; &nbsp';
			echo '<a href="Services.php">Services Offerred</a>';
			echo '<span style="float: right;">';
			echo '<a href="customer-login.php">Customer Login</a>&nbsp; &nbsp; &nbsp';
			echo '<a href="employee-login.php">Employee Login</a>';
			echo '</span>';
			echo '</p>';
		}
	?>
		<h1>OUR PERFECT HOTEL</h1>
		<p>We are located at 1105 Chuck Norris Blvd, Atlantis, Babylon S2G S2R.</p>
		<p>You can reach us at (444) 323-4673</p>
  	</body>
</html>