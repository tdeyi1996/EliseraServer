<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
if (isset($_GET['RoomID'])) {
	$room_id = $_GET['RoomID'];

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
	if (!$conn) {
		exit();
	}

	if ($query = $conn->prepare('SELECT * FROM Room WHERE room_id=?'))
	{
		$query->bind_param('i', $room_id);
		$query->execute();
		$query->bind_result($room_id,
							$cost,
							$size,
							$customer_id,
							$reservation_id);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			while ($query->fetch()) {
				if ($output != '')
					$output .= ',';
				$output .= '{"RoomID":"'  . $room_id . '",';
				$output .= '"Cost":"'   . $cost        . '",';
				$output .= '"Size":"'. $size     . '",'; 
				$output .= '"CustomerID":"'. $customer_id     . '",'; 
				$output .= '"ReservationID":"'. $reservation_id     . '"}';
			}
		}
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>