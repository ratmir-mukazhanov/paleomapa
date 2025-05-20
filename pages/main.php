<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.4.0/ol.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cores.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/about_us.css">
    <link rel="stylesheet" href="../css/contact_us.css">
    <title>Paleomapa - HomePage</title>
</head>
<body>
<?php
require_once "../components/header.php";
require_once "../components/sidebar.php";
?>
    <div id="homepage">
    <h1 class="page-title">Últimas Descobertas Paleontológicas</h1>
    <div class="content-wrapper">
        <div class="featured-article">
        <div class="featured-image">
            <img src="../img/destaqueFossil.webp" alt="Viagem Paleontológica">
        </div>
        <div class="featured-content">
            <div class="featured-tag">Destaque</div>
            <h2 class="featured-title">Viaja no Tempo com a Paleontologia Interativa</h2>
            <p class="featured-desc">Explora fósseis autênticos, sítios arqueológicos e pontos de interesse através de mapas interativos e dados reais. Descobre a história da vida na Terra com visualizações ricas, filtros geográficos e contexto científico – tudo num só portal. Junta-te à jornada pelas eras geológicas!</p>
            <div class="featured-links">
            <a href="#mapaOpenlayers" class="featured-link primary-link">
                <svg class="link-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                Explorar Agora
            </a>
            <a href="../pages/routing.html" class="featured-link secondary-link">
                <svg class="link-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="3 11 22 2 13 21 11 13 3 11"></polygon>
                </svg>
                Explorar Roteiro
            </a>
            </div>
        </div>
        </div>
        
        <div class="news-container">
        <div class="news-header">
            <div class="news-header-content">
            <svg class="news-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                <path d="M18 14h-8"></path>
                <path d="M18 18h-8"></path>
                <path d="M18 10h-8"></path>
            </svg>
            <h2>Notícias Recentes</h2>
            </div>
            <div class="news-subtitle">Mantenha-se informado</div>
        </div>
        <div class="rssFeed"
            data-rss-feed="https://www.alfmuseum.org/feed/"
            data-rss-link-titles="true"
            data-rss-title-wrapper="h3"
            data-rss-max="1">
        </div>
        </div>
    </div>
    </div>
    <div class="mapaDiv" id="mapaOpenlayers">
        <div class="layout-wrapper">
            <?php require_once "../components/header.php"; ?>

            <div class="layout-body">
                <?php require_once "../components/sidebar.php"; ?>

                <div class="main-content" id="fullscreen">
                    <div id="map"></div>

                    <div id="popup" class="ol-popup">
                    <div id="popup-content"></div>
                </div>

                    <div id="layer-toggle">
                        <label for="baselayer-select"><strong>Base Layer:</strong></label><br>
                        <select id="baselayer-select">
                            <option value="standard">Padrão</option>
                            <option value="humanitarian">Humanitário</option>
                            <option value="topo">Topográfico</option>
                        </select>
                    </div>

                    <div id="filters">
                        <strong>Filtros:</strong><br>
                        <!-- Filtros -->

                        <label for="distanceInput">Distância de Cluster:</label>
                        <input type="range" id="distanceInput" min="1" max="100" value="10" step="1">
                        <span id="distanceValue">10</span><br><br>
                        
                        <label for="minDistanceInput">Distância Mínima:</label>
                        <input type="range" id="minDistanceInput" min="1" max="50" value="5" step="1">
                        <span id="minDistanceValue">5</span><br><br>

                        <label>Filtrar por:</label><br><br>
                        
                        <label>Source</label>
                        <input type="text" id="searchInputSource" placeholder="A pesquisar sem filtro">
                            <ul id="dropdownListSource" style="border:1px solid #ccc; max-height:150px; overflow-y:auto; display:none; position:absolute; background:#fff; width:200px"></ul><br><br>

                        <label>Family</label>
                        <input type="text" id="searchInputFamily" placeholder="A pesquisar sem filtro">
                            <ul id="dropdownListFamily" style="border:1px solid #ccc; max-height:150px; overflow-y:auto; display:none; position:absolute; background:#fff; width:200px"></ul><br><br>

                        <label>Order</label>
                        <input type="text" id="searchInputOrder" placeholder="A pesquisar sem filtro">
                            <ul id="dropdownListOrder" style="border:1px solid #ccc; max-height:150px; overflow-y:auto; display:none; position:absolute; background:#fff; width:200px"></ul><br><br>

                        <label>Genus</label>
                        <input type="text" id="searchInputGenus" placeholder="A pesquisar sem filtro">
                            <ul id="dropdownListGenus" style="border:1px solid #ccc; max-height:150px; overflow-y:auto; display:none; position:absolute; background:#fff; width:200px"></ul><br><br>

                        <label>Species</label>
                        <input type="text" id="searchInputSpecies" placeholder="A pesquisar sem filtro">
                            <ul id="dropdownListSpecies" style="border:1px solid #ccc; max-height:150px; overflow-y:auto; display:none; position:absolute; background:#fff; width:200px"></ul>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="main-content" id="aboutUs">
        <div class="about-us-section">
    <div class="about-us-header">
        <h1 class="section-title">Sobre Nós</h1>
    </div>

    <div class="about-cards-container">
        <!-- Missão e Objetivos -->
        <div class="about-card">
            <div class="card-icon">
                <i class="fas fa-bullseye"></i>
            </div>
            <h2>Missão e Objetivos</h2>
            <p>O Paleomapa nasceu com uma missão clara: Permitir o acesso a dados paleontológicos e arqueológicos 
                através de uma plataforma interativa e intuitiva. O nosso objetivo principal é criar uma ponte entre o 
                conhecimento científico especializado e o público geral, tornando a riqueza do patrimônio paleontológico acessível a todos.</p>
            <div class="card-highlights">
                <div class="highlight-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Disponibilização de dados paleontológicos em formato interativo e georreferenciado</span>
                </div>
                <div class="highlight-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Promoção da investigação científica através da centralização de informações</span>
                </div>
                <div class="highlight-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Apoio à educação através de recursos visuais e contextuais sobre paleontologia</span>
                </div>
                <div class="highlight-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Fomento do turismo científico com a identificação de sítios paleontológicos relevantes e áreas de interesse.</span>
                </div>
            </div>
        </div>

        <!-- História e Contexto -->
        <div class="about-card">
            <div class="card-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h2>História e Contexto</h2>
            <p>O projeto Paleomapa surgiu em Fevereiro de 2025, através da disciplina SIG, em que se identificou uma lacuna significativa: 
                a ausência de uma plataforma unificada que integrasse dados paleontológicos em Portugal com uma interface acessível 
                tanto para especialistas quanto para leigos interessados.</p>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h3>Fase de Conceptualização</h3>
                        <p>Fevereiro e Março de 2025 - Identificação do problema e esboço inicial da solução</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h3>Pesquisa e Recolha de Dados</h3>
                        <p>Março de 2025 - Levantamento das fontes de dados disponíveis e estabelecimento de parcerias</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h3>Desenvolvimento da Plataforma</h3>
                        <p>Fim de Março a Junho de 2025 - Implementação da interface e integração dos dados geoespaciais</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h3>Lançamento e Expansão</h3>
                        <p>Junho de 2025 até o presente - Lançamento público e expansão contínua da base de dados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- A Equipa -->
        <div class="about-card">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <h2>A Equipa</h2>
            <p>O Paleomapa nasceu da mente brilhante (e ligeiramente cansada) de um grupo de alunos da ESTGA
                 – Escola Superior de Tecnologia e Gestão de Águeda. Apesar de sermos todos estudantes, 
                 tivemos a orientação sábia (e a paciência lendária) do Professor Luís Jorge Gonçalves, 
                 que manteve o navio a flutuar mesmo quando parecia afundar em bugs, shapefiles e teorias de dinossauros perdidos. 
                 Este projeto é a prova de que, com café, espírito de equipa e um professor com nervos de aço, tudo é possível.</p>
            <div class="team-grid">
        <div class="team-member">
            <div class="member-avatar">
                <img src="../img/gustavoGiao.png" alt="Perfil de membro da equipe">
            </div>
            <h3>Gustavo Gião</h3>
            <p class="member-role">Chefe dos Mapas e da Moral</p>
            <p>Responsável por dizer “malta, temos tempo” duas horas antes do deadline.</p>
        </div>

        <div class="team-member">
            <div class="member-avatar">
                <img src="../img/ratmir.jpg" alt="Perfil de membro da equipe">
            </div>
            <h3>Ratmir Mukazhanov</h3>
            <p class="member-role">Feiticeiro Full-Stack</p>
            <p>Fala JavaScript melhor que português. Debugga bugs que ele próprio criou, com orgulho e sem culpa.</p>
        </div>

        <div class="team-member">
            <div class="member-avatar">
                <img src="../img/filipe.jpg" alt="Perfil de membro da equipe">
            </div>
            <h3>Filipe Rocha</h3>
            <p class="member-role">Senhor dos SIG</p>
            <p>Se há um mapa, ele já pôs pins, layers, e até um easter egg. Consegue calcular distâncias a olho... quase sempre certo.</p>
        </div>

        <div class="team-member">
            <div class="member-avatar">
                <img src="../img/diogoSimao.png" alt="Perfil de membro da equipe">
            </div>
            <h3>Diogo Simão</h3>
            <p class="member-role">Consultor Paleolol</p>
            <p>Chamado para validar fósseis e fazer parecer que sabemos o que estamos a fazer. Garante que nenhum dino foi mal representado neste projeto.</p>
        </div>
        </div>
        </div>

        <!-- Fontes de Dados e Tecnologias -->
        <div class="about-card">
            <div class="card-icon">
                <i class="fas fa-database"></i>
            </div>
            <h2>Fontes de Dados e Tecnologias</h2>
            <p>O Paleomapa integra diversas fontes de dados e utiliza tecnologias modernas para oferecer uma experiência fluida e informativa aos utilizadores.</p>
            
            <div class="tech-section">
                <h3>Fontes de Dados</h3>
                <div class="tech-grid">
                    <div class="tech-item">
                        <i class="fas fa-map"></i>
                        <span>Sistema Nacional de Informação Geográfica (SNIG)</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-university"></i>
                        <span>Departamento de Geociências da Universidade de Aveiro</span>
                    </div>
                </div>
                
                <h3>Tecnologias Utilizadas</h3>
                <div class="tech-grid">
                    <div class="tech-item">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>QGIS para processamento de dados geoespaciais</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-database"></i>
                        <span>PostgreSQL/PostGIS para armazenamento espacial</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-layer-group"></i>
                        <span>OpenLayers para visualização de mapas interativos</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-route"></i>
                        <span>PgRouting para análise de rotas e proximidade</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-code"></i>
                        <span>HTML5, CSS3 e JavaScript para interface do utilizador</span>
                    </div>
                    <div class="tech-item">
                        <i class="fas fa-terminal"></i>
                        <span>PHP para lógica de backend e comunicação com a base de dados</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
    <div id="contacts">
        <div class="main-content">
            <h1 class="section-title">Contacto</h1>
                <div class="contact-container">
                    <div class="contact-form">
                        <h2 class="section-title">Tem dúvidas? Deixe a sua Mensagem</h2>
                        <?php
                            // Verificar se existe mensagem de feedback para exibir
                            if (isset($_SESSION['contact_msg'])) {
                                $alertClass = ($_SESSION['contact_status'] === 'success') ? 'alert-success' : 'alert-error';
                                echo '<div class="alert ' . $alertClass . '">' . $_SESSION['contact_msg'] . '</div>';
                                
                                // Limpar as mensagens da sessão após exibição
                                unset($_SESSION['contact_msg']);
                                unset($_SESSION['contact_status']);
                            }
                            ?>
                        <form id="contactForm" action="../scripts/process_contact.php" method="POST">
                            <div class="form-group">
                                <label for="name">O Teu Nome:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Assunto:</label>
                                <input type="text" id="subject" name="subject" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">A tua Mensagem:</label>
                                <textarea id="message" name="message" class="form-control" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn-submit">Enviar Mensagem</button>
                        </form>
                    </div>
                    
                    <div class="contact-info">
                        <h2>Informação de Contacto</h2>
                        
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>R. Cmte. Pinho e Freitas 28, 3750-127 Águeda<br>Escola Superior de Tecnologia e Gestão de Águeda</span>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span>paleomapaestga@gmail.com</span>
                        </div>

                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.4064575899856!2d-8.450192684595843!3d40.57232725373837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd23a7f4cbb835c5%3A0xe8bedfed9d76b244!2sEscola%20Superior%20de%20Tecnologia%20e%20Gest%C3%A3o%20de%20%C3%81gueda!5e0!3m2!1spt-PT!2spt!4v1712419351056!5m2!1spt-PT!2spt" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
                
                <div class="faq-section">
                    <h2 class="section-title">Perguntas Frequentes</h2>
                    
                    <div class="faq-item">
                        <div class="faq-question">O que é o Paleomapa?</div>
                        <div class="faq-answer">
                            O Paleomapa é uma plataforma interativa dedicada à visualização e gestão de dados paleontológicos e arqueológicos, permitindo explorar fósseis, sítios e pontos de interesse em mapas interativos com contextualização geográfica.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">Quem pode utilizar o Paleomapa?</div>
                        <div class="faq-answer">
                            O Paleomapa pode ser utilizado por investigadores, professores, estudantes e entusiastas da paleontologia e arqueologia. A plataforma é pensada para fins educativos e científicos, com funcionalidades adaptadas a diferentes tipos de utilizadores.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">Como posso contribuir com dados ou sugestões?</div>
                        <div class="faq-answer">
                            Aceitamos contribuições de investigadores, instituições e cidadãos interessados. É possível submeter novos registos de fósseis, sítios ou pontos de interesse através do nosso formulário de submissão. Todos os dados são revistos antes de serem publicados.
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">Os dados apresentados são fiáveis?</div>
                        <div class="faq-answer">
                            Todos os dados no Paleomapa são validados por especialistas em paleontologia e arqueologia. A informação é atualizada regularmente com base em fontes científicas e contribuições credenciadas.
                        </div>
                    </div>
                </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="module" src="../js/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ol@v7.4.0/dist/ol.js"></script>
    <script src="../js/mapa.js"></script>
    <script src="../js/about_us.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.faq-question').click(function() {
            $(this).toggleClass('active');
            $(this).next('.faq-answer').toggleClass('show');
        });
    });
    </script>
</body>
</html>
