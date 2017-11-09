<?php 
require_once("services/auth_service.php"); 
require_once("controllers/manage_articles_ctrl.php"); 
?>


<!doctype html>
<html lang="it">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Weblog</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css-custom/admin.css" rel="stylesheet" />
    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>
    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>
    <!--     Fonts and icons     -->
    <link href="fonts/glyphicons-halflings-regular.ttf">
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
    <link href="fonts/fontawesome-webfont.ttf">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <link href="assets/css/bootstrap-select.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link  href="assets/css/datepicker.css" rel="stylesheet">
	<script src="assets/js/datepicker.js"></script>
    <script src="assets/js/bootstrap-select.js" type="text/javascript"></script>
    <script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
    <script src="assets/js/bootstrap-notify.js"></script>
    <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/js/light-bootstrap-dashboard.js"></script>
</head>
<body>


<div class="wrapper">
    <div class="sidebar" data-color="blue" data-image="assets/img/sidebar.jpg">

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                    WeBlog
                </a>
            </div>        

            <!--   Menu Di Navigazione Admin -->
            <ul class="nav">
                <li><a href="dashboard.php"><i class="pe-7s-graph3"></i><p>Dashboard</p></a></li>
                <li><a href="manage_profile.php"><i class="pe-7s-user"></i><p>Gestione Profilo</p></a></li>
                <li><a href="new_article.php"><i class="pe-7s-news-paper"></i><p>Nuovo Articolo</p></a></li>
                <li class="active"><a href="manage_articles.php"><i class="pe-7s-note2"></i><p>Gestione Articoli</p></a></li>
                <li><a href="new_subject.php"><i class="pe-7s-box2"></i><p>Nuova Categoria</p></a></li>
                <li><a href="manage_subjects.php"><i class="pe-7s-albums"></i><p>Gestione Categorie</p></a></li>
                <?php 
                    if ($root) {
                        echo "<li><a href=\"new_user.php\"><i class=\"pe-7s-add-user\"></i><p>Nuovo Utente</p></a></li>";
                        echo "<li><a href=\"manage_users.php\"><i class=\"pe-7s-users\"></i><p>Gestione Utenti</p></a></li>"; 
 
                    }
                ?>
            </ul>
    	</div>
    </div>

    <div class="main-panel">
		<nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#"><p>Logout</p></a></li>
                        <li class="separator hidden-lg hidden-md"></li>
                    </ul>
                </div>
            </div>
        </nav>



        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <!--   Titolo Sezione -->
                            <div class="header">
                                <h4 class="title">Lista Articoli</h4>
                            </div>

                            <div class="content">
                                                            <div class="thumbnail">

         
                                    <!--   Ricerca -->
                                    <form action="manage_articles.php" role="form">
                                        <!--   Cerca in base ad Autore -->  
                                         <div class="form-group">
                                            <label class="control-label">Autore</label>
                                            <select name='author' class="form-control">
                                            <?php 
                                            if ($root){
                                                $users = User::find_all();
                                                foreach ($users as $user) { ?>
                                                <option value="<?php echo $user->get_username(); ?>"><?php echo $user->get_username(); ?></option> 
                                            <?php 
                                                }
                                            } 
                                            ?>
                                            </select>
                                        </div>                 


                                        <!--   Cerca in base a Categoria --> 
                                         <div class="form-group">
                                            <label class="control-label">Categoria</label>
                                            <select name="subject" class="form-control">
                                            <?php 
                                                $subjects = Subject::find_all();
                                                foreach ($subjects as $subjectp) { ?>
                                                <option value="<?php echo $subjectp->subject; ?>"><?php echo $subjectp->subject; ?></option> 
                                            <?php 
                                                }   
                                            ?>
                                            </select>
                                        </div>

                                        <!--   Cerca in Base a Tempo di Inizio -->
                                         <div class="form-group">
                                            <label class="control-label">Data</label>
                                            <div class="input-group">
                                              <span class="input-group-addon"><i class="pe-7s-date"></i></span>
                                              <input type="text" class="form-control" data-toggle="datepicker" />
                                            </div>               
                                        </div>
                                          
                                        <div class="form-group">
                                                                                    <label class="control-label"></label>

                                        <button type="submit" name="submit" value="publish" class="btn btn-info form-control"><i class="pe-7s-search"></i>
                                        </button>    
                                        </div>
                                    </form> 
                                </div>
                            </div>     



                            <!--   Visualizza tabella articoli -->
                            <div class="content">
                                <div class="content table-responsive table-full-width">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <th>Titolo</th>
                                            <th>Autore</th>
                                            <th>Creato</th>
                                            <th>Azioni</th>
                                        </thead>
                                        <tbody>
                                          <?php foreach($articles_list as $articolo): ?>
                                            <tr>
                                               <td><?php echo $articolo->title; ?></td>
                                               <td><?php echo $articolo->author; ?></td>
                                               <td><?php echo $articolo->created; ?></td>
                                               <td><a href="edit_article.php?article_id=<?php echo $articolo->title; ?>"><i class="material-icons">edit</i></a>
                                               <a href="article.php?article_id=<?php echo $articolo->title; ?>"><i class="material-icons">pageview</i></a>
                                               <a onclick="return confirm('Sicuro di voler cancellare l\'articolo?');" href="control/rimuovi_articolo.php?article_id=<?php echo $articolo->title; ?>"><i class="material-icons">cancel</i></a></td>
                                            </tr>
                                          <?php endforeach; ?>   
                                        </tbody>
                                    </table>
                                </div>                     


                            <!--   Interazione con paginazione Articoli -->
                                <ul class="pagination pagination-primary">
                                <?php
                                    $path = "articles.php";
                                    if ($user) $path = "manage_articles.php";
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
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                </nav>
                <p class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script> 
                </p>
            </div>
        </footer>
    </div>

</div>
</body>
</html>