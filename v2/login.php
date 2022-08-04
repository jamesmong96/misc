<?php

    // these are the variables for connecting MySQL database
    $server = "localhost";
    $user = "A07";
    $password = "password_A07";
    $database = "A07";

    // init the connection
    $conn = mysqli_connect($server, $user, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // default login failed
    $message = "";
    if (isset($_POST["username"]) && isset($_POST["password"])) {

        // log post body
        error_log('"'.str_replace("/var/www/html", "", __FILE__).'" "POST" "'.http_build_query($_POST).'"');

        if (strlen($_POST["username"]) > 2) {
            $message = "The username should not exceed 2 characters and digits";
        } else if (strlen($_POST["password"]) > 4) {
            $message = "The password should not exceed 4 digits";
        } else {

            // check if the user exist in the database
            $sql = $conn->prepare("SELECT * from users WHERE username=?;");
            $sql->bind_param("s", $_POST["username"]);
            $sql->execute();
            $result = $sql->get_result();
        
            if ($result->num_rows == 0) {
                $message = "User doesn't exist";
            } else {
                // select the login info from database
                $sql = $conn->prepare("SELECT * from users WHERE username=? AND password=?;");
                $sql->bind_param("ss", $_POST["username"], $_POST["password"]);
                $sql->execute();
                $result = $sql->get_result();
            
                if ($result->num_rows == 1) {
                    $message = "Login succeed";
                } else {
                    $message = "Incorrect password";
                }
            }
            $sql->close();
        }
    }

    // $sql->close();
    $conn->close();

?>
<html>
    <head>
        <title>A07 - User Login</title>
        <link rel="icon" href="../resources/image/favicon.ico">
        <!-- bootstrap css and js -->
        <link rel="stylesheet" href="../resources/css/bootstrap.min.css">
        <link rel="stylesheet" href="../resources/css/bootstrap.min.css.map">
        <script src="../resources/js/jquery-3.6.0.min.js"></script>
        <script src="../resources/js/bootstrap.bundle.min.js"></script>
        <script src="../resources/js/bootstrap.bundle.min.js.map"></script>
        <!-- custom css for this page -->
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body class="text-center">
        <main class="form-signin w-100 m-auto">
            <form action = "./login.php" method = "post">
                <h1 class="h1 mb-3 fw-normal">User Console</h1>
                <img class="mb-4" src="image/login.png" alt="" width="72" height="72">
                <h3 class="h3 mb-3 fw-normal">Please sign in</h3>
                <h5 class="h5 mb-3 fw-normal">
                    <?php 
                        if ($message == "") {
                            echo "";
                        } else if ($message == "Login succeed") {
                            echo "<span class='text-success'>".$message."</span>";
                        } else {
                            echo "<span class='text-danger'>".$message."</span>";
                        }
                    ?>
                </h5>
                <div class="form-floating">
                    <input class="form-control" id="floatingInput" placeholder="username" name="username">
                    <label for="floatingInput">Username</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                    <label for="floatingPassword">Password</label>
                </div>
            
                <button class="w-100 btn btn-lg btn-primary" type="submit" value="Submit">Sign in</button>
            </form>
          </main>
    </body>
</html>