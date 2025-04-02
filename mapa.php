<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Mapa com OpenLayers</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.4.0/ol.css">
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <div id="map"></div>

  <div id="popup" class="ol-popup">
    <div id="popup-content"></div>
  </div>

  <!-- Панель выбора слоя -->
  <div id="layer-toggle">
  <label for="baselayer-select"><strong>Base Layer:</strong></label><br>
  <select id="baselayer-select">
    <option value="standard">Padrão</option>
    <option value="humanitarian">Humanitário</option>
    <option value="topo">Topográfico</option>
  </select>
</div>

  <!-- Панель фильтров -->
  <div id="filters">
    <strong>Filtros:</strong><br>
    <!-- Здесь будут будущие фильтры -->
  </div>

  <script src="https://cdn.jsdelivr.net/npm/ol@v7.4.0/dist/ol.js"></script>
  <script src="./js/map.js"></script>
</body>
</html>
