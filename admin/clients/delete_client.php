<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$client_id = $_POST['client_id'];

	try {
		$stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
		$stmt->execute([$client_id]);
		header('Location: index.php?message=delete_success');
	} catch (PDOException $e) {
		echo "Ошибка при удалении клиента: " . $e->getMessage();
	}
} else {
	header('Location: index.php');
	exit();
}
?>
