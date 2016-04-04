<?php
class user_data_object extends abstract_data_object {
	private $userId;
	
	/**
	 * Der Konstruktor der Klasse "SubstituteEntryData".
	 *
	 * @param userId => ID of the user
	 */
	function __construct($userId) {
		$this->userId = $userId;
	}
	
	function getUserId() {
		return $this->userId;
	}

	function getUserType() {
		$sql = "SELECT `{$this->databaseData['column_studentId']}` FROM `{$this->databaseData['table_studentVoting']}`
		WHERE `{$this->databaseData['column_studentId']}` = '$studentId'";
		
		return $this->getSingleValue($sql);
	}
}