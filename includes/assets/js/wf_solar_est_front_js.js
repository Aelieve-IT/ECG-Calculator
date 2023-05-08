jQuery(document).ready(function(){
    jQuery("#elementor-tab-title-1742").trigger("click");
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(drawBasic);
});

jQuery(document).ready(function($){
    $("body").prepend('<div class="added_print_div1" style="display:none;"><img src="https://staging.ecgsolar.com/wp-content/uploads/OrangelogoPNg-1.png" width="100px" height="50px"><h3 class="print_call">Call Now: (319) 326-0763</h3><h3 class="print_email">Email: info@ecgsolar.stagesite.dev</h3></div>');
    
}) 
jQuery(document).ready(function($){
      function print_quote() {
        $(document).on('click','.print_button_w',function(e){
        $('.elementor-tab-content').each(function(){
            $(this).css('display','block');
        })
        window.print();
        $('.elementor-tab-content').each(function(){
            $(this).css('display','none');
        })
    })
 }


 // use setTimeout() to execute
 setTimeout(print_quote, 1000)
})

var apikey = 'AIzaSyDE19uW2_YIeuZRC3IYFl1cMZ4QYSrqkbE';
        var script = document.createElement('script');
        if(typeof apikey!='undefined'){
        var af_key=apikey;
        script.src = 'https://maps.googleapis.com/maps/api/js?key='+af_key+'&libraries=places,geometry&callback=initMap';
        }
        else{
            console.log("not working");
        script.src ='https://maps.googleapis.com/maps/api/js?libraries=places,geometry';
        }
        script.async = true;
        document.head.appendChild(script);
        var zoom = 50;
        var lat = 33.7203149;
        var long = 73.0777705;
        window.initMap = function() {
            
            if(jQuery('#wf_est_googleMap').length){
                     const map = new google.maps.Map(document.getElementById("wf_est_googleMap"), {
            zoom: zoom,
            center: { lat: lat, lng: long },
            mapTypeId: 'satellite',


        });
        st_m_img='https://wolfiz.org/realityna/wp-content/uploads/2022/06/20220527_134419.jpg';
          var icon = {
                      url: st_m_img, // url
                      scaledSize: new google.maps.Size(60, 60), // scaled size
                      origin: new google.maps.Point(0,0), // origin
                      anchor: new google.maps.Point(0, 0) // anchor
                      };
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, long),
            map: map,
            draggable: true,
            animation: google.maps.Animation.BOUNCE,
        });
        google.maps.event.addListener(marker, 'dragend', function(evt) {
             var geocoder = new google.maps.Geocoder();
             numlats=parseFloat(evt.latLng.lat().toFixed(5));
             numlongs=parseFloat(evt.latLng.lng().toFixed(5));
             let latlng = { lat:numlats , lng:numlongs  };
            geocoder.geocode({'location': latlng}, function(results, status) {
                // alert(results[0].formatted_address);
            }
          );
            });   
            }
            if(window.location.href.indexOf('solar-calculator-') > -1){
                console.log("no input");
            }else{
                    var options = {componentRestrictions: { country: "us" },};
        const input = document.getElementById('form-field-input_mask');
        var autocomplete = new google.maps.places.Autocomplete(input,options);
            }
    
  }
