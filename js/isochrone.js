// Carregamento e atualização da isócrona (API routing)
window.App = window.App || {};

App.buildIsochroneUrl = function(lat, lon, costingMode, time) {
  return 'https://routing.gis4cloud.pt/isochrone?json=' +
    '{"locations":[{"lat":' + lat + ',"lon":' + lon + '}],' +
    '"costing":"' + costingMode + '","polygons":true,"contours":[{"time":' + time + ',"color":"ff0000"}]}&id=hull inicial';
};

App.loadInitialIsochrone = function() {
  const routing_url = App.buildIsochroneUrl(
    App.config.initialCoords[1],
    App.config.initialCoords[0],
    'pedestrian',
    20
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
  const time = $('#sl1').val();
  const costingMode = App.config.transportModes[App.state.currentTransportMode];

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

      const extent = App.state.isochrone.source.getExtent();
      App.state.map.getView().fit(extent);

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
