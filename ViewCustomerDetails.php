<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
if (isset($_GET['wallet_addr'])) {
	$wallet_addr = $_GET['wallet_addr'];

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
	if (!$conn) {
		exit();
	}

	if ($query = $conn->prepare('SELECT * FROM customers WHERE wallet_addr=?'))
	{
		$query->bind_param('i', $wallet_addr);
		$query->execute();
		$query->bind_result($customer_id,
							$wallet_addr,
							$customer_name,
							$email,
							$mobile_num);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows > 0) {
			while ($query->fetch()) {
				if ($output != '')
					$output .= ',';
				$output .= '{"CustomerID":"'  . $customer_id . '",';
				$output .= '"WalletAddr":"'   . $wallet_addr        . '",';
				$output .= '"Name":"'. $customer_name     . '",'; 
				$output .= '"Email":"'. $email     . '",'; 
				$output .= '"MobileNumber":"'. $mobile_num     . '"}'; 
			}
		}
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>