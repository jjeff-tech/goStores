<?php
// URL Interface
class Enominterface {
	var $PostString;
	var $RawData;
	var $Values;

	function NewRequest() {
		// Clear out all previous values
		$this->PostString = "";
		$this->RawData = "";
		$this->Values = "";
	}

	function AddError( $error ) {
		// Add an error to the result list
		$this->Values[ "ErrCount" ] = "1";
		$this->Values[ "Err1" ] = $error;
	}

	function ParseResponse( $buffer ) {
		// Parse the string into lines
		$Lines = explode( "\r", $buffer );

		// Get # of lines
		$NumLines = count( $Lines );

		// Skip past header
		$i = 0;
		while ( trim( $Lines[ $i ] ) != "" ) {
				$i = $i + 1;
		}

		$StartLine = $i;

		// Parse lines
		$GotValues = 0;
		for ( $i = $StartLine; $i < $NumLines; $i++ ) {
				// Is this line a comment?
				if ( substr( $Lines[ $i ], 1, 1 ) != ";" ) {
						// It is not, parse it
						$Result = explode( "=", $Lines[ $i ] );

						// Make sure we got 2 strings
						if ( count( $Result ) >= 2 ) {
								// Trim whitespace and add values
								$name = trim( $Result[0] );
								$value = trim( $Result[1] );
								$this->Values[ $name ] = $value;

								// Was it an ErrCount value?
								if ( $name == "ErrCount" ) {
								  // Remember this!
								  $GotValues = 1;
						  }
						}
				}
		}

		// Check if we got values
		if ( $GotValues == 0 ) {
				// We didn't, so add an error message
				$this->AddError( "No values returned from server" );
		}
	}

	function AddParam( $Name, $Value ) {
		// URL encode the value and add to PostString
		$this->PostString = $this->PostString . $Name . "=" . urlencode( $Value ) . "&";
		//echo $this->PostString ."<br>";
	}
	function AddParam2($constant_poststring, $Name, $Value ) {
		// URL encode the value and add to PostString
		$this->PostString = $constant_poststring . $Name . "=" . urlencode( $Value ) . "&";
		//echo $this->PostString ."<br>";
	}
	function AddParam1( $constant_poststring,$Name,$Value,$Name1,$Value1) {
		// URL encode the value and add to PostString
		$this->PostString="";
		$this->PostString = $constant_poststring. $Name . "=" . urlencode( $Value ) . "&".$Name1 . "=" . urlencode( $Value1 ) . "&";
		//echo $this->PostString ."<br>";
	}
	
	function Current_poststring() {
	   return $this->PostString;
	}

	function DoTransaction($enommode) {

//	   global $enommode;

	   $Values = "";

		// Create connection to server
		// Test = resellertest.enom.com
		// Live = reseller.enom.com
		if ($enommode == "N")
			$host = 'reseller.enom.com';
		else
			$host = 'resellertest.enom.com';

		//Currently set to use SSL - Do not change unless you want to use non SSL communication
		$port = 443;
		$address = gethostbyname( $host );
		$socket = @fsockopen("ssl://".$host,$port,$errno,$errstr);
		//$socket = @fsockopen($host,$port);
		if ( !$socket ) {
				//$this->AddError( "socket() failed: " . strerror( $socket ) );
				$this->AddError( "socket() failed: " . $errstr );
		} else {
				// Send GET command with our parameters
				$in = "GET /interface.asp?" . $this->PostString . "HTTP/1.0\r\n\r\n";
				$out = '';

				fputs($socket,$in);

				// Read response
				while ( $out=@fread ($socket,2048) ) {
						// Save in rawdata
						$this->RawData .= $out;
				}
				// Close the socket
				fclose( $socket );

				// Parse the output for name=value pairs
				$this->ParseResponse( $this->RawData );
		}
	}
}
?>