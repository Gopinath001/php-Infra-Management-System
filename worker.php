<?php 
session_start();
include('Database.php');
require('config/conf.php');

if(isset($_POST["get_all_columns"])){
	$dataBase = new DB;
	$retrieveColumns = "SHOW columns FROM device_pc";
	$data = $dataBase->getQuery($retrieveColumns);
	$temp_arr = $data->fetchall(PDO::FETCH_ASSOC);
	echo json_encode($temp_arr);

}
if(isset($_POST["get_all_device_pc"])){
	$dataBase = new DB;
	$alldata = "SELECT id,asset_number,tag,brand,ram,processor,laptop_serial,charger_serial_number,hard_disk_capacity,model,os,mouse_serial,bag_details,battery_keyboard_serial,remarks,du.user_name as used_by from device_pc dp inner join device_users du on (dp.used_by = du.device_user_id) where dp.used_by !=0";
	$data = $dataBase->getQuery($alldata);
	$temp_arr = $data->fetchall(PDO::FETCH_ASSOC);
	$res = array_map(function($val){return array_values($val);},$temp_arr);
	echo json_encode(array('data'=>$res));
}
if(isset($_POST["get_all_device_od"])){
	$dataBase = new DB;
	$alldata = "SELECT * FROM device_other";
	$data = $dataBase->getQuery($alldata);
	$temp_arr = $data->fetchall(PDO::FETCH_ASSOC);
	echo json_encode($temp_arr);
}
if(isset($_POST["updateid"])){
	$id = $_POST["updateid"];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "SELECT id,asset_number,tag,brand,ram,processor,laptop_serial,charger_serial_number,hard_disk_capacity,model,os,mouse_serial,bag_details,battery_keyboard_serial,remarks FROM device_pc WHERE id = {$id}";
	$qres=$pdo->query($stmt);
	$res = $qres->fetch(PDO::FETCH_ASSOC);
	echo json_encode($res);

}
if(isset($_POST["updateid_loan"])){
	$id = $_POST["updateid_loan"];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "SELECT * FROM device_pc WHERE id = {$id}";
	$qres=$pdo->query($stmt);
	$res = $qres->fetch(PDO::FETCH_ASSOC);
	echo json_encode($res);

}

if(isset($_POST["getRegisteredUsers"])){
	$sql="SELECT user_name FROM users WHERE verified= 0";
	$db = new DB;
	$data = $db->getQuery($sql);
	$arr = $data->fetchall(PDO::FETCH_ASSOC);
	echo json_encode($arr);
}

if(isset($_POST["updateid_od"])){
	$id = $_POST["updateid_od"];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "SELECT * FROM device_other WHERE id = {$id}";
	$qres=$pdo->query($stmt);
	$res = $qres->fetch(PDO::FETCH_ASSOC);
	echo json_encode($res);

}

if(isset($_POST["userapprovestatus"])){
	$status = $_POST["userapprovestatus"];
	$user = $_POST["user"];
	$response = "";
	if($status==1){
		$db = new DB;
		$db->grantPermission($status,$user);
		if($db){
			echo 1;
		}
		else{
			echo 0;
		}

		
	}
	elseif ($status==2) {
		$db = new DB;
		$db-> deleteUser($user);
		if($db){
			echo 1;
		}
		else{
			echo 0;
		}
	}
}


