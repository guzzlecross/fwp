<?php
include_once 'config.php';

if(isset($_POST['btn-save']))
{
 $id_status = $_POST['id_status'];
 $id_ticket = $_POST['id_ticket'];
 $bank = $_POST['bank'];
 $validasi_admin = '0';
 $bukti=$_FILES['image']['name'];
 $type=$_FILES['image']['type'];
 $size=$_FILES['image']['size'];
 $temp=$_FILES['image']['tmp_name'];
 $image="images/".$bukti; //set upload folder path
 if(empty($bukti)){
     $errorMsg = "please enter name";
 } else if(empty($bukti)){
     $errorMsg = "please input image";
 }else if($type=="image/jpg" || $type=='image/jpeg' || $type=='image/png' || $type=='image/gif'){
    if(!file_exists($image)){
        if($size < 5000000){
            move_uploaded_file($temp, "images/".$bukti);
            if($konfirmasi->create($id_status,$id_ticket,$bank,$image,$validasi_admin))
            {
             header("Location: index.php?inserted");
            }
            else
            {
             header("Location: konfirmasi.php?failure");
            }
            if(isset($_GET['inserted']))
            {
            echo'<div class="clearfix"></div>
                <div class="container kocak">
             <div class="alert alert-info">
                <strong>WOW!</strong> Record was inserted successfully <a href="index.php">HOME</a>!
             </div>
             </div>';
            }
            else if(isset($_GET['failure']))
            {
              echo'  <div class="container kocak">
             <div class="alert alert-warning">
                <strong>SORRY!</strong> ERROR while inserting record !
             </div>
             </div>';
            }
        } else{
            $errorMsg="your file to large please upload 5mb size";
        }
    } else{
        $errorMsg="file upload already exist";
    }
 }
 else{
     $errorMsg="Upload jpg,png,jpeg,gif";
 }
}
?>
<?php
//getting id from url
$id = $_GET['id_ticket'];
 
//selecting data associated with this particular id
$sql = "SELECT * FROM ticket WHERE id_ticket=:id";
$query = $DB_con->prepare($sql);
$query->execute(array(':id' => $id));
 
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
    $id_ticket = $row['id_ticket'];
    $id_status = $row['id_status'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
 
<title>Fashion warehouse project</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css"/>
<link rel="stylesheet" href="style/main.scss"/>
<link rel="stylesheet" href="style/main.css"/>
<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic|Roboto+Mono:400,500|Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
   integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
   crossorigin=""></script>
   <script src='https://api.mapbox.com/mapbox-gl-js/v0.50.0/mapbox-gl.js'></script>
   <link href='https://api.mapbox.com/mapbox-gl-js/v0.50.0/mapbox-gl.css' rel='stylesheet' />
<style>
    label{
        color:white;
    }
</style>
</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top" style="background-color:black">
 <!-- Navbar Section -->
 <div class="my-nav">
         <nav class="navbar navbar-inverse navbar-fixed-top navbar-transparent mnav affix-top" data-spy="affix" data-offset-top="1" data-offset-bottom="1">
            <div class="container mnav">
               <!-- Brand and toggle get grouped for better mobile display -->
               <div class="navbar-header mnav">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                  <a class="navbar-brand font-sm" href="#"><i class="fa fa-pied-piper-pp"></i> FWP <i class="fa fa-pied-piper-alt"></i></a> 
               </div>
               <!-- Collect the nav links, forms, and other content for toggling -->
               <div id="navbar" class="collapse navbar-collapse navbar-right">
                  <ul class="nav navbar-nav navbar-left mnav">
                     <li class="active mnav"><a href="#home">Home</a></li>
                     <li><a href="#about">About</a></li>
                     <li><a href="#service">location</a></li>
                     <li><a href="#product">News</a></li>
                     <li class="gokil"><a href="#portfolio">Buy ticket</a></li>
                     <li><a data-toggle="modal" href="#register">
                        Register
                    </a></li>
                    <li><a  href="#" data-file="my file 1" class="rename">Login</a>
                     </li>
                  </ul>
               </div>
               <!-- /.navbar-collapse --> 
            </div>
            <!-- /.container-fluid --> 
         </nav>
      </div>
  <div class="container-fluid articlenws" style="
     background-image:url('images/bg.jpg'); background-size:100%; height:350px;margin-bottom:100px;">
      <h1 class="text-center" style="margin-top:120px; color:white;">Konfirmasi pembayaran</h1>
  </div>
 </div>
    <div class="row">
    <div class="container">
    <form method="post" enctype="multipart/form-data">
        <div class="form-group row">
        <label class="col-form-label col-md-3 col-lg-2">id status</label>
            <div class="col-md-7">
                <input type="text" class="form-control" name="id_status" value="<?php echo $id_status;?>" disable>
            </div>
        </div>
        <div class="form-group row">
        <label class="col-form-label col-md-3 col-lg-2">id ticket</label>
            <div class="col-md-7">
                <input type="text" class="form-control" name="id_ticket" value="<?php echo $id_ticket;?>" disable>
            </div>
        </div>
        <div class="form-group row">
        <label class="col-form-label col-md-3 col-lg-2">bank</label>
            <div class="col-md-7">
                <input type="text" class="form-control" name="bank">
            </div>
        </div>
        <div class="form-group row">
        <label class="col-form-label col-md-3 col-lg-2">bukti pembayaran</label>
            <div class="col-md-8">
                <input type="file"  class="form-control"  name="image" class="form-control"  placeholder="nama">
            </div>
        </div>
        <div class="form-group row">
        <button type="submit" class="btn btn-primary" name="btn-save">
      <span class="glyphicon glyphicon-plus"></span> Create New Record
   </button>  
            <a href="index.php" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Back to index</a>
        </div>
    </form>
    </div>
    </div>
    </footer>
    <?php require("modal/modal_login.php"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="style/js/main.js"></script>
</body>
</html>