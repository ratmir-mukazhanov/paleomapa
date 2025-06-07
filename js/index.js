var simpleRSSPlugin = (function() {
  // Store processed feeds for reference
  const processedFeeds = new Set();
  let feedsCount = 0;

  // Initialize function to set up RSS feeds
  function init() {
    // Get all feed containers
    const feedsNodes = document.querySelectorAll('[data-rss-feed]');

    // If no feeds, exit
    if (!feedsNodes.length) return;

    // Process each feed container
    feedsNodes.forEach(container => {
      // Skip if already processed
      if (processedFeeds.has(container)) return;
      processedFeeds.add(container);

      // Get feed attributes
      const url = container.getAttribute('data-rss-feed');
      const addLink = container.getAttribute('data-rss-link-titles') || 'true';
      const titleWrapper = container.getAttribute('data-rss-title-wrapper') || 'h2';
      const max = parseInt(container.getAttribute('data-rss-max') || 10);

      // Add loading indicator
      showLoadingState(container);

      // Create unique callback name for this feed
      const callbackName = `simpleRSSCallback_${feedsCount++}`;

      // Set up global callback function
      window[callbackName] = function(data) {
        handleJSON(data, container, titleWrapper, addLink, max);
        // Clean up the global function
        delete window[callbackName];
      };

      // Get data - append as script with callback to avoid CORS
      fetchRSSData(url, callbackName);
    });
  }

  // Show loading state in container
  function showLoadingState(container) {
    const loader = document.createElement('div');
    loader.className = 'rss-loader';
    loader.innerHTML = `
		<div class="loading-spinner">
		  <div class="spinner-circle"></div>
		  <div class="spinner-text">Carregando feed...</div>
		</div>
	  `;

    // Add loading styles
    const style = document.createElement('style');
    style.textContent = `
		.rss-loader {
		  padding: 20px;
		  text-align: center;
		}
		.loading-spinner {
		  display: flex;
		  flex-direction: column;
		  align-items: center;
		  justify-content: center;
		}
		.spinner-circle {
		  width: 30px;
		  height: 30px;
		  border: 3px solid rgba(58, 110, 165, 0.2);
		  border-top-color: var(--accent-color, #ff6b6b);
		  border-radius: 50%;
		  animation: spin 1s linear infinite;
		}
		.spinner-text {
		  margin-top: 10px;
		  color: #888;
		}
		@keyframes spin {
		  to { transform: rotate(360deg); }
		}
	  `;

    document.head.appendChild(style);
    container.appendChild(loader);
  }

  // Fetch RSS data using JSONP approach
  function fetchRSSData(url, callbackName) {
    const script = document.createElement('script');
    script.src = `${document.location.protocol}//api.rss2json.com/v1/api.json?callback=${callbackName}&rss_url=${encodeURIComponent(url)}`;

    // Set timeout to handle failed requests
    const timeout = setTimeout(() => {
      if (script.parentNode) {
        script.parentNode.removeChild(script);
        const container = document.querySelector(`[data-rss-feed="${url}"]`);
        if (container) {
          const loader = container.querySelector('.rss-loader');
          if (loader) loader.innerHTML = '<p class="error-message">Não foi possível carregar o feed.</p>';
        }
      }
    }, 10000); // 10 second timeout

    script.onload = function() {
      clearTimeout(timeout);
      // Remove script after it loads
      if (script.parentNode) script.parentNode.removeChild(script);
    };

    document.head.appendChild(script);
  }

  // Process RSS data and render to container
  function handleJSON(data, container, titleWrapper, addLink, max) {
    // Remove loading indicator
    const loader = container.querySelector('.rss-loader');
    if (loader) container.removeChild(loader);

    // Check if we have valid data
    if (!data || !data.feed || !data.items || !data.items.length) {
      const message = document.createElement('p');
      message.className = 'no-items-message';
      message.textContent = 'Não foram encontrados itens no feed.';
      container.appendChild(message);
      return;
    }

    // Create document fragment for better performance
    const docFrag = document.createDocumentFragment();

    // Add feed container
    const feedContainer = document.createElement('div');
    feedContainer.className = 'rss-feed-content';

    // Process items up to max limit
    const itemsToShow = data.items.slice(0, max);

    itemsToShow.forEach((item, index) => {
      // Create item container
      const itemElement = document.createElement('div');
      itemElement.className = 'rss-item';

      // Create title with or without link
      const titleElement = document.createElement(titleWrapper);

      if (addLink !== 'false') {
        const titleLink = document.createElement('a');
        titleLink.href = item.link;
        titleLink.className = 'rssFeedHyper';
        titleLink.textContent = item.title;
        titleLink.setAttribute('target', '_blank');
        titleLink.setAttribute('rel', 'noopener noreferrer');
        titleElement.appendChild(titleLink);
      } else {
        titleElement.textContent = item.title;
      }

      // Add publish date if available
      if (item.pubDate) {
        const dateElement = document.createElement('div');
        dateElement.className = 'rss-date';

        // Format the date nicely
        const pubDate = new Date(item.pubDate);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.textContent = pubDate.toLocaleDateString('pt-PT', options);

        itemElement.appendChild(dateElement);
      }

      // Add title
      itemElement.appendChild(titleElement);

      // Add content
      const contentElement = document.createElement('div');
      contentElement.className = 'rss-content';
      contentElement.innerHTML = item.content;

      // Find and enhance images
      enhanceImages(contentElement);

      // Add read more link
      if (item.link && contentElement.innerHTML.length > 300) {
        const linkContainer = document.createElement('div');
        linkContainer.className = 'read-more-container';

        const readMoreLink = document.createElement('a');
        readMoreLink.href = item.link;
        readMoreLink.className = 'read-more-link';
        readMoreLink.textContent = 'Ler mais';
        readMoreLink.setAttribute('target', '_blank');
        readMoreLink.setAttribute('rel', 'noopener noreferrer');

        linkContainer.appendChild(readMoreLink);
        contentElement.appendChild(linkContainer);
      }

      itemElement.appendChild(contentElement);

      // Add a divider except for the last item
      if (index < itemsToShow.length - 1) {
        const divider = document.createElement('div');
        divider.className = 'rss-divider';
        itemElement.appendChild(divider);
      }

      // Add to fragment
      feedContainer.appendChild(itemElement);
    });

    docFrag.appendChild(feedContainer);
    container.appendChild(docFrag);

    // Add animation with slight delay
    setTimeout(() => {
      animateItems(container);
    }, 50);

    // Add styles for RSS content
    addRSSStyles();
  }

  // Enhance images in RSS content
  function enhanceImages(contentElement) {
    const images = contentElement.querySelectorAll('img');
    images.forEach(img => {
      // Skip small images (likely icons)
      if (img.width < 50 || img.height < 50) return;

      // Create image container
      const imageContainer = document.createElement('div');
      imageContainer.className = 'image-container';

      // Move image into container
      img.parentNode.insertBefore(imageContainer, img);
      imageContainer.appendChild(img);

      // Add lazy loading
      img.loading = 'lazy';

      // Add lightbox capability if needed
      img.addEventListener('click', function() {
        // Could implement a lightbox here
      });
    });
  }

  // Animate RSS items
  function animateItems(container) {
    const items = container.querySelectorAll('.rss-item');
    items.forEach((item, index) => {
      item.style.opacity = '0';
      item.style.transform = 'translateY(20px)';
      item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      item.style.transitionDelay = `${index * 0.1}s`;

      setTimeout(() => {
        item.style.opacity = '1';
        item.style.transform = 'translateY(0)';
      }, 50);
    });
  }

  // Add styles for RSS content
  function addRSSStyles() {
    // Only add styles once
    if (document.getElementById('rss-plugin-styles')) return;

    const style = document.createElement('style');
    style.id = 'rss-plugin-styles';
    style.textContent = `
		.rss-feed-content {
		  position: relative;
		}
		
		.rss-item {
		  margin-bottom: 25px;
		  position: relative;
		}
		
		.rss-date {
		  font-size: 0.85rem;
		  color: #777;
		  margin-bottom: 8px;
		  font-style: italic;
		}
		
		.rss-content {
		  line-height: 1.6;
		}
		
		.image-container {
		  margin: 15px auto;
		  text-align: center;
		  overflow: hidden;
		  border-radius: 8px;
		}
		
		.image-container img {
		  max-width: 100%;
		  height: auto;
		  transition: transform 0.3s ease;
		  border-radius: 8px;
		  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
		}
		
		.image-container:hover img {
		  transform: scale(1.02);
		}
		
		.read-more-container {
		  margin-top: 15px;
		  text-align: right;
		}
		
		.read-more-link {
		  display: inline-block;
		  padding: 6px 15px;
		  background-color: rgba(58, 110, 165, 0.1);
		  color: var(--primary-color, #3a6ea5);
		  border-radius: 20px;
		  text-decoration: none;
		  font-size: 0.9rem;
		  font-weight: 500;
		  transition: all 0.3s ease;
		}
		
		.read-more-link:hover {
		  background-color: var(--primary-color, #3a6ea5);
		  color: white;
		  transform: translateY(-2px);
		}
		
		.rss-divider {
		  height: 1px;
		  background: linear-gradient(to right, rgba(0,0,0,0.03), rgba(0,0,0,0.1), rgba(0,0,0,0.03));
		  margin: 25px 0;
		}
		
		.error-message {
		  color: #e74c3c;
		  text-align: center;
		  padding: 15px;
		  background-color: rgba(231, 76, 60, 0.1);
		  border-radius: 8px;
		}
		
		.no-items-message {
		  color: #777;
		  text-align: center;
		  padding: 15px;
		}
	  `;

    document.head.appendChild(style);
  }

  // Public methods
  return {
    init: init,
    // Legacy support for old callback method
    handleJSON: function(data) {
      console.warn('Using deprecated handleJSON method. Please update to use the new init() method instead.');
      handleJSON(data, document.querySelector('[data-rss-feed]'), 'h2', 'true', 10);
    }
  };
})();

