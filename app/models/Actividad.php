<?php
require_once 'Model.php';

class Actividad extends Model {
    public function __construct() {
        parent::__construct('actividades');
    }

    public function getActividadesActivas() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE estado = 'activa' ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getActividadesPorTipo($tipo) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE tipo = ? AND estado = 'activa' ORDER BY nombre");
        $stmt->execute([$tipo]);
        return $stmt->fetchAll();
    }

    public function getActividadesConInsumos($actividadId) {
        $sql = "SELECT ci.*, i.nombre as insumo_nombre, i.unidad_medida
                FROM consumo_insumos ci
                JOIN insumos i ON ci.insumo_id = i.id
                WHERE ci.actividad_id = ?
                ORDER BY ci.fecha_consumo DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$actividadId]);
        return $stmt->fetchAll();
    }
}
?>