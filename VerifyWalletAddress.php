<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
if (isset($_GET['WalletAddr'])) {
	$wallet_addr = $_GET['WalletAddr'];

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
	if (!$conn) {
		exit();
	}

	if ($query = $conn->prepare('SELECT customer_id FROM Customer WHERE wallet_addr=?'))
	{
		$query->bind_param('s', $wallet_addr);
		$query->execute();
		$query->bind_result($customer_id);
		
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