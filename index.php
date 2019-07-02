<!DOCTYPE html>
<html lang="en">
<head>
    <title>VibeScribe</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/script.js" type="text/javascript"></script>
</head>
<body>
<?php require_once "inc/functions.php"; ?>

<div class="container">

    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">VibeScribe</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="#content_breaking" class="active">Breaking</a></li>
            <li><a href="#content_popular">Popular</a></li>
            <li><a href="#content_latest">Latest</a></li>
        </ul>
      </div>
    </nav>
     
    <div id="mainContent" class="container text-center"><!--main content-->    
     
        <div id="content_breaking">
            <?php getTab('breaking'); ?>
        </div><!--end content breaking-->  
     
        <div id="content_popular">
            <?php //getTab('popular'); ?>
        </div><!--end content popular-->  
     
        <div id="content_latest">
            <?php //getTab('latest'); ?>
        </div><!--end content latest-->
     
    </div><!--end main content -->
 
</div><!--end container-->
</body>
</html>