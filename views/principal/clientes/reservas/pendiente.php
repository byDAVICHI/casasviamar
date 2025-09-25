<?php
include_once 'views/template/header-cliente.php';
//session_start();
error_reporting(0);
?>

<div class="main-content">

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">TU RESERVA</h4>
                <?php if (!empty($_SESSION['reserva'])) { ?>
                    <div
                        class="alert alert-info alert-dismissible fade show"
                        role="alert">
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>

                        <strong>AVISO!</strong> Tienes una reserva pendiente
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <img
                                src="<?php echo RUTA_PRINCIPAL . 'assets/img/habitaciones/' . $data['habitacion']['foto']; ?>"
                                class="img-fluid rounded-top"
                                alt="" />

                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <strong>Habitacion: </strong>
                                    <?php echo $data['habitacion']['estilo']; ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <strong>Fecha Llegada: </strong>
                                    <?php echo fechaPerzo($_SESSION['reserva']['f_llegada']); ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <strong>Fecha Salida: </strong>
                                    <?php echo fechaPerzo($_SESSION['reserva']['f_salida']); ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <strong>Precio | Noche: </strong>
                                    <?php echo $data['habitacion']['precio']; ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <strong>Capacidad: </strong>
                                    <?php echo $data['habitacion']['capacidad']; ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <strong>N° Casa: </strong>
                                    <?php echo $data['habitacion']['numero']; ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <strong>Descripcion: </strong>
                                    <?php echo $data['habitacion']['descripcion']; ?>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <strong>Precio Total: </strong>
                                    <?php echo $_SESSION['total']; ?>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button
                                            class="accordion-button"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne"
                                            aria-expanded="true"
                                            aria-controls="collapseOne">
                                            PAYPAL
                                        </button>
                                    </h2>
                                    <div
                                        id="collapseOne"
                                        class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <!-- Set up a container element for the button -->
                                            <div id="paypal-button-container"></div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>


                <?php } else { ?>
                    <div
                        class="alert alert-warning alert-dismissible fade show"
                        role="alert">
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>

                        <strong>AVISO!</strong> No tienes ninguna reserva pendiente
                    </div>
                <?php    } ?>
            </div>
        </div>
    </div>
</div>






<?php
include_once 'views/template/footer-cliente.php';
?>

<script src="https://www.paypal.com/sdk/js?client-id=test&currency=MXN"></script>
<script src="https://sandbox.paypal.com/sdk/js?client-id=<?php echo CLIENTE_ID; ?>"></script>
<!-- <script src="https://sdk.mercadopago.com/js/v2"> </script> -->
<script>
    // Render the PayPal button into #paypal-button-container
    paypal.Buttons({

        // Call your server to set up the transaction
        createOrder: function(data, actions) {
            return actions.order.create({
                aPplication_context: {
                    shipping_preference: 'NO_SHIPPING',
                },
                purchase_units: [{
                    amount: {
                        curreny_code: '<?php echo MONEDA_PAYPAL; ?>',
                        value: '<?php echo $_SESSION['total']; ?>',
                    }
                }]
            })
        },

        // Call your server to finalize the transaction
        onApprove: function(data, actions) {
            console.log(data);
            return actions.order.capture().then(function(orderData) {
                fetch(base_url + 'reserva/registrarReserva', {
                    method: 'post',
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        reserva: orderData
                    })
                }).then(function(res) {
                    alert('TRANSACCION EXITOSA');

                    return res.json();
                    // FUNCION
                    /* dato.append('total', total);
                     dato.append('f_llegada', f_llegada);
                     dato.append('f_salida', f_salida);
                     dato.append('habitacion', habitacion);
                     dato.append('usuario', usuario);
                     fetch('../reservas/transaccion.php', {
                         method: "POST",
                         body: dato
                     }).then(res => res.text()) */
                    // Redirige a una nueva pantalla
                    window.location.href = '<?php echo RUTA_PRINCIPAL . 'views/principal/clientes/reservas'; ?>transaccion.php"';
                }).then(function(orderData) {

                });
            })

        }

    }).render('#paypal-button-container');

    /* MERCADO PAGO
    const mp = new MercadoPago('<?php echo PUBLIC_KEY; ?>');
    const bricksBuilder = mp.bricks();

    mp.bricks().create("wallet", "wallet_container", {
        initialization: {
            preferenceId: "<?php echo $data['preference_id']; ?>",
        },
        customization: {
            texts: {
                valueProp: 'smart_option',
            },
        },
    });
    */
</script>




</body>

</html>