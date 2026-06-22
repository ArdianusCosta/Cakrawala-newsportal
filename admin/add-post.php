<?php 
session_start();
// Pastikan file config.php mendefinisikan variabel koneksi $con
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0){  
    header('location:index.php');
}
else{
    if(isset($_POST['submit']))
    {
        // ==========================================================
        // PERUBAHAN UTAMA: Membersihkan data dengan mysqli_real_escape_string()
        // ==========================================================
        // Membersihkan Post Title dan Post Details (Konten Artikel)
        $posttitle      = mysqli_real_escape_string($con, $_POST['posttitle']);
        $catid          = mysqli_real_escape_string($con, $_POST['category']);
        $subcatid       = mysqli_real_escape_string($con, $_POST['subcategory']);
        $postdetails    = mysqli_real_escape_string($con, $_POST['postdescription']);
        
        $arr            = explode(" ",$posttitle);
        $url            = implode("-",$arr);
        $status         = 1;

        // =======================
        // Upload Gambar
        // =======================
        $imagePath = null;

        if (isset($_FILES['postImage']) && $_FILES['postImage']['error'] == 0) {
            // Path relatif (folder uploads ada di dalam folder admin)
            $targetDir = __DIR__ . "/uploads/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES["postImage"]["name"], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg','jpeg','png','gif'];

            if (in_array($ext, $allowed_ext)) {
                $fileName   = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $_FILES["postImage"]["name"]);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $targetFile)) {
                    // Simpan hanya nama file ke DB
                    $imagePath = $fileName;
                } else {
                    die("❌ Upload gagal! Tidak bisa memindahkan file ke folder uploads. Debug path: " . $targetFile);
                }
            } else {
                die("❌ Format file tidak valid. Hanya boleh jpg, jpeg, png, gif.");
            }
        }


        $authorId = $_SESSION['id'];  // id user yang login
        $status   = 0; // default nunggu approval

        // Kueri sudah aman dari tanda kutip di dalam data
        $query = mysqli_query($con,"INSERT INTO tblposts
            (PostTitle,CategoryId,SubCategoryId,PostDetails,PostUrl,Is_Active,PostImage,PostedBy)  
            VALUES('$posttitle','$catid','$subcatid','$postdetails','$url','$status','$imagePath','$authorId')");
        

        if($query){
            $msg="Post successfully added ";
        } else {
            // Pesan error MariaDB/MySQLi yang lebih informatif
            $error="DB Error: " . mysqli_error($con);
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    
        <link rel="icon" href="assets/images/Logo.ico" type="image/x-icon">
        <title>Cakrawala | Tambah Artikel</title>

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
		<link rel="stylesheet" href="../plugins/switchery/switchery.min.css">
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
                                     <h4 class="page-title">Tulis Artikel </h4>
                                     <ol class="breadcrumb p-0 m-0">
                                         <li>
                                             <a href="#">Cakrawala</a>
                                         </li>
                                      
                                         <li class="active">
                                             Tulis Artikel
                                         </li>
                                     </ol>
                                     <div class="clearfix"></div>
                                 </div>
							</div>
						</div>
                        <div class="row">
<div class="col-sm-6">  
<?php if($msg){ ?>
<div class="alert alert-success" role="alert">
<strong>Well done!</strong> <?php echo htmlentities($msg);?>
</div>
<?php } ?>

<?php if($error){ ?>
<div class="alert alert-danger" role="alert">
<strong>Oh snap!</strong> <?php echo htmlentities($error);?></div>
<?php } ?>


</div>
</div>

                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="p-6">
                                    <div class="">
                                        <form name="addpost" method="post" enctype="multipart/form-data">
 <div class="form-group m-b-20">
<label for="exampleInputEmail1">Judul</label>
<input type="text" class="form-control" id="posttitle" name="posttitle" placeholder="Enter title" required>
</div>



<div class="form-group m-b-20">
<label for="exampleInputEmail1">Kategori</label>
<select class="form-control" name="category" id="category" onChange="getSubCat(this.value);" required>
<option value="">Pilih Kategori</option>
<?php
// Feching active categories
$ret=mysqli_query($con,"select id,CategoryName from  tblcategory where Is_Active=1");
while($result=mysqli_fetch_array($ret))
{     
?>
<option value="<?php echo htmlentities($result['id']);?>"><?php echo htmlentities($result['CategoryName']);?></option>
<?php } ?>

</select>  
</div>
    
<div class="form-group m-b-20">
<label for="exampleInputEmail1">Sub Kategori</label>
<select class="form-control" name="subcategory" id="subcategory" required>

</select>  
</div>
          

   <div class="row">
<div class="col-sm-12">
 <div class="card-box">
<h4 class="m-b-30 m-t-0 header-title"><b>Artikel</b></h4>
<textarea class="summernote" name="postdescription" required></textarea>
</div>
</div>
</div>

<div class="form-group">
  <label for="postImage">Upload Gambar</label>
  <input type="file" name="postImage" id="postImage" class="form-control" accept="image/*" onchange="previewImage(event)">
  <br>
  <img id="preview" src="#" alt="Preview Gambar" style="max-width:200px; display:none; border:1px solid #ddd; padding:4px; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
</div>



<button type="submit" name="submit" class="btn btn-success waves-effect waves-light">Upload</button>
 <button type="button" class="btn btn-danger waves-effect waves-light">Kembali</button>
                                         </form>
                                     </div>
                                 </div> </div> </div>
                         </div> </div> <?php include('includes/footer.php');?>

            </div>
            </div>
        <script>
            var resizefunc = [];
        </script>

        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>
        <script src="../plugins/switchery/switchery.min.js"></script>

        <script src="../plugins/summernote/summernote.min.js"></script>
        <script src="../plugins/select2/js/select2.min.js"></script>
        <script src="../plugins/jquery.filer/js/jquery.filer.min.js"></script>

        <script src="assets/pages/jquery.blog-add.init.js"></script>

        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <script>

            jQuery(document).ready(function(){

                $('.summernote').summernote({
                    height: 240,            // set editor height
                    minHeight: null,        // set minimum height of editor
                    maxHeight: null,        // set maximum height of editor
                    focus: false            // set focus to editable area after initializing summernote
                });
                // Select2
                $(".select2").select2();

                $(".select2-limiting").select2({
                    maximumSelectionLength: 2
                });
            });
        </script>

        <script>
            function previewImage(event) {
            var preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block';
            }
        </script>

  <script src="../plugins/switchery/switchery.min.js"></script>

        <script src="../plugins/summernote/summernote.min.js"></script>

    

    </body>
</html>
<?php } ?>