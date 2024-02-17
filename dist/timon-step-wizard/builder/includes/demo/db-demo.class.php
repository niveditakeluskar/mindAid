<?php
define('DB_SERVER','mysql5016.smarterasp.net');
define('DB_USER','9b83d7_email');
define('DB_PASS' ,'123456email');
define('DB_NAME', 'db_9b83d7_email');

class DbDemo{

	public function connect() {
		// Create connection
		$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		return $conn;
	}

	public function select($ip) {
		try {
			$sql="SELECT use_count FROM `form_builder` WHERE `ip`='$ip'";
			$conn=$this->connect();
			$result = mysqli_query($conn, $sql);
			$newArr=array();

			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					$newArr[]=$row;
				}
				mysqli_close($conn);
			} else {
				return -3;
			}
			return $newArr;
		} catch (Exception $e) {
		  	file_put_contents('error.txt', "\xEF\xBB\xBF".$e);
				return -1;
		}
	}

	public function insert($ip) {
    try {
      $conn=$this->connect();
			$stmt = $conn->prepare("INSERT INTO `form_builder` (`ip`,`use_count`) VALUES (?, 0)");
			$stmt->bind_param("s", $ip);

	  	$stmt->execute();
			$stmt->close();
			$conn->close();
      return true;

    } catch (Exception $e) {
			file_put_contents('error.txt', "\xEF\xBB\xBF".$e);
      return false;
    }
  }

	public function update($ip) {
    try {
      $conn=$this->connect();
			$stmt = $conn->prepare("UPDATE `form_builder` SET `use_count`=use_count+1 WHERE `ip`=?");
			$stmt->bind_param("s", $ip);

	  	$stmt->execute();
			$stmt->close();
			$conn->close();
      return true;

    } catch (Exception $e) {
			file_put_contents('error.txt', "\xEF\xBB\xBF".$e);
      return false;
    }
  }

}

?>
