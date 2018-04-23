<?php

$form = new Form();
$form = $form->create()
		->url($this->url."settings/system?run=1")
		->addClass('js-submit-form form-insert form-settings-company')
		->method('post');

$form  	->field("location_address")
		->label($this->lang->translate('Address'))
		->type('textarea')
		->addClass('inputtext')
		->autocomplete("off")
		->attr('data-plugins', 'autosize')
		->value( !empty($this->system['location_address']) ? $this->system['location_address']:'');

$form->field("location_city")
		->label('จังหวัด')
		->addClass('inputtext')
		->autocomplete("off")
	    ->select( $this->city )
	    ->value( !empty($this->system['location_city']) ? $this->system['location_city']:'' );

$form->field("location_zip")
		->label('ไปรษณีย์')
	    ->addClass('inputtext')
		->value( !empty($this->system['location_zip']) ? $this->system['location_zip']:'' );


/* $form->field("location_gps")
		->label('พิกัด GPS (ลากแผนที่เพื่อปรับตำแหน่ง)')
		->text( '<div id="map-wrap" class="map-wrap">'.

			'<div id="map" style="height:350px;width:100%;background-color:grey"></div>'.

			'<div class="map-input">'.
				'<table><tr>'.
					'<td><input type="text" id="location_lat" name="location_lat" value="'.(!empty($this->company['location_lat']) ? $this->company['location_lat']:'').'" class="inputtext"></td>'.
					'<td><input type="text" id="location_lng" name="location_lng" value="'.(!empty($this->company['location_lng']) ? $this->company['location_lng']:'').'" class="inputtext"></td>'.
				'</tr></table>'.
			'</div>'.

		'</div>' ); */

$form  	->submit()
		->addClass("btn-submit btn btn-blue")
		->value($this->lang->translate('Save'));

echo $form->html();

?>
<script type="text/javascript">

var gMap = {

	init: function ( elem, options ) {

		var self = this;

		self.elem = elem;
		self.options = options;

		this.loadMap();
	},

	loadMap: function ( uluru ) {
    	var self = this;

    	if( !uluru ){


    		if( self.options.setCenter ){
    			uluru = self.options.setCenter;
    		}
    		else{
    			uluru = {lat: self.options.lat, lng: self.options.lng};
    		}
    	}

        var mapOptions = {
          	zoom: 10,
          	center: uluru,

          	navigationControl: false,
   			mapTypeControl: false,
    		scaleControl: false,

    		// draggable: false,

  			scrollwheel: false,
  			disableDoubleClickZoom: true,
        };

        self.map = new google.maps.Map(self.elem, mapOptions);

        self.marker = new google.maps.Marker({
          	position: uluru,
          	map: self.map,

          	draggable: true,
          // animation: google.maps.Animation.DROP,
        });


        self.marker.addListener('dragend', function( evt ) {
        	
        	$('#location_lat').val( evt.latLng.lat() ); // .toFixed(3)
        	$('#location_lng').val( evt.latLng.lng() ); // .toFixed(3)
        	// console.log( evt.latLng.lat().toFixed(3), evt.latLng.lng().toFixed(3) );
        });

        // self.getPosition();
    },

    setlatLng: function () {
    	var self = this;

    	// var marcador = self.marker.getPosition();
    	// console.log( marcador.latLng );
    }
}
	
	var latLng = {
		lat: parseFloat($('#location_lat').val()) || '',
		lng: parseFloat($('#location_lng').val()) || ''
	};
	var elem = document.getElementById('map');

	function initMap() { // initialize
		
		if( latLng.lat=='' || latLng.lng=='' ){


			navigator.geolocation.getCurrentPosition(function (p) {
				latLng = {
		    		lat: p.coords.latitude,
		    		lng: p.coords.longitude
		    	};
			});

			if( latLng.lat=='' || latLng.lng=='' ){

				var city = $('#location_city').val();
				var address = $('#location_city').find('option[value=' + city +  ']').text();

				if( address!='' ){
					var geocoder = new google.maps.Geocoder();

					geocoder.geocode( { 'address': address}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {

								gMap.init( elem, {
						    		setCenter: results[0].geometry.location
						    	});
							}
							else{
								console.log("No results found");
							}
						}
						else{
							console.log("Geocode was not successful for the following reason: " + status);
						}
					});

				}
				else{
					console.log( 'Error' );
				}

			}
			else{
				gMap.init( elem, latLng);
			}

		}
		else{
			gMap.init( elem, latLng);
		}

    }

    // google.maps.event.addDomListener(window, 'load', initMap);
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDt1-iJi7cO8S6S7Qtolxm9JnSi39sbnnc&callback=initMap"></script>