// Configura listeners de eventos (cliques, checkboxes, slider, etc.)
window.App = window.App || {};

App.setupEventListeners = function() {
  App.state.map.on('click', (event) => {
    let featureClicked = false;
  
    App.state.map.forEachFeatureAtPixel(event.pixel, (feature, layer) => {
      if (layer && layer.get('selectable') === true) {
        featureClicked = true;
        App.state.popup.hide();
  
        let icon = '';
        let title = '';
        let htmlDetails = '';
  
        if (layer === App.state.fossils.layer) {
          icon = '<i class="fas fa-bone"></i>';
          title = feature.get("title") || "Fóssil";
          htmlDetails = `
            <p><b>Reino:</b> ${feature.get("kingdom") || "N/A"}</p>
            <p><b>Descoberto por:</b> ${feature.get("discovered_by") || "N/A"}</p>
          `;
        } else if (layer === App.state.cafes.layer) {
          icon = '<i class="fas fa-coffee"></i>';
          title = feature.get("name") || "Café";
        } else if (layer === App.state.benchs.layer) {
          icon = '<i class="fas fa-chair"></i>';
          title = "Zona de Descanso";
        } else if (layer === App.state.museums.layer) {
          icon = '<i class="fas fa-landmark"></i>';
          title = feature.get("name") || "Museu";
        } else if (layer === App.state.archaelogical.layer) {
          icon = '<i class="fas fa-archway"></i>';
          title = feature.get("name") || "Sítio Arqueológico";
        }
  
        const popupContent = `
          <div class="popup-custom">
            <div class="popup-header">${icon}<h4>${title}</h4></div>
            <div class="popup-body">${htmlDetails}</div>
          </div>
        `;
  
        App.state.popup.show(feature.getGeometry().getCoordinates(), popupContent);
        return true;
      }
    });
  
    if (!featureClicked) {
      App.handleMapClick(event);
    }
  });

  // Checkboxes de visibilidade (fósseis/cafes)
  $('#toggle-fossils').change(() => {
    const visible = $('#toggle-fossils').is(':checked');
    App.state.fossils.layer.setVisible(visible);
  });

  $('#toggle-cafes').change(() => {
    const visible = $('#toggle-cafes').is(':checked');
    App.state.cafes.layer.setVisible(visible);
  });

  $('#toggle-benchs').change(() => {
    const visible = $('#toggle-benchs').is(':checked');
    App.state.benchs.layer.setVisible(visible);
  });

  $('#toggle-museums').change(() => {
    const visible = $('#toggle-museums').is(':checked');
    App.state.museums.layer.setVisible(visible);
  });

  $('#toggle-archaelogical').change(() => {
    const visible = $('#toggle-archaelogical').is(':checked');
    App.state.archaelogical.layer.setVisible(visible);
  });
  

  // Modo de transporte
  $("input[type='radio']").change(() => {
    App.state.currentTransportMode = $("input[name='options']:checked").val();
    App.updateIsochrone();
  });

  // Slider
  $("input[name='tempo']").each(function () {
    const label = $(this).closest('label');
    if ($(this).is(':checked')) {
      label.addClass('active');
    } else {
      label.removeClass('active');
    }
  });
  

  // Camada base
  $("#layerswitcher input[name=layer]").change(() => {
    App.switchBaseLayer();
  });
};

App.handleMapClick = function(event) {
  if (App.state.popup) {
    App.state.popup.hide();
  }
  // Apaga geometry do ponto inicial
  App.state.startPoint.feature.setGeometry(null);

  // Oculta camadas momentaneamente
  App.state.isochrone.layer.setVisible(false);
  App.state.fossils.layer.setVisible(false);
  App.state.cafes.layer.setVisible(false);
  App.state.benchs.layer.setVisible(false);
  App.state.museums.layer.setVisible(false);
  App.state.archaelogical.layer.setVisible(false);
  
  // Descobre coords
  App.state.coordenadas_3857 = event.coordinate;
  App.state.coordenadas_4326 = ol.proj.transform(event.coordinate, 'EPSG:3857', 'EPSG:4326');

  // Move ponto
  App.state.startPoint.feature.setGeometry(new ol.geom.Point([
    App.state.coordenadas_3857[0],
    App.state.coordenadas_3857[1]
  ]));

  // Recalcula isócrona
  App.updateIsochrone();

  // Mostra o ponto
  App.state.startPoint.layer.setVisible(true);
};
