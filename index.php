<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" ng-app="sample_app"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sean Anderson Programming Samples</title>
        <link rel="shortcut icon" href="img/titlelogo.png" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh1WvT_TQ42_pmYIaM4LHEqjFJUOrwW98&libraries=places"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-route.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-touch.min.js"></script>
        <script src="js/angular-google-maps.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/animate.css">
        <link rel="stylesheet" type="text/css" href="css/custom.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70100005-1', 'auto');
  ga('send', 'pageview');

    </script>
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    </head>
    <body>

        <nav class="navbar navbar-inverse" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="logo-area">
                        <img src="img/sasmall.png" alt="Sean Anderson logo" class="img img-responsive">
                        <a class="navbar-brand" href="http://<?=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>#/"><h4 class="no-marg">Sean Anderson</h4><h5 class="no-marg">Programming samples</h5></a>
                    </div>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav" ng-controller="NavController">
                        <li>
                            <a ng-class="navClass('#')" href="#/">Home</a>
                        </li>
                        <li>
                            <a ng-class="navClass('portfolio')" href="#/portfolio">Portfolio</a>
                        </li>
                        <li>
                            <a ng-class="navClass('forms')" href="#/forms">Forms</a>
                        </li>
                        <li>
                            <a ng-class="navClass('maps')" href="#/maps">Google Maps</a>
                        </li>
                        <li>
                            <a ng-class="navClass('filmsearch')" href="#/filmsearch">Film Seach</a>
                        </li>
                         <li>
                            <a ng-class="navClass('twitterfeed')" href="#/twitterfeed">Custom Twitter Feed</a>
                        </li>
                    </ul>
                    <h5 class="pull-right header-email"><i class="fa fa-paper-plane-o"></i>Email: <a href="mailto:seanandersonmke@gmail.com">seanandersonmke@gmail.com</a></h5>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
    	<div class="animated fadeIn set-height" ng-view></div>
        
        <div class="modal fade mainmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <img class="img img-responsive" src="">
                </div>
            </div>
        </div>

        <script src="js/app.js"></script>
        <script src="js/MainController.js"></script>
        <script src="js/Services.js"></script>
    </body>
</html>