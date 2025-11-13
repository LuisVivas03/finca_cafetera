<?php
/**
 * Header común para todas las páginas
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Finca Cafetera' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #8b4513;">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <strong>☕ Finca Cafetera</strong>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">🏠 Dashboard</a>
                    </li>
                    
                    <!-- Gestión de Personal -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            👥 Gestión de Personal
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="empleados.php">📋 Empleados</a></li>
                            <li><a class="dropdown-item" href="jornales.php">⏱️ Jornales</a></li>
                        </ul>
                    </li>
                    
                    <!-- Gestión Financiera -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            💰 Gestión Financiera
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="ingresos.php">💰 Ingresos</a></li>
                            <li><a class="dropdown-item" href="egresos.php">💸 Egresos</a></li>
                        </ul>
                    </li>
                    
                    <!-- Producción y Ventas -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            🌱 Producción y Ventas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="cosechas.php">🌱 Cosechas</a></li>
                            <li><a class="dropdown-item" href="ventas.php">🛒 Ventas</a></li>
                        </ul>
                    </li>
                    
                    <!-- Catálogos -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            📚 Catálogos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="clientes.php">👥 Clientes</a></li>
                        </ul>
                    </li>
                    
                    <!-- Reportes -->
                    <li class="nav-item">
                        <a class="nav-link" href="reportes.php">📊 Reportes</a>
                    </li>
                </ul>
                
                <?php if ($usuario): ?>
                <div class="navbar-nav">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            👋 <?= htmlspecialchars($usuario['nombre_completo']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text">
                                <small>Rol: <span class="badge bg-primary"><?= ucfirst($usuario['rol']) ?></span></small>
                            </span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../app/controllers/AuthController.php?action=logout">
                                🚪 Cerrar sesión
                            </a></li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>