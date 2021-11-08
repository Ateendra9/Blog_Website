<?php
//declaring variables to prevent errors
$fname = ""; //first name
$lname = ""; //last name
$em = ""; //email
$em2 = ""; //email2
$password = ""; //password
$password2 = ""; //password 2
$date = ""; //Sign up date
$error_array = array(); // holds error message

if(isset($_POST['register_button'])){
	//Reg form values
	//first name
	$fname = strip_tags($_POST['reg_fname']); //remove html tags
	$fname = str_replace(' ', '', $fname); // remove spaces
	$fname = ucfirst(strtolower($fname)); // uppercase
	$_SESSION['reg_fname'] = $fname; //stores first name in session variable

	//last name
	$lname = strip_tags($_POST['reg_lname']); //remove html tags
	$lname = str_replace(' ', '', $lname); // remove spaces
	$lname = ucfirst(strtolower($lname)); // uppercase
	$_SESSION['reg_lname'] = $lname; //stores last name in session variable

	//email
	$em = strip_tags($_POST['reg_email']); //remove html tags
	$em = str_replace(' ', '', $em); // remove spaces
	$em = ucfirst(strtolower($em)); // uppercase
	$_SESSION['reg_email'] = $em; //stores email in session variable

	//email 2
	$em2 = strip_tags($_POST['reg_email2']); //remove html tags
	$em2 = str_replace(' ', '', $em2); // remove spaces
	$em2 = ucfirst(strtolower($em2)); // uppercase
	$_SESSION['reg_email2'] = $em2; //stores email in session variable

	//password
	$password = strip_tags($_POST['reg_password']); //remove html tags
	
	//password 2
	$password2 = strip_tags($_POST['reg_password2']); //remove html tags

	$date = date("Y-m-d");
	
	if($em == $em2){
		//valid email check
		if(filter_var($em, FILTER_VALIDATE_EMAIL)){
			$em = filter_var($em, FILTER_VALIDATE_EMAIL);
			$e_check = mysqli_query($con , "SELECT email FROM users WHERE email='$em'"); //check if email already exists
			//count number of rows returned
			$num_rows = mysqli_num_rows($e_check);
			if($num_rows > 0){
				array_push($error_array,"Email already in use<br>");
			}
		}

		else{
			array_push($error_array, "Invalid Email Format<br>");
		}


	}
	else{
		array_push($error_array, "Emails don't match<br>");
	}

	if(strlen($fname)> 25 || strlen($fname)<2){
		array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
	}
	if(strlen($lname)> 25 || strlen($lname)<2){
		array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
	}
	if($password != $password2){
		array_push($error_array, "Your passwords do not match<br>");
	}
	else{
		if(preg_match('/[^A-Za-z0-9]/', $password)){
			array_push($error_array, "Your Password can only contain English characters or numbers<br>");
		}
	}
	if(strlen($password > 30 || strlen($password)<5)){
		array_push($error_array, "Your Password must be between 5 and 30 character<br>");
	}


	if(empty($error_array)) {
		$password = md5($password); //Encrypt Password before sending to the database
		//Generating username by concatenating fname and lname
		$username = strtolower($fname . "_" . $lname);
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");


		$i = 0;
		//if username exists add number to username
		while (mysqli_num_rows($check_username_query) != 0) {
			$i++; //add 1 to i
			$username = $username . "_" . $i;
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
		}

		//profile pic assign
		$rand = rand(1,2); // random number btw 1 and 2
		if($rand == 1)
			$profile_pic = "assets/images/profile_pics/defaults/head_belize_hole.png";
	    else if($rand == 2)
	    	$profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";


	    $query = mysqli_query($con, "INSERT INTO users VALUES ('','$fname','$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");
	    array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and Login. </span><br>");
	    //clear session variables
	    $_SESSION['reg_fname'] = "";
	    $_SESSION['reg_lname'] = "";
	    $_SESSION['reg_email'] = "";
	    $_SESSION['reg_email2'] = "";
	
	}


}
?>