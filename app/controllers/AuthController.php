<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        // Cargar el modelo de usuario
        $this->usuarioModel = new Usuario();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Usuario y contraseña son requeridos';
                header('Location: /finca_cafetera/public/login.php');
                exit;
            }

            $usuario = $this->usuarioModel->findByUsername($username);
            
            if ($usuario && $this->usuarioModel->verifyPassword($password, $usuario['password_hash'])) {
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'username' => $usuario['username'],
                    'nombre_completo' => $usuario['nombre_completo'],
                    'rol' => $usuario['rol']
                ];
                header('Location: /finca_cafetera/public/dashboard.php');
                exit;
            } else {
                $_SESSION['error'] = 'Credenciales inválidas';
                header('Location: /finca_cafetera/public/login.php');
                exit;
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /finca_cafetera/public/login.php');
        exit;
    }

    public function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario'])) {
            header('Location: /finca_cafetera/public/login.php');
            exit;
        }
        return $_SESSION['usuario'];
    }
}

// Manejar acciones del controlador
if (isset($_GET['action'])) {
    $authController = new AuthController();
    $action = $_GET['action'];
    
    if ($action === 'login') {
        $authController->login();
    } elseif ($action === 'logout') {
        $authController->logout();
    }
}
?>