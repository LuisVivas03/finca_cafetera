<?php
/**
 * Clase Singleton para manejar la conexión a la base de datos
 */

class Database {
    private $connection;
    private static $instance = null;

    private function __construct() {
        // Cargar configuración
        require_once __DIR__ . '/../config/database.php';
        
        try {
            $dsn = "mysql:host=" . DatabaseConfig::HOST . ";dbname=" . DatabaseConfig::DATABASE . ";charset=" . DatabaseConfig::CHARSET;
            $this->connection = new PDO($dsn, DatabaseConfig::USERNAME, DatabaseConfig::PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}
?>