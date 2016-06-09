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

	if ($query = $conn->prepare('UPDATE Room SET customer_id=NULL WHERE room_id=? AND customer_id=?'))
	{
		$query->bind_param('ii', $room_id, $customer_id);
		$query->execute();
		$output .= '{"UpdateRoomCheckoutResult":"valid"}';
	}
	else {
		$output .= '{"UpdateRoomCheckoutResult":"invalid"}';
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>