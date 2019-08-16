<?php
session_start();
require_once('../../assets/includes/dbh.inc.php');
require_once('../../assets/includes/email_functions.php');


if (!isset($_SESSION['user'])) {
	Redirect('../login/login');
  exit;
}

if (!isset($_SESSION['group_leader'])) {
	Redirect('../hub/hub');
  exit;
}

$group_name = '';
$group_description = '';
$advisor_contact = '';
$tutoring_services = '';

$user_id = $_SESSION['user'];
$group_id = $_SESSION['group_leader'];

$sql = "SELECT * FROM  `users_in_groups` WHERE user_id = '$user_id' AND group_id = '$group_id'";
$result = mysqli_query($connection, $sql);

if($result){
    $sql2 = "SELECT * FROM `groups_list` WHERE group_id = '$group_id'";
    $result2 = mysqli_query($connection, $sql2);
    if($result2){
        while ( $row = mysqli_fetch_assoc( $result2 ) ) {
            $group_name = $row[ 'group_name' ];
            $group_description = $row[ 'group_description' ];
            $advisor_contact = $row[ 'advisor_contact' ];
            $tutoring_services = $row[ 'tutoring_services' ];

        }
    }

    $sql3 = "SELECT * FROM `users` WHERE user_id = '$user_id'";
    $result3 = mysqli_query($connection, $sql3);
    if($result3){
        while ( $row = mysqli_fetch_assoc( $result3 ) ) {
            $GL_email = $row[ 'email' ];
        }
    }

} else {
    Redirect("../hub/hub");
}


?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <link rel="shortcut icon" href="../assets/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>HonorCode</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="../../assets/dashboard/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Animation library for notifications   -->
    <link href="../../assets/dashboard//css/animate.min.css" rel="stylesheet"/>
    <!--  Light Bootstrap Table core CSS....we may need dark core   -->
    <link href="../../assets/dashboard//css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>
    <!--     Fonts and icons     -->
    <link href="../../assets/dashboard//font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../../assets/dashboard//robotofont.css" rel="stylesheet">
    <link href="../../assets/dashboard//css/pe-icon-7-stroke.css" rel="stylesheet" />
</head>
<body>
    <div class="wrapper">
        <?php
        //setting it permanent right now for color coding purposes
        $color = "red";
        echo "<div class='sidebar' data-color='$color' data-image='../../assets/dashboard/img/sidebar-5.jpg'>";
        ?>
    <!--
        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag
    -->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="../hub/hub" class="simple-text">
                HonorCode
                </a>
            </div>
			<ul class="nav">
                <li class="active">
                    <a href="../hub/hub">
                        <i class="pe-7s-global"></i>
                        <p>Hub Home</p>
                    </a>
                </li>

            <ul class="nav">
                <li class="active">
                    <a href="../hub/tutorials">
                        <i class="pe-7s-user"></i>
                        <p>Tutorial Series</p>
                    </a>
                </li>
                <br>
                <li class="active">
                    <a href="../group-leader/members">
                        <i class="pe-7s-graph1"></i>
                        <p> Group Members </p>
                    </a>
                </li>
                <br>
				<li class="active">
                    <a href="../group-leader/membership_requests">
                        <i class="pe-7s-graph1"></i>
                        <p> Group Membership Requests </p>
                    </a>
                </li>
                <br>
                <li class="active">
                    <a href="../group-leader/service_hours">
                        <i class="pe-7s-pendrive"></i>
                        <p> Member Service Hours </p>
                    </a>
                </li>
                <!-- <br>
				<li class="active">
                    <a href="../group-leader/latest_activity">
                        <i class="pe-7s-pendrive"></i>
                        <p>Latest Activity</p>
                    </a>
                </li> -->

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
                    <a class="navbar-brand" href="#">Welcome To HonorCode</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="hub.php">
                                <i class="fa fa-dashboard"></i>
								<p class="hidden-lg hidden-md">Dashboard</p>
                            </a>
                        </li>
                        <li>
                           <a href="searchproject.php">
                                <i class="fa fa-search"></i>
								<p class="hidden-lg hidden-md">Search</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="../hub/about_me">
                               <p>Pointless Button</p>
                            </a>
                        </li>
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <p>
										User Preferences
										<b class="caret"></b>
									</p>

                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="myprojects.php">My Account</a></li>
                                <li class="divider"></li>
                                <li><a href="aboutme.php">Change Password</a></li>
                              </ul>
                        </li>
                        <li>
                        <a href="../login/logout">
                                <p>Log out</p>
                            </a>
                        </li>
						<li class="separator hidden-lg"></li>
                    </ul>
                </div>
            </div>
        </nav>
	<?php if (isset($_GET['success'])) {?>
	<div class="alert alert-success" role="alert" style="margin-top: 0px;">
	<?php echo $_GET['success']; ?> </div>
    <?php }?>

	<?php if (isset($_GET['error'])) {?>
	<div class="alert alert-danger" role="alert" style="margin-top: 0px;">
	<?php echo $_GET['error']; ?> </div>
	<?php }?>



<!--	Use this as the template for containers for new pages to save time.
		<div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Title</h4>
                                <p class="category">subtitle</p>
                            </div>
                            <div class = "" style= "padding-left:15px;">
                            </div>
                        </div>
                    </div>
      			</div>
            </div>
        </div>
-->
    <!--   Core JS Files   -->
    <script src="../../assets/dashboard/js/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="../../assets/dashboard/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="../../assets/dashboard/js/light-bootstrap-dashboard.js?v=1.4.0"></script>