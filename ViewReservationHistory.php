<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
if (isset($_GET['CustomerID'])) {
	$customer_id = $_GET['CustomerID'];

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
	if (!$conn) {
		exit();
	}

	if ($query = $conn->prepare('SELECT * FROM Reservation WHERE customer_id=? ORDER BY reservation_id DESC LIMIT 10'))
	{
		$query->bind_param('i', $customer_id);
		$query->execute();
		$query->bind_result($reservation_id,
							$start_datetime,
							$end_datetime,
							$customer_id,
							$room_id);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			while ($query->fetch()) {
				if ($output != '')
					$output .= ',';
				$output .= '{"ReservationID":"'  . $reservation_id . '",';
				$output .= '"StartDateTime":"'  . $start_datetime . '",';
				$output .= '"EndDateTime":"'  . $end_datetime . '",';
				$output .= '"CustomerID":"'. $customer_id     . '",'; 
				$output .= '"RoomID":"'. $room_id     . '"}'; 
			}
		}
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>