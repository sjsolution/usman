(function($) {


    var days = ['sun','mon','tue','wed','thus','fri','sat'];

    for( i = 0 ; i < days.length ; i++){

      if($('#'+days[i]+'_start_time').length &&  $('#'+days[i]+'_end_time').length){
        $('#'+days[i]+'_start_time').timepicker();
        $('#'+days[i]+'_end_time').timepicker();
      }

      if($('#'+days[i]+'_break_from').length && $('#'+days[i]+'_break_to').length){
        $('#'+days[i]+'_break_from').timepicker();
        $('#'+days[i]+'_break_to').timepicker();
      }

    
    }

  


    
    $("#sun_status").on('change', function() {
     
        if ($(this).is(':checked')) {
          

          document.getElementById("sun_row").style.background = 'white';
          document.getElementById("sun_break_from_row").style.background = 'white';
          document.getElementById("sun_break_to_row").style.background = 'white';
          $("#sun_start_time").removeAttr("disabled");
          $("#sun_end_time").removeAttr("disabled");

          $("#sun_break_from").removeAttr("disabled");
          $("#sun_break_to").removeAttr("disabled");

          $("#sun_break_status").prop("checked" , false);
          document.getElementById('sun_break_status').removeAttribute("disabled");


        }else {
         
          document.getElementById("sun_row").style.background = 'lightgray';
          document.getElementById("sun_break_from_row").style.background = 'lightgray';
          document.getElementById("sun_break_to_row").style.background = 'lightgray';
          $('#sun_start_time').attr("disabled", "disabled");
          $('#sun_end_time').attr("disabled", "disabled");

          $('#sun_break_from').attr("disabled", "disabled");
          $('#sun_break_to').attr("disabled", "disabled");

          $("#sun_break_status").prop("checked" , false);
          document.getElementById('sun_break_status').setAttribute("disabled","true");

        }
    });

    $("#sun_break_status").on('change', function() {
     
      if ($(this).is(':checked')) {
        document.getElementById("sun_break_from_row").style.background = 'white';
        document.getElementById("sun_break_to_row").style.background = 'white';


        $("#sun_break_from").removeAttr("disabled");
        $("#sun_break_to").removeAttr("disabled");
        

      }else {
        document.getElementById("sun_break_from_row").style.background = 'lightgray';
        document.getElementById("sun_break_to_row").style.background = 'lightgray';
      
        $('#sun_break_from').attr("disabled", "disabled");
        $('#sun_break_to').attr("disabled", "disabled");
      }
  });
  

 

 

    $("#mon_status").on('change', function() {
        if ($(this).is(':checked')) {
          document.getElementById("mon_row").style.background = 'white';
          document.getElementById("mon_break_from_row").style.background = 'white';
          document.getElementById("mon_break_to_row").style.background = 'white';
          $("#mon_start_time").removeAttr("disabled");
          $("#mon_end_time").removeAttr("disabled");

          $("#mon_break_from").removeAttr("disabled");
          $("#mon_break_to").removeAttr("disabled");

          $("#mon_break_status").prop("checked" , false);
          document.getElementById('mon_break_status').removeAttribute("disabled");


        }else {
          document.getElementById("mon_row").style.background = 'lightgray';
          document.getElementById("mon_break_from_row").style.background = 'lightgray';
          document.getElementById("mon_break_to_row").style.background = 'lightgray';
          $('#mon_start_time').attr("disabled", "disabled");
          $('#mon_end_time').attr("disabled", "disabled");

          $('#mon_break_from').attr("disabled", "disabled");
          $('#mon_break_to').attr("disabled", "disabled");

          $("#mon_break_status").prop("checked" , false);
          document.getElementById('mon_break_status').setAttribute("disabled","true");


        }
    });

    $("#mon_break_status").on('change', function() {
     
      if ($(this).is(':checked')) {
        document.getElementById("mon_break_from_row").style.background = 'white';
        document.getElementById("mon_break_to_row").style.background = 'white';


        $("#mon_break_from").removeAttr("disabled");
        $("#mon_break_to").removeAttr("disabled");

      }else {
        document.getElementById("mon_break_from_row").style.background = 'lightgray';
        document.getElementById("mon_break_to_row").style.background = 'lightgray';
      
        $('#mon_break_from').attr("disabled", "disabled");
        $('#mon_break_to').attr("disabled", "disabled");
      }
  });

    $("#tue_status").on('change', function() {
        if ($(this).is(':checked')) {
          document.getElementById("tue_row").style.background = 'white';
          document.getElementById("tue_break_from_row").style.background = 'white';
          document.getElementById("tue_break_to_row").style.background = 'white';
          $("#tue_start_time").removeAttr("disabled");
          $("#tue_end_time").removeAttr("disabled");

          $("#tue_break_from").removeAttr("disabled");
          $("#tue_break_to").removeAttr("disabled");

          $("#tue_break_status").prop("checked" , false);
          document.getElementById('tue_break_status').removeAttribute("disabled");


        }else {
          document.getElementById("tue_row").style.background = 'lightgray';
          document.getElementById("tue_break_from_row").style.background = 'lightgray';
          document.getElementById("tue_break_to_row").style.background = 'lightgray';
          $('#tue_start_time').attr("disabled", "disabled");
          $('#tue_end_time').attr("disabled", "disabled");

          $('#tue_break_from').attr("disabled", "disabled");
          $('#tue_break_to').attr("disabled", "disabled");

          $("#tue_break_status").prop("checked" , false);
          document.getElementById('tue_break_status').setAttribute("disabled","true");

        }
    });


    $("#tue_break_status").on('change', function() {
     
      if ($(this).is(':checked')) {
        document.getElementById("tue_break_from_row").style.background = 'white';
        document.getElementById("tue_break_to_row").style.background = 'white';


        $("#tue_break_from").removeAttr("disabled");
        $("#tue_break_to").removeAttr("disabled");

      }else {
        document.getElementById("tue_break_from_row").style.background = 'lightgray';
        document.getElementById("tue_break_to_row").style.background = 'lightgray';
      
        $('#tue_break_from').attr("disabled", "disabled");
        $('#tue_break_to').attr("disabled", "disabled");
      }
  });

    $("#wed_status").on('change', function() {
        if ($(this).is(':checked')) {
          document.getElementById("wed_row").style.background = 'white';
          document.getElementById("wed_break_from_row").style.background = 'white';
          document.getElementById("wed_break_to_row").style.background = 'white';
          $("#wed_start_time").removeAttr("disabled");
          $("#wed_end_time").removeAttr("disabled");

          $('#wed_break_from').removeAttr("disabled", "disabled");
          $('#wed_break_to').removeAttr("disabled", "disabled");

          $("#wed_break_status").prop("checked" , false);
          document.getElementById('wed_break_status').removeAttribute("disabled");



        }else {
          document.getElementById("wed_row").style.background = 'lightgray';
          document.getElementById("wed_break_from_row").style.background = 'lightgray';
          document.getElementById("wed_break_to_row").style.background = 'lightgray';
          $('#wed_start_time').attr("disabled", "disabled");
          $('#wed_end_time').attr("disabled", "disabled");

          $('#wed_break_from').attr("disabled", "disabled");
          $('#wed_break_to').attr("disabled", "disabled");

          $("#wed_break_status").prop("checked" , false);
          document.getElementById('wed_break_status').setAttribute("disabled","true");

        }
    });


    $("#wed_break_status").on('change', function() {
     
      if ($(this).is(':checked')) {
        document.getElementById("wed_break_from_row").style.background = 'white';
        document.getElementById("wed_break_to_row").style.background = 'white';


        $("#wed_break_from").removeAttr("disabled");
        $("#wed_break_to").removeAttr("disabled");

      }else {
        document.getElementById("wed_break_from_row").style.background = 'lightgray';
        document.getElementById("wed_break_to_row").style.background = 'lightgray';
      
        $('#wed_break_from').attr("disabled", "disabled");
        $('#wed_break_to').attr("disabled", "disabled");
      }
  });

    $("#thus_status").on('change', function() {
        if ($(this).is(':checked')) {
          document.getElementById("thus_row").style.background = 'white';
          document.getElementById("thus_break_from_row").style.background = 'white';
          document.getElementById("thus_break_to_row").style.background = 'white';
          $("#thus_start_time").removeAttr("disabled");
          $("#thus_end_time").removeAttr("disabled");

          $('#thus_break_from').removeAttr("disabled", "disabled");
          $('#thus_break_to').removeAttr("disabled", "disabled");

          $("#thus_break_status").prop("checked" , false);
          document.getElementById('thus_break_status').removeAttribute("disabled");


        }else {
          document.getElementById("thus_row").style.background = 'lightgray';
          document.getElementById("thus_break_from_row").style.background = 'lightgray';
          document.getElementById("thus_break_to_row").style.background = 'lightgray';
          $('#thus_start_time').attr("disabled", "disabled");
          $('#thus_end_time').attr("disabled", "disabled");

          $('#thus_break_from').attr("disabled", "disabled");
          $('#thus_break_to').attr("disabled", "disabled");

          $("#thus_break_status").prop("checked" , false);
          document.getElementById('thus_break_status').setAttribute("disabled","true");

        }
    });

    $("#thus_break_status").on('change', function() {
     
      if ($(this).is(':checked')) {
        document.getElementById("thus_break_from_row").style.background = 'white';
        document.getElementById("thus_break_to_row").style.background = 'white';


        $("#thus_break_from").removeAttr("disabled");
        $("#thus_break_to").removeAttr("disabled");

      }else {
        document.getElementById("thus_break_from_row").style.background = 'lightgray';
        document.getElementById("thus_break_to_row").style.background = 'lightgray';
      
        $('#thus_break_from').attr("disabled", "disabled");
        $('#thus_break_to').attr("disabled", "disabled");
      }
  });

    $("#fri_status").on('change', function() {
        if ($(this).is(':checked')) {
          document.getElementById("fri_row").style.background = 'white';
          document.getElementById("fri_break_from_row").style.background = 'white';
          document.getElementById("fri_break_to_row").style.background = 'white';
          $("#fri_start_time").removeAttr("disabled");
          $("#fri_end_time").removeAttr("disabled");

          $("#fri_break_from").removeAttr("disabled");
          $("#fri_break_to").removeAttr("disabled");

          $("#fri_break_status").prop("checked" , false);
          document.getElementById('fri_break_status').removeAttribute("disabled");


        }else {
          document.getElementById("fri_row").style.background = 'lightgray';
          document.getElementById("fri_break_from_row").style.background = 'lightgray';
          document.getElementById("fri_break_to_row").style.background = 'lightgray';
          $('#fri_start_time').attr("disabled", "disabled");
          $('#fri_end_time').attr("disabled", "disabled");

          $('#fri_break_from').attr("disabled", "disabled");
          $('#fri_break_to').attr("disabled", "disabled");

          $("#fri_break_status").prop("checked" , false);
          document.getElementById('fri_break_status').setAttribute("disabled","true");

        }
    });

    $("#fri_break_status").on('change', function() {
     
      if ($(this).is(':checked')) {
        document.getElementById("fri_break_from_row").style.background = 'white';
        document.getElementById("fri_break_to_row").style.background = 'white';


        $("#fri_break_from").removeAttr("disabled");
        $("#fri_break_to").removeAttr("disabled");

      }else {
        document.getElementById("fri_break_from_row").style.background = 'lightgray';
        document.getElementById("fri_break_to_row").style.background = 'lightgray';
      
        $('#fri_break_from').attr("disabled", "disabled");
        $('#fri_break_to').attr("disabled", "disabled");
      }
  });

    $("#sat_status").on('change', function() {
        if ($(this).is(':checked')) {
          document.getElementById("sat_row").style.background = 'white';
          document.getElementById("sat_break_from_row").style.background = 'white';
          document.getElementById("sat_break_to_row").style.background = 'white';

  
          $("#sat_start_time").removeAttr("disabled");
          $("#sat_end_time").removeAttr("disabled");

          $("#sat_break_from").removeAttr("disabled");
          $("#sat_break_to").removeAttr("disabled");

          $("#sat_break_status").prop("checked" , false);
          document.getElementById('sat_break_status').removeAttribute("disabled");



        }else {

          document.getElementById("sat_row").style.background = 'lightgray';
          document.getElementById("sat_break_from_row").style.background = 'lightgray';
          document.getElementById("sat_break_to_row").style.background = 'lightgray';
          $('#sat_start_time').attr("disabled", "disabled");
          $('#sat_end_time').attr("disabled", "disabled");

          $('#sat_break_from').attr("disabled", "disabled");
          $('#sat_break_to').attr("disabled", "disabled");

          $("#sat_break_status").prop("checked" , false);
          document.getElementById('sat_break_status').setAttribute("disabled","true");


        }
    });

    $("#sat_break_status").on('change', function() {
     
      if ($(this).is(':checked') && $('#sat_status').is(':checked') ) {
        document.getElementById("sat_break_from_row").style.background = 'white';
        document.getElementById("sat_break_to_row").style.background = 'white';


        $("#sat_break_from").removeAttr("disabled");
        $("#sat_break_to").removeAttr("disabled");

      }else {
        document.getElementById("sat_break_from_row").style.background = 'lightgray';
        document.getElementById("sat_break_to_row").style.background = 'lightgray';
      
        $('#sat_break_from').attr("disabled", "disabled");
        $('#sat_break_to').attr("disabled", "disabled");
      }
  });

})(jQuery);