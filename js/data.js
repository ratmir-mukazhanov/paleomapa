// Carrega dados de escolas e cafés + filtra via Turf
window.App = window.App || {};

App.loadFossilsData = function() {
  $.ajax({
    url: './scripts/fosseis_turf.php',
    async: false,
    success: (data) => {
      App.state.fossils.source.clear();

      const features = App.state.geojsonFormat.readFeatures(data);
      App.state.fossils.fosseis_turf = App.state.geojsonFormat.writeFeaturesObject(features);

      App.updateFossilsWithinIsochrone();
    }
  });
};

App.updateFossilsWithinIsochrone = function() {
  // Limpa a fonte
  App.state.fossils.source.clear();

  // Filtra com Turf
  App.state.fossils.fosseisDentroHull = turf.pointsWithinPolygon(
    App.state.fossils.fosseis_turf,
    App.state.isochrone.hull_turf
  );

  // Adiciona só as que estão dentro
  App.state.fossils.source.addFeatures(
    App.state.geojsonFormat.readFeatures(App.state.fossils.fosseisDentroHull, {
      dataProjection: 'EPSG:4326',
      featureProjection: 'EPSG:3857'
    })
  );
};

App.loadCafesData = function() {
  $.ajax({
    url: './scripts/cafes_turf.php',
    async: false,
    success: (data) => {
      App.state.cafes.source.clear();

      App.state.cafes.cafes_turf = data;

      // Adiciona inicialmente (se quiser ver todos de cara):
      App.state.cafes.source.addFeatures(App.state.geojsonFormat.readFeatures(data, {
        dataProjection: 'EPSG:4326',
        featureProjection: 'EPSG:3857'
      }));
    },
    error: (xhr, status, error) => {
      console.error("Erro ao carregar dados de cafés:", error);
    }
  });
};

App.updateCafesWithinIsochrone = function() {
  App.state.cafes.source.clear();

  App.state.cafes.cafesDentroHull = turf.pointsWithinPolygon(
    App.state.cafes.cafes_turf,
    App.state.isochrone.hull_turf
  );

  App.state.cafes.source.addFeatures(
    App.state.geojsonFormat.readFeatures(App.state.cafes.cafesDentroHull, {
      dataProjection: 'EPSG:4326',
      featureProjection: 'EPSG:3857'
    })
  );
};
