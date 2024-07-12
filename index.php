<?php
    session_start();
    $error = "";
    $servername = "localhost";
    $username = "root";
    $db_name = "secret-diary";
    $password = "";
    if(isset($_GET['logout'])){
        session_unset();
        setcookie("id","",time()-3600);
    }
    if(isset($_SESSION['id'])){
        header("Location: logged.php");
    }
    if(isset($_COOKIE['id'])){
        header("Location: logged.php");
    }

    
    if(isset($_POST['signup'])){
        if(!$_POST['email']){
            $error .="<p>Email is required</p>";
        }
        if(!$_POST['password']){
            $error .="<p>password is required</p>";
        }
        if($error!=""){
            $error = "There are errors in the form".$error;
        }else{
            $mysqli = new mysqli($servername, $username, $password,$db_name);

            // Check connection
            if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
            }
            // echo "Connected successfully";
            $sql = "SELECT * FROM users where email='".$_POST['email']."' LIMIT 1";
            if(mysqli_num_rows(mysqli_query($mysqli,$sql)) > 0){
                $error = "Sorry the email is already taken.";
            }else{
                $hash = password_hash($_POST['password'],PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (email,password) VALUES ('".$_POST['email']."','".$hash."')";
                if (mysqli_query($mysqli, $sql)) {
                    echo "New record created successfully";
                    $_SESSION['id'] = $mysqli->insert_id;
                    if(isset($_POST['stayloggedin'])){
                        setcookie("id",$_SESSION['id'],time()+ 60*60*24*365);
                    }
                    header("Location: logged.php");
                } else {
                    $error = "Cannot signup try later";
                }
            }


        }
    }else if(isset($_POST['login'])){
        if(!$_POST['email']){
            $error .="<p>Email is required</p>";
        }
        if(!$_POST['password']){
            $error .="<p>password is required</p>";
        }
        if($error!=""){
            $error = "There are errors in the form".$error;
        }else{
            $conn = new mysqli($servername, $username, $password, $db_name);

            // Check connection
            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }
            // echo "Connected successfully";
            $sql = "SELECT * FROM users where email='".$_POST['email']."' LIMIT 1";
            $result = mysqli_query($conn,$sql);
            if(mysqli_num_rows($result) > 0){
                $row = $result -> fetch_assoc();
                    if(password_verify($_POST['password'], $row['password'])) {
                    $_SESSION['id'] = $row['id'];
                    if(isset($_POST['stayloggedin'])){
                        setcookie("id",$_SESSION['id'],time()+ 60*60*24*365);
                    }
                    header("Location: logged.php");
                }else{
                    $error = "password does not match";
                }
            }else{
                $error = "Cannot find email.";
            }


        }


    }




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Secret Diary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container" id="signup" style="width:500px; color:white;">
    <div id="error">
<div class="alert alert-danger" role="alert">
  <?php echo $error; ?> 
</div>
    </div>
    <form class="d-flex flex-column" method="post">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
    <div id="emailHelp" class="form-text" style="color:#c3c3c3;">We'll never share your email with anyone else.</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" name="stayloggedin" value="1" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Stay Logged In</label>
  </div>
  <button type="submit" class="btn btn-primary" name="signup" value="signup">Sign up</button>
 <p style="color:white;margin-top:20px">Already have an account <a id="toggle1" href="#" style="color:white" >Click here.</a></p> 
</form>
    </div>

    <div class="container" id="login" style="width:500px;color:white;">
    <form class="d-flex flex-column" method="post">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
  </div>
  <div class="mb-3 form-check">
    <input type="checkbox" name="stayloggedin" value="1" class="form-check-input" id="exampleCheck2">
    <label class="form-check-label" for="exampleCheck2">Stay Logged In</label>
  </div>
  <button type="submit" class="btn btn-primary" name="login" value="login">Log in</button>
  <p style="color:white;margin-top:20px">Don't have an account <a id="toggle2" href="#" style="color:white" >Click here.</a></p> 
</form>
    </div>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $("#error").hide();
        $("#toggle1").click(function(){
            $("#signup").hide();
            $("#login").show();
        });
        $("#toggle2").click(function(){
            $("#login").hide();
            $("#signup").show();
        });
        var error = "<?php echo $error; ?>"
        if(error!=""){
            $("#error").show();
            
        }


    </script>
</body>
</html>