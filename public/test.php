<?php


var_dump($_REQUEST,$_POST);

if($_FILES){
    var_dump($_FILES['file']);
}
?>

<form name="test" method="post" action="" enctype="multipart/form-data" >
    <input name="file" type="file" />
    <br />
    <input type="submit" value="sm">

</form>