function showMap(latitude , longitude) {
  const map = new google.maps.Map(document.getElementById("wf_est_googleMap"), {
    zoom: 20,
    center: { lat: latitude, lng: longitude },
    mapTypeId: 'satellite',
  });
  
  marker = new google.maps.Marker({
            position: new google.maps.LatLng(latitude, longitude),
            map: map,
            draggable: true,
            animation: google.maps.Animation.BOUNCE,
        });
        
        jQuery("input[name=hidden_latitude]").val(latitude);
            jQuery("input[name=hidden_longitude]").val(longitude);
        google.maps.event.addListener(marker, 'dragend', function(evt) {
             var geocoder = new google.maps.Geocoder();
             numlats=parseFloat(evt.latLng.lat().toFixed(5));
             numlongs=parseFloat(evt.latLng.lng().toFixed(5));
             let latlng = { lat:numlats , lng:numlongs  };
            geocoder.geocode({'location': latlng}, function(results, status) {
                // alert(results[0].formatted_address);
            }
          );
            var newlat = parseFloat(evt.latLng.lat().toFixed(5));
            var newlog = parseFloat(evt.latLng.lng().toFixed(5));
            // console.log(newlat) ;
            // console.log(newlog);
            $("input[name=hidden_latitude]").val(newlat);
            $("input[name=hidden_longitude]").val(newlog);
            // window.initMap=showMapLast(newlat,newlog);
            var geocoder = new google.maps.Geocoder();
             let latlngupdate = { lat:newlat , lng:newlog  };
            geocoder.geocode({'location': latlngupdate}, function(results, status) {
                var dragAddress = results[0].formatted_address;
                $('input[name=hidden_darg_address]').val(dragAddress);
                 
            }
          );
            });
}
function initMap(address) {
         var  addressType;
         var state;
         var address1= address;
         var redirectUrl;
         //console.log(address1);
       var geocoder = new google.maps.Geocoder();
            
            geocoder.geocode( { 'address': address1}, function(results, status) {
                
                if('ZERO_RESULTS'!=status){
                //console.log(status);
                
                var length=results[0].address_components.length;
                for (let i = 0; i < length; ++i) {
                     if(results[0].address_components[i].types[0] == "administrative_area_level_1"){
                      state =  results[0].address_components[i].short_name;
                    }
                }
                for (let i = 0; i < length; ++i) {
                    //console.log(results[0].address_components[i].types);
                    if(results[0].address_components[i].types == "postal_code"){
                      addressType =  results[0].address_components[i].long_name;
                        
                    }
                    //console.log(addressType);
                    let baseUrl  = solar_est_php_vars.base_url;
                   redirectUrl = baseUrl + "/solar-calculator/?zipcode=" + addressType + "&address="+ address1+ "&state=" +state;
                  
                }
                if(state=='IL'){
                    // console.log('matched');
                }
                if(typeof addressType === 'undefined'){
                    $('.main_error').html("Please Enter Your Complete Address.");
                    setTimeout(function(){
                     $(".main_error").html('');
                    }, 5000);
                }
                // else if(state != 'IA' && state != 'IL' && state != 'WI' && state != 'MN'  ){
                //     $('.main_error').html("Currently we are only serving Iowa, Illinois, Wisconsin and Minnesota.");
                //     setTimeout(function(){
                //      $(".main_error").html('');
                //     }, 5000);
                // }
                else{
                      window.location.href=redirectUrl;
                }
            }else{
                $('.main_error').html("Please Enter Your Complete Address.");
                setTimeout(function(){
                     $(".main_error").html('');
                    }, 5000);
                return;
            }
            });
}
jQuery(document).ready(function($){
    $(document).on('click','#get_started',function(event){
        event.preventDefault();
        var address = $("#form-field-input_mask").val();
        var  addressType;
         var state;
         var address1= address;
         var redirectUrl;
         //console.log(address1);
       var geocoder = new google.maps.Geocoder();
            
            geocoder.geocode( { 'address': address1}, function(results, status) {
                
                if('ZERO_RESULTS'!=status){
                //console.log(status);
                
                var length=results[0].address_components.length;
                for (let i = 0; i < length; ++i) {
                     if(results[0].address_components[i].types[0] == "administrative_area_level_1"){
                      state =  results[0].address_components[i].short_name;
                    }
                }
                for (let i = 0; i < length; ++i) {
                    //console.log(results[0].address_components[i].types);
                    if(results[0].address_components[i].types == "postal_code"){
                      addressType =  results[0].address_components[i].long_name;
                        
                    }
                    //console.log(addressType);
                    let baseUrl  = solar_est_php_vars.base_url;
                   redirectUrl = baseUrl + "/solar-calculator/?zipcode=" + addressType + "&address="+ address1+ "&state=" +state;
                  
                }
                if(typeof addressType === 'undefined'){
                    $('.main_error').html("Please Enter Your Complete Address.");
                    setTimeout(function(){
                     $(".main_error").html('');
                    }, 5000);
                }
                // else if(state != 'IA' && state != 'IL' && state != 'WI' && state != 'MN' ){
                //     $('.main_error').html("Currently we are only serving Iowa, Illinois, Wisconsin and Minnesota.");
                //     setTimeout(function(){
                //      $(".main_error").html('');
                //     }, 5000);
                // }
                else{
                      window.location.href=redirectUrl;
                }
            }else{
                $('.main_error').html("Please Enter Your Complete Address.");
                setTimeout(function(){
                     $(".main_error").html('');
                    }, 5000);
                return;
            }
            });
        });
})
function GetParameterValues(param) {  
    var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');  
    for (var i = 0; i < url.length; i++) {  
        var urlparam = url[i].split('=');  
        if (urlparam[0] == param) {  
            return urlparam[1];  
        }  
}
}
 function drawTaxCredit() {
          if(typeof(tax_credit) !='undefined'){
        var data = new google.visualization.arrayToDataTable(tax_credit);
        }

        var options = {
          title: '',
          legend: { position: 'none' },
          chart: { title: '',
                   subtitle: '' },
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: ''} // Top x-axis.
            }
          },
          bar: { groupWidth: "5%" }
        };
        var tax_credit_id = jQuery('#top_x_div').attr('id');
        if(typeof(tax_credit_id) !='undefined'){
        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
        }
      }
        //25 year cash flow chart
