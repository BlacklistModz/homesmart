// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var Post = {
		init: function(options, elem) {
			var self = this;
			self.elem = elem;
			self.$elem = $(elem);
			self.options = $.extend( {}, $.fn.post.options, options );
			self.media = {};

			// console.log( self.options );

			self.has_loading = false;

			self.setElem();
			self.Events();
			
			// console.log( self.options  );
		},

		setElem: function () {
			var self = this;


			if( self.options.name ){
				self.$elem.find('[name=name]').val( self.options.name );
			}

			if( self.options.detail ){
				self.$elem.find('[name=detail]').val( self.options.detail );
			}

			if( self.options.embed_url!='' ){
				self.$elem.find('input.js-input-youtube').val( self.options.embed_url );
				self.setEmbed(  self.options.embed_url, self.$elem.find('.media-youtube') );
			}
			

			if( self.options.image_url ){

				var $img = $('<img>', {src: self.options.image_url})
				self.preview_photo( $img, self.$elem.find('.media-photo') );
			}

			
			// self.$elem = $( self.elem );
			// self.$preview = self.$elem.find('.post-preview');
			// self.$media = self.$elem.find('.media-wrapper');
		},

		Events: function  () {
			var self = this;

			self.$elem.find('.js-add-photo').click(function  () {
				var type = $(this).parent().data('type');

				self.$media = $(this).parents('.media-wrapper');
				self.$preview = self.$media.find('.media-preview');
				self.add_photo();
			});


			self.k = false;
			self.$elem.find('.js-input-youtube').keydown(function (e) {
				var val = $(this).val(); 
				self.k = false;
				if( e.ctrlKey && e.keyCode==86){
					self.k = true;
				}

				if(e.keyCode==13){
					self.k = true;
				}

			}).keyup(function (e) {
				var val = $(this).val();

				if( self.k && self.ValidURL( val ) && !self.has_loading ){
					

					self.setEmbed( val, self.$elem.find('.media-youtube') );
				}

			});
	
			self.$elem.submit(function (e) {
				e.preventDefault();

				self.submit();

			});


			self.$elem.find('.js-toggle').change(function  () {
				var $top = $(this).closest('.js-toggle-top');

				$top.find('.js-toggle-active').toggle();
			});
		},


		add_photo: function  () {
			var self = this;

			var $input = $('<input/>', { type: 'file', accept: "image/*", multiple:''});
			$input.trigger('click');

			$input.change(function(){

				self.load_photo( this.files[0], self.$elem.find('.media-photo') );
				/*self.buildFrag( this.files, true );
				self.getModel();*/
			});

			// console.log( 'add_photo' );
		},

		load_photo: function  ( file, $el ) {
			var self = this;

			var width = $el.find('.media-preview').width();
			var $img = $('<div/>',{ class:'image-crop'});

			var reader = new FileReader();
			reader.onload = function (e) {

				var image = new Image();
				image.src = e.target.result;
				$image = $(image);

				image.onload = function() {
					// var $img = this;

					var scaledW = this.width;
					var scaledH = this.height;

					var height = ( scaledH * width ) / scaledW;
					
					$img.css({
						width: width,
						height: height
					});
					
					$img.html( $image );
					self.preview_photo( $img, $el );
					self.media.file = file;
				}
			}

			reader.onprogress = function(data) {
				if (data.lengthComputable) {                                            
	                // var progress = parseInt( ((data.loaded / data.total) * 100), 10 );
	                // $progress.find('.bar').width( progress+"%" );
	            }
        	}
			reader.readAsDataURL( file );
		},

		preview_photo: function  ( $img, $el ) {
			var self = this;

			// var $progress = $('<div/>',{ class:'progress'}).html( $('<span>', {class: 'bar'}) );
			var $remove = $('<div/>',{ class:'remove-button'}).html( $('<span>', {class: 'icon icon-remove'}) );
			
			$el.find('.media-preview').append( $remove, $img );
			$el.addClass('has-file');

			$remove.click(function  () {

				if( self.options.image_cover_url ){

					var url = Event.URL+'posts/del_image_cover/' + self.options.id;
					Dialog.load(url, {}, {
						onClose: function  () { },
						onSubmit: function  () { 

							$.post( url, {id: self.options.id}, function  (response) {

								Dialog.close();

								$el.find('.media-preview').empty();
								$el.removeClass('has-file');

							}, 'json' );
							
						}
					});
				}
				else{

					$el.find('.media-preview').empty();
					$el.removeClass('has-file');
					self.media.file = null;
				}
				

			});
		},

		add_video: function  () {
			var self = this;

			var $input = $('<input/>', { type: 'text', class:'inputtext' });

			var $box = $('<div/>', {class: 'input'}).append( $input );
			self.$preview.html( $box );
			$input.focus();

			var k = false;

			$input.keydown(function (e) {
				var val = $(this).val(); 
				k = false;
				if( e.ctrlKey && e.keyCode==86){
					k = true;
				}

				if(e.keyCode==13){
					k = true;
				}

			}).keyup(function (e) {
				var val = $(this).val();

				if( k && self.ValidURL( val ) && !self.has_loading ){
					self.has_loading = true;

					self.embed( val );
					
				}

			});
		},

		ValidURL: function (textval) {

			var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
		  	return urlregex.test(textval);
		},

		setEmbed: function ( text, $elem ) {
			var self = this;

			self.has_loading = true;
			self.$elem.find('.js-input-youtube').addClass('disabled').prop('disabled', true);
			$elem.addClass('has-loading');

			self.getEmbed( text, function  (response) {

				console.log( response );

				self.has_loading = false;
				self.k = false;
				$elem.removeClass('has-loading');

				self.media.embed = {
					images: response.images,
					title: response.title,
					// url: response.url,
					original_url: response.original_url,
					description: response.description,
					provider_display: response.provider_display,
					provider_name: response.provider_name,
					provider_url: response.provider_url,
				}

				if( response.images.length>0 ){
					self.media.embed.images_url = response.images[0].url;

					if( self.$elem.find('[name=title]').val()=="" ){
						self.$elem.find('[name=title]').val( response.title );
					}

					if( self.$elem.find('[name=text]').val()=='' && self.options.text=='' ){
						self.$elem.find('[name=text]').val( response.description );
					}

					var $remove = $('<div/>',{ class:'remove-button'}).html( $('<span>', {class: 'icon icon-remove'}) );
					var $img = $('<img/>', {src: response.images[0].url });
					$elem.find('.media-preview').empty().append( $remove, $img );

					$remove.click(function  () {

						self.clearInputYoutube();
					});
				}
				else{
					self.error('ไม่สามารถใช้ลิงก์นี้ได้');
					self.clearInputYoutube();
				}

			} );
		},
		getEmbed: function (url, response ) {
			
			if (typeof $.fn.embedly == 'undefined') {
				$.getScript('http://cdn.embed.ly/jquery.embedly-3.1.2.min.js')
					.done(function( script, textStatus ) {
						qEmbedly( url, response );
					})
					.fail(function( jqxhr, settings, exception ) {
						console.log( 'getScript Fail' );
					});
			}
			else{
				qEmbedly( url, response );
			}
			
			function qEmbedly( url, response ) {
				
				$.embedly.extract( url, {key: '57f3d42132e248eb9ded32c9b14732b8'})
				.progress( response );
			}
		},
		clearInputYoutube: function () {
			var self = this;

			self.$elem.find('.media-youtube').find('.media-preview').empty();
			self.$elem.find('.js-input-youtube').val('').removeClass('disabled').prop('disabled', false).focus();
			self.media.embed = {};
		},

		submit: function () {
			var self = this;

			var $form = self.$elem;
			var formData = new FormData();

			$.each($form.serializeArray(), function (index, field) {
				formData.append(field.name, field.value);
	        });

			if( self.media.file ){
				formData.append('file1', self.media.file);
			}

			if( self.media.embed ){
				$.each(self.media.embed, function (name, val) {

					if( name == 'images' ) return;
					formData.append('embed['+ name +']', val);
		        });
				// formData.append('embed',  );
			}

			Event.inlineSubmit( $form, formData ).done(function( result ) {
				Event.processForm($form, result);

			});
		},

		error: function( txt, call ){
			var self = this;

			Dialog.open({
				width: 300,
				bg: 'danger',
				body: '<div class="tac">'+txt+'</div><span role="dialog-close"></span>',
				onClose: function  () {}
			});

			setTimeout(function() {
				Dialog.close();

				if( typeof call === 'function' ){
					call( true );
				}
			}, 3000);
		},
	}
	
	$.fn.post = function( options ) {
		return this.each(function() {
			var $this = Object.create( Post );
			$this.init( options, this );
			$.data( this, 'post', $this );
		});
	};

	$.fn.post.options = {
		image_cover: [],
		embed_url: '',
		text: ''
	};
	
})( jQuery, window, document );