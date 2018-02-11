(function() {
    var templateHtml = '' +
        '<div class="moto-colorizer-content">' +
            '<div>' +
                '{% for colorKey, colorList in colors.base %}' +
                    '<div class="box-colors">' +
                        '{% for color in colorList %}' +
                            '{% if loop.index0 > 0 %}' +
                                '<button type="button" onclick="CKEDITOR.tools.callFunction(\'{{ onClickColorFunction }}\',this);return false;" class="button-color colorizer-setcolor{% if keys.type==\'base\' and keys.key==colorKey and keys.id==loop.index0 %} button-color_selected{% endif %}" data-color="{{ color }}" data-type="base" data-key="{{ colorKey }}" data-id="{{ loop.index0 }}" style="background-color:{{ color }};"></button>' +
                            '{% endif %}' +
                        '{% endfor %}' +
                    '</div>' +
                '{% endfor %}' +
                '<div class="clear"></div>' +
            '</div>' +
            '<div class="custom-colors">' +
                '{% for colorKey, color in colors.custom %}' +
                    '<button type="button" onclick="CKEDITOR.tools.callFunction(\'{{ onClickColorFunction }}\',this);return false;" class="button-color colorizer-setcolor{% if keys.type==\'custom\' and keys.key==loop.index %} button-color_selected{% endif %}" data-color="{{ color }}" data-type="custom" data-key="{{ loop.index }}" style="background-color:{{ color }};"></button>' +
                '{% endfor %}' +
                '<div class="clear"></div>' +
            '</div>' +
            '<a class="moto-colorizer-link" href="javascript: void(0);" onclick="CKEDITOR.tools.callFunction(\'{{ onClickMoreFunction }}\',this);return false;">{{ \'UI.ELEMENT.COLOR_PICKER.MORE_COLORS_TEXT\' | translate }}</a>' +
        '</div>';
    var colorStyleConfig = {
        element: 'span',
        styles: {'color': '#(color)'},
        overrides: [{
            element: 'span',
            styles: {'color': 'null'}
        }]
    };
    var classStyleConfig = {
        element: 'span',
        attributes: {
            'class': '#(class_name)'
        },
        overrides: [{
            element: 'span',
            attributes: {'class': /moto-color/}
        }]
    };
    var currentColor;
    var model;
    var $translate;
    var WidgetFactory;

    function clearOldColors(editor) {
        editor.removeStyle(new CKEDITOR.style(classStyleConfig, {class_name: null}));
        editor.removeStyle(new CKEDITOR.style(colorStyleConfig, {color: null}));
    }

    function getCurrentColor(element, isLink) {
        var classAttribute = element.attr('class');
        var styleAttribute = element.attr('style');
        var classList;
        var i;
        var color;

        isLink = isLink || element.is('a');
        if (classAttribute) {
            classList = classAttribute.split(/\s+/);
            for (i = 0; i < classList.length; i++) {
                if (/^moto-text/.test(classList[i])) {
                    color = model.getNameByFont({
                        fontClass: classList[i],
                        link: isLink
                    });
                    // try to delete '!isLink && ' if you have bug with link color
                } else if (!isLink && /^moto-color/.test(classList[i])) {
                    color = model.getNameByClassName(classList[i]);
                } else if (classList[i] === 'cke_editable') {
                    color = element.css('color');
                }
            }
        }
        if (styleAttribute && /color:/.test(styleAttribute)) {
            color = element.css('color');
        }
        if (!color) {
            color = getCurrentColor(element.parent(), isLink);
        }

        return color;
    }

    function addButton(editor, config) {
        var style = new CKEDITOR.style(classStyleConfig);
        var colorBoxId = CKEDITOR.tools.getNextId() + '_colorizer';
        var blockElement;
        var onClickMoreFunction;
        var onClickColorFunction;

        function renderColorizer(panel) {
            model = config.colorizer;

            onClickMoreFunction = CKEDITOR.tools.addFunction(function() {
                editor.execCommand('spectrum');
            });

            onClickColorFunction = CKEDITOR.tools.addFunction(function(el) {
                var option = null;

                if ($(el).data('type')) {
                    if ($(el).data('type') === 'base') {
                        option = {
                            color: $(el).data('color'),
                            value: model.getBaseName($(el).data('key'), $(el).data('id')),
                            class_name: model.getBaseClassName($(el).data('key'), $(el).data('id'))
                        };
                    } else if ($(el).data('type') === 'custom') {
                        option = {
                            color: $(el).data('color'),
                            value: model.getCustomName($(el).data('key')),
                            class_name: model.getCustomClassName($(el).data('key'))
                        };
                    }
                } else {
                    option = {
                        color: $(el).data('color'),
                        value: $(el).data('color')
                    };
                }

                editor.focus();
                panel.hide();
                editor.fire('saveSnapshot');

                if (option && option.class_name) {
                    clearOldColors(editor);
                    editor.applyStyle(new CKEDITOR.style(classStyleConfig, {class_name: option.class_name}));
                }

                editor.fire('saveSnapshot');
            });
        }

        function onColorizerClick() {
            var startElement = editor.getSelection().getStartElement();

            if (!startElement) {
                currentColor = '#fff';
            } else {
                currentColor = getCurrentColor($(startElement.$));
            }
            WidgetFactory.getTemplateCompiler('nunjucks')(templateHtml).render({
                color: model.getColorByName(currentColor),
                keys: model.getKeysByName(currentColor),
                colors: model,
                onClickColorFunction: onClickColorFunction,
                onClickMoreFunction: onClickMoreFunction
            }, function(error, template) {
                blockElement.setHtml(template);
            });
        }

        function getToolbarButtonElement() {
            return $('#cke_' + editor.name + ' .cke_button__moto_colorizer');
        }

        editor.on('instanceReady', function() {
            getToolbarButtonElement().on('click', onColorizerClick);
        });

        editor.on('destroy', function() {
            getToolbarButtonElement().off('click', onColorizerClick);
        });

        editor.ui.add('moto_colorizer', CKEDITOR.UI_PANELBUTTON, {
            label: $translate.instant('UI.ELEMENT.COLOR_PICKER.COLORS_TEXT'),
            title: $translate.instant('UI.ELEMENT.COLOR_PICKER.COLORS_TEXT'),
            modes: {wysiwyg: 1},
            editorFocus: 0,
            toolbar: 'colorize',
            allowedContent: style,
            requiredContent: style,
            icon: CKEDITOR.plugins.getPath('colorizer') + 'icons' + (CKEDITOR.env.hidpi ? '/hidpi' : '') + '/textcolor.png',
            panel: {
                css: CKEDITOR.skin.getPath('editor'),
                attributes: {
                    role: 'listbox',
                    'aria-label': $translate.instant('UI.ELEMENT.COLOR_PICKER.COLORS_TEXT')
                }
            },
            onBlock: function(panel, block) {
                block.autoSize = true;
                block.element.addClass('cke_colorblock');
                config.colorizer = config.colorizer || config.$injector.get('application.design.colorizer.DesignColorizerModel').objects().first();
                renderColorizer(panel, colorBoxId);
                blockElement = block.element;
                $(blockElement.getDocument().getBody().$)
                    .addClass('moto-colorizer-dialog')
                    .append('<link rel="stylesheet" href="' + editor.config.contentsCss + '">');
                CKEDITOR.ui.fire('ready', this);
            },
            refresh: function() {
                if (!editor.activeFilter.check(style)) {
                    this.setState(CKEDITOR.TRISTATE_DISABLED);
                }
            }
        });
    }

    CKEDITOR.plugins.add('colorizer', {
        icons: 'textcolor',
        hidpi: true,
        init: function(editor) {
            var config = editor.config;

            $translate = editor.config.$injector.get('$translate');
            WidgetFactory = editor.config.$injector.get('coreLibraryWidget');
            editor.addCommand('spectrum', new CKEDITOR.dialogCommand('spectrum', {}));
            addButton(editor, config);
        }
    });

    CKEDITOR.plugins.colorizer = {
        setColor: function(editor, color) {
            if (color) {
                clearOldColors(editor);
                editor.applyStyle(new CKEDITOR.style(colorStyleConfig, {color: color}));
            }
        },
        getCurrentColor: function() {
            return model.getColorByName(currentColor);
        },
        getStyleHref: function() {
            return CKEDITOR.plugins.getPath('colorizer') + 'css/style.css?t=' + CKEDITOR.timestamp;
        }
    };

    CKEDITOR.dialog.add('spectrum', CKEDITOR.plugins.getPath('colorizer') + 'dialogs/spectrum.js?t=' + CKEDITOR.timestamp);
})();
