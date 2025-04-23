// Configura listeners de eventos (cliques, checkboxes, slider, etc.)
window.App = window.App || {};

App.setupEventListeners = function() {
  // Clique no mapa: se clicou num café ou escola, popup. Senão, isócrona.
  App.state.map.on('click', (event) => {
    let featureClicked = false;
    App.state.map.forEachFeatureAtPixel(event.pixel, (feature, layer) => {
      if (layer && layer.get('selectable') === true) {
        featureClicked = true;
        // Fechar popup anterior
        App.state.popup.hide();

        // Popup
        let content = "";
        if (layer === App.state.fossils.layer) {
          content = "<div class='popup-content fossil-popup'>";
          content += "<h4>" + (feature.get("title") || "Fóssil") + "</h4>";
          content += "<p><b>Kingdom:</b> " + (feature.get("kingdom") || "N/A") + "</p>";
          content += "<p><b>Descoberto por:</b> " + (feature.get("discovered_by") || "N/A") + "</p>";
          content += "</div>";
        } 
        else if (layer === App.state.cafes.layer) {
          content = "<div class='popup-content cafe-popup'>";
          content += "<h4>" + (feature.get("name") || "Café") + "</h4>";
          content += "</div>";
        }else if (layer === App.state.benchs.layer) {
          content = "<div class='popup-content cafe-popup'>";
          content += "<h4>" + (feature.get("amenity") || "Zona de Descanso") + "</h4>";
          content += "</div>";
        }else if (layer === App.state.museums.layer) {
          content = "<div class='popup-content cafe-popup'>";
          content += "<h4>" + (feature.get("name") || "Museu") + "</h4>";
          content += "</div>";
        }else if (layer === App.state.archaelogical.layer) {
          content = "<div class='popup-content cafe-popup'>";
          content += "<h4>" + (feature.get("name") || "Sítio Arqueológico") + "</h4>";
          content += "</div>";
        }

        if (content) {
          App.state.popup.show(feature.getGeometry().getCoordinates(), content);
        }
        return true; // Para interromper
      }
    });

    // Se não feature, clique é p/ routing
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
