<?php
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Cosecha.php';

class VentasController {
    private $ventaModel;
    private $clienteModel;
    private $cosechaModel;

    public function __construct() {
        $this->ventaModel = new Venta();
        $this->clienteModel = new Cliente();
        $this->cosechaModel = new Cosecha();
    }

    public function index() {
        return $this->ventaModel->getAll();
    }

    public function crear($data) {
        $data['usuario_id'] = $_SESSION['usuario']['id'];
        $data['total_venta'] = $data['kilos_vendidos'] * $data['precio_kilo'];
        return $this->ventaModel->create($data);
    }

    public function obtenerClientes() {
        return $this->clienteModel->getAll();
    }

    public function marcarComoPagada($ventaId) {
        $data = ['estado' => 'pagada'];
        return $this->ventaModel->update($ventaId, $data);
    }

    public function getEstadisticasVentas() {
        return [
            'ventas_mes' => $this->ventaModel->getVentasMes(),
            'top_clientes' => $this->ventaModel->getTopClientes(),
            'distribucion_ventas' => $this->ventaModel->getDistribucionVentas()
        ];
    }
}
?>