// Initialize the plugin when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  simpleRSSPlugin.init();
});

$.ajax({
  type: "GET",
  url: '../scripts/getSource.php',
  success: function(response) {

    response = JSON.parse(response);

    const items = [];
    const searchInput = document.getElementById("searchInputSource");
    const dropdownList = document.getElementById("dropdownListSource");

    dropdownSearch(items, searchInput, dropdownList);

    if (response.length) {

      $.each(response, function(key, value) {

        items.push(value.source);

      });

    } else {

      items.push("Sem informação");

    }
  }
});

$.ajax({
  type: "GET",
  url: '../scripts/getFamily.php',
  success: function(response) {

    response = JSON.parse(response);

    const items = [];
    const searchInput = document.getElementById("searchInputFamily");
    const dropdownList = document.getElementById("dropdownListFamily");

    dropdownSearch(items, searchInput, dropdownList);

    if (response.length) {

      $.each(response, function(key, value) {

        items.push(value.family);

      });

    } else {

      items.push("Sem informação");

    }
  }
});

$.ajax({
  type: "GET",
  url: '../scripts/getOrder.php',
  success: function(response) {

    response = JSON.parse(response);

    const items = [];
    const searchInput = document.getElementById("searchInputOrder");
    const dropdownList = document.getElementById("dropdownListOrder");

    dropdownSearch(items, searchInput, dropdownList);

    if (response.length) {

      $.each(response, function(key, value) {

        items.push(value.order);

      });

    } else {

      items.push("Sem informação");

    }
  }
});

