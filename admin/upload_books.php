<?php
  session_start();
  if(!isset($_SESSION["AID"])){
    header("location:admin/");
  }
  include("../db.php");
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/register.css">
    <title>Upload books</title>
</head>
<body>

<?php include("header.php");?>

<form action="<?php echo $_SERVER["PHP_SELF"];?>" enctype = "multipart/form-data" method = "post">
  <div class="container content" style="width: 500px; height: 50%; margin:auto; margin-top: 40px; background-color:ivory">
    <h1>Upload books</h1>
    <hr>
    <?php
      if (isset($_POST["upload"])) {
        $target_img = "media/book_img/";
        $target_file = "media/book_file/";

        $target_img_dir = $target_img.basename($_FILES["bookimg"]["name"]);

        $target_img_dir = str_replace(' ', '-', $target_img_dir);
        $target_img_dir = preg_replace('/[^A-Za-z0-9\-_\/.]/', '', $target_img_dir);

        $target_file_dir = $target_file.basename($_FILES["bookfile"]["name"]);

        $qry = "SELECT * from book where bimage = '{$target_img_dir}'";
        $result = $db->query($qry);
        if($result->num_rows>0){
          $find = basename($target_img_dir);
          $ext = pathinfo(basename($target_img_dir), PATHINFO_EXTENSION);
          $replace =  str_replace('.', '', basename($target_img_dir, $ext)).rand(0000,9999).'.'.$ext;
          $target_img_dir = str_replace($find, $replace, $target_img_dir);
        }

        $qry1 = "SELECT * from book where bfile = '{$target_file_dir}'";
        $result1 = $db->query($qry1);
        if($result1->num_rows>0){
          $find1 = basename($target_file_dir);
          $ext1 = pathinfo(basename($target_file_dir), PATHINFO_EXTENSION);
          $replace1 =  str_replace('.', '', basename($target_file_dir, $ext1)).rand(0000,9999).'.'.$ext1;
          $target_file_dir = str_replace($find1, $replace1, $target_file_dir);
        }
        
        if (move_uploaded_file($_FILES["bookimg"]["tmp_name"],$target_img_dir) &&
        move_uploaded_file($_FILES["bookfile"]["tmp_name"],$target_file_dir)) 
        {
          $sql = "INSERT INTO book (bname, author, bimage, bfile, keywords, cat_id, price) 
           VALUES ('{$_POST['name']}','{$_POST['author']}', '{$target_img_dir}', '{$target_file_dir}','{$_POST['keywords']}',{$_POST['book_category']},{$_POST['price']});";
           $res = $db->query($sql);
           echo "<p style='color:green'>Upload successful</p>";
        }
        else {
          echo "<p style='color:red'>Upload unsuccessful</p>";
      }
    }
    ?>
    <label for="name"><b>Name of the book</b></label>
    <input type="text" placeholder="Enter the name of the book" name="name" required>
    
    <label for="author"><b>Author</b></label>
    <input type="text" placeholder="Enter author" name="author" required>

    <label for="keywords" style="margin :auto;"><b>Book Description&emsp;&emsp;&emsp;</b></label>
    <br>
    <div style="padding-top:7px">
    <textarea name="keywords" placeholder="Enter keywords" rows="3" cols="35" style="background-color : rgb(243, 243, 243);" required></textarea>
    </div>
    <br>

    <label for="book_category"><b>Category&emsp;&emsp;&emsp;&nbsp;</b></label>
    <select name="book_category" style="background-color: rgb(235, 235, 235);width:60%;height:40px;" required>
        <?php
          $sql1 = "SELECT * from category";
          $res = $db->query($sql1);
          while ($rows = $res->fetch_assoc()) {
            echo "
            <option value={$rows['cat_id']}>{$rows['cat_name']}</option>
            ";
          }
        ?>
    </select>
    <br><br>

    <label for="book_img"><b>Book Image&emsp;&emsp;</b></label>
    <input type="file" placeholder="Image of book" name="bookimg" required>
    <br><br>

    <label for="book"><b>Book File&emsp;&emsp;&emsp;</b></label>
    <input type="file" placeholder="bookfile" name="bookfile" required>
    <br><br>
    <label for="price"><b>Price&emsp;&emsp;&emsp;&emsp;&emsp;</b></label>
    <input type="number" placeholder="Enter price" name="price" style="background-color: rgb(235, 235, 235)" required>

    <br><br>
    <div class="clearfix" style="padding-left: 3px">
      <button type="submit" class="upload" name="upload">Upload</button>
    </div>
  </div>
</form>

<style>
  #upload{
    background: #8ae600;
  }

  #upload:after{
    color: #8ae600;
  }
</style>

</body>
</html>