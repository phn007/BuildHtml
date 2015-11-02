<?php
Class FTPClient {
	private $connId;
	private $loginOk = false;
	private $messageArray = array();

	private $host;
	private $user;
	private $pass;

	function __set( $name, $value ) {
      	$this->{$name} = $value;
 	}

   	function __get( $name ) {
      	return $this->{$name};
   	}

    public function __construct() { }

    private function logMessage($message) {
    	$this->messageArray[] = $message;
	}

	public function getMessages() {
    	return $this->messageArray;
	}

	public function connect ($isPassive = false) {
	    $this->connId = ftp_connect($this->host); // *** Set up basic connection
	    $loginResult = ftp_login($this->connId, $this->user, $this->pass); // *** Login with username and password
	    ftp_pasv($this->connId, $isPassive); // *** Sets passive mode on/off (default off)

	    if ((!$this->connId) || (!$loginResult)) { // *** Check connection
	        $this->logMessage('FTP connection has failed!');
	        $this->logMessage('Attempted to connect to ' . $this->host . ' for user ' . $this->user, true);
	        return false;
	    } else {
	        $this->logMessage('Connected to ' . $this->host . ', for user ' . $this->user);
	        $this->loginOk = true;
	        return true;
	    }
	}

	public function makeDir($directory) {
	    // *** If creating a directory is successful...
	    if (@ftp_mkdir($this->connId, $directory)) {
	        $this->logMessage('Directory "' . $directory . '" created successfully');
	        return true;
	    } else {
	        // *** ...Else, FAIL.
	        $this->logMessage('Failed creating directory "' . $directory . '"');
	        return false;
	    }
	}

	public function uploadFile ($fileFrom, $fileTo) {
	    // *** Set the transfer mode
	    $asciiArray = array('txt', 'csv');
	    //$extension = end(explode('.', $fileFrom));
	    $arr = explode( '.', $fileFrom );
	    $extension = end( $arr );
	    if (in_array($extension, $asciiArray)) {
	        $mode = FTP_ASCII;     
	    } else {
	        $mode = FTP_BINARY;
	    }
	 
	    // *** Upload the file
	    $upload = ftp_put($this->connId, $fileTo, $fileFrom, $mode);
	 
	    // *** Check upload status
	    if (!$upload) {
            $this->logMessage('FTP upload has failed!');
            return false;
 
        } else {
            $this->logMessage('Uploaded "' . $fileFrom . '" as "' . $fileTo);
            return true;
        }
	}

	public function changeDir($directory) {
	    if (ftp_chdir($this->connId, $directory)) {
	        $this->logMessage('Current directory is now: ' . ftp_pwd($this->connId));
	        return true;
	    } else {
	        $this->logMessage('Couldn\'t change directory');
	        return false;
	    }
	}

	public function getDirListing($directory = '.') {
	    $contents = ftp_nlist($this->connId, $directory);
	    $exclude = array( '.', '..' );
	    foreach ( $contents as $file ) {
	    	if (!in_array( $file, $exclude ) )$files[] = $file;
	    }
	    return $files;
	}

	public function downloadFile ($fileFrom, $fileTo) {
	    // *** Set the transfer mode
	    $asciiArray = array('txt', 'csv');
	    $extension = end(explode('.', $fileFrom));
	    if (in_array($extension, $asciiArray)) {
	        $mode = FTP_ASCII;     
	    } else {
	        $mode = FTP_BINARY;
	    }
	 
	    // try to download $remote_file and save it to $handle
	    if (ftp_get($this->connId, $fileTo, $fileFrom, $mode, 0)) {
	 		$this->logMessage(' file "' . $fileTo . '" successfully downloaded');
	        return true;
	    } else {
	 		$this->logMessage('There was an error downloading file "' . $fileFrom . '" to "' . $fileTo . '"');
	        return false;
	    }
	}

	public function __deconstruct() {
	    if ($this->connId) {
	        ftp_close($this->connId);
	    }
	}
}