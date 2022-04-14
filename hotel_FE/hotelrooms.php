<html>
    <head>
        <title>Room Information</title>
    </head>
    <body>
        <?php
            echo '<p style="text-align: left;">';
            echo '<a href="landingPage.php">Landing Page</a>';
			echo '&emsp;&emsp;<a href="hotelemployeep.php">Employee Dashboard</a>';
            echo '<span style="float: right;">';
            echo '<a href="hotelemployeep.php">Logged in as '. $_COOKIE["userName"] . '</a>&nbsp; &nbsp; &nbsp';
            echo '<a href="Logout.php">Logout</a>';
            echo '</span>';
            echo '</p>';
        ?>
        
    <h1> View/Edit Rooms </h1>
    <form method = "post">
            Room Number: <input type="text" name ="RoomNumber"> <br> 
            <input type="submit" formaction = "view.php?job=update" value = "View/Edit"> 
            <input type="submit" formaction = "view.php?job=add" value = "Add a new room"> 
            <input type="submit" formaction = "view.php?job=delete" value = "Delete room"> <br>
        </form>
    <?php
        echo "<table border='1'> <tr>
		<th>Room Number</th>
        <th>Hotel ID</th>
		<th>Room Type</th>
		<th>Beds</th>
		<th>Floor</th>
		<th>Furniture</th>
		<th>Capacity</th>
		<th>Orientation</th>
		<th>Rate</th>
		</tr>";
		$hotelID = $_COOKIE["hotelID"];
		$url = "http://localhost:8000/employee/rooms?hotel=$hotelID";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		// stub response for thesting
		// $response = array(
		// 	'rooms' => array(
		// 		array("room_no" => 'room_no', "hotelID" => 'hotelID', "type" => 'type',
		// 		"beds" => 'beds', "floor" => 'floor',
		// 		"furniture" => 'furniture', "capacity" => 'capacity',
		// 		"orientation" => 'orientation', 'rate' => 'rate'),
		// 	)
		// );
		// $response = json_encode($response);

		// if($httpcode == 200){
		$response = json_decode($response);
		foreach ($response as $room){
            $roomNumber =  $room->Number;
            $type =  $room->Type;
            $beds =  $room->Beds;
            $floor =  $room->Floor;
            $furniture =  $room->Furniture;
            $capacity =  $room->Capacity;
            $orientation =  $room->Orientation;
            $rate =  $room->Rate;
            echo "<tr>
            <td>" . $roomNumber . "</td>
            <td>" . $hotelID . "</td>
            <td>" . $type . "</td>
            <td>" . $beds . "</td>
            <td>" . $floor . "</td>
            <td>" . $furniture . "</td>
            <td>" . $capacity . "</td>
            <td>" . $orientation . "</td>
            <td>" . "$".$rate . "</td>
            </tr>";		
		}
		// }
		// else{
		//       echo "<br><font color='red'>Unable to display rooms</font>" . 'HTTP code: ' . $httpcode;
		// }
    ?>
        

        <!-- <table style="border-collapse: collapse; width: 100%; height: 117px;" border="1">
            <tbody> 
                <tr style="height: 66px;">
                    <th style="width: 10%; text-align: center; height: 66px;">
                        <h3>Room Number</h3>
                    </th>
                    <td style="width: 10%; text-align: center; height: 66px;">
                        <h3>Hotel ID</h3>
                    </td>
                    <td style="width: 10%; height: 66px; text-align: center;">
                        <h3 style="text-align: center;">Type</h3>
                    </td>
                    <td style="width: 10%; height: 66px; text-align: center;">
                        <h3 style="text-align: center;">Beds</h3>
                    </td>
                    <td style="width: 10%; text-align: center; height: 66px;">
                        <h3 style="text-align: center;">Floor</h3>
                    </td>
                    <td style="width: 10%; text-align: center; height: 66px;">
                        <h3 style="text-align: center;">Furniture</h3>
                    </td>
                    <td style="width: 10%; text-align: center; height: 66px;">
                        <h3 style="text-align: center;">Capacity</h3>
                    </td>
                    <td style="width: 10%; text-align: center; height: 66px;">
                        <h3 style="text-align: center;">Orientation</h3>
                    </td>
                    <td style="width: 10%; text-align: center; height: 66px;">
                        <h3 style="text-align: center;">Rate ($/night)</h3>
                    </td>
                    <td style="width: 10%; text-align: center; height: 66px;">
                        <h3 style="text-align: center;">Edit</h3>
                    </td>
                </tr>
                <tr style="height: 51px;">
                        <td style="width: 10%; height: 51px; text-align: center;">
                            <h3><a title="placeHolder" href="placeHolder">101</a></h3>
                        </td>
                        <td style="width: 10%; height: 51px; text-align: center;">000001</td>
                        <td style="width: 10%; height: 51px; text-align: center;">Non-smoking</td>
                        <td style="width: 10%; height: 51px; text-align: center;">2 Queens&nbsp;</td>
                        <td style="width: 10%; height: 51px; text-align: center;">First&nbsp;</td>
                        <td style="width: 10%; text-align: center; height: 51px;">
                            <p style="text-align: center;">TV, Coffee Table</p>
                        </td>
                        <td style="width: 10%; text-align: center; height: 51px;">
                            <p style="text-align: center;">5</p>
                        </td>
                        <td style="width: 10%; text-align: center; height: 51px;">
                            <p style="text-align: center;">North Facing</p>
                        </td>
                        <td style="width: 10%; text-align: center; height: 51px;">
                            <p style="text-align: center;">200</p>
                        </td>
                        <td style="width: 10%; text-align: center; height: 51px;">
                            <p style="text-align: center;"><a title="placeHolder " href="placeHolder%10">Edit</a></p>
                        </td>
                    </tr>
            </tbody>
        </table> -->
    </body>
</html>

