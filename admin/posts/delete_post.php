<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$post_id = $_POST['post_id'];

	try {
		$stmt = $pdo->prepare("DELETE FROM posts WHERE post_id = ?");
		$stmt->execute([$post_id]);
		header('Location: index.php?message=delete_success');
	} catch (PDOException $e) {
		header('Location: index.php?message=delete_error');
	}
} else {
	header('Location: index.php');
	exit();
}
?>
