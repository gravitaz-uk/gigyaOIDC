<?php
    require_once 'config.php';
?>
<html>
<head>
    <title>demo.gigya.com | OIDC RP Site</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <link href="css/maincss.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" lang="javascript" src="https://cdns.gigya.com/JS/gigya.js?apiKey=<?php echo $apiKey ?>"></script>

    <style type='text/css'>
        /* ** --snipped-- ** */
    </style>
</head>

<body>
    <div class="" id="main_wrapper">
        <div class="" id="logoDiv">
            <a href="<?php echo $urlPrefix ?>">
                <img src="images/GigyaLogo_2016-05-04.png" />
            </a>
        </div>
        <h2>Gigya OIDC Demo RP Site</h2>
        <p></p>
        <div class="" id="loginOptions">
            <h3>Select which flow you would like to use</h3>
            <button class="rpBtn" id="idTokenBtn" />ID Token</button>
            <button class="rpBtn" id="codeBtn" />Auth Code</button>
        </div>
        <script>
            $(document).ready(function() {
                $(document).on("click", "#idTokenBtn", function() {
                    window.location = "index_idToken.php";
                });
                $(document).on("click", "#codeBtn", function() {
                    window.location = "index_code2.php";
                });
            })
        </script>
    </div><!-- /main_wrapper -->
</body>

</html>
