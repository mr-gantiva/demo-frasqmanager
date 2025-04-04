<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ERP Mi Negocio' ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- jQuery (para compatibilidad con algunos plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS Bundle (incluye Popper) - MOVER AL PRINCIPIO -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
            padding-top: 60px;
            /* Para el navbar fijo superior */
        }

        .navbar {
            height: 60px;
            z-index: 1030;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            bottom: 0;
            left: 0;
            width: 200px;
            z-index: 100;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #343a40;
            color: white;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, .75);
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, .05);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, .1);
        }

        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 210px;
            padding: 20px;
            transition: all 0.3s;
            width: 85%;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .main-content.active {
                margin-left: 250px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar superior -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/') ?>">ERP Mi Negocio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                    <?php if (auth()->loggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> <?= auth()->user()->username ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?= base_url('perfil') ?>"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar y Contenido -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="sidebar">
                <ul class="nav flex-column mt-3">
                    <?php
                    // Debug: Mostrar información del usuario actual
                    $isLoggedIn = auth()->loggedIn();
                    $username = $isLoggedIn ? auth()->user()->username : 'No autenticado';
                    $groups = $isLoggedIn ? auth()->user()->getGroups() : [];
                    $groupStr = implode(', ', $groups);
                    ?>

                    <li class="nav-item d-none">
                        <span class="nav-link">
                            <i class="fas fa-info-circle text-warning"></i>
                            Debug: <?= $username ?>
                            <?= !empty($groups) ? "[$groupStr]" : "[Sin rol]" ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/productos') ?>">
                            <i class="fas fa-box"></i> Productos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/clientes') ?>">
                            <i class="fas fa-users"></i> Clientes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/ventas') ?>">
                            <i class="fas fa-shopping-cart"></i> Ventas
                        </a>
                    </li>

                    <?php if ($isLoggedIn && in_array('admin', $groups)): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/reportes') ?>">
                                <i class="fas fa-chart-bar"></i> Reportes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/usuarios') ?>">
                                <i class="fas fa-users-cog"></i> Usuarios
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Contenido principal -->
            <main class="main-content">
                <!-- Mensajes de alerta -->
                <?php if (session()->has('message')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session('message') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>