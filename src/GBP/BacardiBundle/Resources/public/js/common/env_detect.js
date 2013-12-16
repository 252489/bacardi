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
		}

	},

    init: function () {

        EnvDetect.vars.browserData = detect.parse(navigator.userAgent);
	    EnvDetect.methods.setBrowserVersionToHtml();

    }

};