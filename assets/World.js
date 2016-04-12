jQuery(function($) {
    //Жутковатый god-object, согласен.
    var World = {

        loaded: false,

        url: '',

        init: function() {
            var me = this;

            me.$el = $('.world-main');
            me.$tbody = me.$el.find('tbody');
            me.$thead = me.$el.find('thead');
            me.$sortMethod = $('.sort');
            me.$sort = $('#sort-by-ajax');
            me.$loading = $('#loading');

            me.binds();
        },

        binds: function() {
            var me = this;
            me.$thead.on('click', 'th', me._onHeaderClick.bind(me));
        },

        sort: function($column, asc) {
            var me = this;

            if (me._isSortAjax()) {
                /* Я понимаю, что стоит передавать не text, а хранить где-нибудь его имя.
                 Или номер. Или что-нибудь такое. Но в рамках задачи и так прокатит :) */
                me._load($column.text(), asc).then(me.show.bind(me));

            } else {

                me._simpleSort($column, asc);

            }
        },

        show: function(data) {
            if (!$.isArray(data)) {
                try {
                    data = $.parseJSON(data);
                } catch (e) {
                    data = [];
                }
            }

            if ($.isEmptyObject(data)) {
                alert('Ошибка загрузки!');
                return;
            }

            var me = this, l = data.length, i;

            me.$tbody.empty();

            if (!me.loaded) {
                me._fillHeader(data[0]);
                me.loaded = true;
            }

            for (i = 0; i < l; i++) {
                me._addRegion(data[i]);
            }
        },

        _fillHeader: function(region) {
            var $tr = $('<tr>');

            for (var field in region) {
                if (!region.hasOwnProperty(field)) {
                    continue;
                }

                $('<th>').text(field).appendTo($tr);
            }

            $tr.appendTo(this.$thead);
        },

        _addRegion: function(region) {
            var $tr = $('<tr>');

            for (var field in region) {
                if (!region.hasOwnProperty(field)) {
                    continue;
                }

                $('<td>').text(region[field] === null ? '' : region[field]).appendTo($tr);
            }

            $tr.appendTo(this.$tbody);
        },

        _simpleSort: function($column, asc) {

            var me = this,
                index = $column.index(),
                $tr = me.$tbody.children('tr'),
                toSort = $tr.map(function(i) {
                    var value = $(this).children().eq(index).text();

                    if (value == +value) {
                        value = +value;
                    }

                    return {
                        value: value,
                        num: i
                    };
                }), i, l = toSort.length;

            toSort.sort(function(a, b) {
                var first =  a.value > b.value;
                return (first && asc || !first && !asc) ? 1 : -1;
            });

            $tr.appendTo($('<div>'));

            for (i = 0; i < l; i++) {
                $tr.eq(toSort[i].num).appendTo(me.$tbody);
            }
        },

        _load: function(field, asc) {
            var me = this;

            me.$loading.show();

            return $.post(me.url, {
                'orderBy': field,
                'orderType': asc ? 'ASC' : 'DESC'
            }).then(function(data) {
                me.$loading.hide();
                return data;
            });
        },

        _isSortAjax: function() {
            return this.$sort.is(':checked');
        },

        _onHeaderClick: function(e) {
            var me = this, $target = $(e.target), asc;

            if ($target.hasClass('desc')) {
                $target.addClass('asc').removeClass('desc');
                asc = true;
            } else {
                $target.removeClass('asc').addClass('desc');
                asc = false;
            }

            $target.siblings().removeClass('asc desc');

            me.sort($target, asc);
        }
    };

    World.init();

    window.World = World;
});