var Finish = {

	vars: {},

	elems: {},

	methods: {

        setSepiaEffect: function () {

        },

        RGBtoGRAYSCALE: function (r, g, b) {
            return window.parseInt((0.2125 * r) + (0.7154 * g) + (0.0721 * b), 10);
        },

        setDesaturateEffect: function () {
            var imgData = ctx.getImageData(0, 0, dimension, dimension);

            for (y = 0; y < dimension; y++) {
                for (x = 0; x < dimension; x++) {
                    i = (y * dimension + x) * 4;

                    // Apply Monochrome level across all channels:
                    imgData.data[i] = imgData.data[i + 1] = imgData.data[i + 2] = RGBtoGRAYSCALE(imgData.data[i], imgData.data[i + 1], imgData.data[i + 2]);
                }
            }

            return imgData;
        }

	},

	setMethods: function () {



	},

	init: function () {
		this.elems = {
            $resultFrame: $('#js-b-finish__result__frame'),
            $resultPhoto: $('#js-b-finish__result__photo')
		};

		this.setMethods();
	}

};