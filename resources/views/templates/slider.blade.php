@extends('home')
@section('content')
    <div class="slider col-lg-12">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
            @foreach($slider as $Gslider)
            <li data-target="#myCarousel" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
            @endforeach
            </ol>       
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                                @foreach($slider as $Gslider)
                                <div class="item {{ $loop->first ? ' active' : '' }}">
                                    <img src="{{ $Gslider->url }}" class="img-responsive img-banner"/>
                                </div>
                                @endforeach
                            </div>
                          
                            <!-- Left and right controls -->
                            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                              <span class="glyphicon glyphicon-chevron-left"></span>
                              <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                              <span class="glyphicon glyphicon-chevron-right"></span>
                              <span class="sr-only">Next</span>
                            </a>
                          </div>
            </div>
@stop()