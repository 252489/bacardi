var MainPage = {

	vars: {},

	elems: {},

	methods: {



	},

	setMethods: function () {

        this.elems.$textField.toggleInput();

	},

	init: function () {
		this.elems = {
            $textField: $('.js-b-form__field')
		};

		this.setMethods();
	}

};