$.ajax({
  type: "GET",
  url: '../scripts/getGenus.php',
  success: function(response) {

    response = JSON.parse(response);

    const items = [];
    const searchInput = document.getElementById("searchInputGenus");
    const dropdownList = document.getElementById("dropdownListGenus");

    dropdownSearch(items, searchInput, dropdownList);

    if (response.length) {

      $.each(response, function(key, value) {

        items.push(value.genus);

      });

    } else {

      items.push("Sem informação");

    }
  }
});

$.ajax({
  type: "GET",
  url: '../scripts/getSpecies.php',
  success: function(response) {

    response = JSON.parse(response);

    const items = [];
    const searchInput = document.getElementById("searchInputSpecies");
    const dropdownList = document.getElementById("dropdownListSpecies");

    dropdownSearch(items, searchInput, dropdownList);

    if (response.length) {

      $.each(response, function(key, value) {

        items.push(value.species);

      });

    } else {

      items.push("Sem informação");

    }
  }
});

function dropdownSearch(items, searchInput, dropdownList) {

  function selectItem(value) {
    searchInput.value = value;
    dropdownList.style.display = "none";
  }

  function renderDropdown(filteredItems) {
    dropdownList.innerHTML = "";
    if (filteredItems.length > 0) {
      dropdownList.style.display = "block";
      filteredItems.forEach(item => {
        let li = document.createElement("li");
        li.innerText = item;
        li.style.cursor = "pointer";
        li.onclick = () => selectItem(item);
        dropdownList.appendChild(li);
      });
    } else {
      dropdownList.style.display = "none";
    }
  }

  searchInput.addEventListener("focus", () => {
    renderDropdown(items);
  });

  searchInput.addEventListener("input", () => {
    const filtered = items.filter(item => item.toLowerCase().includes(searchInput.value.toLowerCase()));
    renderDropdown(filtered);
  });

  document.addEventListener("click", function(e) {
    if (!searchInput.contains(e.target) && !dropdownList.contains(e.target)) {
      dropdownList.style.display = "none";
    }
  });

}



