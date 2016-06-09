<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
if (isset($_GET['RoomID']) && isset($_GET['CustomerID'])) {
	$room_id = $_GET['RoomID'];
	$customer_id = $_GET['CustomerID'];

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
	if (!$conn) {
		exit();
	}

	$startDate = gmdate("Y-m-d H:i:s", time() + 3600*(8)); 
	$endDate = gmdate("Y-m-d H:i:s", time() + 3600*(8) + 5*60); 
	
	$reservation_id = 0;
	if ($query = $conn->prepare('INSERT INTO Reservation(start_datetime,end_datetime,customer_id,room_id) VALUES(?,?,?,?)'))
	{
		$query->bind_param('ssii', $startDate, $endDate, $customer_id, $room_id);
		$query->execute();
		$output .= '{"InsertReserveResult":"valid",';
		
		$query->store_result();
		$reservation_id = $query->insert_id;
	}
	else {
		$output .= '{"InsertReserveResult":"invalid",';
	}
	
	if ($query = $conn->prepare('UPDATE Room SET customer_id=?,reservation_id=? WHERE room_id=?'))
	{
		$query->bind_param('iii', $customer_id, $reservation_id, $room_id);
		$query->execute();
		$output .= '"UpdateReserveResult":"valid"}'; 
	}
	else {
		$output .= '"UpdateReserveResult":"invalid"}'; 
	}
	
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>