<?php
    require_once 'config.php';
?>
<html>
    <head>
        <title>Open ID Connect Proxy Page | Sample</title>
        <script type="text/javascript" lang="javascript" src="https://cdns.gigya.com/JS/gigya.js?apiKey=<?php echo $apiKey ?>"></script>
        <script type="text/javascript" lang="javascript" src="https://cdns.gigya.com/JS/gigya.oidc.js?apiKey=<?php echo $apiKey ?>">
            {
                loginURL: "OIDCLoginPage.php",
                consentURL: "OIDCConsentPage.php",
                errorURL: "OIDCErrorPage.php"
            }
        </script>
    </head>
    <body>

    </body>
</html>
