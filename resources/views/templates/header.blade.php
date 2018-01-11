
<div class="header col-lg-l2">
            <!-- <div class="container"> -->
    <div class="contenedor-logos col-lg-6">
        <div class="logo col-lg-6">
            <a href="{{url('/')}}"><img src="/img/logo.svg" class="img-logo img-responsive"></a>
        </div>
        <div class="sub-logo col-lg-3">
            <img src="/img/garage.svg" class="img-logo img-responsive">
        </div>
    </div>
    <div class="busqueda col-lg-4 ">
        <form  action="{{url('busqueda')}}" method="POST">
            <input type="hidden" value = "{{ csrf_token() }}" name="_token">
            <div class="input-group input-group-md">   
                <input type="text" class="form-control" placeholder="Busqueda de Producto" name="product_name" required>
                <div class="input-group-btn">
                    <button class="btn  btn-buscar" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </form>
    </div>                
    <div class="registro-login col-lg-2">
        <a href="#" class="registrate">Registrate</a>
        <a href="#" class="ingresa">Ingresa</a>
        <div class="user-icon" data-toggle="modal" data-target="#registrar-login"></div>
    </div>
</div>