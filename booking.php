<!DOCTYPE html>
<html>

<head>
  <title>Booking</title>
</head>

<body>

    <a href="landing.php">Return to Landing Page</a>

    <hr />

    <H2>Customer Room Booking</H2>

    <p>Please select a check-in and check-out date for your reservation:</p>
    <label>Check-in Date: </label>
    <input type="date" name="startdate" placeholder = "yyyy-mm-dd"><br>

    <label>Check-out Date: </label>
    <input type="date" name="enddate" placeholder = "yyyy-mm-dd"><br>

    <br></br>
    <button type="submit">Check Availability</button>
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

    <table style="border-collapse: collapse; width: 100%; height: 310px;" border="1">
    <tbody>
                <tr style="height: 66px;">
					<th style="width: 20%; text-align: center; height: 66px;">
					<h3>Room Number</h3>
					</th>
					<td style="width: 9%; text-align: center; height: 66px;">
					<h3>Type</h3>
					</td>
					<td style="width: 9%; height: 66px; text-align: center;">
					<h3 style="text-align: center;">Beds</h3>
					</td>
					<td style="width: 9%; height: 66px; text-align: center;">
					<h3 style="text-align: center;">Floor&nbsp;</h3>
					</td>
					<td style="width: 9%; text-align: center;">
					<h3 style="text-align: center;">Furniture</h3>
					</td>
					<td style="width: 9%; text-align: center;">
					<h3 style="text-align: center;">Capacity</h3>
					</td>
					<td style="width: 9%; text-align: center;">
					<h3 style="text-align: center;">Orientation</h3>
					</td>
					<td style="width: 9%; text-align: center;">
					<h3 style="text-align: center;">Rate</h3>
					</td>
					<td style="width: 9%; text-align: center;">
					<h3 style="text-align: center;">Total Price</h3>
					</td>
                    <td style="width: 9%; text-align: center;">
					<h3 style="text-align: center;">Select</h3>
					</td>
				</tr>
                <tr style="height: 51px;">
					<td style="width: 20%; height: 51px; text-align: center;">
					<p style="text-align: center;">100</p>
					</td>
					<td style="width: 9%; height: 51px; text-align: center;">Non-smoking</td>
					<td style="width: 9%; height: 51px; text-align: center;">2 Queens</td>
					<td style="width: 9%; height: 51px; text-align: center;">First</td>
					<td style="width: 9%; text-align: center;">Pullout couch, TV, coffee table</td>
					<td style="width: 9%; text-align: center;">
					<p style="text-align: center;">5</p>
					</td>
					<td style="width: 9%; text-align: center;">
					<p style="text-align: center;">North Facing</p>
					</td>
					<td style="width: 9%; text-align: center;">
					<p style="text-align: center;">$200/night</p>
					</td>
					<td style="width: 9%; text-align: center;">
					<p style="text-align: center;">$400</p>
					</td>
                    <td style="width: 9%; text-align: center;">
					<p style="text-align: center;"><input type="checkbox"></p>
					</td>
				</tr>
                                <tr style="height: 51px;">
					<td style="width: 20%; height: 51px; text-align: center;">
					<p style="text-align: center;">201</p>
					</td>
					<td style="width: 9%; height: 51px; text-align: center;">Non-smoking</td>
					<td style="width: 9%; height: 51px; text-align: center;">1 Queen</td>
					<td style="width: 9%; height: 51px; text-align: center;">Second</td>
					<td style="width: 9%; text-align: center;">Couch, TV, coffee table</td>
					<td style="width: 9%; text-align: center;">
					<p style="text-align: center;">2</p>
					</td>
					<td style="width: 9%; text-align: center;">
					<p style="text-align: center;">South Facing</p>
					</td>
					<td style="width: 9%; text-align: center;">
					<p style="text-align: center;">$163/night</p>
					</td>
					<td style="width: 9%; text-align: center;">
					<p style="text-align: center;">$326</p>
					</td>
                    <td style="width: 9%; text-align: center;">
					<p style="text-align: center;"><input type="checkbox"></p>
					</td>
				</tr>
    </tbody>
    </table>
</body>

</html