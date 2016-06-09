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

	if ($query = $conn->prepare('SELECT room_id,cost FROM Room WHERE reservation_id IN (SELECT reservation_id FROM Reservation WHERE customer_id=? AND TIMEDIFF(DATE_ADD(NOW(),INTERVAL 12 HOUR),end_datetime) < 0)'))
	{
		$query->bind_param('i', $customer_id);
		$query->execute();
		$query->bind_result($room_id,$cost);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			while($query->fetch()) {
				$output .= '{"Result":"valid","RoomID":"'.$room_id.'","Cost":"'.$cost.'"}';
			}
		}
		else {
			$output .= '{"Result":"invalid","RoomID":"invalid","Cost":"invalid"}';
		}
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>