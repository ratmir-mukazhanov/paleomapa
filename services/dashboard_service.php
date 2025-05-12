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


    /**
     * Fecha a conexão com o banco de dados
     */
    public function __destruct() {
        if ($this->db_connection) {
            pg_close($this->db_connection);
        }
    }
}
?>