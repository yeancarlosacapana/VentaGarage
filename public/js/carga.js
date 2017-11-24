$(window).load(function(){
    
     $(function() {
      $('#file-input').change(function(e) {
          addImage(e); 
         });
    
         function addImage(e){
          var file = e.target.files[0],
          imageType = /image.*/;
        
          if (!file.type.match(imageType))
           return;
          var reader = new FileReader();
          reader.onload = fileOnload;
          reader.readAsDataURL(file);
         }
      
         function fileOnload(e) {
          var result=e.target.result;
          $('#img-principal').attr("src",result);
         }
        });
      });
      $(window).load(function(){
        
         $(function() {
          $('#file-input1').change(function(e) {
              addImage(e); 
             });
        
             function addImage(e){
              var file = e.target.files[0],
              imageType = /image.*/;
            
              if (!file.type.match(imageType))
               return;
              var reader = new FileReader();
              reader.onload = fileOnload;
              reader.readAsDataURL(file);
             }
          
             function fileOnload(e) {
              var result=e.target.result;
              $('#img-secundaria1').attr("src",result);
             }
            });
          });
          $(window).load(function(){
            
             $(function() {
              $('#file-input2').change(function(e) {
                  addImage(e); 
                 });
            
                 function addImage(e){
                  var file = e.target.files[0],
                  imageType = /image.*/;
                
                  if (!file.type.match(imageType))
                   return;
                  var reader = new FileReader();
                  reader.onload = fileOnload;
                  reader.readAsDataURL(file);
                 }
              
                 function fileOnload(e) {
                  var result=e.target.result;
                  $('#img-secundaria2').attr("src",result);
                 }
                });
              });


      