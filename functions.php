<?php

  include "config.php";

  function getCode() {
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$string = '';
		$max = strlen($characters) - 1;
		for ($i = 0; $i < 30; $i++) {
			$string .= $characters[mt_rand(0, $max)];
		}
		return $string;
	}


  function getItemWhere($table, $column, $clue) {
		global $conn;
		$variable = array();
		$sql = "SELECT * FROM `$table`  WHERE `$column` = '$clue'";
		$query = mysqli_query($conn, $sql);

		while( $row = mysqli_fetch_assoc( $query ) ){

			array_push($variable, $row);

		}

		return $variable[0];

	}

	function getMultipleWhere($table, $column, $clue) {
		global $conn;
		$variable = array();
		$sql = "SELECT * FROM `$table` WHERE `$column` = '$clue'";
		$query = mysqli_query($conn, $sql);
		while( $row = mysqli_fetch_assoc( $query ) ){
		  array_push($variable, $row);
		}
		return $variable;
	  }


	function getItemsFromTable($table, $direction="asc") {
		global $conn;
		$private_conversations = array();
		if($direction == "asc") {
			$sql = "SELECT * FROM `$table` ORDER BY `id` ASC";
		} else {
			$sql = "SELECT * FROM `$table` ORDER BY `id` DESC";
		}
		$query = mysqli_query($conn, $sql);

		while( $row = mysqli_fetch_assoc( $query ) ){

			array_push($private_conversations, $row);

		}

		return $private_conversations;

	}


	function insertUser($username, $password) {
		global $conn;
		$code = getCode();
		$sql = "INSERT INTO `users` (`serial_no`, `username`, `password`) VALUES ('$code', '$username', '$password')";
		$query = mysqli_query($conn, $sql);
	}


	function insertPost($poster, $content) {
		global $conn;
		$code = getCode();
		$sql = "INSERT INTO `posts` (`serial_no`, `content`, `poster`) VALUES ('$code', '$content', '$poster')";
		$query = mysqli_query($conn, $sql);
	}


	function insertComment($id, $content, $poster) {
		global $conn;
		$code = getCode();
		$sql = "INSERT INTO `comments` (`serial_no`, `post`, `content`, `poster`) VALUES ('$code', '$id', '$content', '$poster')";
		$query = mysqli_query($conn, $sql);
	}


	function followUser($user, $target) {
		global $conn;
		$req_user = getItemWhere("users", "username", $user);
		$target_user = getItemWhere("users", "serial_no", $target);
		$id_1 = $target_user['serial_no'];
		$updatedarr_1 = $target_user['followers'].$req_user['serial_no'].",";
		$sql = "UPDATE `users` SET `followers` = '$updatedarr_1' WHERE `serial_no` = '$id_1'";
		$query = mysqli_query($conn, $sql);
		$id_2 = $req_user['serial_no'];
		$updatedarr_2 = $req_user['following'].$target_user['serial_no'].",";
		$sql = "UPDATE `users` SET `following` = '$updatedarr_2' WHERE `serial_no` = '$id_2'";
		$query = mysqli_query($conn, $sql);
	}


	function unfollowUser($user, $target) {
		global $conn;
		$req_user = getItemWhere("users", "username", $user);
		$target_user = getItemWhere("users", "serial_no", $target);
		$id_1 = $target_user['serial_no'];
		$updatedarr_1 = str_replace($req_user['serial_no'].",", "", $target_user['followers']);
		$sql = "UPDATE `users` SET `followers` = '$updatedarr_1' WHERE `serial_no` = '$id_1'";
		$query = mysqli_query($conn, $sql);
		$id_2 = $req_user['serial_no'];
		$updatedarr_2 = str_replace($target_user['serial_no'].",", "", $req_user['following']);
		$sql = "UPDATE `users` SET `following` = '$updatedarr_2' WHERE `serial_no` = '$id_2'";
		$query = mysqli_query($conn, $sql);
	}



?>
