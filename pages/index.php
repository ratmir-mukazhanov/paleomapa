<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/cores.css">
  <link rel="stylesheet" href="../css/index.css">
  
  <title>Paleomapa - HomePage</title>
</head>

<body>
  <?php 
    require_once "../components/header.php";
    require_once "../components/sidebar.php";
  ?>

  <div class="main-content">
      <div
          class="rssFeed"
          data-rss-feed="https://paleontologyworld.com/blog/feed" 
          data-rss-link-titles="true" 
          data-rss-title-wrapper="h3" 
          data-rss-max="1">
      </div>
  </div>

  <script src="../js/index.js"></script>
</body>
</html>
