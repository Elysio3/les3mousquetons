<!DOCTYPE html>
<html lang="en">


<head>
<?php

include $path.'/views/components/autoload.php';

#region session and header
    if(isset($_SESSION['user'])) {
        include $path.'/views/components/header-online-mobile.php';
    } else {
        include $path.'/views/components/header-offline-mobile.php';
    }
#endregion

?>
</head>

<body>

<!-- content here -->

<?php include $path.'/views/components/footer.php'; ?>
</body>
</html>