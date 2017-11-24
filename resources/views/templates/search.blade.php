@extends('home')
@section('link')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop 

@section('content')

<div class="container">
        <div class="col-lg-12">  
            <ol class="contenedor-etiqueta">
                <a href="#" class="enlace-etiqueta"><li class="etiqueta">Homepage</li></a>
                <a href="#" class="enlace-etiqueta"><li class="etiqueta">Venta de Gerage</li></a>
            </ol>
        </div>
        <div class="col-lg-3">
            <div class="filtro-salas">
                <h4 class="font-weight-bold">SALAS</h4>
                <a href="">Seccionales</a>
            </div>
            <div class="filtros">
                <h5>
                    <i class="fa fa-filter fa-4"></i>
                        FILTROS
                </h5>
                <h6>Precio</h6>
                <div id="slider-range"></div>
                <div class="contenedor-precio">
                    <input type="text" name="" id="amount" readonly min="240" max="10000" class="txt-filtrar">
                    <span class="glyphicon glyphicon-minus"></span>   
                    <input type="text" name="" id="amount1" readonly min="240" max="10000" class="txt-filtrar">
                </div>
                <button type="submit" class="btn-filtrar btn btn-block">Filtrar Precio</button>
            </div>
            <div class="fecha-post">
                <h6>Fecha de Post</h6>
                <div class="checkbox">
                    <label><input type="checkbox" value="">Hoy</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" value="">Esta semana</label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" value="">Ultimo mes</label>
                </div>
                <div class="checkbox">
                        <label><input type="checkbox" value="">Todos</label>
                    </div>
            </div>
        </div>
        <div class="col-lg-9">
                <div class="col-lg-6">
                    <form action="">
                        <div class="form-group desplazar-pagina">
                            <label for="porpaginas">Producto por página:</label>
                            <select class="form-control porpagina" id="porpaginas" data-size="4"> 
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 contenedor-ordenar text-right"> 
                    <label class="label-ordenar mr-1">Ordenar por</label>
                    <div class="col-lg-6 contenedor-dropdown dropdown pull-right select-title">                   
                        <button class="btn btn-dropdown dropdown-toggle" type="button" data-toggle="dropdown">
                            Popularidad
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </button>    
                        <ul class="dropdown-menu">
                            <li><a href="#">Precio: Menor - Mayor</a></li>
                            <li><a href="#">Precio: Mayor - Menor</a></li>
                            <li><a href="#">Lo mas reciente</a></li>
                            <li><a href="#">Titulo : A - Z</a></li>
                            <li><a href="#">Titulo : Z - A</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-12 contenedor-lista-producto">
                @foreach ($search as $Gsearch)
                    <div class="col-lg-4">
                            <div class="contenedor-product">
                                    <div class="contenedor-img-product">
                                        <img src="/img/prueba.jpg" alt="" class="img-responsive img-productos">
                                    </div>
                                    <div class="contenedor-texto">
                                        <p class="text-left">{{ $Gsearch->name }}</p>
                                    </div>
                                    <div class="contenedor-price-condicion">
                                        <span class="price">{{ round($Gsearch->price,2)}} </span>
                                        <span class="condicion">{{ ($Gsearch->condition =='new')? 'nuevo':'usado' }}</span>
                                    </div>
                                </div>
                    </div>
                     @endforeach
                     <div class="pagination">{{$search->links()}} </div> 
                </div>
                
        </div>          
      </div>
      
      @stop()
      @section('script')
      <script src="{{ asset('js/price.js') }}"></script>
      @stop