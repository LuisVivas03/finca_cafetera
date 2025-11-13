<?php
require_once 'Model.php';

class Venta extends Model {
    public function __construct() {
        parent::__construct('ventas', false); // false = no usa borrado suave
    }

    public function getVentasMes() {
        $sql = "SELECT SUM(kilos_vendidos) as total_kilos, 
                       SUM(total_venta) as total_ventas
                FROM {$this->table} 
                WHERE MONTH(fecha_venta) = MONTH(CURRENT_DATE()) 
                AND YEAR(fecha_venta) = YEAR(CURRENT_DATE())
                AND estado = 'pagada'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getWithCliente() {
        $sql = "SELECT v.*, 
                       c.nombres as cliente_nombres, 
                       c.apellidos as cliente_apellidos,
                       c.razon_social as cliente_razon_social
                FROM {$this->table} v
                JOIN clientes c ON v.cliente_id = c.id
                ORDER BY v.fecha_venta DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // CORREGIDO: LIMIT con parámetro correcto
    public function getTopClientes($limit = 5) {
        $sql = "SELECT c.id, c.nombres, c.apellidos, c.razon_social,
                       COUNT(v.id) as total_compras,
                       SUM(v.total_venta) as total_compras_monto,
                       SUM(v.kilos_vendidos) as total_kilos
                FROM ventas v
                JOIN clientes c ON v.cliente_id = c.id
                WHERE v.estado = 'pagada'
                GROUP BY c.id
                ORDER BY total_compras_monto DESC
                LIMIT " . (int)$limit;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDistribucionVentas() {
        $sql = "SELECT 
                    calidad,
                    SUM(kilos_vendidos) as total_kilos,
                    SUM(total_venta) as total_ventas,
                    COUNT(*) as cantidad_ventas
                FROM ventas
                WHERE MONTH(fecha_venta) = MONTH(CURRENT_DATE())
                AND estado = 'pagada'
                GROUP BY calidad";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Sobrescribir getAll
    public function getAll() {
        return $this->getWithCliente();
    }
}
?>