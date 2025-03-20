<?php
    $total=$_REQUEST['total']??'';
    include_once "stripe/init.php";
    \Stripe\Stripe::setApiKey("sk_test_51QxYrY2cJRiYNVG56USFKiv7gYT0azfzdCKQf7YE8hJfEPT11nFP6yqt3BVSniFl5eLZaQTcbZqjCCeKMoAJWrot00VA5AVEqY");
    $toke=$_POST['stripeToken'];
    $charge=\Stripe\Charge::create([
        'amount'=>$total,
        'currency'=>'eur',
        'description'=>'Pago de ecommerce',
        'source'=>$toke
    ]);
    if($charge['captured']){
        $queryVenta="INSERT INTO ventas 
        (idCli                       ,idPago             ,fecha) values
        ('".$_SESSION['idCliente']."','".$charge['id']."',now());
        ";
        $resVenta=mysqli_query($con,$queryVenta);
        $id=mysqli_insert_id($con);
        if($resVenta){
            echo "La venta fue exitosa con el id=".$id;
        }
    }
?>