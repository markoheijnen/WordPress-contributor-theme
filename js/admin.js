jQuery( document ).ready(function( $ ) {
	var $contributor_search_button = $('.contributor-search button');

	var search_view = new ContributorView({ el: $("#contributor") });
});

( function($) {
	ContributorView = wp.Backbone.View.extend({
		username: false,
		search_button: $('.contributor-search button'),
		template: wp.template('contributor_profile'),
		block_template: wp.template('contributor_block'),

		initialize: function(){
			var self = this;

			self.search_button.css( 'border-color', self.search_button.css( 'background-color' ) );

			self.search_button.on('mouseenter mouseleave blur', function() {
				self.search_button.css( 'border-color', self.search_button.css( 'background-color' ) );
			});
		},
		render: function() {
			var self = this;
			var options;

			if ( this.prepare && this.username ) {
				options = this.prepare();
			}

			this.views.detach();

			if ( this.template ) {
				options = options || {};
				this.trigger( 'prepare', options );

				var html = this.template( options );

				$.each( contributor_template_blocks, function( key, block_info ) {
					html += self.block_template( block_info );
				});

				this.$el.find('#contributions').html( html );
			}

			this.views.render();
			return this;
		},
		events: {
			"click .contributor-search button": "doSearch"  
		},
		doSearch: function( event ){
			var self = this;

			self.username = $('.contributor-search input').val();

			$.getJSON( 'http://wpcentral.io/api/contributors/' + self.username, function( data ) {
				self.options = data;
				self.render();
			}).error(function(event, jqxhr, exception) {
				self.username = false;
				self.render();
			});
		}
	});
})( jQuery );