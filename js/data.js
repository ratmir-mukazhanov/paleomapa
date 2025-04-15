// Carrega dados de escolas e cafés + filtra via Turf
window.App = window.App || {};

App.loadFossilsData = function() {
  $.ajax({
    url: '../scripts/fosseis_turf.php',
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
    url: '../scripts/cafes_turf.php',
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

App.loadBenchsData = function() {
  $.ajax({
    url: '../scripts/benchs_turf.php',
    async: false,
    success: (data) => {
      App.state.benchs.source.clear();

      App.state.benchs.benchs_turf = data;

      // Adiciona inicialmente (se quiser ver todos de cara):
      App.state.benchs.source.addFeatures(App.state.geojsonFormat.readFeatures(data, {
        dataProjection: 'EPSG:4326',
        featureProjection: 'EPSG:3857'
      }));
    },
    error: (xhr, status, error) => {
      console.error("Erro ao carregar dados de zonas de descanso:", error);
    }
  });
};

App.updateBenchsWithinIsochrone = function() {
  App.state.benchs.source.clear();

  App.state.benchs.benchsDentroHull = turf.pointsWithinPolygon(
    App.state.benchs.benchs_turf,
    App.state.isochrone.hull_turf
  );

  App.state.benchs.source.addFeatures(
    App.state.geojsonFormat.readFeatures(App.state.benchs.benchsDentroHull, {
      dataProjection: 'EPSG:4326',
      featureProjection: 'EPSG:3857'
    })
  );
};

App.loadMuseumsData = function() {
  $.ajax({
    url: '../scripts/museums_turf.php',
    async: false,
    success: (data) => {
      App.state.museums.source.clear();

      App.state.museums.museums_turf = data;

      // Adiciona inicialmente (se quiser ver todos de cara):
      App.state.museums.source.addFeatures(App.state.geojsonFormat.readFeatures(data, {
        dataProjection: 'EPSG:4326',
        featureProjection: 'EPSG:3857'
      }));
    },
    error: (xhr, status, error) => {
      console.error("Erro ao carregar dados de Museus:", error);
    }
  });
};

App.updateMuseumsWithinIsochrone = function() {
  App.state.museums.source.clear();

  App.state.museums.museumsDentroHull = turf.pointsWithinPolygon(
    App.state.museums.museums_turf,
    App.state.isochrone.hull_turf
  );

  App.state.museums.source.addFeatures(
    App.state.geojsonFormat.readFeatures(App.state.museums.museumsDentroHull, {
      dataProjection: 'EPSG:4326',
      featureProjection: 'EPSG:3857'
    })
  );
};

App.loadArchaelogicalData = function() {
  $.ajax({
    url: '../scripts/archaelogical_turf.php',
    async: false,
    success: (data) => {
      App.state.archaelogical.source.clear();

      App.state.archaelogical.archaelogical_turf = data;

      // Adiciona inicialmente (se quiser ver todos de cara):
      App.state.archaelogical.source.addFeatures(App.state.geojsonFormat.readFeatures(data, {
        dataProjection: 'EPSG:4326',
        featureProjection: 'EPSG:3857'
      }));
    },
    error: (xhr, status, error) => {
      console.error("Erro ao carregar dados de de Arqueologia:", error);
    }
  });
};

App.updateArchaelogicalWithinIsochrone = function() {
  App.state.archaelogical.source.clear();

  App.state.archaelogical.archaelogicalDentroHull = turf.pointsWithinPolygon(
    App.state.archaelogical.archaelogical_turf,
    App.state.isochrone.hull_turf
  );

  App.state.archaelogical.source.addFeatures(
    App.state.geojsonFormat.readFeatures(App.state.archaelogical.archaelogicalDentroHull, {
      dataProjection: 'EPSG:4326',
      featureProjection: 'EPSG:3857'
    })
  );
};
