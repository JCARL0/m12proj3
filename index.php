<?php
include_once "admin/db_ecommerce.php";
$con = mysqli_connect($host, $user, $pass, $db);

// Construcción correcta de la condición WHERE
$where = " where 1=1 ";
$nombre = mysqli_real_escape_string($con, $_REQUEST['nombre'] ?? '');

if (!empty($nombre)) {
    $where .= " and nombre like '%" . $nombre . "%'";
}

// Contar el número total de registros
$queryCuenta = "SELECT COUNT(*) as cuenta FROM productos $where;";
$resCuenta = mysqli_query($con, $queryCuenta);
$rowCuenta = mysqli_fetch_assoc($resCuenta);
$totalRegistros = $rowCuenta['cuenta'];

$elementosPorPagina = 6;
$totalPaginas = ceil($totalRegistros / $elementosPorPagina);

$paginaSel = $_REQUEST['pagina'] ?? false;
if ($paginaSel == false) {
    $inicioLimite = 0;
    $paginaSel = 1;
} else {
    $inicioLimite = ($paginaSel - 1) * $elementosPorPagina;
}

$limite = " limit $inicioLimite,$elementosPorPagina ";

// Consulta SQL para obtener productos
$query = "SELECT 
    p.id,
    p.nombre,
    p.precio,
    p.existencia,
    f.web_path
    FROM productos AS p
    INNER JOIN productos_files AS pf ON pf.producto_id = p.id
    INNER JOIN files AS f ON f.id = pf.file_id
    $where
    GROUP BY p.id
    $limite";

$res = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My Ecommerce</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="admin/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="admin/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="admin/plugins/daterangepicker/daterangepicker.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand navbar-dark">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
                    </ul>
                    <form class="form-inline ml-3" action="index.php">
                        <div class="input-group input-group-sm">
                            <input class="form-control bg-gray" type="search" placeholder="Search" name="nombre" value="<?php echo htmlspecialchars($_REQUEST['nombre'] ?? ''); ?>">
                            <input type="hidden" name="modulo" value="productos">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </nav>
                <div class="row mt-1">
                    <?php while ($row = mysqli_fetch_assoc($res)) { ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card border-primary">
                                <img class="card-img-top img-thumbnail" src="<?php echo $row['web_path'] ?>" alt="">
                                <div class="card-body">
                                    <h2 class="card-title"><strong><?php echo $row['nombre'] ?></strong></h2>
                                    <p class="card-text"><strong>Precio:</strong> <?php echo $row['precio'] ?></p>
                                    <p class="card-text"><strong>Existencia:</strong> <?php echo $row['existencia'] ?></p>
                                    <a href="#" class="btn btn-primary">Ver</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($totalPaginas > 0) { ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($paginaSel != 1) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="index.php?modulo=productos&pagina=<?php echo ($paginaSel - 1); ?>">&laquo;</a>
                                </li>
                            <?php } ?>
                            <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                                <li class="page-item <?php echo ($paginaSel == $i) ? "active" : ""; ?>">
                                    <a class="page-link" href="index.php?modulo=productos&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($paginaSel != $totalPaginas) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="index.php?modulo=productos&pagina=<?php echo ($paginaSel + 1); ?>">&raquo;</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <script src="admin/plugins/jquery/jquery.min.js"></script>
    <script src="admin/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="admin/plugins/moment/moment.min.js"></script>
    <script src="admin/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="admin/dist/js/adminlte.js"></script>
</body>
</html>
