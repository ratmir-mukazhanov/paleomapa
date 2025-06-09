<?php
require_once '../db/db_connect.php';

class DashboardService {
    private $db_connection;

    public function __construct() {
        $this->db_connection = connect_db();
    }

    /**
     * Obtém o número total de fósseis
     */
    public function getTotalFosseis() {
        try {
            $query = "SELECT COUNT(*) as total FROM findings";
            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $row = pg_fetch_assoc($result);
            return $row['total'];
        } catch (Exception $e) {
            error_log('Erro ao buscar total de fósseis: ' . $e->getMessage());
            return 0;
        }
    }

    public function searchFossils($term, $page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;
            $term = "%$term%";

            $query = "SELECT id, title, discovered_by, date_discovered, kingdom, 
                 phylum, class, \"order\", family, genus, species, source 
                 FROM findings 
                 WHERE title ILIKE $1 
                 OR discovered_by ILIKE $1 
                 OR kingdom ILIKE $1
                 OR phylum ILIKE $1 
                 OR class ILIKE $1 
                 OR \"order\" ILIKE $1 
                 OR family ILIKE $1 
                 OR genus ILIKE $1 
                 OR species ILIKE $1
                 ORDER BY id DESC
                 LIMIT $2 OFFSET $3";

            $result = pg_query_params($this->db_connection, $query, array($term, $limit, $offset));

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $fossils = [];
            while ($row = pg_fetch_assoc($result)) {
                $fossils[] = $row;
            }
            return $fossils;
        } catch (Exception $e) {
            error_log("Erro ao pesquisar fósseis: " . $e->getMessage());
            return [];
        }
    }

    public function countSearchResults($term) {
        try {
            $term = "%$term%";

            $query = "SELECT COUNT(*) as total FROM findings 
                 WHERE title ILIKE $1 
                 OR discovered_by ILIKE $1 
                 OR kingdom ILIKE $1
                 OR phylum ILIKE $1 
                 OR class ILIKE $1 
                 OR \"order\" ILIKE $1 
                 OR family ILIKE $1 
                 OR genus ILIKE $1 
                 OR species ILIKE $1";

            $result = pg_query_params($this->db_connection, $query, array($term));

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $row = pg_fetch_assoc($result);
            return (int)$row['total'];
        } catch (Exception $e) {
            error_log("Erro ao contar resultados da pesquisa: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtém o número total de sítios arqueológicos
     */
    public function getTotalArchaeologicalSites() {
        try {
            $query = "SELECT COUNT(*) as total FROM archaeological_sites";
            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $row = pg_fetch_assoc($result);
            return $row['total'];
        } catch (Exception $e) {
            error_log('Erro ao buscar total de sítios arqueológicos: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtém o número total de museus
     */
    public function getTotalMuseums() {
        try {
            $query = "SELECT COUNT(*) as total FROM museum_places";
            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $row = pg_fetch_assoc($result);
            return $row['total'];
        } catch (Exception $e) {
            error_log('Erro ao buscar total de museus: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalContactRequests() {
        try {
            $query = "SELECT COUNT(*) as total FROM contact_us";
            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $row = pg_fetch_assoc($result);
            return $row['total'];
        } catch (Exception $e) {
            error_log('Erro ao buscar total de pedidos de contato: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtém o número total de pontos de interesse
     * (soma de sítios arqueológicos, museus, cafés e bancos)
     */
    public function getTotalPontosInteresse() {
        try {
            $totalArchaeological = $this->getTotalArchaeologicalSites();
            $totalMuseums = $this->getTotalMuseums();

            $query = "SELECT COUNT(*) as total FROM benchs";
            $result = pg_query($this->db_connection, $query);
            $row = pg_fetch_assoc($result);
            $totalBenchs = $row['total'];

            $query = "SELECT COUNT(*) as total FROM cafes";
            $result = pg_query($this->db_connection, $query);
            $row = pg_fetch_assoc($result);
            $totalCafes = $row['total'];

            return $totalArchaeological + $totalMuseums + $totalBenchs + $totalCafes;
        } catch (Exception $e) {
            error_log('Erro ao buscar total de pontos de interesse: ' . $e->getMessage());
            return 0;
        }
    }

    // Método para obter todos os pedidos de contato
    public function getAllContactRequests() {
        try {
            $query = "SELECT * FROM contact_us ORDER BY submitted_at DESC";
            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $contacts = [];
            while ($row = pg_fetch_assoc($result)) {
                $contacts[] = $row;
            }
            return $contacts;
        } catch (Exception $e) {
            error_log("Erro ao buscar pedidos de contato: " . $e->getMessage());
            return [];
        }
    }

    // Método para marcar um pedido como processado
    public function markContactAsProcessed($id) {
        try {
            $query = "UPDATE contact_us SET is_processed = TRUE WHERE id = $1";
            $result = pg_query_params($this->db_connection, $query, [$id]);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao marcar pedido como processado: " . $e->getMessage());
            return false;
        }
    }


    public function unmarkContactAsProcessed($id) {
        try {
            $query = "UPDATE contact_us SET is_processed = FALSE WHERE id = $1";
            $result = pg_query_params($this->db_connection, $query, [$id]);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao desmarcar pedido como processado: " . $e->getMessage());
            return false;
        }
    }

    // Método para obter dados temporais dos fósseis (findings) para o gráfico
    public function getTemporalFossilData() {
        try {
            $query = "
            SELECT 
                to_char(created_at, 'YYYY-MM') as month,
                COUNT(*) as count
            FROM findings 
            WHERE created_at IS NOT NULL
            GROUP BY to_char(created_at, 'YYYY-MM')
            ORDER BY month ASC
            LIMIT 12
        ";

            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $data = [];
            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        } catch (Exception $e) {
            error_log('Erro ao buscar dados temporais de fósseis: ' . $e->getMessage());
            return [];
        }
    }

    // Método para obter dados temporais dos pedidos de contato para o gráfico
    public function getTemporalContactData() {
        try {
            $query = "
            SELECT 
                to_char(submitted_at, 'YYYY-MM') as month,
                COUNT(*) as count
            FROM contact_us 
            WHERE submitted_at IS NOT NULL
            GROUP BY to_char(submitted_at, 'YYYY-MM')
            ORDER BY month ASC
            LIMIT 12
        ";

            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $data = [];
            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        } catch (Exception $e) {
            error_log('Erro ao buscar dados temporais de pedidos de contato: ' . $e->getMessage());
            return [];
        }
    }

    // Método para obter todos os fósseis
    public function getAllFossils($page = 1, $limit = 20) {
        try {
            $offset = ($page - 1) * $limit;

            // Query para buscar apenas os fósseis da página atual incluindo o campo source
            $query = "SELECT id, title, discovered_by, date_discovered, kingdom, phylum, class, \"order\", family, genus, species, source 
             FROM findings
             ORDER BY id DESC
             LIMIT $limit OFFSET $offset";

            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            $fossils = [];
            while ($row = pg_fetch_assoc($result)) {
                $fossils[] = $row;
            }
            return $fossils;
        } catch (Exception $e) {
            error_log("Erro ao buscar fósseis: " . $e->getMessage());
            return [];
        }
    }

    public function countFossils() {
        try {
            $query = "SELECT COUNT(*) as total FROM findings";
            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception('Erro na contagem de fósseis: ' . pg_last_error($this->db_connection));
            }

            $row = pg_fetch_assoc($result);
            return (int)$row['total'];
        } catch (Exception $e) {
            error_log("Erro ao contar fósseis: " . $e->getMessage());
            return 0;
        }
    }

    // Método para adicionar um novo fóssil
    public function addFossil($title, $discoveredBy, $dateDiscovered, $kingdom,
                              $phylum, $class, $order, $family, $genus, $species,
                              $latitude, $longitude, $source = null) {
        try {
            // Primeiro, obter o próximo ID disponível
            $idQuery = "SELECT COALESCE(MAX(id) + 1, 1) AS next_id FROM findings";
            $idResult = pg_query($this->db_connection, $idQuery);
            if (!$idResult) {
                throw new Exception('Erro ao obter próximo ID: ' . pg_last_error($this->db_connection));
            }

            $row = pg_fetch_assoc($idResult);
            $nextId = $row['next_id'];

            // Criar objeto de geometria ponto a partir das coordenadas
            $geomQuery = "ST_SetSRID(ST_MakePoint($1, $2), 4326)";

            $query = "INSERT INTO findings (id, title, discovered_by, date_discovered,
                          kingdom, phylum, class, \"order\",
                          family, genus, species, geom, created_at, source)
         VALUES ($3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13,
                 " . $geomQuery . ", CURRENT_TIMESTAMP, $14)";

            $params = array(
                $longitude, $latitude,  // Parâmetros para a geometria (1, 2)
                $nextId, // ID gerado manualmente (3)
                $title, $discoveredBy, // (4, 5)
                !empty($dateDiscovered) ? $dateDiscovered : null, // (6)
                $kingdom, $phylum, $class, $order, // (7, 8, 9, 10)
                $family, $genus, $species, // (11, 12, 13)
                $source // (14)
            );

            $result = pg_query_params($this->db_connection, $query, $params);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao adicionar fóssil: " . $e->getMessage());
            return false;
        }
    }

    // Método para obter um fóssil por ID
    public function getFossilById($id) {
        try {
            // Consulta para buscar o fóssil e suas coordenadas incluindo o campo source
            $query = "SELECT 
                  id, title, discovered_by, date_discovered, kingdom, 
                  phylum, class, \"order\", family, genus, species, source,
                  ST_X(ST_Transform(geom, 4326)) AS longitude,
                  ST_Y(ST_Transform(geom, 4326)) AS latitude
                  FROM findings 
                  WHERE id = $1";

            $result = pg_query_params($this->db_connection, $query, [$id]);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            return pg_fetch_assoc($result);
        } catch (Exception $e) {
            error_log("Erro ao buscar fóssil por ID: " . $e->getMessage());
            return null;
        }
    }

    // Método para atualizar um fóssil existente
    public function updateFossil($id, $title, $discoveredBy, $dateDiscovered, $kingdom,
                                 $phylum, $class, $order, $family, $genus, $species,
                                 $latitude, $longitude, $source = null) {
        try {
            // Criar objeto de geometria ponto a partir das coordenadas
            $geomQuery = "ST_SetSRID(ST_MakePoint($1, $2), 4326)";

            $query = "UPDATE findings SET 
                  title = $3, 
                  discovered_by = $4, 
                  date_discovered = $5, 
                  kingdom = $6, 
                  phylum = $7, 
                  class = $8, 
                  \"order\" = $9, 
                  family = $10, 
                  genus = $11, 
                  species = $12,
                  source = $13,
                  geom = " . $geomQuery . "
                  WHERE id = $14";

            $params = array(
                $longitude, $latitude,  // Parâmetros para a geometria (1, 2)
                $title, $discoveredBy,  // (3, 4)
                !empty($dateDiscovered) ? $dateDiscovered : null, // (5)
                $kingdom, $phylum, $class, $order,  // (6, 7, 8, 9)
                $family, $genus, $species,  // (10, 11, 12)
                $source, // (13)
                $id  // (14)
            );

            $result = pg_query_params($this->db_connection, $query, $params);

            if (!$result) {
                throw new Exception('Erro na execução da query: ' . pg_last_error($this->db_connection));
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao atualizar fóssil: " . $e->getMessage());
            return false;
        }
    }

    // Método para excluir um fóssil por ID
    public function deleteFossil($id) {
        try {
            $query = "DELETE FROM findings WHERE id = $1";
            $result = pg_query_params($this->db_connection, $query, [$id]);

            if (!$result) {
                throw new Exception('Erro ao excluir fóssil: ' . pg_last_error($this->db_connection));
            }

            // Verificar se alguma linha foi afetada
            $rowsAffected = pg_affected_rows($result);
            if ($rowsAffected === 0) {
                throw new Exception('Nenhum fóssil encontrado com o ID fornecido.');
            }

            return true;
        } catch (Exception $e) {
            error_log("Erro ao excluir fóssil: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtém pedidos de contacto com paginação
     * @param int $page Número da página atual
     * @param int $limit Número de registos por página
     * @return array
     */
    public function getContactRequestsPaginated($page = 1, $limit = 10) {
        try {
            $offset = ($page - 1) * $limit;
            $query = "SELECT * FROM contact_us ORDER BY submitted_at DESC LIMIT $2 OFFSET $1";
            $result = pg_query_params($this->db_connection, $query, array($offset, $limit));

            if (!$result) {
                throw new Exception("Erro ao buscar pedidos de contacto: " . pg_last_error($this->db_connection));
            }

            $contacts = array();
            while ($row = pg_fetch_assoc($result)) {
                $contacts[] = $row;
            }

            return $contacts;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return array();
        }
    }

    /**
     * Conta o total de pedidos de contacto
     * @return int
     */
    public function countContactRequests() {
        try {
            $query = "SELECT COUNT(*) as total FROM contact_us";
            $result = pg_query($this->db_connection, $query);

            if (!$result) {
                throw new Exception("Erro ao contar pedidos de contacto: " . pg_last_error($this->db_connection));
            }

            $row = pg_fetch_assoc($result);
            return (int)$row['total'];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return 0;
        }
    }


    /**
     * Fecha a conexão com a base de dados
     */
    public function __destruct() {
        if ($this->db_connection) {
            pg_close($this->db_connection);
        }
    }
}
?>