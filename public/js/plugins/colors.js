// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
	
	var Render = {
		init: function( options, elem ){
			var self = this;

			self.elem = elem;
			self.$elem = $( elem );

			self.options = $.extend( {}, $.fn.colors.options, options );

			self.placeholder();
			self.paint();

			self.display();
			self._selected();
		},
		
		initEvent: function(){
			var self = this;
			
			
			self.$ul.find('a').click(function(e){
				e.preventDefault();
				
				var $this = $(this),
					li = $this.parent();
				
				if( li.hasClass('active') ){
					
				}
				else{
					if( self.options.selected != ""){
						self.options.selected += ","+$this.attr('data-color');
					}
					else{
						self.options.selected += $this.attr('data-color');
					}				
					
					self._selected();
				
				}
				
			});
		},
		
		placeholder: function(){
			
			var self = this;

			if( !self.options.name ){
				self.options.name = $.trim(self.$elem.attr('name'));
			}
			
			if( self.$elem.val()!="" ){
				self.options.selected = self.$elem.val();
			}
			
			if( self.$elem.attr('max') ){
				self.options.max = self.$elem.attr('max')
			}
			
			if( self.$elem.attr('width') ){
				self.options.size[0] = self.$elem.attr('width')
			}
			
			if( self.$elem.attr('height') ){
				self.options.size[1] = self.$elem.attr('height')
			}


			self.colors = [];
			if( self.$elem.find('option').length ){

				$.each( self.$elem.find('option'), function(){

					var value = $(this).val().split(",");

					self.colors.push({
						code: value[0],
						border: !value[1]? value[0]:value[1],
						name: "" 
					});

				});
			}
			else{

				$.each( self._colors, function(key, obj){

					self.colors.push({
						code: obj.code,
						border: !obj.border? obj.code:obj.border,
						name: key 
					});

				});
			}
			
			// add style
            if ($('head style').hasClass('css-colors')==false) {
            	// add colors
				self.colors_CSS = "";

                $('head').append( self.CSS( self.colors_CSS ) );
            }
			
			// ul
			self.$ul = $('<ul>').addClass('color-nav');
			
			// 
			self.$input = $('<input>')
							.addClass('hiddenField')
							.attr({
								type: "hidden",
								name: self.options.name
							});
			
			// 
			self.$UI = $('<div>')
				.addClass('UIColors')
				.append( self.$input )
				.append( self.$ul );
		},
		
		_selected: function(){
			var self = this;
				
			var selected = self.options.selected.split(",");
			
			self.options.selected = "";
			var max = selected.length-self.options.max;
			$.each(selected, function(i, code){
			
				var el = self.$ul.find('a.color-'+code).parent();

				if( el.hasClass('active') && i<max && selected.length>self.options.max) {
					el.removeClass('active');
				}
				else if( el.length ){
					el.addClass('active');
					self.options.selected += self.options.selected
						? ","+code
						: code;
				}

			});
			
			// update input
			self.$input.val( self.options.selected );
		},

		paint: function(){

			var self = this;
			
			var colors = $.extend( {}, self.colors, self.options.colors );

			self.$li = $.map( colors, function( obj, key){
				
				if( self.options.selected == "" || !self.options.selected){
					self.options.selected = obj.code;
				}

				return $('<li>')
					.css({
						width: self.options.size[0],
						height: self.options.size[1],
					})
					.append( $("<a>")
						.addClass( "color-"+obj.code )
						.css({
							backgroundColor: "#"+obj.code,
							// width: self.options.size[0],
							// height: self.options.size[1],
							borderRadius: self.options.size[2]
						})
						.attr('data-color', obj.code)
						
					)
					.append( $("<div>")
						.addClass( "color-border" )
						.css({
							borderColor: "#"+obj.border,
							borderRadius: self.options.size[2]
						})
					);
			});

			self.$ul.append( self.$li );
		},
		
		display: function(){

			this.$elem.replaceWith( this.$UI );

			this.initEvent();
		},
		
		_colors: {
			blue: {
				code: "4617b4",
				border: "1f0068",
				title: "สีน้ำเงิน"
			},
			yellow: {
				code: "f4b300",
				border: "252525",
				title: "สีเหลือง"
			},
			red: {
				code: "b01e00",
				border: "4e0000",
				title: "สีแดง"
			},

			green: {
				code: "00c13f",
				border: "15992a",
				title: "สีเขียว"
			},
			/*grey: {
				code: "e7ebf1",
				border: "aaaaaa",
				title: "สีเทา"
			},*/
			black: {
				code: "252525",
				border: "000000",
				title: "สีดำ"
			},
			/*white: {
				code: "ffffff",
				border: "252525",
				title: "ขาว"
			},*/
			skyblue: {
				code: "1faeff",
				border: "1b58b8",
				title: "สีฟ้า"
			}
		},

		CSS: function(){
			
			return $('<style class="css-colors" type="text/css">' +
            	'.color-nav li{position:relative;z-index:1;overflow:hidden;background-color:transparent;padding:4px}'+
				'.color-nav li,.color-nav a{display:inline-block}'+
				'.color-nav a{position:relative;box-shadow:inset 0 1px 3px rgba(0,0,0,.3);display: block;width: 100%;height: 100%}'+

				'.color-nav .color-border{position: absolute;top: 0;left: 0;border: 2px solid #08c;bottom:0;right:0;z-index:-1;display:none}'+
				'.color-nav .active .color-border{display:block}'+
            '</style>');
		}

	}

	$.fn.colors = function( options ) {
		return this.each(function() {
			var color = Object.create( Render );
			
			color.init( options, this );

			$.data( this, 'colors', color );
		});
	};
	// ae113d
	$.fn.colors.options = {
		selected: "",
		max: 1,
		size: { 0: 32, 1: 32, 2: "" },
		colors: []
	};
	
})( jQuery, window, document );