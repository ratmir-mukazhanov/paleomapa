// Carregamento e atualização da isócrona (API routing)
window.App = window.App || {};

App.buildIsochroneUrl = function(lat, lon, costingMode, time) {
  return 'https://routing.gis4cloud.pt/isochrone?json=' +
    '{"locations":[{"lat":' + lat + ',"lon":' + lon + '}],' +
    '"costing":"' + costingMode + '","polygons":true,"contours":[{"time":' + time + ',"color":"ff0000"}]}&id=hull inicial';
};

App.loadInitialIsochrone = function() {
  const time = parseInt($("input[name='tempo']:checked").val(), 10);
  const costingMode = App.config.transportModes[App.state.currentTransportMode];

  const routing_url = App.buildIsochroneUrl(
    App.config.initialCoords[1],
    App.config.initialCoords[0],
    costingMode,
    time
  );

  $.ajax({
    url: routing_url,
    async: false,
    success: (data) => {
      // Limpar fontes
      App.state.isochrone.source.clear();

      const features = App.state.geojsonFormat.readFeatures(data);
      App.state.isochrone.hull_turf = App.state.geojsonFormat.writeFeaturesObject(features);

      App.state.isochrone.source.addFeatures(App.state.geojsonFormat.readFeatures(data, {
        dataProjection: 'EPSG:4326',
        featureProjection: 'EPSG:3857'
      }));

      App.state.map.getView().fit(App.state.isochrone.source.getExtent());
    }
  });
};

App.updateIsochrone = function() {
  let time = parseInt($("input[name='tempo']:checked").val(), 10);
  
  // Garante que não passa de 15 minutos
  if (time > 15) time = 15;
  if (time < 5) time = 5;

  const costingMode = App.config.transportModes[App.state.currentTransportMode];
  
  // Guarda o zoom atual antes de fazer alterações
  const currentZoom = App.state.map.getView().getZoom();
  const currentCenter = App.state.map.getView().getCenter();

  const routing_url = App.buildIsochroneUrl(
    App.state.coordenadas_4326[1],
    App.state.coordenadas_4326[0],
    costingMode,
    time
  );

  $.ajax({
    url: routing_url,
    async: false,
    success: (data) => {
      // Limpar fontes
      App.state.isochrone.source.clear();
      App.state.fossils.source.clear();
      App.state.cafes.source.clear();
      App.state.benchs.source.clear();
      App.state.museums.source.clear();
      App.state.archaelogical.source.clear();

      const features = App.state.geojsonFormat.readFeatures(data);
      App.state.isochrone.hull_turf = App.state.geojsonFormat.writeFeaturesObject(features);

      App.state.isochrone.source.addFeatures(App.state.geojsonFormat.readFeatures(data, {
        dataProjection: 'EPSG:4326',
        featureProjection: 'EPSG:3857'
      }));

      App.updateFossilsWithinIsochrone();
      App.updateCafesWithinIsochrone();
      App.updateBenchsWithinIsochrone();
      App.updateMuseumsWithinIsochrone();
      App.updateArchaelogicalWithinIsochrone();

      App.state.map.getView().setCenter(ol.proj.transform(App.state.coordenadas_4326, 'EPSG:4326', 'EPSG:3857'));
      App.state.map.getView().setZoom(currentZoom);

      // Ajustar visibilidade
      App.state.isochrone.layer.setVisible(true);
      App.state.fossils.layer.setVisible($('#toggle-fossils').is(':checked'));
      App.state.cafes.layer.setVisible($('#toggle-cafes').is(':checked'));
      App.state.benchs.layer.setVisible($('#toggle-benchs').is(':checked'));
      App.state.museums.layer.setVisible($('#toggle-museums').is(':checked'));
      App.state.archaelogical.layer.setVisible($('#toggle-archaelogical').is(':checked'));
    }
  });
};
