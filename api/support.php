<?php
	// prevent users from opening the file without api
	if(empty($_POST)){
		header('location: /pages/nopermission');
		exit();
	}
	
	// DB LINK
	try {
		$DB_HOST = "localhost";
		$DB_NAME = "siloprobebv_com_1";
		$DB_USER = "siloprobebv_01";
		$DB_PASS = "hzcR8ksCHP";

		$conn = new PDO("mysql:host=".$DB_HOST.";dbname=".$DB_NAME, $DB_USER, $DB_PASS);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo 'connection succeeded';
	}catch (PDOException $e) {
		echo "DB Connection failed... ";
	}


	// check if valid
	$ticket_id = preg_replace('/[^0-9]/',"",$_POST["ticket_id"]);
	$session_id = preg_replace('/[^a-zA-Z0-9]/',"",$_POST["session_id"]);

	$stmt = $conn->prepare("SELECT * FROM tickets WHERE ticket_id = ".$ticket_id." AND session_id = '".$_POST["session_id"]."'");
	if($stmt->execute()){ 
		$results = $stmt->fetchAll();
		$rows = count($results);

		if($rows != 1){
			echo 'This ticket is not valid';
		}
		
	}else{
		echo 'query failed';
	}


	if($_GET["type"] == 1){
		$data = array();
		$data["messages"] = array();
		
		$getTickets = $conn->prepare("SELECT *, m.user_id FROM ticket_messages tm LEFT JOIN members m ON tm.user_id = m.user_id WHERE ticket_id = ".$ticket_id);
		if($getTickets->execute()){ 
			while($item = $getTickets->fetch()){
				if($item["role"] == 3){
					$item["fullname"] = "Internal Support Team";
				}
				array_push($data["messages"], $item);
			}
			
		}else{
			echo "Getting ticket messages failed";
		}
		

		$role = str_replace("8800707566","",$_POST["api_id"]);
		
		if($role == 3){
			$stmt = $conn->prepare("SELECT *, m.user_id FROM tickets t JOIN ticket_status ts ON t.status = ts.status_id JOIN members m ON t.user_id = m.user_id WHERE ticket_id = ".$ticket_id);
		}else{
			$stmt = $conn->prepare("SELECT *, m.user_id FROM tickets t JOIN ticket_status ts ON t.status = ts.status_id JOIN members m ON t.support_id = m.user_id WHERE ticket_id = ".$ticket_id);
		}


        if($stmt->execute()){
            $ticket = $stmt->fetchAll();
        }
		
        $data["ticket"] = $ticket[0];
		
		echo (json_encode($data));
	}	
	
	if($_GET["type"] == 2){
		$role = str_replace("6932411194","",$_POST["api_id"]);
		
		if($role == 3){
			$stmt = $conn->prepare("UPDATE tickets SET support_online = 0 WHERE ticket_id = ".$ticket_id);
		}else{
			$stmt = $conn->prepare("UPDATE tickets SET user_online = 0 WHERE ticket_id = ".$ticket_id);
		}

		$stmt->execute();
	}
	
	if($_GET["type"] == 3){
		$role = str_replace("4609701837","",$_POST["api_id"]);
		
		if($role == 3){
			$stmt = $conn->prepare("UPDATE tickets SET support_online = 1 WHERE ticket_id = ".$ticket_id);
		}else{
			$stmt = $conn->prepare("UPDATE tickets SET user_online = 1 WHERE ticket_id = ".$ticket_id);
		}

		$stmt->execute();
	}


	

	