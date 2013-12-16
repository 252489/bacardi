var Page = {

	vars: {
		output: null
	},

	elems: {},

	methods: {

		selectSex: function () {
			if ($(this).hasClass('active')) return;

			Page.elems.$sexBtn.removeClass('active');
			$(this).addClass('active');

			Page.elems.$sexInput.val($(this).attr('data-sex'));
			Page.methods.checkSexAndImgChosen();
		},

		getCroppedImg: function () {
			if (!Page.vars.output) return;
			window[Page.vars.output].methods.getCroppedImg();
		},

		checkSexAndImgChosen: function () {
			if (Page.elems.$sexInput.val() && Page.elems.$imgInput.val()) {
				Page.elems.$submitBtn.show();
			}
		}

	},

	setMethods: function () {

		this.elems.$sexBtn.on('click', function () {
			Page.methods.selectSex.bind(this)();
		});

		this.elems.$cropBtn.on('click', function () {
			Page.methods.getCroppedImg();
		});

	},

	init: function () {
		this.elems = {
			$wrapper:       $('#js-b-photo'),
			$output:        $('#js-b-output'),
			$cropBtn:       $('#js-b-photo__crop-btn'),
			$cropResult:    $('#js-b-crop__result'),

			$sexBtn:        $('.js-b-photo__sex'),
			$sexInput:      $('#js-sex-input'),
			$imgInput:      $('#js-img-input'),

			$submitBtn:     $('#js-b-photo__ready-btn')
		};

		this.setMethods();
	}

};