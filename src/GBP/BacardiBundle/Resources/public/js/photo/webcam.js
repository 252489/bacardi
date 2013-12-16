var Webcam = {

	vars: {
		options: {
			'audio': false,
			'video': true,

			el: 'js-b-output__webcam__container',

			extern: null,
			append: true,

			width: 634,
			height: 477,

			mode: 'callback',

			swffile: 'data/fallback/jscam_canvas_only.swf',

			quality: 100,

			onCapture: function () {
				window.webcam.save();
			},

			onSave: function (data) {
				console.log('saved');
			},

			onLoad: function () {
				console.log('loaded');
			}
		},

		loading: 0
	},

	elems: {},

	methods: {

		success: function (stream) {
			Webcam.vars.loading = 0;
			if (Webcam.vars.options.context === 'webrtc') {
				Page.vars.output = 'Webcam';
				Page.elems.$cropBtn.show();

				var video = Webcam.vars.options.videoEl;
				var vendorURL = window.URL || window.webkitURL;
				video.src = vendorURL ? vendorURL.createObjectURL(stream) : stream;

				video.onerror = function () {
					stream.stop();
					streamError();
				};
			}
		},

		deviceError: function (error) {
			Webcam.vars.loading = 0;
			ErrorPopup.methods.show('\
				Ошибка видеокамеры. Возможные причины: <br>\
				<ul>\
					<li>Камера не подключена к компьютеру</li>\
					<li>Нет разрешения на доступ к вашей видеокамере</li>\
				</ul>\
			');
		},




		getCroppedImg: function () {
			if (Webcam.vars.options.context === 'webrtc') {
				Page.elems.$cropBtn.hide();

				var img     = new Image(),
					canvas  = $('<canvas></canvas>')[0],
					video   = $('video')[0];

				canvas.width = 370;
				canvas.height = 477;
				canvas.getContext('2d').drawImage(video, -132, 0);

				var dataURL = canvas.toDataURL();

				img.onload = function () {
					Page.elems.$imgInput.val(dataURL);
					Page.elems.$cropResult.html(this);
					Page.elems.$wrapper.addClass('crop_result_added');
                    Page.elems.$wrapper.removeClass('output_photo_added');

					Page.methods.checkSexAndImgChosen();
				};

				img.src = dataURL;

			} else{
				ErrorPopup.methods.show('Не поддерживается функционал браузера. Воспользуйтесь загрузкой фото с ПК');
			}
		},

		initWebcam: function () {
			if (Page.vars.output == 'Webcam') {
				Page.elems.$imgInput.val('');
				Page.elems.$cropResult.html('');
				Page.elems.$wrapper.removeClass('crop_result_added');
                Page.elems.$wrapper.removeClass('output_photo_added');
				Page.elems.$cropBtn.show();
			} else if (!Webcam.vars.loading) {
				Webcam.vars.loading = 1;

				Page.elems.$output.html('\
				    <div class="b-output__photo__mask" id="js-b-output__photo__mask"></div>\
					<div class="b-output__webcam__container" id="js-b-output__webcam__container"></div>\
				');

                Webcam.elems.$mask      = $('#js-b-output__photo__mask');
				Webcam.elems.$output    = $('#js-b-output__webcam__container');
				Webcam.vars.webcam      = getUserMedia(Webcam.vars.options, Webcam.methods.success, Webcam.methods.deviceError);

                Page.elems.$wrapper.addClass('output_photo_added');
			}
		}

	},

	setMethods: function () {

		this.elems.$loadBtn.on('click', function () {
			Webcam.methods.initWebcam();
		});

	},

	init: function () {
        var browser = EnvDetect.vars.browserData.browser;

        if (
            browser.family == 'Chrome' && browser.major >= 21   ||
            browser.family == 'Opera' && browser.major >= 12    ||
            browser.family == 'Firefox' && browser.major >= 24
        ) {

            this.elems = {
                $loadBtn: $('#js-b-photo__webcam-btn')
            };

            window.webcam = this.vars.options;

            this.elems.$loadBtn.show();
            this.setMethods();

        } else {
            console.error('не поддерживается камера');
        }

	}

};