$("#changeMap").on("click", function() {
  changeMap();
});

function changeMap() {

  var x = document.getElementById("mapaOpenlayers");
  var y = document.getElementById("mapaRouting");


  if (x.style.display === "block" && y.style.display === "none") {

    x.style.display = "none";
    y.style.display = "block";

  } else {

    x.style.display = "block";
    y.style.display = "none";

  }

}

document.addEventListener('DOMContentLoaded', function() {
  // Garantir que a barra de progresso termine após o carregamento do DOM
  document.querySelector('.loading-bar').style.width = '80%';

  // Iniciar a contagem de recursos carregados
  let loadedResources = 0;
  const totalResources = document.images.length + document.querySelectorAll('script').length + document.querySelectorAll('link').length;
  const minLoadTime = 1500; // Tempo mínimo para mostrar o loading (1.5 segundos)
  const startTime = Date.now();

  // Criar um texto "científico" aleatório para mostrar durante o carregamento
  const loadingTexts = [
    "A carregar dados paleontológicos...",
    "A sincronizar eras geológicas...",
    "A preparar os fósseis digitais...",
    "A escavar registros históricos...",
    "A analisar camadas estratigráficas...",
    "A mapear sítios arqueológicos..."
  ];

  let textIndex = 0;
  const textInterval = setInterval(function() {
    document.querySelector('.loading-text').textContent = loadingTexts[textIndex];
    textIndex = (textIndex + 1) % loadingTexts.length;
  }, 800);

  // Função para esconder a tela de carregamento
  function hideLoadingScreen() {
    const elapsedTime = Date.now() - startTime;
    const remainingTime = Math.max(0, minLoadTime - elapsedTime);

    setTimeout(function() {
      document.querySelector('.loading-bar').style.width = '100%';

      setTimeout(function() {
        clearInterval(textInterval);
        document.getElementById('loading-screen').classList.add('hidden');
      }, 500);
    }, remainingTime);
  }

  // Verificar se a página está completamente carregada
  window.addEventListener('load', hideLoadingScreen);

  // Se a página demorar muito, esconder o loading após 6 segundos
  setTimeout(hideLoadingScreen, 6000);
});




