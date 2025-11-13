<?php
require_once __DIR__ . '/../models/Ingreso.php';
require_once __DIR__ . '/../models/Cliente.php';

class IngresosController {
    private $ingresoModel;
    private $clienteModel;

    public function __construct() {
        $this->ingresoModel = new Ingreso();
        $this->clienteModel = new Cliente();
    }

    public function index() {
        return $this->ingresoModel->getAll();
    }

    public function crear($data) {
        $data['usuario_id'] = $_SESSION['usuario']['id'];
        return $this->ingresoModel->create($data);
    }

    public function obtenerPorRangoFechas($fechaInicio, $fechaFin) {
        return $this->ingresoModel->getByDateRange($fechaInicio, $fechaFin);
    }

    public function obtenerClientes() {
        return $this->clienteModel->getAll();
    }

    // CORREGIDO: Este método debe llamar al modelo
    public function obtenerIngresosMes() {
        return $this->ingresoModel->getIngresosMes();
    }
}
?>