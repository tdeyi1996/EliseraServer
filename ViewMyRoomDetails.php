<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

function check_in_range($start_date, $end_date, $date_from_user)
{
  // Convert to timestamp
  $start_ts = strtotime($start_date);
  $end_ts = strtotime($end_date);
  $user_ts = strtotime($date_from_user);

  // Check that user date is between start & end
  return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}


$output = '';
if (isset($_GET['CustomerID'])) {
	$customer_id = $_GET['CustomerID'];

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
	if (!$conn) {
		exit();
	}
	
	$userRoomID = 0;
	if ($query = $conn->prepare('SELECT room_id FROM Room WHERE customer_id=? AND customer_id IS NOT NULL AND reservation_id IS NULL'))
	{
		$query->bind_param('i', $customer_id);
		$query->execute();
		$query->bind_result($room_id);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			while ($query->fetch()) {
				$userRoomID = $room_id;
			}
		}
	}
	
	if ($userRoomID != 0) {
		if ($query = $conn->prepare('SELECT start_date,end_date FROM Purchase WHERE room_id=? AND customer_id=? ORDER BY purchase_id DESC LIMIT 1'))
		{
			$query->bind_param('ii', $room_id, $customer_id);
			$query->execute();
			$query->bind_result($start_date, $end_date);
			
			$query->store_result();
			$resultRows = $query->num_rows;
			
			if ($resultRows > 0) {
				while ($query->fetch()) {
					$current_date = date('Y-m-d');
					if (check_in_range($start_date, $end_date, $current_date)) {
						$output .= '{"Result":"valid",';
						$output .= '"RoomID":"'. $userRoomID     . '",';  
						$output .= '"StartDate":"'. $start_date     . '",';  
						$output .= '"EndDate":"'. $end_date     . '"}'; 
					}
				}
			}
		}
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>