<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/cores.css">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Paleomapa - HomePage</title>
  <style>
  </style>
</head>
<body>
  <?php
    require_once "../components/header.php";
    require_once "../components/sidebar.php";
  ?>
  <div class="main-content">
    <h1 class="page-title">Últimas Descobertas Paleontológicas</h1>
    
    <div class="content-wrapper">
      <div class="featured-article">
        <div class="featured-image">
          <img src="../img/destaqueFossil.webp" alt="Destaque Paleontológico">
        </div>
        <div class="featured-content">
          <h2 class="featured-title">Explorando o Mundo da Paleontologia</h2>
          <p class="featured-desc">Descubra as mais recentes pesquisas e descobertas no fascinante campo da paleontologia. Nosso portal traz informações atualizadas sobre fósseis, dinossauros e a história da vida na Terra.</p>
          <a href="#" class="featured-link">Saiba mais</a>
        </div>
      </div>
      
      <div class="news-container">
        <div class="news-header">
          <i class="fas fa-newspaper news-icon"></i>
          <h2>Notícias Recentes</h2>
        </div>
        <div class="rssFeed" 
            data-rss-feed="https://www.fossilcrates.com/blogs/news.atom"
            data-rss-link-titles="true"
            data-rss-title-wrapper="h3"
            data-rss-max="1">
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="../js/index.js"></script>
</body>
</html>