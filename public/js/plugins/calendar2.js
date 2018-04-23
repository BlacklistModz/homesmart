// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
	
	var Calendar2 = {
		init: function( options, elem ){
			var self = this;

			self.elem = elem;
			self.$elem = $( elem );

			self.options = $.extend( {}, $.fn.calendar2.options, options );

			self.config();
			self.setElem();
			self.refresh();

			self.Events()

			if ( typeof self.options.onComplete === 'function' ) {
				self.options.onComplete.apply( self, arguments );
			}


			// upcoming
			self.upcoming.init(  self.$upcoming ,self.options );

			$(window).resize(function() {
				self.resize();
				self._Month.resizeTab();
			});
		},

		config: function () {
			var self = this;


			if( !self.options.selectedDate ){
				self.options.selectedDate = new Date();
			}
			self.options.selectedDate.setHours(0, 0, 0, 0);

			// set date
			self.date = {
				today: new Date(),
				theDate: self.options.theDate,
				selected: self.options.selectedDate,
				lang: self.options.lang,

			};
			self.date.today.setHours(0, 0, 0, 0);

			if( !self.date.theDate ){
				self.date.theDate = new Date( self.date.today );
			}

			self.date.theDate_mini = new Date( self.date.today );
		},

		setElem: function () {
			var self = this;


			$.each( self.$elem.find('[ref]'), function(index, el) {
				self['$'+$(this).attr('ref')] = $(this);
			});
			
		},
		
		refresh: function () {
			var self = this;

			self.display();
			self.displayMini();
		},
		display: function () {
			var self = this;

			// var settings = self.calculation( self.date.theDate );
			// console.log( settings );
			self.$calendar.html( self.Theme[ self.options.format ]( self ) );
			self.$calendar.append( $('<div>', {class: 'calendarBoxDataEvent'}) );

			self.resize();
		},

		Theme: {
			month: function ( self, options ) {

				var settings = self.calculation( self.date.theDate );

				var theYear = self.date.theDate.getFullYear();
				self.$title.text( Datelang.month( self.date.theDate.getMonth(), 'normal', self.options.lang ) + ' ' + Datelang.year( theYear, 'normal', self.options.lang ) );


				var $table = $('<table>', {class: 'table-calendar'});

				var $head = $('<tr>');
				$.each(settings.header, function(index, obj) {
					$head.append( $('<th>', {text: Datelang.day( obj.key, 'normal', self.options.lang ) }) );
				});

				var $body = $('<tbody>');
				$.each( settings.lists, function(y, row){

					var $tr = $('<tr>');

					$.each(row, function(x, call){

						var dayNumber = $('<span>', {class: 'calendar-dayNumber', text: call.date.getDate()} );

						var $td = $('<td>', {'data-date': self.getDateToStr(call.date) }).append( 
							$('<div>', { class:'calendar-day'} ).html( dayNumber )
						);

						if( call.noday ){
							$td.addClass('over');

							if( call.date.getDate()==1 || (y==0&&x==0)){

								dayNumber.text( call.date.getDate() +' '+ Datelang.month( call.date.getMonth(), "normal", self.options.lang ) );
								

							}
						}
						if( call.today ){
							$td.addClass('today');
						}

						$tr.append( $td );
					});

					$body.append( $tr );
				});

				return $table.append( $('<thead>').html( $head ), $body );
			},

			year: function ( self ) {
				
				$calendar = $('<div>', {class: 'calendar-format-year'});

				var theYear = self.date.theDate.getFullYear();

				self.$title.text( Datelang.year( theYear, 'normal', self.options.lang ) );
				// $calendar.append( $('<h2>', {class: 'mts mhm'}).text( theYear ) );
				
				var ul = $('<ul>', {class: 'calendar-format-year-lists'});

				for (var i = 0; i < 12; i++) {

					var date = new Date( theYear, i, 1 );

					var li = $('<li>');

					var inner = $('<div>', {class: 'mam'}).append( 
						  $('<h3>', {class: 'mbs mls'}).text( Datelang.month( date.getMonth(), 'normal', self.options.lang ) )
						, self.calendarMini( date, {
							lists: false
						} ) 
					);
					li.html( inner );


					ul.append( li );
				} 

				return $calendar.append( ul );
			}
		},
		calculation: function ( date ) {
			
			var self = this;
			var data = {};
			var theDate = date || self.date.today;

			var firstDate = new Date( theDate.getFullYear(), theDate.getMonth(), 1);
			firstDate = new Date(theDate);
	        firstDate.setDate(1);
	        var firstTime = firstDate.getTime();
			var lastDate = new Date(firstDate);
	        lastDate.setMonth(lastDate.getMonth() + 1);
	        lastDate.setDate(0);
	        var lastTime = lastDate.getTime();
	        var lastDay = lastDate.getDate();

			// Calculate the last day in previous month
	        var prevDateLast = new Date(firstDate);
	        prevDateLast.setDate(0);
	        var prevDateLastDay = prevDateLast.getDay();
	        var prevDateLastDate = prevDateLast.getDate();

	        var prevweekDay = self.options.weekDayStart;
	        if( prevweekDay>prevDateLastDay ){
	        	prevweekDay = 7-prevweekDay;
	        }
	        else{
	        	prevweekDay = prevDateLastDay-prevweekDay;
	        }

			data.lists = [];
			for (var y = 0, i = 0; y < 7; y++){

				var row = [];
				var weekInMonth = false;

				for (var x = 0; x < 7; x++, i++) {
					var p = ((prevDateLastDate - prevweekDay) + i);

					var call = {};
					var n = p - prevDateLastDate;
					call.date = new Date( theDate ); 
					call.date.setHours(0, 0, 0, 0); 
					call.date.setDate( n );

					// If value is outside of bounds its likely previous and next months
	            	if (n >= 1 && n <= lastDay){
	            		weekInMonth = true;

	            		if( self.date.today.getTime()==call.date.getTime()){
	                    	call.today = true;
	                    }

	                    if( self.date.selected.getTime()==call.date.getTime() ){
	                    	call.selected = true;
	                    }
	            	}
	            	else{
	            		call.noday = true;
	            	}

					row.push(call);
				}

				if( row.length>0 && weekInMonth ) data.lists.push(row);
			}

			data.header = [];
			for (var x=0,i=self.options.weekDayStart; x<7; x++, i++) {
				if( i==7 ) i=0;
				data.header.push({
	        		key: i, 
	        		text: Datelang.day( i, 'normal', self.options.lang ),  // numbar, type, lang
	        	});
			};

			self.date.first = firstDate;
			self.date.end = lastDate;
			self.date.start_date = data.lists[0][0].date;
			self.date.end_date = data.lists[ data.lists.length-1 ][6].date;

			return data;
		},
		getDateToStr: function (theDate) {
			
			month = theDate.getMonth()+1;
			month = month < 10 ? "0"+month:month;

			date = theDate.getDate();
			date = date < 10 ? "0"+date:date;

			return theDate.getFullYear() + "-" + month + "-" + date;
		},
		calendarMini: function (date, options) {
			var self = this;

			var options = $.extend( {}, {
				lists: true
			}, options );
			var settings = self.calculation( date || self.date.theDate );

			var $table = $('<table>', {class: 'table-calendar-mini'});

			var $head = $('<tr>');
				$head.append( $('<th>', {text: 'CW' }) );
			$.each(settings.header, function(index, obj) {
				$head.append( $('<th>', {text: Datelang.day( obj.key, 'short', self.options.lang ) }) );
			});


			var $body = $('<tbody>');
			$.each( settings.lists, function(y, row){

				var $tr = $('<tr>');

				$.each(row, function(x, call){

					if( x==0 ){
						
						$tr.append( $('<td>', {class:'cw', text: self.getWeekNumber(call.date) }) );
					}

					var ul = '';
					if( options.lists ){
						ul = $('<ul>', {class: 'calendar-list'});
					}

					var $td = $('<td>', {'data-date': PHP.dateJStoPHP( call.date ) }).append( 
						$('<div>', {class: "inner"}).append(
							  $('<span>', {text: call.date.getDate()})
							, ul
						)
					);

					if( call.noday ){
						$td.addClass('over');
					}
					if( call.today ){
						$td.addClass('today');
					}

					$tr.append( $td );
				});

				$body.append( $tr );
			});

			return $table.append( $('<thead>').html( $head ), $body );
		},
		getWeekNumber: function (d) {
			
			// Copy date so don't modify original
		    d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
		    // Set to nearest Thursday: current date + 4 - current day number
		    // Make Sunday's day number 7
		    d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay()||7));
		    // Get first day of year
		    var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
		    // Calculate full weeks to nearest Thursday
		    var weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7);
		    // Return array of year and week number
		    return weekNo;
		},

		resize: function () {
			var self = this;


			if( self.options.format == 'month' ){

				var offset = self.$calendar.offset();

				var $thead = self.$calendar.find('thead');

				var  full_h = $(window).height() - (offset.top + $thead.outerHeight() + 22 );

				var top = offset.top;

				var length = self.$calendar.find('tbody > tr').length;
				var item_h = full_h / length;

				item_h = item_h < 100 ? 100:item_h;

				self.$calendar.find('tbody > tr > td').height( item_h );

			}
		},

		Events: function () {
			var self = this;

			self.$elem.find( '[data-action-prevnext]').click(function (e) {
				
				e.preventDefault();

				var offset = $(this).hasClass("prev") ? -1 : 1;
				var newDate = new Date( self.date.theDate );

				if( self.options.format=='year' ){
					newDate.setFullYear( self.date.theDate.getFullYear() + offset);
				}
				else if( self.options.format=='month' ){
					newDate.setMonth( self.date.theDate.getMonth() + offset);
				}
				
				self.date.theDate = newDate;
                self.display();


                if( self.options.format=='month' ){
					self.date.theDate_mini = newDate;
					self.displayMini();
				}

			} );

			self.$elem.find('[data-action=today]').click(function(e){
				e.preventDefault();

				today = new Date(); today.setHours(0,0,0,0);

				if( self.date.theDate.getTime()==today.getTime() ) return;

				self.date.theDate = today;
                self.display();

                if( self.options.format=='month' ){
					self.date.theDate_mini = today;
					self.displayMini();
				}
			});

			self.$elem.find('[data-action-format]').click(function(e){
				e.preventDefault();
				
				$(this).addClass('active').siblings().removeClass('active');
				self.options.format = $(this).attr('data-action-format');

				today = new Date();
				today.setHours(0,0,0,0);

				if( self.options.format=='month' ){
					self.date.theDate = self.date.theDate_mini;
					self.displayMini();

				}
				else{
					self.date.theDate = today;
				}

                self.display();
			});


			// mini
			self.$elem.find( '[data-action-prevnext-mini]').click(function (e) {
				e.preventDefault();

				var offset = $(this).hasClass("prev") ? -1 : 1;
				
				var newDate = new Date( self.date.theDate_mini );
				newDate.setMonth( self.date.theDate_mini.getMonth() + offset);

				self.date.theDate_mini = newDate;
				self.displayMini();

				if( self.options.format=='month' ){
					self.date.theDate = newDate;
					self.display();
				}

			} );
			self.$elem.find('[data-action-mini=today]').click(function(e){
				e.preventDefault();

				today = new Date(); today.setHours(0,0,0,0);

				if( self.date.theDate_mini.getTime()==today.getTime() ) return;

				self.date.theDate_mini = today;
				self.displayMini();

				if( self.options.format=='month' ){
					self.date.theDate = today;
					self.display();
				}
			});
		},

		displayMini: function () {
			var self = this;

			self.$calendarMini.html( self.calendarMini( self.date.theDate_mini ) );
			self.setCalendarMini();
		},
		setCalendarMini: function () {
			var self = this;

			self.$elem.find('[ref=title_mini_month]').text( Datelang.month( self.date.theDate_mini.getMonth(), 'normal', self.options.lang ) );
			self.$elem.find('[ref=title_mini_year]').text( Datelang.year( self.date.theDate_mini.getFullYear(), 'normal', self.options.lang ) );


			self.$elem.find('.calendarLeft-listsboxWrap').css('top', self.$elem.find('.calendarLeft-calendarWrap').outerHeight() );	

			self._Month.init( self );
		},



		_Month: {
			init: function ( then ) {
				var self = this;

				self._this = then;
				self.options = then.options;

				self.refresh();
			},
			refresh: function () {
				var self = this;
				setTimeout(function () {
					
					self.fetch().done(function( res ) {
	 					
	 					self._this.data_Month = {
	 						date: self._this.getDateToStr( self._this.date.theDate_mini ),
	 						results: res
	 					};
						self.buildFrag( res );
					});
				}, 1);	
			},
			fetch: function () {
				var self = this;

				return $.ajax({
					url: Event.URL + 'events/calendar',
					data: { 
						start_date: self._this.getDateToStr(self._this.date.start_date), 
						end_date: self._this.getDateToStr(self._this.date.end_date)
					},
					dataType: 'json'
				})
				.always(function() { })
				.fail(function() { });
			},
			buildFrag: function ( results ) {
				var self = this;

				var today = new Date(); today.setHours(0, 0, 0, 0);
				self._this.$calendarMini.find('.table-calendar-mini');

				$.each(results, function(i, obj ) {

					// mini
					var res = obj.start_date.split("-");
					var theDate = new Date(parseInt(res[0]), parseInt(res[1])-1, parseInt(res[2]));
					theDate.setHours(0, 0, 0, 0);

					var date = PHP.dateJStoPHP( theDate );

					var box = self._this.$calendarMini.find('[data-date="' + date + '"]');
					if( box.find('.calendar-list-item').length < 3 ){
						box.find('.calendar-list').append( $('<li>', {class: 'calendar-list-item'}).css('background-color', '#'+obj.color_code ) );
					}

					// 
					if( self._this.options.format=='month' ){
						self.calTab(obj);
					}
					
				});
			},


			calTab: function ( obj ) {
				var self = this; 

				start = new Date( obj.start_date );
				start.setHours(0,0,0,0);

				end = new Date( obj.end_date );
				end.setHours(0,0,0,0);

				// 
				dStart = '';
				row = -1;
				first = true;
				col = 0;
				
				for (var j = 1; j < 60; j++) {

					var date = new Date( start );
					elem = self._this.$calendar.find('[data-date='+ self._this.getDateToStr(date) +']');

					if( !dStart && elem.length==1 ){
						dStart = date;
					}

					if( elem.length==1 ){

						col++;
						var data = elem.data('lists') || [];

						if( row<0 ){
							row = Object.keys(data).length;

							if( row>0 && first ){
								for (var k = 0; k < 10; k++) {
									if( !data[k] ){
										row = k;
										break;
									}
									
								};
							}
						}

						data[row || 0] = obj;
						first = false;

						// update
						elem.data('lists', data);

						dEnd = new Date( elem.closest('tr').find('[data-date]').last().data('date') );
						dEnd.setHours(0,0,0,0);
						// console.log( dEnd );

						if( start.getTime()==dEnd.getTime() && end.getTime()!=dEnd.getTime() ){
							obj.next = true;
							break;	
						} 
					}
					else{
						obj.prev = true;
					}
					
					if( start.getTime() == end.getTime() ){
						break;
					}
					start.setDate( start.getDate() + 1 );
				};
				
				obj.row = row;
				obj.col = col;

				// console.log( obj.text,  dStart );
				obj.dStart = new Date( dStart );
				obj.dStart.setHours(0,0,0,0);
				obj.td = self._this.$calendar.find('[data-date='+ self._this.getDateToStr(obj.dStart) +']');
				obj.tr = obj.td.closest('tr');

				var limit =  parseInt( (obj.td.height()-25) / 20 ) -2;
				
				if( obj.row > limit ){
					obj.is_more = obj.row-limit;
					

					obj.row = limit+1;
					if( obj.is_more == 1 ){						
						self.setTab( obj );
					}
					else if (obj.is_more > 1){

						self._this.$calendar.find('.calendarBoxDataEvent>.tab[data-e-date='+self._this.getDateToStr(obj.dStart)+']>.text').text( obj.is_more + ' more...' );
					}
					
					
					return;
				}

				if( obj.td.length==0 ) return;

				tdCol = obj.td.parent().index();
				if( obj.col>(7-tdCol) ){
					obj.next = true;
					obj.col = 7-tdCol;
				}

				self.setTab( obj );

				if( obj.next ){
					obj.next = false;
					obj.prev = true;

					obj.start_date = obj.dStart.setDate( obj.dStart.getDate()+obj.col );
					self.calTab( obj );
				}
			},
			setTab: function ( data ) {
				var self = this;

				var title = $('<span/>').text( data.text );
				var icon = '';
				var tab = $('<div>');

				if( data.is_more ){

					tab = $('<a>', {class: 'tab is_more', 'data-e-date': self._this.getDateToStr(data.dStart) }).html( $('<div>', {class: 'text ellipsis'}).text( '1 more...' ) );
				
				}else{

					if( data.url ){
						tab = $('<a>', {href: data.url}); //.text( title );

						if( data.plugin == 'dialog' ){
							tab.dialog();
						}
					}

					
					var tRight = '';
					var $bg = '';
					var is_allday = false;

					if( data.allday || data.prev || data.next ){
						is_allday = true;
						$bg = $('<div>', {class: 'bg'}).css('background-color', "#"+ (data.color_code? data.color_code: '333') );
					}

					if( data.icon ){
						icon = $('<i>', {class: 'mrs icon-' +  data.icon});
					}
					else if( !is_allday ){
						icon = $('<i>', {class: 'mrs icon-circle e-icon'}).css('color', "#"+ (data.color_code? data.color_code: '333') );


						var d = new Date( data.start_date );
						m = d.getMinutes();
						m = m < 10 ? "0"+m: m;
						tRight = $('<span>', {class: 'time'}).text( d.getHours() + ":" + m );
					}
				

					tab.attr({
						class: 'tab col-'+data.col,
						'data-id': data.id
					})
					.addClass( is_allday ? 'is_allday':'' )
					.addClass( data.prev ? 'prev':'' )
					.addClass( data.next ? 'next':'' )
					.append(
						  $bg 
						, tRight
						, $('<div>', {class: 'text ellipsis'}).append( icon, title )
					);
				}

				self._this.$calendar.find('.calendarBoxDataEvent').append( tab );

				// Event 
				tab.mouseenter(function () {
					$('.tab[data-id='+ $(this).data('id') +']').addClass('hover');
				}).mouseleave(function () {
					$('.tab[data-id='+ $(this).data('id') +']').removeClass('hover');
				});

				tab.data( data );
				self.resizeTab();
			},
			resizeTab: function () {
				var self = this;

				$.each(self._this.$calendar.find('.calendarBoxDataEvent>.tab'), function (i, obj) {
					
					data = $( obj ).data();

					// res
					w = data.td.outerWidth();
					h = data.td.height();

					data.pos = data.td.position();

					data.pos.left += 1;
					data.pos.top += ($( obj ).outerHeight() * (data.row)) + (data.row)+ 25;

					// set height
					orverH = (data.row+1)*21 + 25;
					if( orverH > h ){
						// data.tr.find('[data-date]').height( orverH );
					}

					// set width
					data.width = 0;
					var date = new Date( data.dStart );
					for (var d = 0; d < data.col; d++) {
						td = self._this.$calendar.find('[data-date='+ self._this.getDateToStr(date) +']');
						data.width+=td.outerWidth();
						date.setDate( date.getDate()+1 );
					};

					if( data.is_more ){
						data.width = w;
					}

					$( obj ).css( data.pos ).width( data.width );
				});
			},
		},



		upcoming: {

			init: function ($el, options) {
				var self = this;

				self.$elem = $el;
				self.options = options;

				self.refresh();
			},

			refresh: function () {
				var self = this;
				setTimeout(function () {
					
					self.fetch().done(function( res ) {
	 
						// self.dataEvt = results;
						self.buildFrag( res );
						self.display();
					});
				}, 1);	
			},
			fetch: function () {
				var self = this;

				return $.ajax({
					url: Event.URL + 'events/upcoming',
					dataType: 'json'
				})
				.always(function() { })
				.fail(function() { });
			},
			buildFrag: function ( results ) {
				var self = this;

				var today = new Date(); today.setHours(0, 0, 0, 0);
				self.items = $.map(results.lists, function( items, key) {

					var li = $('<li>');

					var res = key.split("-");
					var theDate = new Date(parseInt(res[0]), parseInt(res[1])-1, parseInt(res[2]));
					theDate.setHours(0, 0, 0, 0);
					var difference = theDate.getTime()-today.getTime();

					var title = '';
					if( difference==0 ){
						title = self.options.lang=='th' ? 'วันนี้, ' : 'Today, ';
					}
					else if( difference==86400000  ){
						title = self.options.lang=='th' ? 'พรุ่งนี้, ' : 'Tomorrow, ';
					}


					li.append( $('<h4>').addClass( difference==0 ? 'today':'' ).append(
						  title
						, Datelang.day(theDate.getDay(), 'normal', self.options.lang)
						, (self.options.lang=='th' ? 'ที่ ' : ', ')
						, theDate.getDate()
						, ' '
						, Datelang.month(theDate.getMonth(), 'normal', self.options.lang)
					) );
					
					var ul = $('<ul>', {class: 'ui-calendar-left-listsitem'});
					$.each(items, function(i, data) {

						var name = $('<div>', {class: 'e-name'}).text( data.title );

						if( data.allday ){
							name.css('background-color', '#'+data.color_code);
						}

						ul.append( $('<li>', {class: 'e-item clearfix'} )
							.addClass( data.allday ? 'is-allday' : 'has-color' )
							.append(

								  $('<div>', {class: 'rfloat e-time'}).text( data.time_str )

								, data.allday 
									? ''
									: $('<div>', {class: 'e-color'}).css('background-color', '#'+data.color_code) 

								, name
							) 
						);
					});

					li.append( ul );

					return li;
					// return something;
				});
			},
			display: function () {
				
				var self = this;
				self.$elem.find('[role=listsbox]').append( self.items );
			}
		}

	};

	$.fn.calendar2 = function( options ) {
		return this.each(function() {
			var $this = Object.create( Calendar2 );
			$this.init( options, this );
			$.data( this, 'calendar2', $this );
		});
	};

	$.fn.calendar2.options = {
		// string
		lang: "th",
		format: 'month', 
		summary: true,

		// date
		weekDayStart: 1,
		theDate: null,
		selectedDate: null,

		// resize
		resize: true,
		bordertop: 1,
		borderleft: 1,

		onComplete: function () {}
	};
	
})( jQuery, window, document );