// Define a configuração das camadas (isócrona, escolas, cafés, ponto inicial)
window.App = window.App || {};

// Cria a layer para a isócrona
App.setupIsochroneLayer = function() {
  App.state.isochrone.source = new ol.source.Vector({});
  App.state.isochrone.layer = new ol.layer.Vector({
    title: 'hull',
    source: App.state.isochrone.source
  });
  App.state.map.addLayer(App.state.isochrone.layer);
};

App.setupFossilsLayer = function() {
  App.state.fossils.source = new ol.source.Vector({
    format: new ol.format.GeoJSON(),
    projection: 'EPSG:4326'
  });

  App.state.fossils.layer = new ol.layer.Vector({
    title: 'Instituições de ensino',
    nome: 'fosseis_layer',
    source: App.state.fossils.source,
    style: App.createFossilStyles()
  });

  // Define como selecionável
  App.state.fossils.layer.set('selectable', true);
  App.state.map.addLayer(App.state.fossils.layer);
};

App.setupCafesLayer = function() {
  App.state.cafes.source = new ol.source.Vector({
    format: new ol.format.GeoJSON(),
    projection: 'EPSG:4326'
  });

  App.state.cafes.layer = new ol.layer.Vector({
    title: 'Cafés',
    nome: 'cafes_layer',
    source: App.state.cafes.source,
    style: App.createCafeStyles()
  });

  App.state.cafes.layer.set('selectable', true);
  App.state.map.addLayer(App.state.cafes.layer);
};

App.setupStartPointLayer = function() {
  // Cria a feature do ponto de partida
  App.state.startPoint.feature = new ol.Feature();

  // Define estilo
  const startPointStyle = [
    new ol.style.Style({
      image: new ol.style.Icon({
        opacity: 0.75,
        anchor: [0.5, 300],
        anchorXUnits: 'fraction',
        anchorYUnits: 'pixels',
        src: './img/start.png',
        scale: 0.15
      })
    }),
    new ol.style.Style({
      image: new ol.style.Circle({
        radius: 5,
        fill: new ol.style.Fill({
          color: 'rgba(230,120,30,0.7)'
        })
      })
    })
  ];

  App.state.startPoint.feature.setStyle(startPointStyle);

  // Cria a layer
  App.state.startPoint.layer = new ol.layer.Vector({
    source: new ol.source.Vector({
      features: [App.state.startPoint.feature]
    })
  });

  App.state.map.addLayer(App.state.startPoint.layer);
};

// Estilo para fósseis
App.createFossilStyles = function() {
  return new ol.style.Style({
    image: new ol.style.Icon({
      anchor: [0.5, 1.0],  // Ajuste a posição conforme necessário
      scale: 0.05,          // Valor < 1 para diminuir
      src: './img/fossil.png'
    })
  });
};

// Estilo para os cafés
App.createCafeStyles = function() {
  return new ol.style.Style({
    image: new ol.style.Icon({
      anchor: [0.5, 1.0],   // Ajuste conforme desejar
      scale: 0.04,           // Ajuste conforme o tamanho que quer
      src: './img/cafe.png' // Caminho do ícone
    })
  });
};

