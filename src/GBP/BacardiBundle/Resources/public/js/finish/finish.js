var Finish = {

    vars: {
        effectsOptions: {
            'desaturate': {
                desaturate: true
            },
            'sepia': {
                sepia: true
            }
        }
    },

    elems: {},

    methods: {

        changeEffect: function () {
            Finish.elems.$effectBtn.removeClass('active');
            $(this).addClass('active');

            var effect = Finish.vars.effectsOptions[$(this).attr('data-effect')];
            new VintageJS(
                Finish.elems.$resultPhotoImg[0],
                null,
                effect
            );
        },

        changeFrame: function () {
            var id = $(this).parent().index();

            Finish.elems.$resultFrameImg.removeClass('active');
            Finish.elems.$resultFrameImg.eq(id).addClass('active');
        },

        getPhotoUrl: function () {
            var canvas  = $('<canvas></canvas>')[0],
                photo   = Finish.elems.$resultPhotoImg,
                frame   = Finish.elems.$resultFrame.find('img:visible');

            canvas.width  = 384;
            canvas.height = 567;

            canvas.getContext('2d').drawImage(photo[0], -5, -22);
            canvas.getContext('2d').drawImage(frame[0], 0, 0);

            return canvas.toDataURL();
        },

        finish: function (isSaveOnPk) {
            Finish.elems.$imgInput.val(this.getPhotoUrl());
            Finish.elems.$saveTypeInput.val(isSaveOnPk);
            Finish.elems.$form.submit();
        }

    },

    setMethods: function () {

        this.elems.$effectBtn.on('click', function () {
            if ($(this).hasClass('active')) return;
            Finish.methods.changeEffect.bind(this)();
        });

        this.elems.$frameBtn.on('click', function () {
            if ($(this).hasClass('active')) return;
            Finish.methods.changeFrame.bind(this)();
        });

        this.elems.$save.on('click', function () {
        	Finish.methods.finish(1);
        });

        this.elems.$send.on('click', function () {
            Finish.methods.finish(0);
        });

    },

    init: function () {
        this.elems = {
            $effectBtn:         $('.js-b-finish__style-btn'),
            $frameBtn:          $('.js-b-finish__frame'),

            $save:              $('#js-b-finish__save'),
            $send:              $('#js-b-finish__send'),

            $result:            $('#js-b-finish__result'),
            $resultFrame:       $('#js-b-finish__result__frame'),
            $resultFrameImg:    $('.js-b-finish__result__frame__img'),
            $resultPhoto:       $('#js-b-finish__result__photo'),
            $resultPhotoImg:    $('#js-b-finish__result__photo__img'),

            $form:              $('#js-b-finish__form'),
            $imgInput:          $('#js-img-input'),
            $saveTypeInput:     $('#js-save-type-input')
        };

        this.setMethods();
    }

};