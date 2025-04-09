const layersMap = {
standard: new ol.layer.Tile({
    title: 'OpenStreetMap Standard',
    source: new ol.source.OSM(),
    visible: true
}),
humanitarian: new ol.layer.Tile({
    title: 'Humanitarian',
    source: new ol.source.OSM({
    url: 'https://{a-c}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png'
    }),
    visible: false
}),
topo: new ol.layer.Tile({
    title: 'Topographic',
    source: new ol.source.XYZ({
    url: 'https://{a-c}.tile.opentopomap.org/{z}/{x}/{y}.png'
    }),
    visible: false
})
};

const vectorSource = new ol.source.Vector({
url: '../scripts/data.php',
format: new ol.format.GeoJSON()
});

const clusterSource = new ol.source.Cluster({
    distance: 10,
    minDistance: 5,
    source: vectorSource,
  });

/*
const vectorLayer = new ol.layer.Vector({
source: vectorSource,
style: new ol.style.Style({
    image: new ol.style.Circle({
    radius: 6,
    fill: new ol.style.Fill({ color: '#0077cc' }),
    stroke: new ol.style.Stroke({ color: '#ffffff', width: 2 })
    })
})
});
*/
    
const styleCache = {};
const clustersLayer = new ol.layer.Vector({
    source: clusterSource,
    style: (feature) => {
        const size = feature.get('features').length;
        let style = styleCache[size];
        if (!style) {
        style = new ol.style.Style({
            image: new ol.style.Circle({
            radius: 10,
            stroke: new ol.style.Stroke({
                color: '#fff',
            }),
            fill: new ol.style.Fill({
                color: '#3399CC',
            }),
            }),
            text: new ol.style.Text({
            text: size.toString(),
            fill: new ol.style.Fill({
                color: '#fff',
            }),
            }),
        });
        styleCache[size] = style;
        }
        return style;
    },
});

const defaultControls = new ol.control.defaults.defaults();

const map = new ol.Map({
    controls: defaultControls.extend([
        new ol.control.FullScreen({
            source: 'fullscreen',
        }),
    ]),
    target: 'map',
    layers: [...Object.values(layersMap), /*vectorLayer*/ clustersLayer],
    view: new ol.View({
        center: ol.proj.fromLonLat([-8, 39.5]),
        zoom: 6
    })
});

// Смена слоя по селектору
document.getElementById('baselayer-select').addEventListener('change', (e) => {
const selected = e.target.value;
for (const key in layersMap) {
    layersMap[key].setVisible(key === selected);
}
});

// Popup
const popup = document.getElementById('popup');
const popupContent = document.getElementById('popup-content');

/*map.on('singleclick', function (evt) {
popup.style.display = 'none';
const features = [];
map.forEachFeatureAtPixel(evt.pixel, function (feature) {
    features.push(feature);
});

if (features.length > 0) {
    let html = `<strong>Registos (${features.length}):</strong><ul style="padding-left: 16px;">`;

    features.forEach(f => {
    const p = f.getProperties();
    html += `<li>
        <b>${p.title}</b><br>
        <small>ID: ${p.id}</small><br>
        ${p.family ? `<small>Family: ${p.family}</small><br>` : ''}
        ${p.date_discovered ? `<small>Discovered: ${p.date_discovered}</small>` : ''}
    </li><br>`;
    });

    html += '</ul>';
    popupContent.innerHTML = html;

    const pixel = evt.pixel;
    popup.style.left = `${pixel[0]}px`;
    popup.style.top = `${pixel[1]}px`;
    popup.style.display = 'block';
}
});*/

map.on('singleclick', function (evt) {

    popup.style.display = 'none';
    
    const features = [];
    
    map.forEachFeatureAtPixel(evt.pixel, (feature) => {

      if (feature.get('features')) {

        features.push(...feature.get('features'));

      } else {

        features.push(feature);

      }

    });
  
    if (features.length > 0) {

      let html = `<strong>Registos (${features.length}):</strong><ul style="padding-left:16px;">`;
      
      features.forEach(f => {

        const p = f.getProperties();
        html += `<li>
          <b>${p.title}</b><br>
          <small>ID: ${p.id}</small><br>
          ${p.family ? `<small>Family: ${p.family}</small><br>` : ''}
          ${p.date_discovered ? `<small>Discovered: ${p.date_discovered}</small>` : ''}
        </li><br>`;

      });
  
      html += '</ul>';
      popupContent.innerHTML = html;
  
      const pixel = evt.pixel;
      popup.style.left = `${pixel[0]}px`;
      popup.style.top = `${pixel[1]}px`;
      popup.style.display = 'block';

    }
});
  

document.getElementById('distanceInput').addEventListener('input', (e) => {

    const distance = parseInt(e.target.value, 10);

    document.getElementById('distanceValue').textContent = distance;

    clusterSource.setDistance(distance);

});
  
  document.getElementById('minDistanceInput').addEventListener('input', (e) => {

    const minDistance = parseInt(e.target.value, 10);

    document.getElementById('minDistanceValue').textContent = minDistance;

    clusterSource.setMinDistance(minDistance);

});