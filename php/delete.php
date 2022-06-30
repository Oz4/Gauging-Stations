<?php
require __DIR__.'/connection.php';

$id = $_POST['id'];


$sql = "DELETE FROM iqs_table.iqs WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();

print_r($id);

?>