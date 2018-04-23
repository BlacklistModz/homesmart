// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var Slideshow = {
		init: function ( options, elem ) {
			var self = this;
			self.elem = elem;
			self.$elem = $(elem);
			self.options = $.extend( {}, $.fn.slideshow.options, options );

			if( !options.max_height ){
				self.options.max_height = self.$elem.height();
			}
			self.setup();

			self.resize();
			$(window).resize(function () {
				self.resize();
			});

			if( self.limit <= 1 ){
				self.inx = 0;
				self.display( 1 );
				return false;
			}
			
			self.refresh( 1 );

			self.control();
		},

		resize: function(){
			var self = this;

			var img = self.$elem.find('img').eq(0);
			var image = new Image();
			image.src = img.attr('src');
			image.onload = function() {
				
				var scaledW = this.width;
				var scaledH = this.height;

				var fullW = $(window).width();
				var fullH = ( scaledH*fullW )/scaledW;

				if(fullH > self.options.max_height){
					fullH = self.options.max_height;
					self.$elem.addClass('hY');
				}else if( fullH < self.options.min_height ){
					fullH = self.options.min_height;
					self.$elem.addClass('hY');
				}
				else if( self.$elem.hasClass('hY') ){
					self.$elem.removeClass('hY');
				}

				self.$elem.find('#slideshow').css('height', 336  );
			}		
		},

		setup: function(){
			var self = this;
			
			self.limit = self.$elem.find('#slide-image>li').length;
			self.prevnext = 'next';
			self.is_first = true;

			if( self.options.effect=='slide' ){
				self.options.random = false;
			} 

			self.inx = -1;
			self.$ol = self.$elem.find('#slide-image');
			self.$ol.find('li').each(function () {
				$(this).css({opacity:0, display: 'none'});
			});
		},
		refresh: function( length ){
			var self = this;
			
			self.timeout = setTimeout(function () {
				
				self.buildFrag();
				self.display();
				
				if ( self.options.refresh && self.limit > 1 ) {
					self.prevnext = 'next';
					self.refresh();
				}
				
            }, length || self.options.refresh );
		},
		
		buildFrag:function( random ){
			var self = this;

			if( random || self.options.random ){
				var inx = Math.floor(Math.random() * (self.limit-1) );

				if( self.inx==inx ){
					self.inx = self.inx>=(self.limit-1) ||  self.inx < 0 ? 0: self.inx+1;
				}
				else{
					self.inx=inx;
				}
			}
			else{
				self.inx = self.inx>=(self.limit-1) ||  self.inx < 0? 0: self.inx+1;
			}
			
		},
		
		display: function( length ){
			var self = this;


			if( self.is_first ){
				self.$ol.find( 'li' ).eq(self.inx).css({opacity:1, display:'block'}).addClass('on');

				self.$elem.find('.slider-caption-content li').eq(self.inx).addClass('show').addClass('active');

				self.$elem.find('.slide-header>ul>li').eq(self.inx).addClass('show').addClass('active');

				self.id_load = false;
				self.is_first = false;

				if( self.options.effect=='slide' ){
					self.$ol.find( 'li' ).css({display:'block', opacity: 1});
					self.slide_prevnext();
				}

			}
			else{


				

				self.$elem.find('.slide-header>ul>li').eq(self.inx).addClass('active').siblings().removeClass('show');
				self.$elem.find('.slider-caption-content li').eq(self.inx).addClass('show').siblings().removeClass('show');


				self.$elem.find('.slider-caption-content li').eq(self.inx).addClass('active').siblings().removeClass('active');


				if( self.options.effect=='slide' && self.limit > 2 ){
					var li = self.$ol.find( 'li' ).eq(self.inx);

					var width = self.$ol.width();

					next = li.next().length ? li.next() : self.$ol.find('li:first-child');
					next.css({
						'left': width*2,
						zIndex: 1
					});
				}

				self.effect[ self.options.effect ](self, length, function () {


					self.$elem.find('.slide-header>ul>li').eq(self.inx).addClass('show').siblings().removeClass('active').removeClass('show');

					
				});
			}

			// dotnav
			var ol = self.$elem.find('.dotnav ul');
			// ol.find('.current').removeClass('current');
			ol.find('li').eq( self.inx ).addClass('current').siblings().removeClass('current');
		},

		effect: {
			fade: function ( then, length, callback ) {
				var self = then;

				var active = self.$ol.find( 'li.on' );
			
				if( active.length==1 ){
					active.animate({opacity:0}, length || 300, function()
					{
						active.hide().removeClass('on');
					});
				}

				self.$ol.find( 'li' ).eq(self.inx).show().animate({opacity:1}, length || 300, function () {
					$( this ).addClass('on');
					self.id_load = false;

					if( typeof callback == 'function' ){
						callback();
					}
				});

			},

			slide: function (then, tempo, callback ) {
				var self = then;

				active = self.$ol.find( 'li.on' );
				var width = self.$ol.width();
				
				tempo = tempo || 500;

				if( active.length==1 ){

					w = self.prevnext=='next' ? width*-1: width;
					
					active.animate({left: w}, tempo, "linear", function () {
						$(this).css({
							// zIndex: 0,
						}).removeClass('on');
					});
				}

				var li = self.$ol.find( 'li' ).eq(self.inx);

				li.css({
					'left': self.prevnext=='next' ? width: width*-1,
					zIndex: 2
				}).animate({left: 0}, tempo, "linear", function () {
					$(this).addClass('on');
					self.id_load = false;

					self.$ol.find( 'li' ).css({zIndex: 0});					
					self.slide_prevnext();

					if( typeof callback == 'function' ){
						callback();
					}
				});

				var next = li.next().length ? li.next() : self.$ol.find('li:first-child');
				next.animate({left: self.$ol.width()}, tempo, "linear");
			},

		},

		slide_prevnext: function () {
			var self = this;

			if( self.limit < 2 ) return false;



			var li = self.$ol.find( 'li' ).eq(self.inx);
			var width = self.$ol.width();
			li.css({zIndex: 2});

			/*for (var i = Things.length - 1; i >= 0; i--) {
				Things[i]
			}*/
			prev = li.prev().length ? li.prev() : self.$ol.find('li:last-child');
			prev.css({
				'left': width*-1,
				zIndex: 1
			});	

			next = li.next().length ? li.next() : self.$ol.find('li:first-child');

			next.css({
				'left': width,
				zIndex: 1
			});

		},

		control: function(){
			var self = this;

			$('.dotnav-item', self.$elem).click(function(e){
				e.preventDefault();

				if( $(this).hasClass('current') || self.id_load || $(this).parent().hasClass('current') ){
					return false;
				}

				self.prevnext = 'next';
				self.$ol.stop();
				self.id_load = true;
				clearTimeout( self.timeout );
				self.inx = $(this).parent().index();

				self.display();
				self.refresh();
			});

			$('.prev, .next', self.$elem).click(function(e){
				e.preventDefault();

				if( self.id_load ) return false;

				self.$ol.stop();
				self.id_load = true;
				clearTimeout( self.timeout );

				if( $(this).hasClass('prev') ){
					self.inx --;

					if(self.inx < 0){
						self.inx = self.limit;
						self.inx --;
					}
					self.prevnext = 'prev';
				}
				else{
					self.inx ++;

					if(self.inx > (self.limit-1)){
						self.inx = 0;
					}
					self.prevnext = 'next';
				}

				self.display();
				self.refresh();
			});
		}
	};

	$.fn.slideshow = function( options ) {
		return this.each(function() {
			var $this = Object.create( Slideshow );
			$this.init( options, this );
			$.data( this, 'slideshow', $this );
		});
	};

	$.fn.slideshow.options = {

		effect: 'fade',
		speed: 500,
		// wrapEachWith: '<div></div>',
		auto: true,
		refresh: 13000,
		random: true,
		// max_height: 450,
		min_height: 180
	};
	
})( jQuery, window, document );