<?php
require_once 'Model.php';

class Cliente extends Model {
    public function __construct() {
        parent::__construct('clientes');
    }

    public function getClientesFrecuentes() {
        // CORREGIDO: LIMIT con concatenación
        $sql = "SELECT c.*, COUNT(v.id) as total_compras, SUM(v.total_venta) as monto_total
                FROM clientes c
                LEFT JOIN ventas v ON c.id = v.cliente_id AND v.estado = 'pagada'
                WHERE c.estado = 'activo'
                GROUP BY c.id
                HAVING total_compras > 0
                ORDER BY monto_total DESC
                LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getHistorialCompras($clienteId) {
        $sql = "SELECT v.* 
                FROM ventas v
                WHERE v.cliente_id = ? AND v.estado = 'pagada'
                ORDER BY v.fecha_venta DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$clienteId]);
        return $stmt->fetchAll();
    }
}
?>