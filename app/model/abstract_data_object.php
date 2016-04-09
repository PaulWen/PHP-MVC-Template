<?php
/**
 * The main model which provides a connection to the database.
 * Each DataObject is required to extend from this class.
 */
abstract class abstract_data_object {
	
//////////////////////////////Konstanten//////////////////////////////	
	const HOST_IP='localhost';
	const PORT='3306';
	const USERNAME='root';
	const PASSWORD ='';
	
	const DATABASE ='lecture_poll_web_app';
	const TABLE_POLL ='poll';
	const TABLE_STUDENT_VOTING ='student_voting';
	const COLUMN_STUDENT_ID ='student_id';
	const COLUMN_NAME ='name';
	const COLUMN_TEACHER_POLL_CODE ='teacher_poll_code';
	const COLUMN_STUDENT_POLL_CODE ='student_poll_code';
	const COLUMN_DATETIME ='datetime';
	const COLUMN_RATING ='rating';

	const ADMIN_USERTYPE = 2;
	const USER_USERTYPE = 1;
	const UNKOWN_USERTYPE = 0;
	
//////////////////////////////Datenfelder//////////////////////////////
	protected static $databaseConnection;
	
	protected function hasConnection() {
		if ($this->databaseConnection) {
			return true;
		} else {
			$this->databaseConnection = mysqli_connect(self::HOST_IP, self::USERNAME, self::PASSWORD, self::DATABASE, self::PORT);
				
			$sql = "SET character_set_results = 'utf8',
			  character_set_client = 'utf8',
			  character_set_connection = 'utf8',
			  character_set_database = 'utf8',
			  character_set_server = 'utf8'";
			mysqli_query($this->databaseConnection, $sql);
			if (!$this->databaseConnection) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	protected function getSingleValue($sql) {
		if (!$this->hasConnection()) return;
		
		$query = mysqli_query($this->databaseConnection, $sql);
		$data = mysqli_fetch_array($query);
		$result = array_pop($data);
		
		return $result;
	}
	
	protected function getMultipleRecords($sql) {
		if (!$this->hasConnection()) return;
		
		$query = mysqli_query($this->databaseConnection, $sql);
		$record = mysqli_fetch_assoc($query);
		
		$i = 0;
		$result = array();
		while($record = mysqli_fetch_assoc($query)) {
			$result[$i] = $record;
			$i++;
		}
		
		return $result;
	}
	
	protected function executeSqlQueryNoReturn() {
		if (!$this->hasConnection()) return;
		
		mysqli_query($this->databaseConnection, $sql);
	}
	
	protected static function randomCode($length){
		$charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$result = '';
	
		for ($i = 0; $i < $length; $i++) {
			$randomNumber = floor(mt_rand() % strlen($charset));
			$result = $result . substr($charset, $randomNumber, 1);
		}
	
		return $result;
	}
}
?>