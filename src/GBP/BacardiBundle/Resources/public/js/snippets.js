$.fn.toggleInput = function(options){
    var settings = $.extend({
        color: '#000',      // цвет плейсхолдера
        password: false,    // поддержка секьюра пароля
        onFocus: null,
        onBlur: null,
        onInit: null,
        onChange: null,
        onPaste: null,
        onDrop: null
    }, options);

    this.each(function(){
        var
            s = $(this),
            preVal = s.val(),
            color = s.css('color');

        function checkFn(fn){
            return settings[fn] && typeof settings[fn] == 'function';
        }

        if( checkFn('onInit') ) settings.onInit(s);

        s.on({
            focus: function(e){
                if( checkFn('onFocus') ) settings.onFocus(e, s);
                if(s.val() == preVal){
                    s.val('').css('color', settings.color ? settings.color : color);
                    if( settings.password ){
                        var passInput = s.clone(true);
                        passInput.attr('type','password');
                        s.replaceWith(passInput);
                        s = passInput;
                        s.focus();
                    }
                }
            },
            blur: function(e){
                if( checkFn('onBlur') ) settings.onBlur(e, s);
                if(s.val() == ''){
                    s.css('color', color);
                    if( settings.password ){
                        var passInput = s.clone(true);
                        passInput.attr('type','text');
                        s.replaceWith(passInput);
                        s = passInput;
                    }
                }
                s.val( s.val() == '' ? preVal : s.val() );
            },
            'change, keyup': function(e){
                if( checkFn('onChange') ) settings.onChange(e, s);
            },
            paste: function(e){
                if( checkFn('onPaste') ) settings.onPaste(e, s);
            },
            drop: function(e){
                e.dataTransfer = e.originalEvent.dataTransfer;
                var thisVal = $(this).val(),
                    newVal = e.dataTransfer.getData('text');

                setTimeout($.proxy(function() {
                    if( thisVal == preVal ){
                        s.val(newVal).css('color', settings.color ? settings.color : color).focus();
                    }

                    if( checkFn('onDrop') ) settings.onDrop(e, s);
                }, this), 0);
            }
        });

    });
};