<?php
require_once 'database_connection.php';

session_start();

if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : null;
	$date_of_birth = isset($_POST['date_of_birth']) ? trim($_POST['date_of_birth']) : null;
	$full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : null;
	$password = isset($_POST['password']) ? trim($_POST['password']) : null;

	$query = $pdo->prepare("SELECT phone_number, date_of_birth, full_name FROM users WHERE user_id = ?");
	$query->execute([$user_id]);
	$current_data = $query->fetch(PDO::FETCH_ASSOC);

	$params = [];
	$sql = "UPDATE users SET ";

	if (!empty($phone_number)) {
		$sql .= "phone_number = ?, ";
		$params[] = $phone_number;
	} else {
		$params[] = $current_data['phone_number'];
	}

	if (!empty($date_of_birth)) {
		$sql .= "date_of_birth = STR_TO_DATE(?, '%Y-%m-%d'), ";
		$params[] = $date_of_birth;
	} else {
		$sql .= "date_of_birth = ?, ";
		$params[] = $current_data['date_of_birth'];
	}

	if (!empty($full_name)) {
		$sql .= "full_name = ?, ";
		$params[] = $full_name;
	} else {
		$params[] = $current_data['full_name'];
	}

	if (!empty($password)) {
		$sql .= "password = ?, ";
		$params[] = password_hash($password, PASSWORD_DEFAULT);
	}

	$sql = rtrim($sql, ', ');

	$sql .= " WHERE user_id = ?";
	$params[] = $user_id;

	try {
		$stmt = $pdo->prepare($sql);
		$stmt->execute($params);

		$_SESSION['profile_update_success'] = true;
		header('Location: profile.php');
		exit();
	} catch (PDOException $e) {
		echo "Error updating profile: " . $e->getMessage();
	}
} else {
	header('Location: profile.php');
	exit();
}
?>
