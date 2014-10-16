jQuery( document ).ready(function( $ ) {
	var $contributor_search_button = $('.contributor-search button');

	$contributor_search_button.css( 'border-color', $contributor_search_button.css( 'background-color' ) );

	$contributor_search_button.on('hover blur', function() {
		$contributor_search_button.css( 'border-color', $contributor_search_button.css( 'background-color' ) );
	});

	$contributor_search_button.on('click', function(evt) {
		evt.preventDefault();

		$.getJSON( 'http://wpcentral.io/api/contributors/' + $('.contributor-search input').val(), function( data ) {
			console.log(data);
		}).error(function(event, jqxhr, exception) {
			console.log('oops');
		});
	});
});