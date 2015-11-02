<?php
 class CustomException extends Exception {
 	function handle() {
 		$this->displayMessage();
 		$this->log();
 		//$this->redirect();
 		exit(1);
 	}

 	function displayMessage() {
 		echo 'Error: ' . $this->getMessage();
 		echo '<br>';
 		echo 'File: ' . $this->getFile();
 		echo ' ';
 		echo 'Line: ' . $this->getLine();
 	}

 	function log() {
 		$message  = $this->getMessage();
 		$message .= ' File: ' . $this->getFile();
 		$message .= ' Line: ' . $this->getLine();
 		$message .= "\n";
 		$destination = BASE_PATH . 'custom-exception-error.log';
 		error_log( $message, 3, $destination );
 	}

 	function redirect() {
 		$url = HOME_URL . 'error';
 		header('Location: ' . $url );
 	} 	
}
