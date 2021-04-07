<?php 
    include 'init.php';
           
    $errorcode = isset($_GET["errorcode"]) ? $_GET["errorcode"] : 0;
    
    if ($errorcode != 0 ){
        $uzenet = Felhasznalo::getErrorMessage($errorcode);
    }  
    
    // Check if the user is already logged in, if yes then redirect him to welcome page
    if(Felhasznalo::isLoggedIn())
    {
        header("location: ../firstpage.php");
        exit;
    }
?>

<html>
<head>
    <link rel="preconnect" href="https://fonts.gstatic.com">        
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <link rel="stylesheet" href="css/bsoverride.css" type="text/css" media="all" />  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/73be1b065a.js" crossorigin="anonymous"></script>
    
    <title>Check In</title>
</head>
<body id="bootstrap-overrides">
    <div class = "container-fluid container mrglogin">
            <div class="col-12 d-flex justify-content-center">
                <h1>Bejelentkezés</h1>
            </div>
            <form action="function/login.php" method="POST">
            <div class = "row">
                <div class = "col-6 d-flex flex-row-reverse form-label">
                    <label for="email">EmailCím: </label>                
                </div>
                <div class="col-6 d-flex flex-row form-label">
                    <input type="text" id="email" name="email" placeholder = "emailcím...">
                </div>
                <div class = "col-6 d-flex flex-row-reverse form-label">
                    <label class="pdingboxright" for ="password">Jelszó: </label>                
                </div>
                <div class = "col-6 d-flex flex-row form-label">
                    <input type = "password" id = "password" name="password" placeholder = "jelszó...">
                </div>
                <div class = "col d-flex justify-content-center">
                    <input type="submit" value="Bejelentkezés" class="btn btn-primary">
                    
                </div>
                <?php if($errorcode != 0){?>
                <div class = "col-12 d-flex justify-content-center alert alert-danger">
                    <?=$uzenet?>
                </div>
                <?php }?>
            </div>
            </form>                    
        </div>
    </div>

</body>
</html>


