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
        }

    },

    setMethods: function () {

        this.elems.$effectBtn.on('click', function () {
            if ($(this).hasClass('active')) return;
            Finish.methods.changeEffect.bind(this)();
        });

    },

    init: function () {
        this.elems = {
            $effectBtn:         $('.js-b-finish__style-btn'),

            $resultFrame:       $('#js-b-finish__result__frame'),
            $resultPhoto:       $('#js-b-finish__result__photo'),
            $resultPhotoImg:    $('#js-b-finish__result__photo__img')
        };

        this.setMethods();
    }

};