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

	if ($query = $conn->prepare('SELECT * FROM Customer WHERE wallet_addr=?'))
	{
		$query->bind_param('s', $wallet_addr);
		$query->execute();
		$query->bind_result($customer_id,
							$wallet_addr,
							$customer_name,
							$email,
							$mobile_num,
							$picture_url);
		
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
				$output .= '"MobileNumber":"'. $mobile_num     . '",'; 
				$output .= '"PictureURL":"'. $picture_url     . '"}'; 
			}
		}
	}
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>