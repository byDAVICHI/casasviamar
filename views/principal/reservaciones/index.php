<?php
include_once 'views/template/header-principal.php';
?>

<section class="section contact-section" id="next">
    <div class="container">
        <div class="row">
            <div class="col-md-7" data-aos="fade-up" data-aos-delay="100">

                <form action="#" method="post" class="bg-white p-md-5 p-4 mb-5 border">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="text-black font-weight-bold" for="name">Nombre</label>
                            <input type="text" id="name" class="form-control ">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="text-black font-weight-bold" for="phone">Teléfono</label>
                            <input type="text" id="phone" class="form-control ">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="text-black font-weight-bold" for="email">Correo Electronico</label>
                            <input type="email" id="email" class="form-control ">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="text-black font-weight-bold" for="checkin_date">Fecha Check In</label>
                            <input type="text" id="checkin_date" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="text-black font-weight-bold" for="checkout_date">Fecha Check Out</label>
                            <input type="text" id="checkout_date" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="adults" class="font-weight-bold text-black">Adultos</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                <select name="" id="adults" class="form-control">
                                    <option value="">1</option>
                                    <option value="">2</option>
                                    <option value="">3</option>
                                    <option value="">4</option>
                                    <option value="">5</option>
                                    <option value="">6</option>
                                    <option value="">7</option>
                                    <option value="">8</option>
                                    <option value="">9</option>
                                    <option value="">10+</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="children" class="font-weight-bold text-black">Niños</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                <select name="" id="children" class="form-control">
                                    <option value="">1</option>
                                    <option value="">2</option>
                                    <option value="">3</option>
                                    <option value="">4</option>
                                    <option value="">5</option>
                                    <option value="">6</option>
                                    <option value="">7</option>
                                    <option value="">8</option>
                                    <option value="">9</option>
                                    <option value="">10+</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <div class="row mb-4">
                        <div class="col-md-12 form-group">
                            <label class="text-black font-weight-bold" for="message">Nota</label>
                            <textarea name="message" id="message" class="form-control " cols="30" rows="8"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <a href="https://wa.me/527661151203" class="btn btn-primary text-white py-2 mr-3">RESERVAR AHORA</a>
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-md-5" data-aos="fade-up" data-aos-delay="200">
                <div class="row">
                    <div class="col-md-10 ml-auto contact-info">
                        <p><span class="d-block">Dirección:</span> <span> Calle Juan de la Barrera, <br> Colonia Emiliano Zapata CP: 93570</span></p>
                        <p><span class="d-block">Teléfono:</span> <span> 782 214 1920 <br> 766 115 1203 </span></p>
                        <p><span class="d-block">Correo Electronico:</span> <span> casasviamar@gmail.com</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include_once 'views/template/footer-principal.php';
?>
</body>

</html>