<html>
    <head>
        <title>Room Information</title>
    </head>
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
        ?>
           
    <?php
        $hotelID = $_COOKIE['hotelID'];
        if (isset($_POST['editComplete'])) {
            $curl = curl_init();
        
            $roomNumber = curl_escape($curl, $_POST["RoomNumber"]);
            $type = curl_escape($curl, $_POST["Type"]);
            $beds = curl_escape($curl, $_POST["Beds"]);
            $floor = curl_escape($curl, $_POST["Floor"]);
            $furniture = curl_escape($curl, $_POST["Furniture"]);
            $capacity = curl_escape($curl, $_POST["Capacity"]);
            $orientation = curl_escape($curl, $_POST["Orientation"]);
            $rate = curl_escape($curl, $_POST["Rate"]);

            
            $url = "http://localhost:8000/employee/rooms?room_no={$roomNumber}&hotelID={$hotelID}&type={$type}&beds={$beds}&floor={$floor}&furniture={$furniture}&capacity={$capacity}&orientation={$orientation}&rate={$rate}";

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_PUT, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);

            echo "<label> Successfully edited </label>
            <form action='hotelrooms.php' method='post'>
                <input type='submit' value='Return to rooms'>
            </form>";
        }

        elseif (isset($_POST['edit'])) {
            $roomNumber = $_POST["RoomNumber"];
            $type = $_POST["Type"];
            $beds = $_POST["Beds"];
            $floor = $_POST["Floor"];
            $furniture = $_POST["Furniture"];
            $capacity = $_POST["Capacity"];
            $orientation = $_POST["Orientation"];
            $rate = $_POST["Rate"];

            echo "<form action='hotelrooms.php' method='post'>
            Room Number: <input type='text' name='RoomNumber' value='" . $roomNumber . "' readonly><br>
            Hotel ID: <input type='number' name='hotelID' value=$hotelID readonly><br>
            Type: <input type='text' name='Type' value='" . $type . "'><br>
            Beds: <input type='text' name='Beds' value='" . $beds . "'><br>
            Floor: <input type='text' name='Floor' value='" . $floor . "'><br>
            Furniture: <input type='text' name='Furniture' value='" . $furniture . "'><br>
            Capacity: <input type='text' name='Capacity' value='" . $capacity . "'><br>
            Orientation: <input type='text' name='Orientation' value='" . $orientation . "'><br>
            Rate: <input type='text' name='Rate' value='" . $rate . "'><br>
            <input type='hidden' name='editComplete' value='true'>
            <input type='submit' value='Update'>
            </form>
            ";
        }

        else if (isset($_POST['delete'])) {
            $curl = curl_init();
            $roomNumber = curl_escape($curl, $_POST["RoomNumber"]);

            $url = "http://localhost:8000/employee/rooms?hotelID=$hotelID&roomNo={$roomNumber}";

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            
            echo "<label> Successfully deleted </label>
            <form action='hotelrooms.php' method='post'>
                <input type='submit' value='Return to rooms'>
            </form>";
        }

        elseif (isset($_POST['add'])){
            if( empty($_POST['RoomNumber']) || empty($_POST['Type']) || empty($_POST['Beds']) || empty($_POST['Floor']) || empty($_POST['Furniture']) || empty($_POST['Capacity']) || empty($_POST['Orientation']) || empty($_POST['Rate'])) {
                echo "<label>Please make sure all fields are entered. </label>";
            } else {
                $curl = curl_init();
                $roomNumber = curl_escape($curl, $_POST["RoomNumber"]);
                $type = curl_escape($curl, $_POST["Type"]);
                $beds = curl_escape($curl, $_POST["Beds"]);
                $floor = curl_escape($curl, $_POST["Floor"]);
                $furniture = curl_escape($curl, $_POST["Furniture"]);
                $capacity = curl_escape($curl, $_POST["Capacity"]);
                $orientation = curl_escape($curl, $_POST["Orientation"]);
                $rate = curl_escape($curl, $_POST["Rate"]);
                
                $url = "http://localhost:8000/employee/rooms?room_no={$roomNumber}&hotelID={$hotelID}&type={$type}&beds={$beds}&floor={$floor}&furniture={$furniture}&capacity={$capacity}&orientation={$orientation}&rate={$rate}";
                
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                curl_close($curl);
                
                echo "<label>Successfully added room.</label>";
            }

            echo "
            <form action='hotelrooms.php' method='post'>
                <input type='submit' value='Return to rooms'>
            </form>
            ";
        }

        else {
            echo "<h1> Edit/Delete Rooms </h1>";
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
            <th>Edit</th>
            <th>Delete</th>
            </tr>";
            $hotelID = $_COOKIE["hotelID"];
            $url = "http://localhost:8000/employee/rooms?hotel=$hotelID";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

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
                <td> 
                    <form action='hotelrooms.php' method='post'>
                        <input type = 'submit' value = 'Edit'>
                        <input type = 'hidden' name = 'RoomNumber' value=$roomNumber>
                        <input type = 'hidden' name = 'Type' value=$type>
                        <input type = 'hidden' name = 'Beds' value='". $beds ."'>
                        <input type = 'hidden' name = 'Floor' value=$floor>
                        <input type = 'hidden' name = 'Furniture' value='". $furniture ."'>
                        <input type = 'hidden' name = 'Capacity' value=$capacity>
                        <input type = 'hidden' name = 'Orientation' value=$orientation>
                        <input type = 'hidden' name = 'Rate' value=$rate>
                        <input type = 'hidden' name = 'edit' value='true'>
                    </form>
                </td>
                <td> 
                    <form action='hotelrooms.php' method='post'>
                        <input type = 'submit' value = 'Delete'>
                        <input type = 'hidden' name = 'RoomNumber' value=$roomNumber>
                        <input type = 'hidden' name = 'Type' value=$type>
                        <input type = 'hidden' name = 'Beds' value=$beds>
                        <input type = 'hidden' name = 'Floor' value=$floor>
                        <input type = 'hidden' name = 'Furniture' value=$furniture>
                        <input type = 'hidden' name = 'Capacity' value=$capacity>
                        <input type = 'hidden' name = 'Orientation' value=$orientation>
                        <input type = 'hidden' name = 'Rate' value=$rate>
                        <input type = 'hidden' name = 'delete' value=''>
                    </form>
                </td>
                </tr>
                ";		
            }
            echo "</table><br><br>
            <h1>Add a room</h1>
            <table border='1'> <tr>
                <th>Room Number</th>
                <th>Hotel ID</th>
                <th>Room Type</th>
                <th>Beds</th>
                <th>Floor</th>
                <th>Furniture</th>
                <th>Capacity</th>
                <th>Orientation</th>
                <th>Rate</th>
                <th>Add</th>
            </tr>
            <tr>
                <form action='hotelrooms.php' method='post'>
                    <th><input type='number' name='RoomNumber' value=''></th>
                    <th><input type='number' name='HotelID' value=$hotelID readonly></th>
                    <th><input type='text' name='Type' value=''></th>
                    <th><input type='text' name='Beds' value=''></th>
                    <th><input type='text' name='Floor' value=''></th>
                    <th><input type='text' name='Furniture' value=''></th>
                    <th><input type='number' name='Capacity' value=''></th>
                    <th><input type='text' name='Orientation' value=''></th>
                    <th><input type='rate' name='Rate' value=''></th>
                    <input type='hidden' name='add' value='true'>
                    <th><input type='submit' value='Add'></th>
                </form>
            </tr></table>";
        }
    ?>
    </body>
</html>

