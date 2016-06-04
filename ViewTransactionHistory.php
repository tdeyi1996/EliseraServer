<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
if (isset($_GET['customerID'])) {
	$customer_id = $_GET['customerID'];

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
	if (!$conn) {
		exit();
	}

	if ($query = $conn->prepare('SELECT * FROM purchases WHERE customer_id=?'))
	{
		$query->bind_param('i', $customer_id);
		$query->execute();
		$query->bind_result($purchase_id,
							$cost,
							$start_date,
							$end_date,
							$room_id,
							$customer_id);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			while ($query->fetch()) {
				if ($output != '')
					$output .= ',';
				$output .= '{"PurchaseID":"'  . $purchase_id . '",';
				$output .= '"Cost":"'   . $cost        . '",';
				$output .= '"StartDate":"'. $start_date     . '",'; 
				$output .= '"EndDate":"'. $end_date     . '",'; 
				$output .= '"RoomID":"'. $room_id     . '",'; 
				$output .= '"CustomerID":"'. $customer_id     . '"}'; 
			}
		}
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>