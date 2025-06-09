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

function getSearchParams() {
    return {
        source: $("#searchInputSource").val(),
        family: $("#searchInputFamily").val(),
        order: $("#searchInputOrder").val(),
        genus: $("#searchInputGenus").val(),
        species: $("#searchInputSpecies").val()
    };
}

function buildUrl(params) {
    const esc = encodeURIComponent;
    return '../scripts/data.php?' +
        'source=' + esc(params.source) +
        '&family=' + esc(params.family) +
        '&order=' + esc(params.order) +
        '&genus=' + esc(params.genus) +
        '&species=' + esc(params.species);
}

$("#searchInputSource, #searchInputFamily, #searchInputOrder, #searchInputGenus, #searchInputSpecies").on('input', updateVectorSource);

$("#dropdownListSource, #dropdownListFamily, #dropdownListOrder, #dropdownListGenus, #dropdownListSpecies").on('click', 'li', updateVectorSource);



function updateVectorSource() {
    const params = getSearchParams();
    const url = buildUrl(params);

    vectorSource.setUrl(url);
    vectorSource.refresh();

}


let vectorSource = new ol.source.Vector({
    url: '../scripts/data.php',
    format: new ol.format.GeoJSON()
});

let clusterSource = new ol.source.Cluster({
    distance: 10,
    minDistance: 5,
    source: vectorSource
});
    
const styleCache = {};
const clustersLayer = new ol.layer.Vector({
    source: clusterSource,
    style: (feature) => {
        const clusterFeatures = feature.get('features');
        const size = feature.get('features').length;

        const UA = clusterFeatures.some(f => f.get('source') === 'UA');

        const fillColor = UA ? '#accb59' : '#5d4a38';

        const cacheKey = `${size}-${UA}`;

        if (!styleCache[cacheKey]) {
            styleCache[cacheKey] = new ol.style.Style({
                image: new ol.style.Circle({
                    radius: 10,
                    stroke: new ol.style.Stroke({
                        color: '#fff',
                    }),
                    fill: new ol.style.Fill({
                        color: fillColor,
                    }),
                }),
                text: new ol.style.Text({
                    text: size.toString(),
                        fill: new ol.style.Fill({
                            color: '#fff',
                    }),
                }),
            });
        }
        return styleCache[cacheKey];
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
    layers: [...Object.values(layersMap), clustersLayer],
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
            html += `<li class="fossil-popup-item">
                <h4 class="fossil-title">${p.title || 'Fóssil sem título'}</h4>
                <div class="fossil-details">
                    <small class="id-field"><strong>ID:</strong> ${p.id}</small><br>

                    ${p.family ? `<small><strong>Família:</strong> ${p.family}</small><br>` : ''}
                    ${p.order ? `<small><strong>Ordem:</strong> ${p.order}</small><br>` : ''}
                    ${p.genus ? `<small><strong>Género:</strong> ${p.genus}</small><br>` : ''}
                    ${p.species ? `<small><strong>Espécie:</strong> ${p.species}</small><br>` : ''}

                    ${p.discovered_by ? `<small><strong>Descoberto por:</strong> ${p.discovered_by}</small><br>` : ''}
                    ${p.date_discovered ? `<small><strong>Data descoberta:</strong> ${p.date_discovered}</small><br>` : ''}
                    
                    ${p.source ? `<small><strong>Fonte:</strong> ${p.source}</small>` : ''}
                </div>
            </li>`;
        });

        html += '</ul>';
        popupContent.innerHTML = html;
        popupContent.scrollTop = 0;

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


$('#searchInputSource, #searchInputFamily, #searchInputOrder, #searchInputGenus, #searchInputSpecies').on('input', function() {
    const inputId = $(this).attr('id');
    const clearButtonId = 'clear' + inputId.replace('searchInput', '');

    if ($(this).val().length > 0) {
        $('#' + clearButtonId).addClass('visible');
    } else {
        $('#' + clearButtonId).removeClass('visible');
    }
});

// Verificar inputs ao carregar a página
$(document).ready(function() {
    $('#searchInputSource, #searchInputFamily, #searchInputOrder, #searchInputGenus, #searchInputSpecies').each(function() {
        const inputId = $(this).attr('id');
        const clearButtonId = 'clear' + inputId.replace('searchInput', '');

        if ($(this).val().length > 0) {
            $('#' + clearButtonId).addClass('visible');
        }
    });
});

// Função para limpar o input e atualizar o mapa
$('#clearSource, #clearFamily, #clearOrder, #clearGenus, #clearSpecies').click(function() {
    const buttonId = $(this).attr('id');
    const inputId = 'searchInput' + buttonId.replace('clear', '');

    // Limpar o valor do input
    $('#' + inputId).val('').trigger('input');

    // Ocultar o botão X
    $(this).removeClass('visible');

    // Atualizar o mapa com o filtro removido
    updateVectorSource();

    // Fechar qualquer dropdown que esteja aberto
    $('.dropdown-list').hide();
});

// Adicionar botão X quando um item do dropdown é selecionado
$(".dropdown-list").on('click', 'li', function() {
    const dropdownId = $(this).parent().attr('id');
    const inputId = dropdownId.replace('dropdownList', 'searchInput');
    const clearButtonId = dropdownId.replace('dropdownList', 'clear');

    // Mostrar o botão X após selecionar um item
    $('#' + clearButtonId).addClass('visible');
});

// Função para determinar se um dropdown deve abrir para cima
function setupDropdownDirection() {
    // Define os últimos dois dropdowns para abrir para cima
    $('#dropdownListGenus, #dropdownListSpecies').addClass('dropup');

    // Função para verificar e ajustar a direção do dropdown conforme o espaço disponível
    $('.search-input input').on('focus', function() {
        const inputId = $(this).attr('id');
        const dropdownId = inputId.replace('searchInput', 'dropdownList');
        const $dropdown = $('#' + dropdownId);

        // Se for um dos últimos dois dropdowns
        if (dropdownId === 'dropdownListGenus' || dropdownId === 'dropdownListSpecies') {
            $dropdown.addClass('dropup');
        } else {
            // Para os outros, verifique o espaço disponível
            const inputBottom = $(this).offset().top + $(this).outerHeight();
            const windowHeight = $(window).height();
            const spaceBelow = windowHeight - inputBottom;

            // Se houver pouco espaço abaixo (menos de 200px), abra para cima
            if (spaceBelow < 200) {
                $dropdown.addClass('dropup');
            } else {
                $dropdown.removeClass('dropup');
            }
        }
    });
}

// Inicialize a função quando o documento estiver pronto
$(document).ready(function() {
    setupDropdownDirection();
});