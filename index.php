<!DOCTYPE html>

<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="yafra php example with Slim and Rest API">
    <meta name="author" content="Martin Weber">
    <link rel="icon" href="/favicon.ico">

    <title>Yafra.org PHP app</title><!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css" type="text/css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css" type="text/css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- Custom styles for this template -->
    <link href="main.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">
        <!-- Static navbar -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span></button> <a class="navbar-brand" href="/">yafra.org</a>
                </div>

                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="/">News</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sektione</a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="http://www.yafra.org/">yafra.org</a></li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li><a data-toggle="modal" data-target="#about">About us</a></li>
                        <li><a data-toggle="modal" data-target="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>


<!-- Marketing messaging and featurettes
================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->

<div class="container marketing">


<!-- Internal Message -->
    <div class="row">
        <div class="col-md-12">
            <h2>DB access demo</h2>
            <?php
            require_once 'backend/DbHandler.php';
				use backend\DbHandler as DbHandler;
            $db = new DbHandler();
            $result = $db->getMessages(5, 0); // fetching all events
            foreach ($result as $msg) {
                echo '<ul>';
                echo '<li>' . $msg["msgdate"] . ": " . $msg["msgtext"] . '</li>';
                echo '</ul>';
            }
            ?>
        </div>
    </div>


	<!-- FOOTER -->
	<footer>
		<p class="pull-right"><a href="#">Nach oben</a></p>
		<p>&copy; 2014 yafra.org &middot;</p>
	</footer>

    <!-- Modal -->
    <div class="modal fade" id="about" tabindex="-1" role="dialog" aria-labelledby="aboutLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">About us</h4>
                </div>
                <div class="modal-body">
                    <p>yafr.org page.</p>
                    <address>
                    </address>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal contact -->
    <div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="contactLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">About us</h4>
                </div>
                <div class="modal-body">
                    <p>yafra.org.</p>
                    <address>
                    </address>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    </div><!--/.fluid-container-->

	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	 <!-- Latest compiled and minified JavaScript -->
	 <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	</body>
</html>
