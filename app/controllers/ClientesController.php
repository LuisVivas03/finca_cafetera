<?php
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Venta.php';

class ClientesController {
    private $clienteModel;
    private $ventaModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
        $this->ventaModel = new Venta();
    }

    public function index() {
        return $this->clienteModel->getAll();
    }

    public function crear($data) {
        return $this->clienteModel->create($data);
    }

    public function actualizar($id, $data) {
        return $this->clienteModel->update($id, $data);
    }

    public function eliminar($id) {
        return $this->clienteModel->delete($id);
    }

    public function obtenerConHistorial($clienteId) {
        $cliente = $this->clienteModel->find($clienteId);
        $historial = $this->clienteModel->getHistorialCompras($clienteId);
        
        return [
            'cliente' => $cliente,
            'historial' => $historial
        ];
    }

    public function getClientesFrecuentes() {
        return $this->clienteModel->getClientesFrecuentes();
    }

    public function buscar($termino) {
        $sql = "SELECT * FROM clientes 
                WHERE (nombres LIKE ? OR apellidos LIKE ? OR razon_social LIKE ? OR numero_identificacion LIKE ?)
                AND estado = 'activo'";
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $termino = "%$termino%";
        $stmt->execute([$termino, $termino, $termino, $termino]);
        return $stmt->fetchAll();
    }
}
?>