<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Dashboard</h3></div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Inicio</li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="newsletter-inner section-inner">
                        <div class="newsletter-header text-center is-revealing">
                            <h2 class="section-title mt-0">¡Te esperamos!</h2>
                            <p class="section-paragraph">Posterior al registro nosotros te contactaremos.</p>
                        </div>
                        <form action="" id="formRegistro">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <label for=""><span class="text-danger">*</span> Nombre completo</label>
                                    <input type="text" class="form-control form-group form-lg" placeholder="Ingrese nombre completo" name="nombre">
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <label for=""><span class="text-danger">*</span> Edad</label>
                                    <input type="number" min="10" class="form-control form-group form-lg" placeholder="Ingrese su edad" name="edad">
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <label for=""><span class="text-danger">*</span> Dirección</label>
                                    <input type="text" class="form-control form-group form-lg" placeholder="Ingrese su dirección" name="direccion">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <label for=""><span class="text-danger">*</span> Tel. celular</label>
                                    <input type="number" min="0" class="form-control form-group form-lg" placeholder="Ingrese un número de teléfono" name="telefono">
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <label for=""><span class="text-danger">*</span> Correo electrónico</label>
                                    <input type="email" class="form-control form-group form-lg" placeholder="Ingrese un correo electrónico" name="correo">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <label for=""><span class="text-danger">*</span> Eres universitario.</label>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-check">
                                        <label class="form-check-label" for="universitario1">
                                            <input class="form-check-input" type="radio" name="universitario" id="universitario1" value="1">
                                            Si
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-check">
                                        <label class="form-check-label" for="universitario2">
                                            <input class="form-check-input" type="radio" name="universitario" id="universitario2" value="2">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row hidden" id="esUniversitario">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                    <label for="">Especifique lo siguiente:</label>
                                    <div class="form-check">
                                        <label class="form-check-label" for="exampleRadios3">
                                            <input class="form-check-input" type="radio" name="radioUnivresidad" id="exampleRadios3" value="1" checked>
                                            Alumno
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="exampleRadios1">
                                            <input class="form-check-input" type="radio" name="radioUnivresidad" id="exampleRadios1" value="2" >
                                            Administrativo
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label" for="exampleRadios2">
                                            <input class="form-check-input" type="radio" name="radioUnivresidad" id="exampleRadios2" value="3">
                                            Docente
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12">
                                    <label for=""><span class="text-danger">*</span> Adscripción:</label>
                                    <select name="" class="form-control form-group form-lg select2" id="universidadOEscuela">
                                        <option value="">Seleccione una opción</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <label for="">Número de personas que lo acompañan</label> <small class="text-danger">¿En caso de ir con acompañantes seleccionar cuantos?</small>
                                    <select name="" id="numeroDePersonas" class="form-control form-group ">
                                        <option value="">Sin acompañantes</option>
                                        <option value="1">1 persona</option>
                                        <option value="2">2 personas</option>
                                        <option value="3">3 personas</option>
                                        <option value="4">4 personas</option>
                                        <option value="5">5 personas</option>
                                        <option value="6">6 personas</option>
                                        <option value="7">7 personas</option>
                                        <option value="8">8 personas</option>
                                        <option value="9">9 personas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="divCapturaPersonas"></div>
                            <button class="btn btn-uac btn-lg btn-block" id="registrar">Registrate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/modulos/dashboard/dashboard.js"></script>