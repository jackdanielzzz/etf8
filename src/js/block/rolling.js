$(() => {
    (function($){

        $.fn.makeRollingCaption = function(options) {

            options = $.extend({
                speed: 20
            }, options);

            this.each(function(index, container){

                var methods = {

                    initialize: function() {

                        this.container = $(container).first();
                        this.content = this.container.find('.b-rolling-caption__content');
                        this.wrap = this.container.find('.b-rolling-caption__wrap');

                        this.setWidth();
                        this.wrap.show();

                        var item = this.content.find('span').first();

                        this.start(item);

                        $(window).on('resize', $.proxy(function(){
                            this.content.stop(true, true).css('left', 0);
                            if ( this.timer ) clearTimeout(this.timer); else this.wrap.hide();
                            this.timer = setTimeout($.proxy(this.onStopResize, this), 300);

                        }, this));

                    },

                    setWidth: function() {
                        this.wrap.width(this.container.parent().width());
                    },

                    onStopResize: function() {

                        this.setWidth();

                        this.timer = null;
                        this.wrap.show();
                        this.start(this.content.find('span').first());
                    },

                    start: function(item) {
                        if ( item.length ) {

                            this.content.animate({
                                left: '-=' + item.width() + 'px'
                            }, item.width() * options.speed, 'linear', $.proxy(function(){
                                var next = item.next();
                                if ( !next ) next = this.content.find('span').first();
                                this.content.append(item);
                                if ( !this.timer ) {
                                    this.content.css('left', 0);
                                    this.start(next);
                                }

                            }, this));

                        }
                    }

                };

                methods.initialize();

            });

        };

    })(jQuery);


    $(function(){
        $('#rolling-caption').makeRollingCaption({ speed: 20 });
    });

});