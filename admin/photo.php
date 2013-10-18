<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
body {width:320px; height:120px;font-family:"Trebuchet MS", "Lucida Grande", Verdana, Arial, Helvetica, sans-serif; font-size:12pt;}
</style>
</head>
<body>

<?php if ($_GET['act']==1) {echo"Фотография успешно добавлена<br>Добавить еще";}
 if ($_GET['act']==2) {echo"Произошла ошибка попробуйте снова";}?>
<form enctype="multipart/form-data" action="photo_upload.php" method="post">
 <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
 <input name="photo" class="d1" type="file" value="Выбрать файл" />
 <input type="submit" value="Загрузить"/>
</form>
</body>
</html>
