$( function() {
    iPrecio1 = $("#amount1").val();
    iPrecio2 = $( "#amount" ).val();
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 5000,
      values: [ parseInt(iPrecio2), parseInt(iPrecio1) ],
      slide: function( event, ui ) {
        $( "#amount" ).val( ui.values[0]);
        $( "#amount1" ).val(ui.values[1]);
      }
    });
    // $( "#amount" ).val(  $( "#slider-range" ).slider( "values", 0 ) );
    // $( "#amount1" ).val(  $( "#slider-range" ).slider( "values", 1 ));
  } );

  $("#hoy").click(function(){
    if($(this).is(':checked')){
        $(this).val('hoy')
    }else{
        $(this).val('')
    }
    $("#filtrar_fecha").click()
});
$("#semana").click(function(){
    if($(this).is(':checked')){
        $(this).val('semana')
    }else{
        $(this).val('')
    }
    $("#filtrar_fecha").click()
});
$("#mes").click(function(){
    if($(this).is(':checked')){
        $(this).val('mes')
    }else{
        $(this).val('')
    }
    $("#filtrar_fecha").click()
});
$("#all").click(function(){
    if($(this).is(':checked')){
        $(this).val('all')
    }else{
        $(this).val('')
    }
    window.location.reload();
});