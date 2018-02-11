(function() {
    var isOnClick = false,
        onClickValue;

    function addCombo(editor, config, comboName) {
        var $translate = editor.config.$injector.get('$translate'),
            panelTitle = $translate.instant('UI.ELEMENT.FONT.TEXT_STYLES_LABEL'),
            defaultLabel = panelTitle,
            styleDefinition = {
                element: '*',
                attributes: {
                    'class': '#(class_name)'
                }
            },
            style = new CKEDITOR.style(styleDefinition),
            styles = {};

        config.styles = config.styles || config.$injector.get('core.library.styles.StylesModel').objects();

        if (config.styles) {
            config.styles.forEach(function(item) {
                var styleDef = {};
                if (item.type == 'text') {
                    styleDef.attributes = {
                        'class': item.class_name
                    };
                    styleDef.element = item.tag || '*';
                    styles[item.class_name] = new CKEDITOR.style(styleDef);
                    styles[item.class_name]._.definition.name = item.name;
                }
            });

            editor.ui.addRichCombo(comboName, {
                label: panelTitle,
                title: panelTitle,
                toolbar: 'moto',
                allowedContent: style,
                requiredContent: style,
                panel: {
                    css: [CKEDITOR.skin.getPath('editor')].concat(config.contentsCss),
                    multiSelect: false,
                    attributes: {
                        'aria-label': panelTitle
                    }
                },
                init: function() {
                    var self = this;
                    self.startGroup(panelTitle);
                    editor.config.styles.forEach(function(item) {
                        if (styles[item.class_name]) {
                            self.add(item.class_name, item.name, item.class_name);
                        }
                    });
                },
                onClick: function(value) {
                    var style, p, selectedElements;
                    isOnClick = true;
                    onClickValue = value;
                    function getSelectedElements(editor) {
                        var walker = new CKEDITOR.dom.walker(editor.getSelection().getRanges()[0]),
                            selectedElements = [];
                        walker.evaluator = function(node) {
                            var element;
                            element = node.$.parentElement || node.$.parentNode;
                            if (selectedElements.indexOf(element) < 0 && $(element).is('p,h1,h2,h3,h4,li')) {
                                selectedElements.push(element);
                            }
                        };
                        while (walker.next()) {
                        }
                        return selectedElements;
                    }
                    editor.focus();
                    editor.fire('saveSnapshot');
                    selectedElements = getSelectedElements(editor);

                    if (selectedElements.length == 0) {
                        if (p = editor.elementPath().block.$) {
                            selectedElements.push(p);
                        }
                    }

                    selectedElements.forEach(function(selectedElement) {
                        var $paragraphElement = $(selectedElement).closest('p,h1,h2,h3,h4,li'), $allElementsInParagraph;

                        // $allElementsInParagraph - @legacy
                        $allElementsInParagraph = $paragraphElement.find('*');
                        for (style in styles) {
                            $paragraphElement.removeClass(style);
                            $allElementsInParagraph.removeClass(style);
                        }
                        if (value) {
                            editor.config.allowedContent = false;
                            $paragraphElement.addClass(value);
                            editor.config.allowedContent = true;
                        }
                    });

                    editor.fire('saveSnapshot');
                },
                onRender: function() {
                    var parent = this;

                    editor.on('updateParagraphStylesSelect', function(event) {
                        var style;
                        for (style in styles) {
                            if (event.data.paragraphElement.hasClass(style)) {
                                this.setValue(styles[style]._.definition.name);
                                this._.value = style;
                                return;
                            }
                        }
                    }, parent);

                    editor.on('selectionChange', function(ev) {
                        var self = this,
                            currentValue = self.getValue(),
                            elementPath = ev.data.path,
                            elements = elementPath.elements,
                            i, value, element;

                        if (isOnClick) {
                            isOnClick = false;
                            self.setValue(styles[onClickValue]._.definition.name);
                            self._.value = onClickValue;
                            return;
                        }
                        for (i = 0; elements[i]; i++) {
                            element = elements[i];
                            for (value in styles) {
                                if (styles[value].checkElementMatch(element, true, editor)) {
                                    if (value != currentValue) {
                                        self.setValue(styles[value]._.definition.name);
                                        self._.value = value;
                                    }
                                    return;
                                } else {
                                    if (element.$.classList.contains(value)) {
                                        if (value != currentValue) {
                                            self.setValue(styles[value]._.definition.name);
                                            self._.value = value;
                                        }
                                        return;
                                    }
                                }
                            }
                        }
                        self.setValue('', defaultLabel);
                    }, parent);
                },
                refresh: function() {
                    if (!editor.activeFilter.check(style)) {
                        this.setState(CKEDITOR.TRISTATE_DISABLED);
                    }
                }
            });
        }
    }

    CKEDITOR.plugins.add('cssstyle', {
        requires: 'richcombo',
        init: function(editor) {
            addCombo(editor, editor.config, 'CssStyleCombo');
        }
    });
})();
