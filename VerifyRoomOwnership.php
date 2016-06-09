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

	if ($query = $conn->prepare('SELECT room_id,customer_id,reservation_id FROM Room WHERE customer_id=?'))
	{
		$query->bind_param('i', $customer_id);
		$query->execute();
		$query->bind_result($room_id, $customer_id, $reservation_id);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			while($query->fetch()) {
				if (!is_null($customer_id) && is_null($reservation_id)) {
					$output .= '{"CustomerIDResult":"valid","ReservationIDResult":"invalid"}'; // purchased
				}
				else if (!is_null($customer_id) && !is_null($reservation_id)) {
					$output .= '{"CustomerIDResult":"valid","ReservationIDResult":"valid"}'; // reserved
				}
				else {
					$output .= '{"CustomerIDResult":"invalid","ReservationIDResult":"invalid"}';
				}				
			}
		}
		else {
			$output .= '{"CustomerIDResult":"invalid","ReservationIDResult":"invalid"}';
		}
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>