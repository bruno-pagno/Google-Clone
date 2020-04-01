<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to Boogle</title>
    <link rel="stylesheet" href="assets/css/style.css">

    <meta charset="UTF-8">
    <meta name="description" content="Search for images and Sites">
    <meta name="keywords" content="keywords, search, broogle, images, sites">
    <meta name="author" content="Bruno Pagno">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <div class="wrapper indexPage">

        <div class="mainSection">
        
            <div class="logoContainer">
                <img src="assets/images/logo.png" alt="Boogle Logo">        
            </div>
        
            <div class="searchContainer">

                <form action="search.php" method="GET">
                    <input class="searchBox" type="text" name="term">
                    <input class="searchButton" type="submit" value="Search">
                </form>

            </div>

        </div>

    </div>

</body>
</html>