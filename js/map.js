document.getElementById("headerTitlePage").textContent = "Paleontology Map of Portugal";

// Responsável pela criação e configuração inicial do mapa e view
window.App = window.App || {};

App.createMap = function() {
  // Cria camadas base (Bing, OSM)
  const baseLayers = App.createBaseLayers();

  // Define a view do mapa
  const view = new ol.View({
    projection: 'EPSG:3857',
    center: ol.proj.transform(App.config.initialCoords, 'EPSG:4326', 'EPSG:3857'),
    zoom: App.config.initialZoom,
    minZoom: App.config.minZoom,
    maxZoom: App.config.maxZoom
  });

  // Cria popup overlay
  App.state.popup = new ol.Overlay.Popup({
    popupClass: "default anim",
    closeBox: true,
    positioning: $("#positioning").val(),
    autoPan: {
      animation: { duration: 100 }
    }
  });

  // Instancia o mapa e guarda em App.state.map
  App.state.map = new ol.Map({
    layers: baseLayers,
    target: 'mapa',
    renderer: 'canvas',
    view: view,
    overlays: [App.state.popup]
  });
};

App.createBaseLayers = function() {
  const layers = [];

  // Camada Bing (Aérea)
  layers[0] = new ol.layer.Tile({
    source: new ol.source.BingMaps({
      key: App.config.bingMapsKey,
      imagerySet: 'Aerial'
    })
  });

  // Camada OpenStreetMap
  layers[1] = new ol.layer.Tile({
    source: new ol.source.OSM()
  });

  return layers;
};
