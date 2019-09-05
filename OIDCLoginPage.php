<?php
    require_once 'config.php';
?>
<html>
    <head>
    <title>OIDC Login Page | demo.gigya.com</title>
        <script type="text/javascript" src="https://cdns.gigya.com/js/gigya.js?apiKey=<?php echo $apiKey?>"></script>
        <style>
            html, body {
                margin: 0px;
                padding: 25px;
                color: #8a8a8a;
            }
            #errorsDiv {
                display: none;
            }
            #main_wrapper {
                display: block;

            }
            .container {
                text-align: center;
                margin: 0px auto !important;
                width: 100%;
            }
            h1.gigya-screen-caption, #container_content { 
                float: left !important;
            }
        </style>
    </head>
    <body>
        <div class="" id="logoDiv">
            <img src="images/GigyaLogo_2016-05-04.png" />
        </div>
        <h2>Gigya OIDC OP Login Page</h2>
        <div id="container"></div>
        <script>
            function getOIDCClientId() {
                var a,b,c,d,e;
                a=sessionStorage['gigyaCache'];
                if (a) {
                    b=JSON.parse(a);
                    console.dir(b);
                    c=b['gig_oidcContext_<?php echo $apiKey?>'].response;
                    d=c[0].clientID;
                }
            }
            function redirectToProxy() {
                var url = gigya.utils.URL.addParamsToURL("OIDCProxyPage.php",{
                    mode: 'afterLogin'
                });
                window.location.href = url;
            }
            gigya.socialize.addEventHandlers({
                onLogin: function() {
                    getOIDCClientId();
                    redirectToProxy();
                }
            });
            gigya.accounts.showScreenSet({
                    screenSet: '<?php echo $screenSet_prefix?>-RegistrationLogin',
                    containerID: "container",
                    sessionExpiration: '14400' // 4 hours
            });
        </script>
    </body>
</html>