function drawBasic() {
    if(typeof(cash_flow) !='undefined'){
      var data = new google.visualization.DataTable();
        data.addColumn('number', 'X');
        data.addColumn('number', 'Savings');
        data.addRows([]);
        cash_flow.forEach(element => 
                          
                         data.addRows([
            [element[0],element[1]]
        ])
                         );
      }
    // console.log(cash_flow);
    // console.log(data);

    var options = {
        hAxis: {
          title: 'Years'
        },
        vAxis: {
          title: 'Savings'
        },
        backgroundColor: 'white',
        width: 980,
        height:400,
        legend: {  position: 'top', alignment: 'end' }
      };
      var year_flow_id = jQuery('#chart_div').attr('id');
      if(typeof(year_flow_id) !='undefined'){
    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    chart.draw(data, options);
      }
}
   jQuery(document).ready(function($){
   if(window.location.href.indexOf('solar-calculator/') > -1){
            var encode_address=GetParameterValues('address');
            var decode = unescape(encode_address);
            $("input[name=hidden_address]").val(decode);
            var autocompletelast = $("input[name=hidden_address]").val();
            $(".address_below_map").html(autocompletelast);
            var addresslast= autocompletelast;
            function load_map_after() {
            var geocoder = new google.maps.Geocoder();
            
              geocoder.geocode( { 'address': addresslast}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();
                window.initMap=showMap(latitude,longitude);
              } 
              
            });
            }


             // use setTimeout() to execute
             setTimeout(load_map_after, 1000)

        }
 })
if(window.location.href.indexOf('your-final-solar') > -1){
window.addEventListener('load',function(){
    var latitude = parseFloat(GetParameterValues('latitude')); 
    var longitude  = parseFloat(GetParameterValues('longitude')); 
             var geocoder = new google.maps.Geocoder();
             let latlng = { lat:latitude , lng:longitude  };
            geocoder.geocode({'location': latlng}, function(results, status) {
                var decodeAddress = results[0].formatted_address;
                 jQuery('.estimate').html(decodeAddress);
                 
            }
          );
    window.initMap=showMapLast(latitude,longitude);
    });
}
function showMapLast(latitude , longitude) {
  const map = new google.maps.Map(document.getElementById("wf_est_googleMap_last"), {
    zoom: 17,
    center: { lat: latitude, lng: longitude },
    mapTypeId: 'satellite',
  });
  
}

