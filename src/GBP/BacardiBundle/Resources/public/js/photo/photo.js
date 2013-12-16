var Photo = {

	vars: {
		zoomOutDisabled: 0,
		resizeDelta: 0.05,
		containerSize: [370, 472],
		imgSize: null
	},

	elems: {},

	methods: {

		getImgSize: function () {
			Photo.vars.imgSize = [
				Photo.elems.$img.width(),
				Photo.elems.$img.height()
			];
		},

		disableZoomOut: function () {
			Photo.vars.zoomOutDisabled = 1;
			Photo.elems.$zoomOut.addClass('disabled');
		},

		activeZoomOut: function () {
			Photo.vars.zoomOutDisabled = 0;
			Photo.elems.$zoomOut.removeClass('disabled');
		},

		zoomIn: function () {
			this.activeZoomOut();
			this.resize(1);
		},

		zoomOut: function () {
			if (Photo.vars.zoomOutDisabled) return;
			this.resize(-1);
		},

		resize: function (delta) {
			var _delta      = Math.abs(Photo.vars.resizeDelta + delta),
				newSize     = [Photo.vars.imgSize[0] * _delta,  Photo.vars.imgSize[1] * _delta];

			if (
				newSize[0] < Photo.vars.containerSize[0] ||
					newSize[1] < Photo.vars.containerSize[1]
				) {

				var newImgWidth = Photo.vars.containerSize[1] * Photo.vars.imgSize[0] / Photo.vars.imgSize[1];

				if (newImgWidth > Photo.vars.containerSize[0]) {
					newSize = [
						Photo.vars.containerSize[1] * Photo.vars.imgSize[0] / Photo.vars.imgSize[1],
						Photo.vars.containerSize[1]
					];
				} else {
					newSize = [
						Photo.vars.containerSize[0],
						Photo.vars.containerSize[0] * Photo.vars.imgSize[1] / Photo.vars.imgSize[0]
					];
				}

				this.disableZoomOut();
			}

			Photo.elems.$img.add(Photo.elems.$pseudoImg)
				.width(newSize[0])
				.height(newSize[1]);

			Photo.vars.imgSize = newSize;
		},

		setOverscroll: function () {
			Photo.elems.$imgPseudoContainer.overscroll({
				showThumbs: false
			}).on({
				'mousedown': function () {
					this.mousedown = 1;
				},
				'mouseup': function () {
					this.mousedown = 0;
				},
				'mousemove': function () {
					if (!this.mousedown) return;

					Photo.elems.$imgContainer
						.scrollLeft($(this).scrollLeft())
						.scrollTop($(this).scrollTop());
				}
			});
		},




		getCroppedImg: function () {
			var $img    = $('img', Photo.elems.$imgContainer),
				img     = new Image(),
				canvas  = $('<canvas></canvas>')[0],
				size = [
					$img.width(),
					$img.height()
				],
				offset  = [
					-Photo.elems.$imgContainer.scrollLeft(),
					-Photo.elems.$imgContainer.scrollTop()
				],

				video   = $('video')[0];

			canvas.width = 370;
			canvas.height = 477;
			canvas.getContext('2d').drawImage($img[0], offset[0], offset[1], size[0], size[1]);

			var dataURL = canvas.toDataURL();

			img.onload = function () {
				Page.elems.$imgInput.val(dataURL);
				Page.elems.$cropResult.html(this);
				Page.elems.$wrapper.addClass('crop_result_added');
				Page.elems.$wrapper.removeClass('output_photo_added');

				Page.methods.checkSexAndImgChosen();
			};

			img.src = dataURL;
		},

		loadImg: function (e) {
			var $img = loadImage(
				e.target.files[0],
				function (img) {
					$img = null;

					if(img.type === "error") {
						ErrorPopup.methods.show('Произошла ошибка при загрузке картинки');
					} else {
						Page.vars.output = 'Photo';

						Page.elems.$output.html('\
			                <div class="b-output__photo__container__wrapper">\
			                    <div class="b-output__photo__container" id="js-b-output__photo__pseudo-container"></div>\
			                </div>\
			                <div class="b-output__photo__mask" id="js-b-output__photo__mask"></div>\
			                <div class="b-output__photo__container" id="js-b-output__photo__container"></div>\
		                ');

						Photo.elems.$mask               = $('#js-b-output__photo__mask');
						Photo.elems.$imgContainer       = $('#js-b-output__photo__container');
						Photo.elems.$imgPseudoContainer = $('#js-b-output__photo__pseudo-container');
						Photo.elems.$img                = $(img);
						Photo.elems.$pseudoImg          = $('<div></div>');

						Photo.elems.$imgContainer.html(img);
						Photo.elems.$imgPseudoContainer.html(Photo.elems.$pseudoImg);

						Photo.methods.getImgSize();
						Photo.methods.resize(-1);

						Photo.methods.setOverscroll();

						Page.elems.$wrapper.addClass('output_photo_added');
					}
				},
				{
					maxWidth: 600
				}
			);
		}

	},

	setMethods: function () {

		jQuery.fn.overscroll.settings = {
			driftDecay: 1
		};

		this.elems.$zoomIn.on('click', function () {
			Photo.methods.zoomIn();
		});

		this.elems.$zoomOut.on('click', function () {
			Photo.methods.zoomOut();
		});

		this.elems.$loadImg.on('change', function (e) {
			Photo.methods.loadImg(e);
		});

	},

	init: function () {
		this.elems = {
			$loadImg:   $('#js-b-photo__load'),
			$zoomBtn:   $('.js-b-photo__img__zoom'),
			$zoomIn:    $('#js-b-photo__img__zoom_in'),
			$zoomOut:   $('#js-b-photo__img__zoom_out')
		};

		this.setMethods();
	}

};