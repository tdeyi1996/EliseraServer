<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
require_once 'config.php';

$output = '';
if (isset($_GET['WalletAddr']) && isset($_GET['FullName']) && isset($_GET['Email']) && isset($_GET['MobileNo']) && isset($_GET['PictureURL'])) {
	$wallet_addr = $_GET['WalletAddr'];
	$customer_name = $_GET['FullName'];
	$email = $_GET['Email'];
	$mobile_num = $_GET['MobileNo'];
	$picture_url = $_GET['PictureURL'];

	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
	if (!$conn) {
		exit();
	}
	
	// Verify if wallet address exists
	$doesWalletExist = true;
	if ($query = $conn->prepare('SELECT customer_id FROM Customer WHERE wallet_addr=?'))
	{
		$query->bind_param('i', $wallet_addr);
		$query->execute();
		$query->bind_result($customer_id);
		
		$query->store_result();
		$resultRows = $query->num_rows;
		
		if ($resultRows == 0) {
			$doesWalletExist = false;
		}
	}

	if (!$doesWalletExist) {
		if ($query = $conn->prepare('INSERT INTO Customer(wallet_addr,customer_name,email,mobile_num,picture_url) VALUES(?,?,?,?,?)'))
		{
			$query->bind_param('sssis', $wallet_addr, $customer_name, $email, $mobile_num, $picture_url);
			$query->execute();
			$output .= '{"InsertAccountResult":"valid"}';
		}
		else {
			$output .= '{"InsertAccountResult":"invalid"}';
		}
	}
	else {
		$output .= '{"InsertAccountResult":"invalid"}';
	}
	
	$conn->close();
}

$output = '{"records":['.$output.']}';
echo $output;
?>