if(isset($_POST["getdata_updatepage"])){
	$response = array('data' => array());
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "SELECT id,asset_number,tag,brand,ram,processor,laptop_serial,charger_serial_number,hard_disk_capacity,model,os,mouse_serial,bag_details,battery_keyboard_serial,remarks from device_pc";
	foreach ($pdo->query($stmt) as $row) {
		$id = $row['id'];
		$editicon = '<span style="cursor:pointer; padding-right:10px;" onclick="getFieldsForUpdate('.$id.')">&#x2710;</span> <!--- <span style="cursor:pointer" onclick="deleteFieldsForUpdate('.$id.')">&#x2717;</span> --->';
		$response['data'][] = array(
			$row['id'],
			$row['asset_number'],
			$row['tag'],
			$row['brand'],
			$row['ram'],
			$row['processor'],
			$row['laptop_serial'],
			$row['charger_serial_number'],
			$row['hard_disk_capacity'],
			$row['model'],
			$row['os'],
			$row['mouse_serial'],
			$row['bag_details'],
			$row['battery_keyboard_serial'],
			'<span title="'.$row['remarks'].'">'.substr($row['remarks'],0,10).'</span>',
			$editicon
		);
	}
	echo json_encode($response);
}
if(isset($_POST["getdata_updatepage_loan"])){
	$response = array('data' => array());
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "SELECT id,asset_number,brand,laptop_serial,charger_serial_number,model, du.user_name as used_by from device_pc dp inner join device_users du on (dp.used_by = du.device_user_id) ";
	if(isset($_POST["onlyStock"])){
		$stmt .= "where dp.used_by = 0";
	}
	else{
		$stmt .= "where dp.used_by != 0";
	}
	foreach ($pdo->query($stmt) as $row) {
		$id = $row['id'];
		$editicon = '<span style="cursor:pointer;" onclick="getFieldsForUpdate_loan('.$id.')">&#x2692;';
		$response['data'][] = array(
			$row['id'],
			$row['asset_number'],
			$row['brand'],
			$row['laptop_serial'],
			$row['charger_serial_number'],
			$row['model'],
			$row['used_by'],
			$editicon
		);
	}
	echo json_encode($response);
}
if(isset($_POST["deleteid_od"])){
	$id = $_POST["deleteid_od"];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "DELETE FROM device_other WHERE id = {$id}";
	if($pdo->query($stmt)){
		echo 1;
	}
	else
	{
		echo 0;
	}
}

if(isset($_POST["getdata_updatepage_od"])){
	$response = array('data' => array());
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "SELECT * from device_other";
	foreach ($pdo->query($stmt) as $row) {
		$id = $row['id'];
		$editicon = '<span style="cursor:pointer; padding-right:10px;" onclick="getFieldsForUpdate_od('.$id.')">&#x2710;</span><span style="cursor:pointer" onclick="deleteFieldsForUpdate_od('.$id.')">&#x2717;</span>';
		$response['data'][] = array(
			$row['id'],
			$row['device_id'],
			$row['name'],
			$row['serial'],
			$row['brand'],
			$row['other_info'],
			$editicon
		);
	}
	echo json_encode($response);
}


if(isset($_POST["getdata_updatepage_od_loan"])){
	$response = array('data' => array());
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "SELECT * from device_other";
	foreach ($pdo->query($stmt) as $row) {
		$id = $row['id'];
		$editicon = '<span style="cursor:pointer; padding-right:10px;" onclick="getFieldsForUpdate_od_loan('.$id.')">&#x2692;';
		$response['data'][] = array(
			$row['id'],
			$row['device_id'],
			$row['name'],
			$row['serial'],
			$row['brand'],
			$row['used_by'],
			$editicon
		);
	}
	echo json_encode($response);
}




if(isset($_POST["deleteid"])){
	$id = $_POST["deleteid"];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "DELETE FROM device_pc WHERE id = {$id}";
	if($pdo->query($stmt)){
		echo 1;
	}
	else
	{
		echo 0;
	}
}

if(isset($_POST["updaterow"])){
	$row=[
	'tochange'=>$_POST["id_seq"],
	'asset_number'=>$_POST["asset_number"],
	'brand'=>$_POST["brand"],
	'tag'=>$_POST["tag"],
	'laptop_serial'=>$_POST["laptop_serial"],
	'ram'=>$_POST["ram"],
	'bag_details'=>$_POST["bag_details"],
	'remarks'=>$_POST["remarks"],
	'processor'=>$_POST["processor"],
	'mouse_serial'=>$_POST["mouse_serial"],
	'battery_keyboard_serial'=>$_POST["battery_keyboard_serial"],
	'charger_serial_number'=>$_POST["charger_serial_number"],
	'harddisk_capacity'=>$_POST["harddisk_capacity"],
	'model'=>$_POST["model"],
	'os'=>$_POST["os"]
];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "UPDATE device_pc SET asset_number=:asset_number, brand =:brand, tag=:tag , laptop_serial =:laptop_serial, processor =:processor, mouse_serial=:mouse_serial, bag_details =:bag_details, battery_keyboard_serial=:battery_keyboard_serial , ram =:ram, charger_serial_number =:charger_serial_number, hard_disk_capacity =:harddisk_capacity, model =:model, remarks =:remarks, os =:os WHERE id =:tochange;";
	$status = $pdo->prepare($stmt)->execute($row);
	if($status){
		echo 1;
	}
	else
	{
		echo 0;
	}

}

