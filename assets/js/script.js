jQuery(document).ready(function ($) {

	//get all feeds from the DOM
	var $feeds = $('.isv-feed');

	//If any feeds were found...
	if ($feeds.length > 0) {

		//loop through all the found feeds
		$feeds.each(function () {

			//get single feed
			var $feed = $(this);

			//get the specs for this feed
			var feedcode = $feed.data('feedcode');
			var filters = $feed.data('filters');
			var content = $feed.data('content');
			var id = $feed.attr('id');

			isv_get_feed(feedcode, filters, id, content, '', '' );

		});
	}

	function isv_get_feed(feedcode, filters, id, content, from, until) {

		//start timing
		var start = performance.now();

		$('#' + id).html(window.isv_params.loader);

		//perform a server-request to get the feed-HTML
		$.ajax({
			url: window.isv_params.ajax_url,
			method: 'POST',
			data: {
				'action': 'isv_get_feed',
				'feedcode': feedcode,
				'filters': filters,
				'id': id,
				'content' : content,
				'from': from,
				'until': until,
				'start': start,
				'debug': true
			},
			dataType: 'json',
			success: isv_get_feed_done,
		});
	}

	function isv_get_feed_done(response) {

		//log everything we got back from the server
		console.log(response);

		//add html-result to DOM
		if (response.debug === true) {
			$('#' + response.id).html('<pre>' + response.html + '</pre>');
		} else {
			$('#' + response.id).html(response.html);
		}

		//end timing
		var end = performance.now();

		console.log('AJAX call "isv_get_feed" took ' + Math.floor(end - response.start) + ' miliseconds.');

		isv_bind_ctas();
	}

	function isv_bind_ctas() {

		//unbind and rebind the click-event on our booking-buttons
		$('.isv-book-button').unbind('click').on('click', isv_book_btn_clicked);

		//unbind and rebind the click-event on our booking-buttons
		$('.isv-button-fetch-filtered').unbind('click').on('click', isv_filter_btn_clicked);

		//initialze the datepickers
		$('.isv-datepicker').datepicker({
			minDate: new Date(),
			dateFormat: 'dd-mm-yy'
		});
	}

	function isv_filter_btn_clicked(e) {
		e.preventDefault();

		//get single feed
		var $feed = $(this);

		//get the specs for this feed
		var feedcode = $feed.data('feedcode');
		var filters = $feed.data('filters');
		var id = $feed.data('id');
		var content = $feed.data('content');
		var from = $('#isv-from-' + feedcode).val();
		var until = $('#isv-until-' + feedcode).val();

		isv_get_feed(feedcode, filters, id, content, from, until);

		return false;
	}

	function isv_book_btn_clicked(e) {

		e.preventDefault();

		$button = $(this);

		$button.addClass('isv-loading');
		$('body').addClass('isv-loading');

		//assemble the ajax_url from the buttons specs
		var ajax_url = window.isv_params.bookingspage_endpoint_uri + '?planitem&key=' + $button.data('key') + '&feedcode=' + $button.data('feedcode') + '&url=' + document.location.href;

		$.ajax({
			url: ajax_url,
			method: 'GET',
			dataType: 'jsonp',
			jsonpCallback: 'planResult'
		});

		return false;
	}

});

