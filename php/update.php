<?php
require __DIR__.'/connection.php';

$id = $_POST['id'];
$no = $_POST['no'];
$tds = $_POST['tds'];
$tur = $_POST['tur'];
$ph = $_POST['ph'];
$level = $_POST['level'];
$date = $_POST['date'];
$time = $_POST['time'];

$sql = "UPDATE iqs_table.iqs SET no= :no, tds= :tds, tur= :tur, ph= :ph, level= :level, date= :date, time= :time WHERE id= :id";
$stmt = $db->prepare($sql);

$stmt->bindParam(':no', $no, PDO::PARAM_STR);
$stmt->bindParam(':tds', $tds, PDO::PARAM_STR);
$stmt->bindParam(':tur', $tur, PDO::PARAM_STR);
$stmt->bindParam(':ph', $ph, PDO::PARAM_STR);
$stmt->bindParam(':level', $level, PDO::PARAM_STR);
$stmt->bindParam(':date', $date, PDO::PARAM_STR);
$stmt->bindParam(':time', $time, PDO::PARAM_STR);
$stmt->bindParam(':id', $id, PDO::PARAM_STR);
$stmt->execute();


?>