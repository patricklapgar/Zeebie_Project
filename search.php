<?php 
    include("config.php");
    include("classes/SiteResultsProvider.php");

    $term = isset($_GET["term"]) ? $_GET["term"] : exit("You must enter a search term");
    $type = isset($_GET["type"]) ? $_GET["type"] : "sites";   
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;   

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Zeebee</title>
        
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
        
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="wrapper">

            <div class="header">
                <div class="headerContent">
                    <div class="logoContainer">
                        <a href="index.php">
                            <img src="assets/images/zeebeeLogo.png" alt="">
                        </a>
                    </div>

                    <div class="searchContainer">
                        <form action="search.php" method="GET">
                            <div class="searchBarContainer">
                                <input type="text" class="searchBox" name="term" value="<?php echo $term; ?>">
                                <button class="searchButton">
                                    <img src="assets/images/icons/search.png" alt="">
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tabsContainer">
                    <ul class="tabList">
                        <li class="<?php echo $type == 'sites' ? 'active' : ''; ?>">
                            <a  href='<?php echo "search.php?term=$term&type=sites"; ?>'>
                                Sites
                            </a>
                        </li>
                        <li class="<?php echo $type == 'images' ? 'active' : ''; ?>">
                            <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
                                Images
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mainResultsSection">
                <?php
                    $resultsProvider = new SiteResultsProvider($con);

                    $pageSize = 20;
                    $numResults = $resultsProvider->getNumResults($term);
                    
                    echo "<p class='resultsCount'>$numResults results found</p>";

                    echo $resultsProvider->getResultsHtml($page, $pageSize, $term);
                ?>
            </div>

            <div class="paginationContainer">
                <div class="pageButtons">
                    <div class="pageNumberContainer">
                        <img src="assets/images/pageStart.png" alt="">
                    </div>

                    <?php 

                        $pagesToShow = 10;
                        $numPages = ceil($numResults / $pageSize);
                        $pagesLeft = min($pagesToShow, $numPages);

                        $currentPage = $page - floor($pagesToShow / 2);

                        if($currentPage < 1){
                            $currentPage = 1;
                        }

                        if($currentPage + $pagesLeft > $numPages + 1){
                            $currentPage = $numPages + 1 - $pagesLeft;
                        }

                        while($pagesLeft != 0 && $currentPage <= $numPages) {

                            if($currentPage == $page){
                                echo "<div class='pageNumberContainer'>
                                        <img src='assets/images/pageSelected.png'>
                                        <span class='pageNumber'>$currentPage</span>
                                      </div>";
                            } else {
                                echo "<div class='pageNumberContainer'>
                                        <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                            <img src='assets/images/page.png'>
                                            <span class='pageNumber'>$currentPage</span>
                                        </a>    
                                      </div>";
                            }

                            $currentPage++;
                            $pagesLeft--;
                        }
                    ?>

                    <div class="pageNumberContainer">
                        <img src="assets/images/pageEnd.png" alt="">
                    </div>
                </div> 
            </div>
            
        </div>
        <script type="text/javascript" src="assets/js/script.js"></script>
    </body>
</html>