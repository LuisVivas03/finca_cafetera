<?php
require_once 'Model.php';

class Cosecha extends Model {
    public function __construct() {
        parent::__construct('cosechas', false); // false = no usa borrado suave
    }

    public function getCosechasMes() {
        $sql = "SELECT SUM(kilos_cosechados) as total 
                FROM {$this->table} 
                WHERE MONTH(fecha_cosecha) = MONTH(CURRENT_DATE()) 
                AND YEAR(fecha_cosecha) = YEAR(CURRENT_DATE())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getWithLote() {
        $sql = "SELECT c.*, l.nombre as lote_nombre, l.codigo_lote
                FROM {$this->table} c
                JOIN lotes l ON c.lote_id = l.id
                ORDER BY c.fecha_cosecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Sobrescribir getAll
    public function getAll() {
        return $this->getWithLote();
    }
}
?>