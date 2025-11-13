<?php
require_once __DIR__ . '/../models/Egreso.php';
require_once __DIR__ . '/../models/Proveedor.php';

class EgresosController {
    private $egresoModel;
    private $proveedorModel;

    public function __construct() {
        $this->egresoModel = new Egreso();
        $this->proveedorModel = new Proveedor();
    }

    public function index() {
        return $this->egresoModel->getAll();
    }

    public function crear($data) {
        $data['usuario_id'] = $_SESSION['usuario']['id'];
        return $this->egresoModel->create($data);
    }

    public function obtenerPorTipo($tipo) {
        return $this->egresoModel->getByTipo($tipo);
    }

    public function obtenerProveedores() {
        return $this->proveedorModel->getAll();
    }

    // CORREGIDO: Este método debe llamar al modelo
    public function obtenerEgresosMes() {
        return $this->egresoModel->getEgresosMes();
    }

    public function getEstadisticasMensuales() {
        $sql = "SELECT 
                    tipo,
                    SUM(monto) as total,
                    MONTH(fecha_egreso) as mes,
                    YEAR(fecha_egreso) as ano
                FROM egresos 
                WHERE YEAR(fecha_egreso) = YEAR(CURRENT_DATE())
                GROUP BY tipo, MONTH(fecha_egreso), YEAR(fecha_egreso)
                ORDER BY mes DESC";
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>