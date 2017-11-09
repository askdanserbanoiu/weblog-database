<?php 
require_once("services/auth_service.php"); 
require_once("controllers/manage_profile_ctrl.php"); 	
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
    <link rel="stylesheet" href="assets/css/iziModal.min.css">
    <script src="assets/js/iziModal.min.js" type="text/javascript"></script>
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
            <div class="logo" >
                <a  class="simple-text">
                    WeBlog
                </a>
            </div>

            <!--   Menu Di Navigazione Admin -->
            <ul class="nav">
                <li><a href="dashboard.php"><i class="pe-7s-graph3"></i><p>Dashboard</p></a></li>
                <li class="active"><a href="manage_profile.php"><i class="pe-7s-user"></i><p>Gestione Profilo</p></a></li>
                <li><a href="new_article.php"><i class="pe-7s-news-paper"></i><p>Nuovo Articolo</p></a></li>
                <li><a href="manage_articles.php"><i class="pe-7s-note2"></i><p>Gestione Articoli</p></a></li>
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
                    <div class="col-md-8">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Informazioni Base</h4>
                            </div>
                            <!--   Visualizza Messaggi di Avvertimento -->
                            <?php
                                if ($message) {   
                                    echo "<div class=\"content\"><div class=\"alert alert-info\">" . $message . "</div></div>";
                                }
                            ?>
                            <!--   Interazione con Info del Profilo -->
                            <div class="content">
                                <form action="manage_profile.php" method="post">
                                    <div class="row">
                                        <!--   Visualizzazione Username -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" class="form-control" value="<?php echo $user->get_username();?>" readonly>
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="row">
                                        <!--   Visualizzazione/Modifica Nome -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nome</label>
                                                <input type="text" class="form-control" name="name" value="<?php echo $user->get_name();?>">
                                            </div>
                                        </div>  
                                        <!--   Visualizzazione/Modifica Cognome --> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Cognome</label>
                                                <input type="text" class="form-control" name="surname" value="<?php echo $user->get_surname();?>">
                                            </div>
                                        </div>   
                                    </div>
                                    <div class="row">
                                        <!--   Visualizzazione/Modifica Data Nascita -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Data Nascita</label>
                                                <div class="input-group">
                                                  <span class="input-group-addon" id="basic-addon1"><i class="pe-7s-date"></i></span>
                                                  <input type="text" class="form-control" name="birth" data-toggle="datepicker" value="<?php echo $user->get_birth();?>">                                                                           
                                                </div>
                                            </div>
                                        </div>
                                        <!--   Visualizzazione/Modifica Indirizzo -->   
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Indirizzo</label>
                                                <input type="text" class="form-control" name="address" value="<?php echo $user->get_address();?>">
                                            </div>
                                        </div>   
                                    </div>
                                    <!--   Conferma Modifica Info Base --> 
                                    <button type="submit" name="submit" value="edit_info" class="btn btn-info btn-fill pull-right">Salva Modifiche</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                            <hr>
                            <!--  Processo Cambio Password  -->
                            <div class="header">
                                <h4 class="title">Cambio Password</h4>
                            </div>
                            <div class="content">
                                <form action="manage_profile.php" method="post">
                                    <div class="row">
                                        <!--  Inserimento Vecchia Password  -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Vecchia Password</label>
                                                <input type="password" name="old_password" class="form-control">
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="row">
                                        <!--  Inserimento Nuova Password  -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nuova Password</label>
                                                <input type="password" name="new_password" class="form-control">
                                            </div>
                                        </div>   
                                        <!--  Inserimento Ancora di Nuova Password  -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ripeti Nuova Password</label>
                                                <input type="password" name="re_password" class="form-control">
                                            </div>
                                        </div>   
                                    </div>
                                    <!--  Conferma Cambio Password  -->
                                    <button type="submit" name="submit" value="edit_password" class="btn btn-info btn-fill pull-right">Salva Password</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-user">
                            <div class="image">
                                <img src="images/blue-profile.png" alt="Sfondo Foto Profilo"/>
                            </div>
                            <div class="content">
                                <div class="author">
                                    <div class="author-container" id="pavatar">
                                      <img src="images/<?php echo $avatar->filename; ?>" alt="Avatar" class="image-pavatar avatar">
                                      <div class="middle-pavatar">
                                        <div class="text-pavatar">Cambia</div>
                                      </div>
                                    </div>

                                    <form action="manage_profile.php" method="post" enctype="multipart/form-data" id="uploadImageForm"">
                                        <input id="uploadImage" type="file" name="user_img" onchange="PreviewImage();" />
                                        <button id="uploadImageButton" type="submit" name="submit" value="edit_avatar" class="btn btn-info btn-fill pull-right"></button>
                                    </form>


                                     <h4 class="title"><?php echo $user->get_fullname(); ?></h4><br>
                                     <?php echo $root ? "Amministratore Root" : "Amministratore";?><br>
                                     <?php echo $user->get_username(); ?>
                                </div>
                            </div>
                            <hr>
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

        <script src="controllers/manage_profile_ctrl.js" type="text/javascript"></script>

    </div>
</div>
</body>
</html>