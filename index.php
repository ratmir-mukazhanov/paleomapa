<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/cores.css">
    <title>Paleomapa - HomePage</title>
</head>

<?php 
  require_once "header.php";
  require_once "sidebar.php";
?>

<body>

<div class="main-content">
    <div
        class="rssFeed"
        data-rss-feed="https://paleontologyworld.com/blog/feed" 
        data-rss-link-titles="true" 
        data-rss-title-wrapper="h3" 
        data-rss-max="1">
    </div>
</div>


</body>

<script src="./js/index.js"></script>

</html>