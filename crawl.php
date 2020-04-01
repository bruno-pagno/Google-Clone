<?php
include("config.php");
include("classes/DomDocumentParser.php");

$alredyCrawled = array();
$crawling = array();
$alredyFoundImages = array();

function linkExists($url) {
    global $con;

    $query = $con->prepare("SELECT * FROM sites WHERE url = :url");

    $query->bindParam(":url", $url);

    $query->execute();
    return $query->rowCount() != 0;
}

function insertLink($url, $title, $description, $keywords) {
    global $con;

    $query = $con->prepare("INSERT INTO sites(url, title, description, keywords)
                            VALUES(:url, :title, :description, :keywords)");

    $query->bindParam(":url", $url);
    $query->bindParam(":title", $title);
    $query->bindParam(":description", $description);
    $query->bindParam(":keywords", $keywords);

    return $query->execute();
}

function insertImage($url, $src, $alt, $title) {
    global $con;

    $query = $con->prepare("INSERT INTO images(siteUrl, imageUrl, alt, title)
                            VALUES(:siteUrl, :imageUrl, :alt, :title)");

    $query->bindParam(":siteUrl", $url);
    $query->bindParam(":imageUrl", $src);
    $query->bindParam(":alt", $alt);
    $query->bindParam(":title", $title);

    return $query->execute();
}

function createLink($src, $url){

    $scheme = parse_url($url)["scheme"]; // Example: http
    $host = parse_url($url)["host"]; // Example: www.bbc.com
    
    if(substr($src, 0, 2) == "//") {
        $src = $scheme . ":" . $src ;
    } 
    else if(substr($src, 0, 1) == "/") {
        $src = $scheme. "://" . $host . $src;
    }
    else if(substr($src, 0, 2) == "./") {
        $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src, 1) ;
    }
    else if(substr($src, 0, 3) == "../") {
        $src = $scheme . "://" . $host . "/" . $src ;
    }
    else if(substr($src, 0, 5) != "https" && substr($src, 0, 4) != "http") {
        $src = $scheme . "://" . $host . "/" . $src ;
    }

    
    return $src;
} 

function getDetails($url) {

    global $alredyFoundImages;
    
    $parser = new DomDocumentParser($url);
    $titleArray = $parser->getTitleTags();

    if(sizeof($titleArray) == 0 || $titleArray->item(0) == NULL){
        return;
    }

    $title = $titleArray->item(0)->nodeValue;
    $title = str_replace("\n", "", $title);

    if($title == "") return;

    $description = "";
    $keywords = "";

    $metaArray = $parser->getMetaTags();
    foreach($metaArray as $meta){
        if($meta->getAttribute("name") == "description"){
            $description = $meta->getAttribute("content");
        }

        if($meta->getAttribute("name") == "keywords"){
            $keywords = $meta->getAttribute("content");
        }
    }

    $keywords = str_replace("\n", "", $keywords);
    $description = str_replace("\n", "", $description);

    if(linkExists($url)) {
        echo "$url alredy exists on the table!<br>";
    }else if(insertLink($url, $title, $description, $keywords)){
        echo "SUCESS! URL: $url<br>";
    }else {
        echo "FAILED to Insert! URL: $url<br>";
    }

    $imageArray = $parser->getImages();
    foreach($imageArray as $image){
        $src = $image->getAttribute("src");
        $title = $image->getAttribute("title");
        $alt = $image->getAttribute("alt");

        if(!$title && !$alt) continue;
        
        $src = createLink($src, $url);
        if(!in_array($src, $alredyFoundImages)) {
            $alredyFoundImages[] = $src;

            echo "INSERTING IMAGE: " . insertImage($url, $src, $alt, $title);
        }
    }
}

function followLinks($url){

    global $alredyCrawled;
    global $crawling;

    $parser = new DomDocumentParser($url);

    $linkList = $parser->getLinks();
    
    foreach($linkList as $link){
        $href = $link->getAttribute("href");

        if(strpos($href, "#") !== false){
            continue;
        }else if(substr("$href", 0, 11) == "javascript:"){
            continue;
        }

        $href = createLink($href, $url);

        if(!in_array($href, $alredyCrawled)){
            $alredyCrawled[] = $href;
            $crawling[] = $href;

            getDetails($href);
        }

    }

    array_shift($crawling);

    foreach($crawling as $site) {
        followLinks($site);    
    }

}

$startUrl = "https://www.dogsday.com.br/";
followLinks($startUrl);
?>