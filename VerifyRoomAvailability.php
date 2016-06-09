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

	if ($query = $conn->prepare('SELECT room_id FROM Room WHERE room_id=? AND customer_id IS NULL AND reservation_id IS NULL'))
	{
		$query->bind_param('i', $room_id);
		$query->execute();
		$query->bind_result($room_id);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			$output .= '{"Result":"valid"}';
		}
		else {
			$output .= '{"Result":"invalid"}';
		}
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>