if(isset($_POST["updaterow_loan"])){
	$row=[
	'tochange'=>$_POST["id_seq"],
	'usby'=>$_POST["used_by"]
];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "UPDATE device_pc SET used_by=(select device_user_id from device_users where user_name =:usby) WHERE id =:tochange;";
	$status = $pdo->prepare($stmt)->execute($row);
	if($status){
		echo 1;
	}
	else
	{
		echo 0;
	}

}

if(isset($_POST["updaterow_"])){
	$row=[
	'tochange'=>$_POST["id_seq"],
	'd_id'=>$_POST["device_id"],
	'brand'=>$_POST["brand"],
	'd_serial'=>$_POST["device_serial"],
	'name'=>$_POST["name"],
	'othinf'=>$_POST["other_info"]
];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "UPDATE device_other SET device_id=:d_id, brand =:brand, serial =:d_serial, other_info =:othinf, name =:name WHERE id =:tochange;";
	try {
		$status = $pdo->prepare($stmt)->execute($row);
	} catch (Exception $e) {
		$status=0;
	}
	
	if($status){
		echo 1;
	}
	else
	{
		echo 0;
	}

}
if(isset($_POST["updaterow_loan_od"])){
	$row=[
	'tochange'=>$_POST["id_seq"],
	'usby'=>$_POST["used_by"]
];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "UPDATE device_other SET used_by=:usby WHERE id =:tochange;";
	try {
		$status = $pdo->prepare($stmt)->execute($row);
	} catch (Exception $e) {
		$status=0;
	}
	
	if($status){
		echo 1;
	}
	else
	{
		echo 0;
	}

}

function _checkIfPresent($paramVar){
	return (isset($_POST[$paramVar]) && !empty($_POST[$paramVar]) ) ;
}

function _attachInput($paramOriginalArr,$paramVar){
	if(_checkIfPresent($paramVar)){
		$paramOriginalArr[$paramVar] = $_POST[$paramVar];
	}
	return $paramOriginalArr;
}

function _addSql($paramSql,$paramVar){
	if(_checkIfPresent($paramVar)){
		$paramSql .= ', ' . $paramVar . '=:' . $paramVar;
	}
	return $paramSql;
}

if(isset($_POST["btn_submit_pc"])){
	try{
		$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
		$row = [
			'asset_number'=>$_POST["asset_number"],
			'brand_'=>$_POST["brand"],
			'tag'=>$_POST["tag"],
			'laptop_serial'=>$_POST["laptop_serial"],			
			'processor'=>$_POST["processor"],
			'ram'=>$_POST["ram"],
			'charger_serial_number'=>$_POST["charger_serial_number"],
			'hard_disk_capacity'=>$_POST["harddisk_capacity"],
			'model'=>$_POST["model"],
			'os'=>$_POST["os"],

		];

		$row = _attachInput($row,'mouse_serial');
		$row = _attachInput($row,'battery_keyboard_serial');
		$row = _attachInput($row,'bag_details');
		$row = _attachInput($row,'remarks');


		$sql="INSERT INTO device_pc SET asset_number=:asset_number, brand=:brand_, tag=:tag, laptop_serial=:laptop_serial,processor=:processor, ram=:ram, charger_serial_number=:charger_serial_number, hard_disk_capacity=:hard_disk_capacity, model=:model, os=:os";
		$sql = _addSql($sql,'mouse_serial');
		$sql = _addSql($sql,'battery_keyboard_serial');
		$sql = _addSql($sql,'bag_details');
		$sql = _addSql($sql,'remarks');
		$status = $pdo->prepare($sql)->execute($row);
	}
	catch (PDOException $e){
		$status =false;
		header("Location: users.php?id=add");
		$_SESSION['add_status'] = "There was an error inserting.. troubleshoot! <br> Most probably device id was not unique!";
	}
	if ($status) {
		$lastId = $pdo->lastInsertId();
		header("Location: users.php?id=add");
		$_SESSION['add_status'] = "Success! Insert Success";
	}
	else {

		$_SESSION['add_status'] = "There was an error inserting.. troubleshoot!";
	}
}


