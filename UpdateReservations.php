<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
if (!$conn) {
	exit();
}

if ($query = $conn->prepare('UPDATE Room SET customer_id=null,reservation_id=null WHERE reservation_id IN (SELECT reservation_id FROM Reservation WHERE end_datetime <= DATE_ADD(NOW(),INTERVAL 12 HOUR))'))
{
	$query->execute();	
	$query->store_result();
	$resultRows = $query->num_rows;
	
	$output .= '"{"Result":"valid"}'; 
}
else {
	$output .= '"{"Result":"invalid"}'; 
}
$conn->close();

$output = '{"records":['.$output.']}';
echo $output;
?>