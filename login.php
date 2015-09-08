<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Login Immobiliare.it</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<style>
    body{background: #eeeeee};
</style>
</head>
<body>
<?php
include 'db_connection.php';
include 'functions.php';
priv_session_start();
if(login_check($mysqli) == true) {

    // contenuto pagina se gia dentro
    echo 'Caio <strong>'.$_SESSION['nomeTitolare'].' '.$_SESSION['cognomeTitolare'].' </strong>sei gia loggato <br/>';
    ?>
    <a href="logout.php"><strong>LogOut</strong></a>
    <?

} else {
    //echo '<p class="bg-danger">You are not authorized to access this page, please login. </p><br/>';
    if(isset($_GET['error'])) {
        echo '<p style="text-align: center" class="bg-danger"><strong>Error Logging In!</strong></p><br/>';

    }?>
<div class="container">
    <form id="myForm" style="width: 400px;margin-left: auto;margin-right: auto;margin-top: 10em" data-toggle="validator" validate="true" action="accedi.php" method="post" name="login_form">
        <input type="email" class="form-control" name="email" placeholder="Insert Email" required="" data-error="Bruh, that email address is invalid" /><br />
        <input type="password" name="p" placeholder="Insert password" class="form-control" id="password"/><br />
        <!-- -->
        <input class="submit btn btn-lg btn-primary btn-block"  value="Login" onclick="formhash(this.form, this.form.password);" />
    </form>
</div>
    <?
}

?>
<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<!-- validazione email ecc -->
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<!-- libreria sha512 -->
<script type="text/javascript" src="sha512.js"></script>
<script type="text/javascript" src="forms.js"></script>
<script>
    $('#myForm').validate();
</script>
</body>
</html>



