<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Mapa com OpenLayers</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.4.0/ol.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>
  <div class="layout-wrapper">
    <?php require_once "header.php"; ?>

    <div class="layout-body">
      <?php require_once "sidebar.php"; ?>

      <div class="main-content">
        <div id="map"></div>

        <div id="popup" class="ol-popup">
          <div id="popup-content"></div>
        </div>

        <div id="layer-toggle">
          <label for="baselayer-select"><strong>Base Layer:</strong></label><br>
          <select id="baselayer-select">
            <option value="standard">Padrão</option>
            <option value="humanitarian">Humanitário</option>
            <option value="topo">Topográfico</option>
          </select>
        </div>

        <div id="filters">
          <strong>Filtros:</strong><br>
          <!-- Filtros -->
        </div>
      </div>
    </div>
  </div>
</body>


<script src="https://cdn.jsdelivr.net/npm/ol@v7.4.0/dist/ol.js"></script>
<script src="./js/mapa.js"></script>

</html>
