<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$contract_id = $_POST['contract_id'];

	try {
		$stmt = $pdo->prepare("DELETE FROM contracts WHERE id = ?");
		$stmt->execute([$contract_id]);
		header('Location: index.php?message=delete_success');
	} catch (PDOException $e) {
		echo "Ошибка при удалении контракта: " . $e->getMessage();
	}
} else {
	header('Location: index.php');
	exit();
}
?>
