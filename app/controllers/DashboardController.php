<?php
require_once __DIR__ . '/../models/Ingreso.php';
require_once __DIR__ . '/../models/Egreso.php';
require_once __DIR__ . '/../models/Cosecha.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../models/Jornal.php';
require_once __DIR__ . '/../models/Cliente.php';

class DashboardController {
    private $ingresoModel;
    private $egresoModel;
    private $cosechaModel;
    private $ventaModel;
    private $empleadoModel;
    private $jornalModel;
    private $clienteModel;

    public function __construct() {
        $this->ingresoModel = new Ingreso();
        $this->egresoModel = new Egreso();
        $this->cosechaModel = new Cosecha();
        $this->ventaModel = new Venta();
        $this->empleadoModel = new Empleado();
        $this->jornalModel = new Jornal();
        $this->clienteModel = new Cliente();
    }

    public function getEstadisticas() {
        return [
            'ingresos_mes' => $this->ingresoModel->getIngresosMes(),
            'egresos_mes' => $this->egresoModel->getEgresosMes(),
            'cafe_cosechado' => $this->cosechaModel->getCosechasMes(),
            'cafe_vendido' => $this->ventaModel->getVentasMes()['total_kilos'] ?? 0,
            'total_empleados' => $this->empleadoModel->count(),
            'total_pagos_jornales' => $this->jornalModel->getTotalPagosMes(),
            'total_clientes' => $this->clienteModel->count(),
            'ventas_mes' => $this->ventaModel->getVentasMes()['total_ventas'] ?? 0,
            'utilidad_mes' => $this->ingresoModel->getIngresosMes() - $this->egresoModel->getEgresosMes(),
            'margen_mes' => $this->calcularMargen()
        ];
    }

    private function calcularMargen() {
        $ingresos = $this->ingresoModel->getIngresosMes();
        $egresos = $this->egresoModel->getEgresosMes();
        
        if ($ingresos > 0) {
            return (($ingresos - $egresos) / $ingresos) * 100;
        }
        return 0;
    }

    public function getActividadReciente() {
        // Combinar ingresos y egresos recientes
        $sql = "SELECT 
                    'ingreso' as tipo,
                    fecha_ingreso as fecha,
                    descripcion,
                    monto,
                    NULL as proveedor
                FROM ingresos
                WHERE fecha_ingreso >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                
                UNION ALL
                
                SELECT 
                    'egreso' as tipo,
                    fecha_egreso as fecha,
                    descripcion,
                    monto,
                    p.razon_social as proveedor
                FROM egresos e
                LEFT JOIN proveedores p ON e.proveedor_id = p.id
                WHERE fecha_egreso >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                
                ORDER BY fecha DESC
                LIMIT 10";
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTopClientes() {
        return $this->ventaModel->getTopClientes(5);
    }

    public function getProximasCosechas() {
        $sql = "SELECT l.nombre, l.codigo_lote, l.variedad_cafe,
                       MAX(c.fecha_cosecha) as ultima_cosecha
                FROM lotes l
                LEFT JOIN cosechas c ON l.id = c.lote_id
                WHERE l.estado = 'activo'
                GROUP BY l.id
                ORDER BY ultima_cosecha DESC
                LIMIT 5";
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getJornalesPendientes() {
        $sql = "SELECT COUNT(*) as total 
                FROM jornales 
                WHERE estado = 'pendiente' 
                AND fecha_jornal >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)";
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}
?>