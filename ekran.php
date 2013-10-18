<html>
<head></head>
<form action="ekran.php" method="POST">
<input type="text" name="text">
<input type="submit" name="submit">
</form>
<body></body>
</html>
<?php
if (isset($_POST['text'])) {$text = $_POST['text']; echo "$text";}

 
?>