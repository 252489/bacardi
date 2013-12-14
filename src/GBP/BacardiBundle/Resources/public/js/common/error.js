var ErrorPopup = {

	vars: {},

	elems: {},

	methods: {

		show: function (text) {
			console.log(text);

			ErrorPopup.elems.$popup.html(text).show();
			ErrorPopup.elems.$popup.css({
				marginLeft: -ErrorPopup.elems.$popup.outerWidth() / 2,
				marginTop: -ErrorPopup.elems.$popup.outerHeight() / 2
			}).addClass('active');
		},

		hide: function () {
			ErrorPopup.elems.$popup.hide().removeClass('active');
		}

	},

	setMethods: function () {

		this.elems.$popup.on('click', function (e) {
			e.stopPropagation();
		});

		$(document).on('click.closeError', function () {
			ErrorPopup.methods.hide();
		});

	},

	init: function () {
		this.elems = {
			$popup: $('#js-b-error-popup')
		};

		this.setMethods();
	}

};