if(isset($_POST["btn_submit_device"])){
	try
	{
		$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
		$row=[
			'dev_id'=>$_POST["device_id"],
			'brand_'=>$_POST["brand"],
			'serial_'=>$_POST["device_serial"],
			'name_'=>$_POST["name"],
			'otherInf'=>$_POST["other_info"],
		];


		$sqlstmt="INSERT INTO device_other SET device_id=:dev_id, name=:name_, serial=:serial_, brand=:brand_, other_info=:otherInf";
		$status = $pdo->prepare($sqlstmt)->execute($row);
	}
	catch (PDOException $e){
		$status =false;
		header("Location: users.php?id=add");
		$_SESSION['add_status'] = "There was an error inserting.. troubleshoot! <br> Most probably device id was not unique!";
	}
	if ($status) {
		$lastId = $pdo->lastInsertId();
		header("Location: users.php?id=add");
		$_SESSION['add_status'] = "Success! Insert Success";
	}
	else {
		$_SESSION['add_status'] = "There was an error inserting.. troubleshoot!";
	}
}

/*
Added for further requiremnets.
*/
if(isset($_POST['getDeviceUsers'])){
	$response = array('data' => array());
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$stmt = "SELECT du.device_user_id AS 'Serial',du.user_name AS 'Users', du.email_id  AS 'email', t.teamName AS team ,br.branch_name AS 'Branch' FROM device_users du INNER JOIN branch br ON du.r_branch_id = br.branch_id INNER JOIN team t ON (du.r_team_id = t.team_no) WHERE device_user_id != 0";
	foreach ($pdo->query($stmt) as $row) {
		$userName =$row['Users'];
		$editicon = '<span style="cursor:pointer; padding-right:10px;" onclick="mapUser('.htmlspecialchars('"',ENT_QUOTES).$userName.htmlspecialchars('"',ENT_QUOTES).')">&#x2710;</span>';
		$response['data'][] = array(
			$row['Serial'],
			$userName,
			$row['email'],
			$row['team'],
			$row['Branch'],
			$editicon
		);
	}
	echo json_encode($response);
}

if(isset($_POST['mapUser'])){
	$status = 0;
	try{
		$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
		$data =["user" => $_POST['mapUser'],"branch"=>$_POST['branch']];
		$sql ="UPDATE device_users SET r_branch_id = (SELECT branch_id from branch where branch_name=:branch ) where user_name=:user";
		$status = $pdo->prepare($sql)->execute($data);
	}
	catch (PDOException $e){
		echo "0";
		die();
	}
	echo $status;	
}

if(isset($_POST['nDeviceUser'])){
	$status = 0;
	try{
		$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
		$data =["user" => $_POST['nDeviceUser']];
		$sql ="INSERT INTO device_users SET device_user_id=0, user_name=:user, r_branch_id=0";
		$status = $pdo->prepare($sql)->execute($data);
	}
	catch (PDOException $e){
		echo "0";
		die();
	}
	echo $status;	
}

if(isset($_GET['userL'])){
	$userQuery = $_GET['userL'];
	$pdo = new PDO("mysql:host=$hostname;dbname=$dbName", $username, $password, $options);
	$sql ="SELECT user_name from device_users where user_name LIKE '%{$userQuery}%'";
	$qres=$pdo->query($sql);
	$res = $qres->fetchall(PDO::FETCH_ASSOC);
	echo json_encode(array_column($res,'user_name'));
}
?>
