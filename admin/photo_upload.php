<?php session_start();
include "../utils.php";
$login = $_SESSION['login'];
echo "<html><head></head><body><img src=\"../img/loading.gif\">";

class SimpleImage {
 
   var $image;
   var $image_type;
 
   function load($filename) {
 
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
 
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
 
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
 
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }      
 
}


$b = $idb;
$b_photo = $b."_photo";
$r3 = mysql_query("SELECT * FROM $b_photo");
$i = 0;
$max = 1;
 while ($row = mysql_fetch_array($r3)) {
 	$i++;
 	$ind  = explode('_',$row['name']);
 	$ind1 = explode('.',$ind[1]);
 	$ind2 = $ind1[0];
 	if ($max < $ind2) {
 		$max = $ind2;
 	}
 	
 }
 
$photo_id = ++$max;
if ($i > 10) {
	$r = false;
} else {
    $r = true;
}


if ($r) {
	$filename = $b."_".check($photo_id).".jpg";
	$dir = "../upload/";
	$file = $_FILES['photo'];

   if ($file['type'] == "image/jpeg" || $file['type'] == "image/pjpeg" ||  $file['type'] == "image/jpg" ) {
		$trans = new SimpleImage();
		$trans->load($file['tmp_name']);
		$wi = $trans->getWidth();
		$he = $trans->getHeight();
		$trans->resizeToWidth(640);
		$trans->save($file['tmp_name']);
		$r2 = move_uploaded_file($file['tmp_name'], $dir.$filename);
		$r4 = mysql_query("INSERT INTO {$idb}_photo (name) VALUES ('$filename')");
		if ($r2 && $r4) {
			echo "<meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/admin/photo.php?b={$b}&act=1\"></body></html>";
		}
	}
}



if (!$r ||  !$r2 || !$r4){
if (!$r) {
   $er="1";
}
if (!$r2){
   $er=$er."2";
}
if (!$r4){
   $er=$er."4";
}
 echo"<meta http-equiv=\"refresh\" content=\"1;URL=http://www.basebooking.ru/admin/photo.php?b={$b}&act=2&er={$er}\"></body></html>";}
?>