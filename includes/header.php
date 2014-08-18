<?php include('connect_db.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="css/main.css" />
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<title>SeCoX</title>
</head>

<body>
	<div class="container">
		<nav class="navbar navbar-inverse" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="./">SeCoX (Semantic Content X-tractor)</a>
				</div>
			</div><!-- /.container-fluid -->
		</nav>

		<div class="row">
			<div class="col-md-3">
				<div class="well well-sm">
					<?php $script_file = basename($_SERVER["SCRIPT_FILENAME"]);?>
					<ul class="nav nav-pills nav-stacked" id="yw1">
						<li<?php echo ($script_file == 'index.php')?' class="active"':'';?>>
							<a href="index.php"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a>
						</li>
						<li<?php echo ($script_file == 'sites.php')?' class="active"':'';?>>
							<a href="sites.php"><span class="glyphicon glyphicon-globe"></span> Sites</a>
						</li>
						<li<?php echo ($script_file == 'parse.php')?' class="active"':'';?>>
							<a href="parse.php"><span class="glyphicon glyphicon-floppy-save"></span> Parse</a>
						</li>
						<li<?php echo ($script_file == 'dbpedia.php')?' class="active"':'';?>>
							<a href="dbpedia.php"><span class="glyphicon glyphicon-link"></span> Dbpedia</a>
						</li>
						<li<?php echo ($script_file == 'string-match.php')?' class="active"':'';?>>
							<a href="string-match.php"><span class="glyphicon glyphicon-transfer"></span> String Match</a>
						</li>
						<li<?php echo ($script_file == 'compare.php')?' class="active"':'';?>>
							<a href="compare.php"><span class="glyphicon glyphicon-ok"></span> Compare</a>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="col-md-9">