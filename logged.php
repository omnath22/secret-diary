<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $db_name = "secret-diary";
    $password = "";
    $content = "";
    if(isset($_COOKIE['id'])){
        $_SESSION['id'] = $_COOKIE['id'];
    }

    if(!isset($_SESSION['id'])){
        header("Location: index.php");
    }
    if(isset($_POST['value'])){
        // echo $_POST['value'];
        $mysqli = new mysqli($servername, $username, $password,$db_name);

            // Check connection
        if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
        }
       
            $sql = "UPDATE users SET diary = '".$_POST['value']."' WHERE id=".$_SESSION['id']." LIMIT 1";
        if (mysqli_query($mysqli, $sql)) {
            
        }
        
        
    }

        // echo $_POST['value'];
        $mysqli = new mysqli($servername, $username, $password,$db_name);

            // Check connection
        if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
        }
        $sql = "SELECT * FROM users where id='".$_SESSION['id']."' LIMIT 1";
        $result = mysqli_query($mysqli,$sql);
        if(mysqli_num_rows($result) > 0){
            $row = $result -> fetch_assoc();
            $content .=$row['diary'];
        }
    

    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Diary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
</head>
<body>
    
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand">Secret Diary</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <button class="btn btn-danger"> <a class="nav-link active" style="color:white;" aria-current="page" href='index.php?logout=1'>Logout</a>
</button>
       
      </div>
    </div>
  </div>
</nav>


<textarea name="diary" id="diary" style="width:100%;height:85vh;" ></textarea>

<!--
<footer class="footer" style="position: fixed;bottom: 0;width: 100%;background-color: #000;color: #fff;padding: 10px;text-align: center;">
 
        <div class="button-group">
            <button id="left"><</button>
            <span id="datetochange">Today</span>
            <button id="right">></button>
        </div>
</footer>
    -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
   <script>
    var input = "";
/*    var i=0;
    $("#left").click(function(){
        var today = new Date();
        ++i;
        const yesterday = new Date(today.setDate(today.getDate() -i));
        const options = { weekday: 'long', month: 'long', day: 'numeric' };
        // console.log(yesterday.toLocaleDateString('en-US', options));
        if(i==0){
            $("#datetochange").text("Today");
        }else{
        $("#datetochange").text(yesterday.toLocaleDateString('en-US', options));
    }
    });

    $("#right").click(function(){
        var today = new Date();
        --i;
        const yesterday = new Date(today.setDate(today.getDate() -i));
        
        const options = { weekday: 'long', month: 'long', day: 'numeric' };
        // console.log(yesterday.toLocaleDateString('en-US', options));
        if(i==0){
            $("#datetochange").text("Today");
        }else{
        $("#datetochange").text(yesterday.toLocaleDateString('en-US', options));
        }
    });
*/
    $("#diary").on('input', function() {
    // console.log( this.value );
    input = this.value.replace(/\n/g, "<br>");
    // document.getElementById("diary").addEventListener('keydown', (event) => {
    // if (event.key === 'Enter') {
    //     // alert('You pressed Enter!');
    //     input = input +"\n";
    // }
    // });
    


    $.ajax
            ({ 
                url: '/secret%20diary/logged.php',  //replace with your own url
                data: {"value": input},
                type: 'POST',
                success: function(result)
                {
                    // console.log(result)
                }
            });
});

var content = '<?php echo nl2br($content); ?>';
$("#diary").text(content.replace(/<br\s*\/?>/gi, "\n"));

</script>
</body>
</html>