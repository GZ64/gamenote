<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>Blog Template for Bootstrap</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo DATA_WEB ?>css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?php echo DATA_WEB ?>css/blog.css" rel="stylesheet">
    </head>

    <body>

        <div class="blog-masthead">
            <div class="container">
                <nav class="nav blog-nav">
                    <a class="nav-link active" href="<?php echo LINK_WEB ?>jeu">Home</a>
                </nav>
            </div>
        </div>

        <div class="blog-header">
            <div class="container">
                <h1 class="blog-title">The Bootstrap Blog</h1>
                <p class="lead blog-description">An example blog template built with Bootstrap.</p>
            </div>
        </div>

        <div class="container">

            <div class="row">

                <div class="col-sm-8 blog-main">

                    <?php echo $viewContent ?>

                </div><!-- /.blog-main -->

                <div class="col-sm-3 offset-sm-1 blog-sidebar">
                    <div class="sidebar-module sidebar-module-inset">
                        <h4>About</h4>
                        <p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
                    </div>
                    <div class="sidebar-module">
                        <h4>Archives</h4>
                        <ol class="list-unstyled">
                            <li><a href="#">March 2014</a></li>
                            <li><a href="#">February 2014</a></li>
                            <li><a href="#">January 2014</a></li>
                            <li><a href="#">December 2013</a></li>
                            <li><a href="#">November 2013</a></li>
                            <li><a href="#">October 2013</a></li>
                            <li><a href="#">September 2013</a></li>
                            <li><a href="#">August 2013</a></li>
                            <li><a href="#">July 2013</a></li>
                            <li><a href="#">June 2013</a></li>
                            <li><a href="#">May 2013</a></li>
                            <li><a href="#">April 2013</a></li>
                        </ol>
                    </div>
                    <div class="sidebar-module">
                        <h4>Elsewhere</h4>
                        <ol class="list-unstyled">
                            <li><a href="#">GitHub</a></li>
                            <li><a href="#">Twitter</a></li>
                            <li><a href="#">Facebook</a></li>
                        </ol>
                    </div>
                </div><!-- /.blog-sidebar -->

            </div><!-- /.row -->

        </div><!-- /.container -->

        <footer class="blog-footer">
            <p>Blog template built for <a href="https://getbootstrap.com">Bootstrap</a> by <a href="https://twitter.com/mdo">@mdo</a>.</p>
            <p>
                <a href="#">Back to top</a>
            </p>
        </footer>


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?php echo DATA_WEB ?>js/jquery-3.2.1.min.js"></script>
    </body>
</html>