jQuery(document).ready(function($){
    
    $(".range").before("<span class='index'> $50 </span>");
    $(".range").after("<span class='index_2'> $650 </span>");
    
    $(".range_shade").before("<span class='index'> 0% </span>");
    $(".range_shade").after("<span class='index_2'> 70% </span>");
    
    $(".range_offset").before("<span class='index'> 10% </span>");
    $(".range_offset").after("<span class='index_2'> 100% </span>");
    
    $(document).on('click','.ff-el-form-check-radio',function(){
        if($(this).attr('name')!='input_radio_contact'){
        $(this).closest('div.fluentform-step').find('button.ff-btn-next').trigger( "click" );
            
        }
    })
    jQuery('input[name=input_radio_contact]').on('click', function() {
        jQuery(this).closest('div.contact_radio').find('label.ff-el-form-check-label').removeAttr('style');
        jQuery(this).closest('label.ff-el-form-check-label').attr('style',"background-color: rgba(255, 109, 0, 1) !important; border-color: rgba(255, 109, 0, 1) !important");
    
    })
    
    jQuery('.btn_yes').on('click', function() {
        jQuery('.btn_yes').removeAttr('style');
        jQuery('.btn_yes').css({'background-color': '#ff6d00'});
        jQuery('.btn_yes').css('color','white');
        jQuery('.btn_no').css({'background-color': '#fff'});
        jQuery('.btn_no').css({'color' : 'black' });
    
    })
    jQuery('.btn_no').on('click', function() {
        jQuery('.btn_yes').css({'background-color': '#fff'});
        jQuery('.btn_yes').css({'color' : 'black'});
        jQuery('.btn_no').css({'background-color': '#ff6d00'});
        jQuery('.btn_no').css({'color' : 'white'});
    
    })
    
    $(document).on('click','.radio_close',function(){
        $('input[name=hidden_utility_name]').val($(this).val());
        $(this).closest('div.fluentform-step').find('button.ff-btn-next').trigger( "click" );
    })
    $('.monthly_kwh_container ').hide();
    $('.electric_bill_con ').show();
    $('.monthly_kwh').val("NULL");
    $(document).on('click','.btn_yes',function(){
        $(".monthly_kwh").val('');
        $('.monthly_kwh_container ').show();
        $('.electric_bill_con ').hide();
    })
    $(document).on('click','.btn_no',function(){
        $('.monthly_kwh').val("NULL");
        $('.electric_bill_con ').show();
        $('.monthly_kwh_container ').hide();
    })
})

    window.addEventListener('load',function(){
    jQuery('.iti__selected-flag').css('pointer-events','none');
    jQuery('.ff-step-body div.fluentform-step:nth-child(1)').find('button.ff-btn-next').css('display','none');
    jQuery('.ff-step-body div.fluentform-step:nth-child(5)').find('button.ff-btn-next').css('display','none');
    jQuery('.ff-step-body div.fluentform-step:nth-child(6)').find('button.ff-btn-next').css('display','none');
    jQuery('.ff-step-body div.fluentform-step:nth-child(7)').find('button.ff-btn-next').css('display','none');
    jQuery('.ff-step-body div.fluentform-step:nth-child(8)').find('button.ff-btn-next').css('display','none');
//  jQuery('.ff-step-body div.fluentform-step:nth-child(10)').find('button.ff-btn-next').css('display','none');
//  jQuery('.ff-step-body div.fluentform-step:nth-child(11)').find('button.ff-btn-next').css('display','none');
    jQuery('.ff-step-body div.fluentform-step:nth-child(12)').find('button.ff-btn-next').css('display','none');
//  jQuery('.ff-step-body div.fluentform-step:nth-child(13)').find('button.ff-btn-next').css('display','none');
    jQuery('.fluentform .iti__selected-flag').html('+1');
//  jQuery('.contact_radio').find('label').css({'background-color': '#1959A1', 'padding' :'17px 36px 18px 10px' , 'font-size': '12px', 'font-weight': '800' , 'border-style' : 'solid' , 'border-color': '#1959A1', 'border-width': '2px', 'border-radius': '3px', 'margin-left': '8px' ,'color': 'white' });
    jQuery('.contact_radio').find('div.ff-el-input--content').css({'display': 'flex', 'justify-content': 'center'});
    jQuery('.contact_radio').find('div.ff-el-form-check').css('margin-left', '20% !important');
    
    //adding unique class
    jQuery('.ff-step-body div.fluentform-step:nth-child(1)').addClass("step1");
    jQuery('.ff-step-body div.fluentform-step:nth-child(2)').addClass("step2");
    jQuery('.ff-step-body div.fluentform-step:nth-child(3)').addClass("step3");
    jQuery('.ff-step-body div.fluentform-step:nth-child(4)').addClass("step4");
    jQuery('.ff-step-body div.fluentform-step:nth-child(5)').addClass("step5");
    jQuery('.ff-step-body div.fluentform-step:nth-child(6)').addClass("step6");
    jQuery('.ff-step-body div.fluentform-step:nth-child(7)').addClass("step7");
    jQuery('.ff-step-body div.fluentform-step:nth-child(8)').addClass("step8");
    jQuery('.ff-step-body div.fluentform-step:nth-child(9)').addClass("step9");
    jQuery('.ff-step-body div.fluentform-step:nth-child(10)').addClass("step10");
    jQuery('.ff-step-body div.fluentform-step:nth-child(11)').addClass("step11");
    jQuery('.ff-step-body div.fluentform-step:nth-child(12)').addClass("step12");
    jQuery('.ff-step-body div.fluentform-step:nth-child(13)').addClass("step13");
    jQuery('.ff-step-body div.fluentform-step:nth-child(14)').addClass("step14");
    jQuery('.ff-step-body div.fluentform-step:nth-child(15)').addClass("step15");
    jQuery('.step8').find('#ff_5_names_last_name_').addClass("name_last");
    jQuery('.step8').find('.ff-el-input--content').addClass("a");
    jQuery('.step15').find('.ff_list_3col').find('.ff-el-input--content').addClass("time_checkboxes");
    
    })
    jQuery(document).on('click','.ff-btn-prev',function(){
//     jQuery('.ff-step-body div.fluentform-step:nth-child(10)').find('button.ff-btn-next').css('display','none');
//     jQuery('.ff-step-body div.fluentform-step:nth-child(11)').find('button.ff-btn-next').css('display','none');
    })
