<?php echo '<link rel="stylesheet" type="text/css" href="style.css" media="screen" />'; ?>
<html>
    <head>
        <title>Room Information</title>
    </head>
    <body>
        <nav>
            <footer>
                <left>
                    <a href="./hotellandingpage.php">Homepage</a>
                </left>
                <right>
                    <a> Hello,&nbsp </a>
                    <a href="./hotelemployeep.php">Jeff </a> <!-- grab username from cookie? --> 
                    <a>&nbsp&nbsp&nbsp&nbsp; </a>
                    <a href="./hotellogoutpage.php">Logout</a>
                </right>
            </footer>
        </nav>
        
        <h3> View/Edit Rooms </h3>
        <form method = "post">
            Room Number: <input type="text" name ="RoomNumber"> <br> 
            <input type="submit" formaction = "view.php?job=update" value = "View/Edit"> 
            <input type="submit" formaction = "view.php?job=add" value = "Add a new room"> 
            <input type="submit" formaction = "view.php?job=delete" value = "Delete room"> <br>
        </form>

        <table style="border-collapse: collapse; width: 100%; height: 117px;" border="1">
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
        </table>
    </body>
</html>

