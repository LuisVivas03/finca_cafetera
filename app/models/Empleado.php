<?php
require_once 'Model.php';

class Empleado extends Model {
    public function __construct() {
        parent::__construct('empleados');
    }

    public function getWithJornales($empleadoId) {
        $sql = "SELECT e.*, 
                       SUM(j.horas_trabajadas) as total_horas_mes,
                       SUM(j.total_pago) as total_pagado_mes
                FROM empleados e
                LEFT JOIN jornales j ON e.id = j.empleado_id 
                    AND MONTH(j.fecha_jornal) = MONTH(CURRENT_DATE())
                    AND YEAR(j.fecha_jornal) = YEAR(CURRENT_DATE())
                WHERE e.id = ?
                GROUP BY e.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empleadoId]);
        return $stmt->fetch();
    }

    public function getActivos() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE estado = 'activo'");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>