jQuery(document).ready(function($){
    var state= $("input[name=hidden_state]").val();
    // console.log(state);
       let ajaxurl      = solar_est_php_vars.admin_url;
        jQuery.ajax(
        {
            url:ajaxurl,
            type: 'POST',
            data:{
                action: 'wf_get_state_provider_data',
                state: state,

                },
            success: function (data) 
            {
                $(".utility_radio_1").html(data);
            }
        }
      );    
    //Redirect to main page from first step
    $(".back_zip").click(function(){
        let baseUrl  = solar_est_php_vars.base_url;
        redirectUrl = baseUrl;
        window.location.href=redirectUrl;
    });
    
    // Bill Cost Slider
    var bill_price = $( ".range" ).val();
    $( ".bill_price" ).html('<p style="color: #2eaae1; font-size: 1.75em;"> $'+bill_price+'</p>');
    
    $(".range").change(function(){
      var bill_price = $( ".range" ).val();
      
      $( ".bill_price" ).html('<p style="color: #2eaae1; font-size: 1.75em;"> $'+bill_price+'</p>');
    });
    
     var roof_shade_val = $( ".range_shade" ).val();
    $( ".roof_shade_val" ).html('<p style="color: #2eaae1; font-size: 1.75em;"> '+roof_shade_val+'%</p>');
    
    $(".range_shade").change(function(){
      var roof_shade_val = $( ".range_shade" ).val();
      
      $( ".roof_shade_val" ).html('<p style="color: #2eaae1; font-size: 1.75em;"> '+roof_shade_val+'%</p>');
    });
    
     var offset_val = $( ".range_offset" ).val();
    $( ".offset_val" ).html('<p style="color: #2eaae1; font-size: 1.75em;"> '+offset_val+'%</p>');
    
    $(".range_offset").change(function(){
      var offset_val = $( ".range_offset" ).val();
      
      $( ".offset_val" ).html('<p style="color: #2eaae1; font-size: 1.75em;"> '+offset_val+'%</p>');
    });
    
    jQuery(document).on('click','#elementor-tab-title-1741',function(){
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawTaxCredit);
    });
    
    jQuery(document).on('click','#elementor-tab-title-1742',function(){
        google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(drawBasic);
    });
})