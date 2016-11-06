/*
	jQuery plugin: tabledataslider 
	Created: 29-3-2015 Updated 12-4-2015
	Copyright: (C) 2015. All rights reserved.GNU General Public License version 2 or later; 
  Author: Jeroen Slemmer
	Version: 1.0
	Description: Enable (by event) TR-elements (A) to open/close corresponding detail data in TD-elements (B) in the same or other rows.
	
	Include jquery.tabledataslider.js in the head of the webpage: 
	<script src="pathto/jquery.tabledataslider.js"></script> where pathto is the relative path to the directory where the script is stored.
	TR-elements A must have an id composed of a prefix (ig 'data') and a number (ig '567'). 
	For example: <tr id="data567">
	TD-elements B must have an id composed of a prefix (ig 'detail') and a number (ig '567'). 
	For example: <td id="detail567">
	Numbers in id's of TR-elements A and corresponding TD-elements B should match.
	For example: '567' in <tr id="data567"> matches '567' in <td id=detail567">
	
	TR-elements A can have a class attribute to be used as a selector. For example: <tr class="data" ... >
	
	Activate them by invoking: $(selector).tabledataslider(options) where options is an object. For example: $('tr.data').tabledataslider({detailIdPrefix:'detail'})
	Possible options and its defaults: see below.
	
	After activating you can also open/close elements by invoking: $(selector).tabledataPerformSlide(open); where open: true or false.
	For example: $('tr#data4').tabledataPerformSlide(true);
	CSS style can be applied to the TR elements in 4 states:
		tr[tabledata-slider=opened]
		tr[tabledata-slider=closed] 
		tr[tabledata-slider=opening] 
		tr[tabledata-slider=closing] 
*/
(function ( $ ) {
	$.fn.tabledataslider = function(options) {

		var settings = $.extend({
		// these are the options and their defaults.
		detailIdPrefix: 'detail', // detail TD id's start with prefix: 'detail' 
		openFixedDuration: 700, 	// fixed time in milliseconds taken to animate opening of details
		closeFixedDuration: 300, 	// fixed time in milliseconds taken to animate closing of details
		openRelativeDuration: 0, 	// milliseconds/pixel content height to animate opening of details
		closeRelativeDuration: 0, // milliseconds/pixel content height to animate closing of details	
		detailMinHeight : 0, 			// minimal height of details TD when closed
		detailInitHeight : 0,	 		// initial height of details TD
		toggleEvent: 'click',			// event on which open/close should be performed
		afterclosing: function(){},// performed after opening details
		afteropening: function(){} // performed after closing details
		}, options );
		
		// add necessary style for detail containers and child block elements
		$('head').prepend('<style>table div.tabledata-slider{height:'+settings.detailInitHeight+'px;}table div.tabledata-slider>*{margin:0;padding:0;}</style>');				
		this.each(function(){
			if ($(this).prop('tagName')!= 'TR')throw new Error('Only TR elements can trigger a tabledata slider. Element is a '+$(this).prop('tagName'));
			// find details
			var masterId = $(this).attr('id');
			if (typeof masterId == 'undefined')throw new Error('TR elements must have an id to trigger a tabledata slider.');
			var match = /(\d+)/g.exec(masterId);
			if (match == null || match.length == 0) throw new Error('TR element id must contain a number to trigger a tabledata slider.');
			var detailContainer = $('#'+settings.detailIdPrefix+match[0]);
			if (detailContainer.length == 0)throw new Error('No TD element found to be a tabledata slider. Check its id.')
			if (detailContainer.prop('tagName')!='TD') throw new Error('Only TD elements can be a tabledata slider. Element is a '+ detailContainer.prop('tagName')+' element');
			// if not already slider
			if ($(detailContainer).find('.tabledata-slider').length == 0){ 
				var details = $(detailContainer).find('>*');
				// create nested containers: outer one for manipulating height, inner one for calculating maximum height of its content
				$(detailContainer).append('<div class="tabledata-slider" style="display:block;overflow:hidden;"><div class="tabledata-wrapper" style="display:block;"></div></div>');
				this.slider = $(detailContainer).find('.tabledata-slider');
				this.wrapper = $(this.slider).find('.tabledata-wrapper');
				wrapper = this.wrapper;
				// move content to inner container: the wrapper
				$(details).each(function(){
					$(wrapper).append($(this));
				});
				// set initial state as an attribute 'tabledata-slider' to 'closed'
				$(this).attr('tabledata-slider','closed');
			}
		});
		
		// open or close the details for selection
		$(this).init.prototype.tabledataPerformSlide = function(open){
			for (var o in this) {
				if (typeof this[o] == 'object')
					$(this[o]).tabledataPerformSlidePrivate(open);
			}
			return this;
		}
					
		// open or close the details one element
		$(this).init.prototype.tabledataPerformSlidePrivate = function(open){
			var me = this[0];
			if ($(me).attr('tabledata-slider') == 'opening' || $(me).attr('tabledata-slider') == 'closing') return;
			var height = $(me.wrapper).height();
			if (open){
				$(me).attr('tabledata-slider','opening');
				var duration = (settings.openRelativeDuration>0)?(height-settings.detailMinHeight)*settings.openRelativeDuration:settings.openFixedDuration;
				$(me.slider).animate({height:height},duration,function(){
					$(me).attr('tabledata-slider','opened');
					settings.afteropening();
				});
			} else {
				$(me).attr('tabledata-slider','closing');
				var duration = (settings.closeRelativeDuration>0)?(height-settings.detailMinHeight)*settings.closeRelativeDuration:settings.closeFixedDuration;
				$(me.slider).animate({height:settings.detailMinHeight},duration,function(){
					$(me).attr('tabledata-slider','closed');
					settings.afterclosing();
				});
			}
			return this;
		}
				
		// bind opening and closing to event
		$(this).bind(settings.toggleEvent,function(){
			if ($(this).attr('tabledata-slider') == 'closed')
				$(this).tabledataPerformSlidePrivate(true);
			else if ($(this).attr('tabledata-slider') == 'opened')
				$(this).tabledataPerformSlidePrivate(false);
		});	
		return this;
	};
}( jQuery ));