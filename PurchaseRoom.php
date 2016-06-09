<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
if (isset($_GET['StayDuration']) && isset($_GET['RoomID']) && isset($_GET['CustomerID']) && isset($_GET['StartDate']) && isset($_GET['EndDate'])) {
	$room_id = $_GET['RoomID'];
	$customerID = $_GET['CustomerID'];
	$roomCost = 0;
	$startDate = $_GET['StartDate'];
	$endDate = $_GET['EndDate'];

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
	if (!$conn) {
		exit();
	}
	
	$isRoomEmpty = false;
	if ($query = $conn->prepare('SELECT cost,customer_id,reservation_id FROM Room WHERE room_id=?'))
	{
		$query->bind_param('i', $room_id);
		$query->execute();
		$query->bind_result($cost, $customer_id, $reservation_id);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			while ($query->fetch()) {
				if (is_null($customer_id) && is_null($reservation_id)) {
					$roomCost = $cost;
					$isRoomEmpty = true;
				}
				else {
					$isRoomEmpty = false;
				}
			}
		}
	}
	
	// Check for reservation_id
	$reservationID_ = 0;
	if ($query = $conn->prepare('SELECT reservation_id FROM Reservation WHERE customer_id=? ORDER BY reservation_id DESC LIMIT 1'))
	{
		$query->bind_param('i', $customer_id);
		$query->execute();
		$query->bind_result($reservationID);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			while ($query->fetch()) {
				$reservationID_ = $reservationID;
			}
		}
	}

	if ($isRoomEmpty || $reservation_id == $reservationID_) {
		$roomCost = $roomCost * $_GET['StayDuration'];
		if ($query = $conn->prepare('INSERT INTO Purchase(cost,start_date,end_date,room_id,customer_id) VALUES(?,?,?,?,?)'))
		{
			$query->bind_param('issii', $roomCost, $startDate, $endDate, $room_id, $customerID);
			$query->execute();
			$output .= '{"InsertPurchaseResult":"valid",';
		}
		else {
			$output .= '{"InsertPurchaseResult":"invalid",';
		}
		
		if ($query = $conn->prepare('UPDATE Room SET customer_id=?,reservation_id=NULL WHERE room_id=?'))
		{
			$query->bind_param('ii', $customerID, $room_id);
			$query->execute();
			$output .= '"UpdateRoomInfoResult":"valid"}'; 
		}
		else {
			$output .= '"UpdateRoomInfoResult":"invalid"}'; 
		}
	}
	else {
		$output .= '{"InsertPurchaseResult":"invalid",';
		$output .= '"UpdateRoomInfoResult":"invalid"}'; 
	}
	
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>