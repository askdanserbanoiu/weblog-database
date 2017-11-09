<?php 
require_once("services/auth_service.php"); 
require_once("controllers/articles_ctrl.php"); 
?>


<!DOCTYPE HTML>
<html>
<head>
	<title>WeBlog</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css-custom/public.css" rel="stylesheet" />
	<link href="assets/css-custom/blue-nav.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/main.css" />
	<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
	<script src="https://code.jquery.com/jquery-2.1.3.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="   crossorigin="anonymous"></script>
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>

<!-- Header -->
<div class="navbar navbar-default navbar-static-top">
  <div class="container">
  	
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="articles.php">WeBlog</a>
    </div>
    <div class="collapse navbar-collapse">
      	<ul class="nav navbar-nav">
        <li class="dropdown dropdown-lg">
                <a class="dropdown-toggle dropdown-lg" data-toggle="dropdown" aria-expanded="false">Ricerca</a>
                <div class="dropdown-menu" role="menu">
                    <form class="form-horizontal" role="form">
                      <div class="form-group">
                        <label for="filter">Per Categoria</label>
                        <select name="category" class="form-control">
                        <?php 
                            foreach ($subjects as $subjectp) { ?>
                            <option value="<?php echo $subjectp->subject; ?>"><?php echo $subjectp->subject; ?></option> 
                        <?php 
                            }   
                        ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="filter">Per Autore</label>
                        <select name="category" class="form-control">
                        <?php 
                            foreach ($users as $user) { ?>
                            <option value="<?php echo $user->get_username(); ?>"><?php echo $user->get_username(); ?></option> 
                        <?php 
                            }
                        ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="filter">Per Data</label>
						<div class="input-group">
                          <span class="input-group-addon"><i class="pe-7s-date"></i></span>
                          <input  class="form-control" data-toggle="datepicker" />
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                    </form>
            	</div>
        </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php if ($session->is_logged_in()) { ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img class="profile-img img-circle" src="images/<?php echo $avatar->filename; ?>" alt="Profilo"/>
                    <strong><?php echo $user->get_fullname(); ?></strong>
                    <span class="glyphicon glyphicon-chevron-down"></span>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="dashboard.php" target="_blank"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>Area Admin</a></li>
                  <li><a href="/logout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
               </ul>
            </li>
            <?php } else { ?>
            <li><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </div>
  </div>
</div>

<section id="main" class="container">
	<div class="box">
		<div class="row">
			<?php  
			foreach($articles_list as $article): 
			$img = Image::find_by_id($article->article_img_id); 
			?>

			<div class="6u 12u(mobilep)">
			<section class="box special">
				<span class="image featured"><a href="article.php?article_id=<?php echo $article->title; ?>"><img class="img-fluid" src="images/<?php echo $img->filename; ?>" alt="" /></a></span>
				<h3><a href="article.php?article_id=<?php echo $article->title; ?>"><?php echo $article->title; ?></a></h3>
				<ul class="actions">
					<li><a href="article.php?article_id=<?php echo $article->title; ?>" class="button alt">Leggi</a></li>
				</ul>
			</section>
			</div>
			<?php endforeach; ?> 
		</div>
		<!--   Interazione con paginazione Articoli -->
		<div class="form-group">
		    <ul class="pagination">
		    <?php
		        $path = "articles.php";
		        $html = "";
		        for ($i=1; $i <= $pagination->total_pages(); $i++){
		            if ($i == $pagination->current_page) {
		                $html .= "<li class=\"active\"><a href=\"#\">{$i}</a></li>";
		            } else {
		                $html .= "<li><a href=\"{$path}?page={$i}";
		                if ($author != null) $html .= "&&author={$author}";
		                if ($subject != null) $html .= "&&subject={$subject}";
		                if ($month != null) $html .= "&&month={$month}";
		                if ($year != null) $html .= "&&year={$year}";
		                $html .= "\">{$i}</a></li>";
		            }
		        }
		        echo $html;
		    ?>
		    </ul>
		</div>
	</div>
</section>

<!-- Footer -->
<footer id="footer">
<ul class="copyright">
	<li>&copy; WeBlog. All rights reserved.</li>
</ul>
</footer>



</body>
</html>