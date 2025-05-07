// Configurações de popup, seleção e comportamento
window.App = window.App || {};

App.setupPopup = function() {
  // Exemplo simples:
  App.state.isochrone.layer.set('selectable', false);
  App.state.fossils.layer.set('selectable', true);
  App.state.cafes.layer.set('selectable', true);
  App.state.benchs.layer.set('selectable', true);
  App.state.museums.layer.set('selectable', true);
  App.state.archaelogical.layer.set('selectable', true);
};
