<?php
use webtools\controller;
use webtools\libs\Helper;

class TextDbModel {
	use DeleteDatabase;

	function deleteDatabase( $dbs ) {
		$this->runDeleteDatabase( $dbs );
	}
}

trait DeleteDatabase {
	function runDeleteDatabase( $dbs ) {
		$db = new Database();
		$conn = $db->connectDatabase();
		foreach ( $dbs as $dbName ) {
			$db->deleteDatabase( $conn, $dbName );
		}
	}
}