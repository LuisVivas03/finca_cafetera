<?php
require_once 'Model.php';

class Reporte extends Model {
    public function __construct() {
        parent::__construct(''); // No tiene tabla específica
    }

    public function getReporteFinanciero($fechaInicio, $fechaFin) {
        $sql = "SELECT 
                    'ingresos' as tipo,
                    fecha_ingreso as fecha,
                    descripcion,
                    monto,
                    NULL as proveedor
                FROM ingresos
                WHERE fecha_ingreso BETWEEN ? AND ?
                
                UNION ALL
                
                SELECT 
                    'egresos' as tipo,
                    fecha_egreso as fecha,
                    descripcion,
                    monto * -1 as monto,
                    p.razon_social as proveedor
                FROM egresos e
                LEFT JOIN proveedores p ON e.proveedor_id = p.id
                WHERE fecha_egreso BETWEEN ? AND ?
                
                ORDER BY fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin, $fechaInicio, $fechaFin]);
        return $stmt->fetchAll();
    }

    public function getReporteProduccion($fechaInicio, $fechaFin) {
        $sql = "SELECT 
                    c.fecha_cosecha,
                    l.nombre as lote,
                    l.variedad_cafe,
                    c.kilos_cosechados,
                    c.calidad,
                    c.rendimiento
                FROM cosechas c
                JOIN lotes l ON c.lote_id = l.id
                WHERE c.fecha_cosecha BETWEEN ? AND ?
                ORDER BY c.fecha_cosecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll();
    }
}
?>