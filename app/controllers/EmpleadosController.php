<?php
require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../models/Jornal.php';

class EmpleadosController {
    private $empleadoModel;
    private $jornalModel;

    public function __construct() {
        $this->empleadoModel = new Empleado();
        $this->jornalModel = new Jornal();
    }

    public function index() {
        return $this->empleadoModel->getAll();
    }

    public function crear($data) {
        return $this->empleadoModel->create($data);
    }

    public function actualizar($id, $data) {
        return $this->empleadoModel->update($id, $data);
    }

    public function eliminar($id) {
        return $this->empleadoModel->delete($id);
    }

    public function obtenerConDetalles($id) {
        return $this->empleadoModel->getWithJornales($id);
    }

    public function obtenerJornales($empleadoId, $mes = null, $ano = null) {
        return $this->jornalModel->getJornalesEmpleado($empleadoId, $mes, $ano);
    }
}
?>