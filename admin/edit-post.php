<?php 
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['login'])==0){ 
    header('location:index.php');
}
else{
    // UPDATE
    if(isset($_POST['update'])){
        $posttitle   = $_POST['posttitle'];
        $catid       = $_POST['category'];
        $subcatid    = $_POST['subcategory'];
        $postdetails = $_POST['postdescription'];
        $arr         = explode(" ",$posttitle);
        $url         = implode("-",$arr);
        $status      = 1;
        $postid      = intval($_GET['pid']);
        $postedby    = $_POST['postedby'];

        // jika upload gambar baru
        if(!empty($_FILES['postimage']['name'])){
            $imgfile = $_FILES["postimage"]["name"];
            $extension = substr($imgfile,strlen($imgfile)-4,strlen($imgfile));
            $allowed_extensions = array(".jpg","jpeg",".png",".gif");
            if(!in_array($extension,$allowed_extensions)){
                $error="Invalid format. Only jpg / jpeg/ png /gif format allowed";
            }
            else{
                $imgnewfile=md5($imgfile).$extension;
                move_uploaded_file($_FILES["postimage"]["tmp_name"],"postimages/".$imgnewfile);

                $query=mysqli_query($con,"update tblposts set 
                    PostTitle='$posttitle',
                    CategoryId='$catid',
                    SubCategoryId='$subcatid',
                    PostDetails='$postdetails',
                    PostUrl='$url',
                    Is_Active='$status',
                    PostedBy='$postedby',
                    PostImage='$imgnewfile',
                    UpdationDate=NOW()
                    where id='$postid'");
            }
        } else {
            // tanpa ganti gambar
            $query=mysqli_query($con,"update tblposts set 
                PostTitle='$posttitle',
                CategoryId='$catid',
                SubCategoryId='$subcatid',
                PostDetails='$postdetails',
                PostUrl='$url',
                Is_Active='$status',
                PostedBy='$postedby',
                UpdationDate=NOW()
                where id='$postid'");
        }

        if($query){
            $msg="Post updated successfully";
        } else {
            $error="Something went wrong . Please try again.";    
        } 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cakrawala | Edit Artikel</title>
     
    <link rel="icon" href="assets/images/Logo.ico" type="image/x-icon">
    <link href="../plugins/summernote/summernote.css" rel="stylesheet" />
    <link href="../plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/jquery.filer/css/jquery.filer.css" rel="stylesheet" />
    <link href="../plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/modernizr.min.js"></script>
    <script>
    function getSubCat(val) {
      $.ajax({
          type: "POST",
          url: "get_subcategory.php",
          data:'catid='+val,
          success: function(data){
              $("#subcategory").html(data);
          }
      });
    }
    </script>
</head>
<body class="fixed-left">
<div id="wrapper">
    <?php include('includes/topheader.php');?>
    <?php include('includes/leftsidebar.php');?>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Edit Post </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="#">Admin</a></li>
                                <li><a href="#"> Posts </a></li>
                                <li class="active">Edit Post</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <!-- alert -->
                <div class="row">
                    <div class="col-sm-6">  
                    <?php if($msg){ ?>
                        <div class="alert alert-success" role="alert">
                        <strong>Well done!</strong> <?php echo htmlentities($msg);?>
                        </div>
                    <?php } ?>
                    <?php if($error){ ?>
                        <div class="alert alert-danger" role="alert">
                        <strong>Oh snap!</strong> <?php echo htmlentities($error);?>
                        </div>
                    <?php } ?>
                    </div>
                </div>

                <?php
                $postid=intval($_GET['pid']);
                $query=mysqli_query($con,"select tblposts.*, 
                        tblcategory.CategoryName as category,
                        tblsubcategory.Subcategory as subcategory
                        from tblposts 
                        left join tblcategory on tblcategory.id=tblposts.CategoryId 
                        left join tblsubcategory on tblsubcategory.SubCategoryId=tblposts.SubCategoryId 
                        where tblposts.id='$postid'");
                while($row=mysqli_fetch_array($query)){
                ?>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="p-6">
                            <div class="">
                                <form name="addpost" method="post" enctype="multipart/form-data">
                                    <div class="form-group m-b-20">
                                        <label>Post Title</label>
                                        <input type="text" class="form-control" name="posttitle" value="<?php echo htmlentities($row['PostTitle']);?>" required>
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>Category</label>
                                        <select class="form-control" name="category" id="category" onChange="getSubCat(this.value);" required>
                                            <option value="<?php echo htmlentities($row['CategoryId']);?>"><?php echo htmlentities($row['category']);?></option>
                                            <?php
                                            $ret=mysqli_query($con,"select id,CategoryName from tblcategory where Is_Active=1");
                                            while($result=mysqli_fetch_array($ret)){    
                                            ?>
                                            <option value="<?php echo htmlentities($result['id']);?>"><?php echo htmlentities($result['CategoryName']);?></option>
                                            <?php } ?>
                                        </select> 
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>Sub Category</label>
                                        <select class="form-control" name="subcategory" id="subcategory" required>
                                            <option value="<?php echo htmlentities($row['SubCategoryId']);?>"><?php echo htmlentities($row['subcategory']);?></option>
                                        </select> 
                                    </div>

                                    <div class="form-group m-b-20">
                                        <label>Current Image</label><br>
                                        <?php if($row['PostImage']!=''){ ?>
                                            <img src="uploads/<?php echo htmlentities($row['PostImage']);?>" width="200" />
                                        <?php } ?>
                                        <br><br>
                                        <input type="file" name="postimage" class="form-control">
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card-box">
                                                <h4 class="m-b-30 m-t-0 header-title"><b>Post Details</b></h4>
                                                <textarea class="summernote" name="postdescription" required><?php echo htmlentities($row['PostDetails']);?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" name="update" class="btn btn-success waves-effect waves-light">Update </button>
                                </form>
                            </div>
                        </div> 
                    </div> 
                </div>
                <?php } ?>

            </div> <!-- container -->
        </div> <!-- content -->
        <?php include('includes/footer.php');?>
    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="../plugins/summernote/summernote.min.js"></script>
<script src="../plugins/select2/js/select2.min.js"></script>
<script>
jQuery(document).ready(function(){
    $('.summernote').summernote({ height: 240 });
    $(".select2").select2();
});
</script>
</body>
</html>
<?php } ?>
