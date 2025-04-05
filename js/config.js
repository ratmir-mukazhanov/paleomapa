// Define as configurações globais da aplicação
window.App = window.App || {};

App.config = {
  bingMapsKey: 'AvBCehWm6Ep1VVa23v2BM-SsqJ1X3hx7l5CRWAj3ThglltxV7J87lENctywpvfsS',
  initialCoords: [-8.651697, 40.641121], // (longitude, latitude)
  initialZoom: 12,
  minZoom: 4,
  maxZoom: 22,
  transportModes: {
    'ape': 'pedestrian',
    'carro': 'auto',
    'bicicleta': 'bicycle'
  }
};
