# 🦖 PaleoMapa - Sistema Interativo de Visualização Paleontológica

**PaleoMapa** é uma aplicação web interativa para visualização, exploração e gestão de dados paleontológicos em Portugal. Desenvolvida com foco educativo e científico, integra dados geoespaciais com funcionalidades avançadas de filtragem, contextualização geográfica e administração.

🔗 **[Ver Online](https://gis4cloud.com/grupo2_ptas2025)**

## 🌍 Funcionalidades Principais

- **Mapa Interativo** com visualização de fósseis, sítios arqueológicos e pontos de interesse.
- **Filtragem Avançada** por idade geológica, tipo de fóssil (espécie, família, ordem, genése, etc) e raio de distância.
- **Painel Administrativo** para inserção, edição e remoção de registos paleontológicos e pedidos de contacto.
- **Contextualização Geográfica** com museus, parques, cafés e zonas de descanso.
- **Importação Automatizada** de dados paleontológicos (Excel).
- **Exportação Automatizada** de dados paleontológicos (SQL, Excel, CSV).
- **Cálculo de Isócronas** (tempo de deslocação a pé, de bicicleta ou de carro - 5, 10, 15 minutos).
- **Camadas Personalizadas** com alternância dinâmica.

## 🧱 Tecnologias Utilizadas

- **Frontend:**
  - [OpenLayers](https://openlayers.org/)
  - [Turf.js](https://turfjs.org/)
  - HTML, CSS, JavaScript

- **Backend:**
  - PHP (API)
  - PostgreSQL + PostGIS
  - PgRouting

- **Outros:**
  - QGIS (pré-processamento de dados)
  - Deck.gl (visualização de grandes volumes de dados)

## 🗂️ Estrutura do Projeto

- 📁 `/admin` – Painel administrativo da aplicação  
- 📁 `/components` – Componentes reutilizáveis  
- 📁 `/css` – Ficheiros de estilos  
- 📁 `/db` – Conexão e lógica da base de dados  
- 📁 `/img` – Imagens usadas na interface  
- 📁 `/js` – Scripts JavaScript da aplicação  
- 📁 `/login` – Página e lógica de autenticação  
- 📁 `/pages` – Páginas da aplicação (mapa, detalhes, etc.)  
- 📁 `/scripts` – Scripts PHP (ex: importação, cálculos, chamadas de camadas, etc)  
- 📁 `/services` – Serviços de lógica backend/API  
- 📁 `/vendor` – Dependências PHP geridas pelo Composer  
- 📄 `index.php` – Ponto de entrada da aplicação  
- 📄 `composer.json` – Definições das dependências PHP  
- 📄 `README.md` – Este ficheiro :)

## 🛠️ Instalação e Execução

### 1. Clonar o repositório
```bash
git clone https://github.com/seu-username/paleomapa.git
cd paleomapa
```
### 2. Criar a base de dados PostgreSQL com extensão
```bash 
PostGIS e PgRouting
psql -U teu_utilizador -c "CREATE DATABASE paleomapa;"
psql -U teu_utilizador -d paleomapa -c "CREATE EXTENSION postgis;"
```
### 3. Importar os dados iniciais
```bash
psql -U teu_utilizador -d paleomapa -f data/import.sql
```
### 4. Colocar os ficheiros na pasta pública do servidor local (por ex. htdocs ou www)
```bash
(Este passo é manual – move a pasta ou usa comandos cp/mv conforme o teu ambiente)
```
### 5. Iniciar o servidor (Apache + PostgreSQL) com XAMPP, Laragon, etc.
```bash
# Aceder via navegador a:
# http://localhost/paleomapa/index.html
```
## 👥 Equipa

- **Gustavo Gião** — Dev Full Stack
- **Ratmir Mukazhanov** — Dev Full Stack
- **Filipe Rocha** — Dev Full Stack 
- **Diogo Simão** — Dev Full Stack

## 📄 Licença

Este projeto está licenciado sob a **Licença MIT**.

Podes usar, modificar e distribuir livremente este software, desde que mantenhas o aviso de copyright original.

Consulta o ficheiro [`LICENSE`](LICENSE) para mais informações.
