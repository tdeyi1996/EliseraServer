<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
if (!$conn) {
	exit();
}

if ($query = $conn->prepare('SELECT room_id,cost,size FROM Room WHERE customer_id IS NULL AND reservation_id IS NULL'))
{
	$query->execute();
	$query->bind_result($room_id, $cost, $size);
	
	$query->store_result();
	$resultRows = $query->num_rows;
	
	if ($resultRows > 0) {
		while ($query->fetch()) {
			if ($output != '')
				$output .= ',';
			$output .= '{"RoomID":"'. $room_id     . '",';
			$output .= '"Cost":"'. $cost     . '",'; 
			$output .= '"Capacity":"'. $size     . '"}'; 			
		}
	}
}
$conn->close();

$output = '{"records":['.$output.']}';
echo $output;
?>