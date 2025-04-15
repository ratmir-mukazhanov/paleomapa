// Armazena o "estado" principal da aplicação (mapa, layers, dados, etc.)
window.App = window.App || {};

App.state = {
  coordenadas_3857: [],
  coordenadas_4326: [],
  currentTransportMode: 'ape',
  isochrone: {
    hull_turf: null,
    source: null,
    layer: null
  },
  fossils: {
    fosseis_turf: null,
    fosseisDentroHull: null,
    source: null,
    layer: null
  },
  cafes: {
    cafes_turf: null,
    cafesDentroHull: null,
    source: null,
    layer: null
  },
  benchs: {
    benchs_turf: null,
    benchsDentroHull: null,
    source: null,
    layer: null
  },
  museums: {
    museums_turf: null,
    museumsDentroHull: null,
    source: null,
    layer: null
  },
  archaelogical: {
    archaelogical_turf: null,
    archaelogicalDentroHull: null,
    source: null,
    layer: null
  },
  geojsonFormat: new ol.format.GeoJSON(),
  startPoint: {
    feature: null,
    layer: null
  },
  popup: null,
  map: null
};
