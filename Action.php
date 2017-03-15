<?php

	include("pdoInc.php");
	session_start();
	date_default_timezone_set("Asia/Taipei");
	
	switch($_POST['action']){
		
		// record action
		case 0:

			$user_index = $_POST['user_index'];
			$action = $_POST['action'];
			$information = $_POST['information'];
			$video_time = $_POST['video_time'];

			$sql = 'INSERT INTO log(user_index, action, information, video_time)
					VALUES (:user_index, :action, :information, :video_time)';
			$stmt = $dbh->prepare($sql);
			
			$stmt->bindParam(':user_index', $user_index, PDO::PARAM_INT);
			$stmt->bindParam(':action', $action, PDO::PARAM_STR);
			$stmt->bindParam(':information', $information, PDO::PARAM_STR);
			$stmt->bindParam(':video_time', $video_time, PDO::PARAM_INT);
			$stmt->execute();

			header('Content-Type: application/json');
			echo json_encode($array);

			break;

		// get user
		case 1:
			$sth = $dbh->prepare('SELECT * FROM user');
			$sth->execute();
			$i = 1;

			while($row=$sth->fetch(PDO::FETCH_ASSOC)){
				if($i==$_POST['user_index']){
					$array[0]['user_account'] = $row['account'];
					$array[0]['condition'] = $row['condition'];
					$array[0]['user_role'] = $row['role'];
					$array[0]['channel'] = $row['channel'];
					$array[0]['friend_index'] = $row['friend'];
					$friend = $row['friend'];
					break;
				}
				$i++;
			}
			
			$sth = $dbh->prepare('SELECT * FROM user');
			$sth->execute();
			$i = 1;
			while($row=$sth->fetch(PDO::FETCH_ASSOC)){
				if($i==$friend){
					$array[0]['friend_account'] = $row['account'];
					break;
				}
				$i++;
			}
			
			header('Content-Type: application/json');
			echo json_encode($array);
			
			break;


		case 2:

			$sth = $dbh->prepare('SELECT * FROM channel WHERE number = :number_bind');
			$sth->bindParam(':number_bind', $_POST['channel_number'], PDO::PARAM_INT);
			$sth->execute();
			while($row=$sth->fetch(PDO::FETCH_ASSOC)){
				$array[0]['index'] = $row['index'];
			}
			
			$channel_index = $array[0]['index'];

			$sth = $dbh->prepare('SELECT * FROM clip WHERE channel_index = :channel_index_bind');
			$sth->bindParam(':channel_index_bind', $channel_index, PDO::PARAM_INT);
			$sth->execute();
			$i = 0;
			while($row=$sth->fetch(PDO::FETCH_ASSOC)){
				$array[$i]['index'] = $row['index'];
				$array[$i]['start'] = $row['start'];
				$array[$i]['end'] = $row['end'];
				$array[$i]['user_index'] = $row['user_index'];
				$i++;
			}
			
			header('Content-Type: application/json');
			echo json_encode($array);
			
			break;

		// record message
		case 3:
			
			$content = $_POST['content'];
			$user_index = $_POST['user_index'];
			$clip_index = $_POST['clip_index'];
			$snapshot = $_POST['snapshot'];
			$time = date('H:i:s', time());

			$sql = 'INSERT INTO message(content, user_index, clip_index, snapshot, time)
					VALUES (:content, :user_index, :clip_index, :snapshot, :time)';
			$stmt = $dbh->prepare($sql);
			
			$stmt->bindParam(':content', $content, PDO::PARAM_STR);
			$stmt->bindParam(':user_index', $user_index, PDO::PARAM_INT);
			$stmt->bindParam(':clip_index', $clip_index, PDO::PARAM_INT);
			$stmt->bindParam(':snapshot', $snapshot, PDO::PARAM_STR);
			$stmt->bindParam(':time', $time, PDO::PARAM_STR);
			
			$stmt->execute();
			$lastIndex = $dbh->lastInsertId();
			
			$array[0]['time'] = $time;
			$array[0]['index'] = $lastIndex;

			header('Content-Type: application/json');
			echo json_encode($array);

			break;

		// load message
		case 4:

			$sth = $dbh->prepare('SELECT * FROM message WHERE user_index = :user1_bind OR user_index = :user2_bind');
			$sth->bindParam(':user1_bind', $_POST['user1'], PDO::PARAM_INT);
			$sth->bindParam(':user2_bind', $_POST['user2'], PDO::PARAM_INT);
			$sth->execute();
			$i = 0;

			while($row=$sth->fetch(PDO::FETCH_ASSOC)){
				$array[$i]['index'] = $row['index'];
				$array[$i]['content'] = $row['content'];
				$array[$i]['user_index'] = $row['user_index'];
				$array[$i]['clip_index'] = $row['clip_index'];
				$array[$i]['snapshot'] = $row['snapshot'];
				$array[$i]['time'] = $row['time'];
				$i++;
			}

			$array[0]['amount'] = $i;
			
			header('Content-Type: application/json');
			echo json_encode($array);
			
			break;

		// link message
		case 5:

			$sth = $dbh->prepare('SELECT * FROM message');
			$sth->execute();
			$i = 1;

			while($row=$sth->fetch(PDO::FETCH_ASSOC)){
				if($i==$_POST['index']){
					$array[0]['clip_index'] = $row['clip_index'];
					$array[0]['snapshot'] = $row['snapshot'];
				}
				$i++;
			}

			$array[0]['amount'] = $i;
			
			header('Content-Type: application/json');
			echo json_encode($array);

			break;

		// record clip
		case 6:

			$start = $_POST['start'];
			$end = $_POST['end'];
			$user_index = $_POST['user_index'];

			$sql = 'INSERT INTO clip(start, end, user_index)
					VALUES (:start, :end, :user_index)';
			$stmt = $dbh->prepare($sql);
			
			$stmt->bindParam(':start', $start, PDO::PARAM_INT);
			$stmt->bindParam(':end', $end, PDO::PARAM_INT);
			$stmt->bindParam(':user_index', $user_index, PDO::PARAM_INT);
			$stmt->execute();
			$lastIndex = $dbh->lastInsertId();

			$array[0]['clip_index'] = $lastIndex;

			header('Content-Type: application/json');
			echo json_encode($array);
			
			
			break;

		// get clip
		case 7:

			$sth = $dbh->prepare('SELECT * FROM clip');
			$sth->execute();
			$i = 1;
			while($row=$sth->fetch(PDO::FETCH_ASSOC)){
				if($i==$_POST['clip_index']){
					$array[0]['start'] = $row['start'];
					$array[0]['end'] = $row['end'];
				}
				$i++;
			}
			
			header('Content-Type: application/json');
			echo json_encode($array);
			
			break;

		// no use
		case 8:

			$sth = $dbh->prepare('SELECT * FROM message WHERE clip_index = :clip_index_bind');
			$sth->bindParam(':clip_index_bind', $_POST['clip_index'], PDO::PARAM_INT);
			$sth->execute();
			$i = 0;
			while($row=$sth->fetch(PDO::FETCH_ASSOC)){
				$array[$i]['content'] = $row['content'];
				$array[$i]['user_index'] = $row['user_index'];
				$i++;
			}
			
			header('Content-Type: application/json');
			echo json_encode($array);
			
			break;
	}
?>