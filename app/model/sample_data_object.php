<?php
class sample_data_object extends abstract_data_object {
	private $studentId;
	private $datetime;
	
	/**
	 * Der Konstruktor der Klasse "SubstituteEntryData".
	 *
	 * @param studentId => ID of the student who rated
	 * @param datetime => time of the rating
	 */
	function __construct($studentId, $studentPollCode, $datetime, $rating) {
		$this->studentId = $studentId;
		$this->studentPollCode = $studentPollCode;
		$this->datetime = $datetime;
		$this->rating = $rating;
	}
	
	
	function getStudentId() {
		return $this->studentId;
	}

	function getDatetime() {
		return $this->datetime;
	}

	function getStudentPollCode() {
		$sql = "SELECT `{$this->databaseData['column_studentId']}` FROM `{$this->databaseData['table_studentVoting']}`
		WHERE `{$this->databaseData['column_studentId']}` = '$studentId'";
		
		return $this->getSingleValue($sql);
	}
}