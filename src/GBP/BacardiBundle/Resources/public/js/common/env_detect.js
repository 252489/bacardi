var EnvDetect = {

   vars: {
	   browserData: null
   },

	methods: {

		setBrowserVersionToHtml: function () {
			var htmlClass,
				browser = EnvDetect.vars.browserData.browser;

			if (!!navigator.userAgent.match(/Trident\/7\./)) {
				htmlClass = 'IE IE11';
			} else {
				htmlClass = browser.family == 'IE' ? '' : 'not-IE ';
				htmlClass += browser.family +' '+ browser.family + browser.major;
			}

			$('html').addClass(htmlClass);
		},

		checkWebCamSupport: function () {
			var browser = EnvDetect.vars.browserData.browser;

			if (
				browser.family == 'Chrome' && browser.major >= 21   ||
				browser.family == 'Opera' && browser.major >= 12    ||
				browser.family == 'Firefox' && browser.major >= 24
			) {
				Webcam.init();
			} else {
				console.error('не поддерживается камера');
			}
		}

	},

    init: function () {

        EnvDetect.vars.browserData = detect.parse(navigator.userAgent);
	    EnvDetect.methods.setBrowserVersionToHtml();
	    EnvDetect.methods.checkWebCamSupport();

    }

};