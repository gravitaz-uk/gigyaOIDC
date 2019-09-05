<?php
    require_once 'config.php';
    $now = new DateTime();
    $params = array(
        'client_id' => $clientID,
        'response_type' => 'id_token',
        'redirect_uri' => $redirectURI,
        'scope' => 'openid email profile',
        'nonce' => $now->format('U')
    );
    $redirectParams = http_build_query($params);
?>
<html>
    <head>
        <title>Gigya RP Demo Site</title>
        <link rel="shortcut icon" href="favicon.ico?v=2" type="image/x-icon"/>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <link href="/css/maincss.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>        
        <div class="" id="main_wrapper">
            <div class="" id="logoDiv">
                <a href="<?php echo $urlPrefix . 'RPLogin.php'?>">
                    <img src="images/GigyaLogo_2016-05-04.png" />
                </a>
            </div>
        <h2>Gigya OIDC Demo RP Site</h2>
        <p></p>
        <div class="" id="loginBtnDiv">
            <button class="rpBtn" id="loginBtn" />Login via id_token flow</button>
        </div>
        <script>
            var authorizeEndpointLink = "<?php echo $authorizeEndpoint . '?' . $redirectParams ?>";
            $(document).on("click", "#loginBtn", function() {
                window.location=authorizeEndpointLink;
            });
        </script>
    </div><!-- /main_wrapper -->
    </body>
</html>