function planResult(response) {

	jQuery('body').removeClass('isv-loading');

	var response_obj = JSON.parse(response);

	//log everything we got back from the server
	console.log(response_obj);

	if (response_obj.result === 1) {

		//planning was successfull
		//redirect to basketpage
		document.location = response_obj.basketurl;

	} else {

		//planning not successfull
		//inform customer
		alert(response_obj.description);
	}
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImlzdi1zY3JpcHRzLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoic2NyaXB0LmpzIiwic291cmNlc0NvbnRlbnQiOlsialF1ZXJ5KGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoJCkge1xuXG5cdC8vZ2V0IGFsbCBmZWVkcyBmcm9tIHRoZSBET01cblx0dmFyICRmZWVkcyA9ICQoJy5pc3YtZmVlZCcpO1xuXG5cdC8vSWYgYW55IGZlZWRzIHdlcmUgZm91bmQuLi5cblx0aWYgKCRmZWVkcy5sZW5ndGggPiAwKSB7XG5cblx0XHQvL2xvb3AgdGhyb3VnaCBhbGwgdGhlIGZvdW5kIGZlZWRzXG5cdFx0JGZlZWRzLmVhY2goZnVuY3Rpb24gKCkge1xuXG5cdFx0XHQvL2dldCBzaW5nbGUgZmVlZFxuXHRcdFx0dmFyICRmZWVkID0gJCh0aGlzKTtcblxuXHRcdFx0Ly9nZXQgdGhlIHNwZWNzIGZvciB0aGlzIGZlZWRcblx0XHRcdHZhciBmZWVkY29kZSA9ICRmZWVkLmRhdGEoJ2ZlZWRjb2RlJyk7XG5cdFx0XHR2YXIgZmlsdGVycyA9ICRmZWVkLmRhdGEoJ2ZpbHRlcnMnKTtcblx0XHRcdHZhciBjb250ZW50ID0gJGZlZWQuZGF0YSgnY29udGVudCcpO1xuXHRcdFx0dmFyIGlkID0gJGZlZWQuYXR0cignaWQnKTtcblxuXHRcdFx0aXN2X2dldF9mZWVkKGZlZWRjb2RlLCBmaWx0ZXJzLCBpZCwgY29udGVudCwgJycsICcnICk7XG5cblx0XHR9KTtcblx0fVxuXG5cdGZ1bmN0aW9uIGlzdl9nZXRfZmVlZChmZWVkY29kZSwgZmlsdGVycywgaWQsIGNvbnRlbnQsIGZyb20sIHVudGlsKSB7XG5cblx0XHQvL3N0YXJ0IHRpbWluZ1xuXHRcdHZhciBzdGFydCA9IHBlcmZvcm1hbmNlLm5vdygpO1xuXG5cdFx0JCgnIycgKyBpZCkuaHRtbCh3aW5kb3cuaXN2X3BhcmFtcy5sb2FkZXIpO1xuXG5cdFx0Ly9wZXJmb3JtIGEgc2VydmVyLXJlcXVlc3QgdG8gZ2V0IHRoZSBmZWVkLUhUTUxcblx0XHQkLmFqYXgoe1xuXHRcdFx0dXJsOiB3aW5kb3cuaXN2X3BhcmFtcy5hamF4X3VybCxcblx0XHRcdG1ldGhvZDogJ1BPU1QnLFxuXHRcdFx0ZGF0YToge1xuXHRcdFx0XHQnYWN0aW9uJzogJ2lzdl9nZXRfZmVlZCcsXG5cdFx0XHRcdCdmZWVkY29kZSc6IGZlZWRjb2RlLFxuXHRcdFx0XHQnZmlsdGVycyc6IGZpbHRlcnMsXG5cdFx0XHRcdCdpZCc6IGlkLFxuXHRcdFx0XHQnY29udGVudCcgOiBjb250ZW50LFxuXHRcdFx0XHQnZnJvbSc6IGZyb20sXG5cdFx0XHRcdCd1bnRpbCc6IHVudGlsLFxuXHRcdFx0XHQnc3RhcnQnOiBzdGFydCxcblx0XHRcdFx0J2RlYnVnJzogdHJ1ZVxuXHRcdFx0fSxcblx0XHRcdGRhdGFUeXBlOiAnanNvbicsXG5cdFx0XHRzdWNjZXNzOiBpc3ZfZ2V0X2ZlZWRfZG9uZSxcblx0XHR9KTtcblx0fVxuXG5cdGZ1bmN0aW9uIGlzdl9nZXRfZmVlZF9kb25lKHJlc3BvbnNlKSB7XG5cblx0XHQvL2xvZyBldmVyeXRoaW5nIHdlIGdvdCBiYWNrIGZyb20gdGhlIHNlcnZlclxuXHRcdGNvbnNvbGUubG9nKHJlc3BvbnNlKTtcblxuXHRcdC8vYWRkIGh0bWwtcmVzdWx0IHRvIERPTVxuXHRcdGlmIChyZXNwb25zZS5kZWJ1ZyA9PT0gdHJ1ZSkge1xuXHRcdFx0JCgnIycgKyByZXNwb25zZS5pZCkuaHRtbCgnPHByZT4nICsgcmVzcG9uc2UuaHRtbCArICc8L3ByZT4nKTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0JCgnIycgKyByZXNwb25zZS5pZCkuaHRtbChyZXNwb25zZS5odG1sKTtcblx0XHR9XG5cblx0XHQvL2VuZCB0aW1pbmdcblx0XHR2YXIgZW5kID0gcGVyZm9ybWFuY2Uubm93KCk7XG5cblx0XHRjb25zb2xlLmxvZygnQUpBWCBjYWxsIFwiaXN2X2dldF9mZWVkXCIgdG9vayAnICsgTWF0aC5mbG9vcihlbmQgLSByZXNwb25zZS5zdGFydCkgKyAnIG1pbGlzZWNvbmRzLicpO1xuXG5cdFx0aXN2X2JpbmRfY3RhcygpO1xuXHR9XG5cblx0ZnVuY3Rpb24gaXN2X2JpbmRfY3RhcygpIHtcblxuXHRcdC8vdW5iaW5kIGFuZCByZWJpbmQgdGhlIGNsaWNrLWV2ZW50IG9uIG91ciBib29raW5nLWJ1dHRvbnNcblx0XHQkKCcuaXN2LWJvb2stYnV0dG9uJykudW5iaW5kKCdjbGljaycpLm9uKCdjbGljaycsIGlzdl9ib29rX2J0bl9jbGlja2VkKTtcblxuXHRcdC8vdW5iaW5kIGFuZCByZWJpbmQgdGhlIGNsaWNrLWV2ZW50IG9uIG91ciBib29raW5nLWJ1dHRvbnNcblx0XHQkKCcuaXN2LWJ1dHRvbi1mZXRjaC1maWx0ZXJlZCcpLnVuYmluZCgnY2xpY2snKS5vbignY2xpY2snLCBpc3ZfZmlsdGVyX2J0bl9jbGlja2VkKTtcblxuXHRcdC8vaW5pdGlhbHplIHRoZSBkYXRlcGlja2Vyc1xuXHRcdCQoJy5pc3YtZGF0ZXBpY2tlcicpLmRhdGVwaWNrZXIoe1xuXHRcdFx0bWluRGF0ZTogbmV3IERhdGUoKSxcblx0XHRcdGRhdGVGb3JtYXQ6ICdkZC1tbS15eSdcblx0XHR9KTtcblx0fVxuXG5cdGZ1bmN0aW9uIGlzdl9maWx0ZXJfYnRuX2NsaWNrZWQoZSkge1xuXHRcdGUucHJldmVudERlZmF1bHQoKTtcblxuXHRcdC8vZ2V0IHNpbmdsZSBmZWVkXG5cdFx0dmFyICRmZWVkID0gJCh0aGlzKTtcblxuXHRcdC8vZ2V0IHRoZSBzcGVjcyBmb3IgdGhpcyBmZWVkXG5cdFx0dmFyIGZlZWRjb2RlID0gJGZlZWQuZGF0YSgnZmVlZGNvZGUnKTtcblx0XHR2YXIgZmlsdGVycyA9ICRmZWVkLmRhdGEoJ2ZpbHRlcnMnKTtcblx0XHR2YXIgaWQgPSAkZmVlZC5kYXRhKCdpZCcpO1xuXHRcdHZhciBjb250ZW50ID0gJGZlZWQuZGF0YSgnY29udGVudCcpO1xuXHRcdHZhciBmcm9tID0gJCgnI2lzdi1mcm9tLScgKyBmZWVkY29kZSkudmFsKCk7XG5cdFx0dmFyIHVudGlsID0gJCgnI2lzdi11bnRpbC0nICsgZmVlZGNvZGUpLnZhbCgpO1xuXG5cdFx0aXN2X2dldF9mZWVkKGZlZWRjb2RlLCBmaWx0ZXJzLCBpZCwgY29udGVudCwgZnJvbSwgdW50aWwpO1xuXG5cdFx0cmV0dXJuIGZhbHNlO1xuXHR9XG5cblx0ZnVuY3Rpb24gaXN2X2Jvb2tfYnRuX2NsaWNrZWQoZSkge1xuXG5cdFx0ZS5wcmV2ZW50RGVmYXVsdCgpO1xuXG5cdFx0JGJ1dHRvbiA9ICQodGhpcyk7XG5cblx0XHQkYnV0dG9uLmFkZENsYXNzKCdpc3YtbG9hZGluZycpO1xuXHRcdCQoJ2JvZHknKS5hZGRDbGFzcygnaXN2LWxvYWRpbmcnKTtcblxuXHRcdC8vYXNzZW1ibGUgdGhlIGFqYXhfdXJsIGZyb20gdGhlIGJ1dHRvbnMgc3BlY3Ncblx0XHR2YXIgYWpheF91cmwgPSB3aW5kb3cuaXN2X3BhcmFtcy5ib29raW5nc3BhZ2VfZW5kcG9pbnRfdXJpICsgJz9wbGFuaXRlbSZrZXk9JyArICRidXR0b24uZGF0YSgna2V5JykgKyAnJmZlZWRjb2RlPScgKyAkYnV0dG9uLmRhdGEoJ2ZlZWRjb2RlJykgKyAnJnVybD0nICsgZG9jdW1lbnQubG9jYXRpb24uaHJlZjtcblxuXHRcdCQuYWpheCh7XG5cdFx0XHR1cmw6IGFqYXhfdXJsLFxuXHRcdFx0bWV0aG9kOiAnR0VUJyxcblx0XHRcdGRhdGFUeXBlOiAnanNvbnAnLFxuXHRcdFx0anNvbnBDYWxsYmFjazogJ3BsYW5SZXN1bHQnXG5cdFx0fSk7XG5cblx0XHRyZXR1cm4gZmFsc2U7XG5cdH1cblxufSk7XG5cbmZ1bmN0aW9uIHBsYW5SZXN1bHQocmVzcG9uc2UpIHtcblxuXHRqUXVlcnkoJ2JvZHknKS5yZW1vdmVDbGFzcygnaXN2LWxvYWRpbmcnKTtcblxuXHR2YXIgcmVzcG9uc2Vfb2JqID0gSlNPTi5wYXJzZShyZXNwb25zZSk7XG5cblx0Ly9sb2cgZXZlcnl0aGluZyB3ZSBnb3QgYmFjayBmcm9tIHRoZSBzZXJ2ZXJcblx0Y29uc29sZS5sb2cocmVzcG9uc2Vfb2JqKTtcblxuXHRpZiAocmVzcG9uc2Vfb2JqLnJlc3VsdCA9PT0gMSkge1xuXG5cdFx0Ly9wbGFubmluZyB3YXMgc3VjY2Vzc2Z1bGxcblx0XHQvL3JlZGlyZWN0IHRvIGJhc2tldHBhZ2Vcblx0XHRkb2N1bWVudC5sb2NhdGlvbiA9IHJlc3BvbnNlX29iai5iYXNrZXR1cmw7XG5cblx0fSBlbHNlIHtcblxuXHRcdC8vcGxhbm5pbmcgbm90IHN1Y2Nlc3NmdWxsXG5cdFx0Ly9pbmZvcm0gY3VzdG9tZXJcblx0XHRhbGVydChyZXNwb25zZV9vYmouZGVzY3JpcHRpb24pO1xuXHR9XG59Il19
