<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - ERP Mi Negocio</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }

        .auth-card {
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .auth-card .card-header {
            background-color: #343a40;
            color: white;
            border-radius: 1rem 1rem 0 0;
            padding: 1.5rem;
            text-align: center;
        }

        .auth-card .card-body {
            padding: 2rem;
        }

        .app-logo {
            margin-bottom: 1rem;
            width: 80px;
            height: 80px;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="card auth-card">
            <div class="card-header">
                <div class="text-center mb-2">
                    <i class="fas fa-store fa-3x app-logo"></i>
                </div>
                <h4 class="card-title mb-0"><?= $this->renderSection('title') ?></h4>
            </div>
            <div class="card-body">
                <?= $this->renderSection('main') ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>