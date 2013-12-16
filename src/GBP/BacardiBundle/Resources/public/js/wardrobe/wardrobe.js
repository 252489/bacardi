var Wardrobe = {

	vars: {
		emptyWear: 1,
		popupCategory: null,
		defaultPhotoSlots: {},
		photoSlots: {}
	},

	elems: {},

	methods: {

        openBlock: function () {
	        Wardrobe.vars.popupCategory = $(this).attr('data-cat');

            Wardrobe.elems.$popupTitle.html($(Wardrobe.elems.blockTitle, this).html());
            Wardrobe.elems.$popupContainer.html($(Wardrobe.elems.blockItems, this).html());
	        Wardrobe.elems.$popup.show();

	        Wardrobe.vars.scroller.update();
        },

		selectItem: function () {
			var $newImg     = $(Wardrobe.elems.popupItemHidden, this).find('img').clone(),
				category    = $newImg.attr('data-cat'),
				$prevImg    = Wardrobe.vars.photoSlots[category];

            if ($prevImg) {
                $prevImg.replaceWith($newImg);
            } else {
                Wardrobe.elems.$photo.append($newImg);
            }

            Wardrobe.vars.photoSlots[category] = $newImg;
			$(Wardrobe.elems.unwearItem +'[data-cat='+ category +']').addClass('active');
		},

		setDefaultSlots: function () {
			var $images = $('img', Wardrobe.elems.$photo);

			$images.each(function () {
				Wardrobe.vars.photoSlots[$(this).attr('data-cat')] = $(this);
			});
		},



		unwearItem: function () {
			var category    = $(this).attr('data-cat'),
				$slotImg    = Wardrobe.vars.defaultPhotoSlots[category],
				$photoImg   = Wardrobe.elems.$photo.find('img[data-cat='+ category +']');

			if ($slotImg) {
				$photoImg.replaceWith($slotImg.clone());
			} else {
				$photoImg.remove();
			}

			$(this).removeClass('active');
		},



		getSortedImages: function () {
			var k, m,
				images          = {},
				imagesSorted    = {},
				$images         = $('img', Wardrobe.elems.$photo);

			$images.each(function () {
				images[$(this).css('z-index')] = $(this);
			});

			var keys    = Object.keys(images),
				len     = keys.length;

			keys.sort();

			for (k = 0; k < len; k++) {
				m = keys[k];
				imagesSorted[m] = images[m];
			}

			return imagesSorted;
		},

		getPhotoUrl: function () {
			var i,
				images  = this.getSortedImages(),
				canvas  = $('<canvas></canvas>')[0];

			canvas.width  = 265;
			canvas.height = 570;

			for (i in images) {
				canvas.getContext('2d').drawImage(images[i][0], 0, 0);
			}

			return canvas.toDataURL();
		},

		finish: function () {
			Wardrobe.elems.$imgInput.val(this.getPhotoUrl());
            Wardrobe.elems.$form.submit();
		}

	},

	setMethods: function () {

        this.elems.$block.on('click', function (e) {
            e.stopPropagation();
            Wardrobe.methods.openBlock.bind(this)();
        });

		this.elems.$unwearItem.on('click', function (e) {
            e.stopPropagation();
            Wardrobe.methods.unwearItem.bind(this)();
        });

        this.elems.$popup.draggable({
            containment: this.elems.$wrapper,
            scroll: false
        });

        this.elems.$popup.on('click', function (e) {
            e.stopPropagation();
        });

		this.elems.$popupBar.on({
			mouseenter: function () {
				Wardrobe.elems.$popup.draggable('option', 'disabled', true);
			},
			mouseleave: function () {
				Wardrobe.elems.$popup.draggable('option', 'disabled', false);
			}
		});

		this.elems.$popup.on('click', Wardrobe.elems.popupItem, function () {
			Wardrobe.methods.selectItem.bind(this)();
		});

		this.elems.$ready.on('click', function () {
			Wardrobe.methods.finish();
		});



        $(document).on('click.closePopup', function () {
            if (Wardrobe.elems.$popup.is(':visible')) {
	            Wardrobe.elems.$popup.hide();
            }
        });
	},

	init: function () {
		this.elems = {
            $wrapper:           $('#js-b-wardrobe'),
			$block:             $('.js-b-wardrobe__block'),
            blockItems:         '.js-b-wardrobe__items',
			blockTitle:         '.js-b-wardrobe__block__title',

            $popup:             $('#js-b-wardrobe__popup'),
            $popupTitle:        $('#js-b-wardrobe__popup__title'),
			$popupBar:          $('#js-b-wardrobe__popup__bar'),
            $popupContainer:    $('#js-b-wardrobe__popup__container'),
			popupItem:          '.js-b-wardrobe__popup__item',
			popupItemHidden:    '.js-b-wardrobe__popup__item_hidden',

			$unwearItem:        $('.js-b-wardrobe__block__unwear'),
			unwearItem:         '.js-b-wardrobe__block__unwear',

			$photo:             $('#js-b-wardrobe__photo'),
			$ready:             $('#js-b-wardrobe__ready'),

            $form:              $('#js-b-wardrobe__form'),
            $imgInput:          $('#js-img-input')
		};

		this.vars.scroller = new Scroller({
			root:           '#js-b-wardrobe__popup',
			scroller:       '#js-b-wardrobe__popup__container',
			track:          '#js-b-wardrobe__popup__track',
			bar:            '#js-b-wardrobe__popup__bar'
		});

		this.setMethods();
		this.methods.setDefaultSlots();
	}

};