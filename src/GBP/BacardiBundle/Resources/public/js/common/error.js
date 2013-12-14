var ErrorPopup = {

	vars: {},

	elems: {},

	methods: {

		show: function (text) {
			console.log(text);

			Error.elems.$popup.html(text).show();
			Error.elems.$popup.css({
				marginLeft: -Error.elems.$popup.outerWidth() / 2,
				marginTop: -Error.elems.$popup.outerHeight() / 2
			}).addClass('active');
		},

		hide: function () {
			Error.elems.$popup.hide().removeClass('active');
		}

	},

	setMethods: function () {

		this.elems.$popup.on('click', function (e) {
			e.stopPropagation();
		});

		$(document).on('click.closeError', function () {
			Error.methods.hide();
		});

	},

	init: function () {
		this.elems = {
			$popup: $('#js-b-error-popup')
		};

		this.setMethods();
	}

};