<?php

$content = isset($_GET['content']) ? $_GET['content'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<form>
    <input type="text" name="content" value="" style="width:200px" />
    <input type="submit" value="submit" />

</form>



页面内容：<?php echo $content;?>

</body>
</html>




