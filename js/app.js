// Onde juntamos tudo: init, loadInitialData, e chamamos cada parte do fluxo
window.App = window.App || {};

App.init = function() {
  App.setupUI();
  App.createMap();

  // Configurar as layers principais
  App.setupIsochroneLayer();
  App.setupFossilsLayer();
  App.setupCafesLayer();
  App.setupBenchsLayer();
  App.setupMuseumsLayer();
  App.setupArchaelogicalLayer();
  App.setupStartPointLayer();

  // Configurar popup
  App.setupPopup();

  // Controles
  App.setupControls();

  // Eventos
  App.setupEventListeners();

  // Drag & Drop
  App.setupDragAndDrop();

  // Carregar dados iniciais
  App.loadInitialData();
};

App.setupUI = function() {
  $('#sl1').slider();
};

App.setupControls = function() {
  // Exemplo: add attribution, fullscreen, rotate, scaleline, zoom
  const attributionControl = new ol.control.Attribution({ className: 'ol-attribution', target: null });
  App.state.map.addControl(attributionControl);

  App.state.map.addControl(new ol.control.FullScreen());
  App.state.map.addControl(new ol.control.Rotate());
  App.state.map.addControl(new ol.control.ScaleLine());
  App.state.map.addControl(new ol.control.Zoom());
};

App.setupDragAndDrop = function() {
  const dragAndDrop = new ol.interaction.DragAndDrop({
    formatConstructors: [
      ol.format.GPX,
      ol.format.GeoJSON,
      ol.format.KML,
      ol.format.TopoJSON
    ]
  });
  dragAndDrop.on('addfeatures', (event) => {
    const vectorSource = new ol.source.Vector({
      features: event.features,
      projection: event.projection
    });
    App.state.map.getLayers().push(new ol.layer.Vector({ source: vectorSource }));
    App.state.map.getView().fit(vectorSource.getExtent(), App.state.map.getSize());
  });
  App.state.map.addInteraction(dragAndDrop);
};

App.switchBaseLayer = function() {
  const checkedLayer = $('#layerswitcher input[name=layer]:checked').val();
  const baseLayers = App.state.map.getLayers().getArray().filter(layer =>
    layer instanceof ol.layer.Tile && layer.getSource() instanceof ol.source.TileImage
  );
  for (let i = 0; i < baseLayers.length; i++) {
    baseLayers[i].setVisible(i == checkedLayer);
  }
};

// Carregamento inicial de dados e layers
App.loadInitialData = function() {
  // Usa as coords iniciais da config
  App.state.coordenadas_4326 = App.config.initialCoords;

  // Converte para 3857 corretamente
  const projected = ol.proj.transform(App.state.coordenadas_4326, 'EPSG:4326', 'EPSG:3857');
  App.state.startPoint.feature.setGeometry(new ol.geom.Point(projected));

  // Isócrona inicial
  App.loadInitialIsochrone();

  // Fosséis
  App.loadFossilsData();

  // Cafés
  App.loadCafesData();
  App.updateCafesWithinIsochrone();

  // Zonas de Descanso
  App.loadBenchsData();
  App.updateBenchsWithinIsochrone();

  // Museus
  App.loadMuseumsData();
  App.updateMuseumsWithinIsochrone();

  // Sítios Arqueológicos
  App.loadArchaelogicalData();
  App.updateArchaelogicalWithinIsochrone();

  // Recarrega isócrona para garantir que tudo se encaixe
  App.loadInitialIsochrone();

  // Ajusta a camada base
  App.switchBaseLayer();
};

// Inicia
$(document).ready(function() {
  App.init();
});
