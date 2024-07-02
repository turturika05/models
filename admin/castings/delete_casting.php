<?php
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$casting_id = $_POST['casting_id'];

	try {
		$stmt = $pdo->prepare("DELETE FROM castings WHERE casting_id = ?");
		$stmt->execute([$casting_id]);
		header('Location: index.php?message=delete_success');
	} catch (PDOException $e) {
		header('Location: index.php?message=delete_error');
	}
} else {
	header('Location: index.php');
	exit();
}
?>
