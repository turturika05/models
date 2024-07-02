<?php
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$user_id = $_POST['user_id'];

	try {
		$stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
		$stmt->execute([$user_id]);
		header("Location: index.php?message=delete_success");
		exit();
	} catch (PDOException $e) {
		header("Location: index.php?message=delete_error");
		exit();
	}
}
?>
