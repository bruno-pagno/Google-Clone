<?php
    $term = $_GET["term"];
    if($term == "")exit("You must enter a search term!");

    $type = isset($_GET["type"]) ? $_GET["type"] : "sites";




    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to Boogle</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="wrapper">

        <div class="header">

            <div class="headerContent">
                
                <div class="logoContainer">
                    <a href="index.php">
                        <img src="assets/images/logo.png" alt="Boogle Logo">        
                    </a>
                </div>

                <div class="searchContainer">
                    
                    <form action="search.php" method="GET">
                    
                        <div class="searchBarContainer">

                            <input type="text" class="searchBox" name="term">
                            <button class="searchButton">
                                <img src="assets/images/icons/searchIcon.png" alt="Search Icon">
                            </button>

                        </div>

                    </form>

                </div>
            
            </div>

            <div class="tabsContainer">
                
                <ul class="tabList">

                    <li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>
                            Sites
                        </a>
                    </li>


                    <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                        <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
                            Images
                        </a>
                    </li>

                </ul>

            </div>
        
        </div>
    </div>

</body>
</html>