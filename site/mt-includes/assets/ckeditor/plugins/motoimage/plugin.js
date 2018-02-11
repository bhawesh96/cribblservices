'use strict';

(function() {
    CKEDITOR.plugins.add('motoimage', {
        icons: 'image',
        hidpi: true,
        onLoad: function() {
            CKEDITOR.addCss(
                '.cke_widget_motoimagewidget {' +
                    'max-width: 100%;' +
                '}' +
                '.cke_widget_wrapper_moto-content-image_left-floated {' +
                    'float: left;' +
                '}' +
                '.cke_widget_wrapper_moto-content-image_right-floated {' +
                    'float: right;' +
                '}' +
                '.cke_widget_wrapper_moto-content-image_centered-block {' +
                    'display: block !important;' +
                    'line-height: 0;' +
                    'text-align: center;' +
                '}' +
                '.moto-content-image-container {' +
                    'position: relative;' +
                '}' +
                '.moto-content-resizer {' +
                    'position: absolute;' +
                    'display: block;' +
                    'right: 0;' +
                    'transform: translate(0, -50%);' +
                    'top: 50%;' +
                    'width: 10px;' +
                    'height: 10px;' +
                    'opacity: 0.5;' +
                    'background: #000;' +
                    'border: 1px solid #d1d1d1;' +
                    'cursor: ew-resize;' +
                    'z-index: 10;' +
                '}' +
                '.moto-content-resizer:hover {' +
                    'opacity: 1;' +
                '}' +
                '.cke_widget_wrapper_moto-content-image_right-floated .moto-content-resizer {' +
                    'right: auto;' +
                    'left: 0;' +
                '}' +
                '.cke_widget_motoimagewidget > .cke_widget_drag_handler_container {' +
                    'top: 0 !important;' +
                    'width: 21px;' +
                    'box-shadow: inset 0px 0px 0px 1px #d1d1d1;' +
                    'background-position: center center !important;' +
                    'background-repeat: no-repeat !important;' +
                    'background-color: #fff !important;' +
                    'opacity: 0.5;' +
                '}' +
                '.cke_widget_motoimagewidget > .cke_widget_drag_handler_container:hover {' +
                    'opacity: 1;' +
                '}' +
                '.cke_widget_motoimagewidget:hover > .cke_widget_drag_handler_container {' +
                    'height: 21px;' +
                '}' +
                '.cke_widget_motoimagewidget .cke_widget_drag_handler {' +
                    'width: 21px;' +
                    'height: 21px;' +
                '}'
            );
        },
        init: function(editor) {
            var coreLibraryModal = editor.config.$injector.get('coreLibraryModal');
            var MediaLibraryService = editor.config.$injector.get('application.media_library.services.MediaLibraryService');
            var $translate = editor.config.$injector.get('$translate');
            var PathResolver = editor.config.$injector.get('coreLibraryPathResolver');
            var coreLibraryUtils = editor.config.$injector.get('coreLibraryUtils');
            var coreLibrarySpacingHelper = editor.config.$injector.get('coreLibrarySpacingHelper');
            var $editorDocument;

            var alignClassesMap = {
                'none': '',
                'left': 'moto-content-image_left-floated',
                'center': 'moto-content-image_centered-block',
                'right': 'moto-content-image_right-floated'
            };

            /**
             * Function for handling mousemove event on editor document
             * @param {Object} event JQuery mousemove event object
             */
            function mouseMoveHandler(event) {
                var image = event.data.widget.parts.image;
                var wrapper = event.data.widget.parts.wrapper;
                var newX = event.screenX;
                // difference on X axe
                var moveDiff = newX - event.data.startX;
                // we should invert resizing if image was right floated
                var resizeFactor = (wrapper.hasClass('moto-content-image_right-floated')) ? -1 : 1;
                // we should double resize ratio if image was block centered
                var resizeRatio = (wrapper.hasClass('moto-content-image_centered-block')) ? 2 : 1;
                var newImageWidth = event.data.startImageWidth + moveDiff * resizeFactor * resizeRatio;
                var newImageSize = coreLibraryUtils.proportionalResize(event.data.originalImageDimentions).setWidth(newImageWidth);

                // we should not resize image less than 1x1
                if (newImageSize.width > 0 && newImageSize.height > 0) {
                    image.setAttributes({
                        width: newImageSize.width,
                        height: newImageSize.height
                    });
                }
            }

            /**
             * Function for removing some event handlers from editor document
             */
            function mouseUpHandler() {
                $editorDocument.off('mousemove', mouseMoveHandler);
                $editorDocument.off('mouseup', mouseUpHandler);
                editor.fire('saveSnapshot');
            }

            /**
             * Function for removing resizer DOM element from widget DOM
             * @param {CKEditor.widget} widget  CKeditor widget object
             */
            function removeImageResizer(widget) {
                $(widget.element.$).find('.moto-content-resizer').remove();
            }

            /**
             * Function for adding resizer DOM element to widget DOM and handling mousedown event
             * @param {CKEditor.widget} widget  CKeditor widget object
             */
            function addAndSetupImageResizer(widget) {
                var resizerElement = $('<span class="moto-content-resizer" title="' + $translate.instant('COMMON.TEXT.RESIZE').replace(/"/g, '&quot;') + '"></span>');
                var image = widget.parts.image.$;

                // remove all previous resizer nodes
                removeImageResizer(widget);

                $editorDocument = $(editor.document.$);

                $(widget.element.$).find('.moto-content-image-container').append(resizerElement);
                resizerElement.on('mousedown', function(event) {
                    var startX = event.screenX;
                    var originalImageDimentions = {
                        width: (image.naturalWidth) ? image.naturalWidth : 1,
                        height: (image.naturalHeight) ? image.naturalHeight : 1
                    };
                    var startImageWidth = image.clientWidth;

                    event.preventDefault();
                    event.stopPropagation();

                    $editorDocument.on('mousemove', {
                        widget: widget,
                        originalImageDimentions: originalImageDimentions,
                        startImageWidth: startImageWidth,
                        startX: startX
                    }, mouseMoveHandler);
                    $editorDocument.on('mouseup', mouseUpHandler);
                });
            }

            /**
             *
             * @sample : loading image from widget element
             *  image = new ImageContentWidgetClass();
             *  image.setWidgetElement(widgetElement);
             *  image.loadFromWidgetElement();
             *  console.warn('generateHtmlCode', image.generateHtmlCode());
             *
             * @constructor
             */
            function ImageContentWidgetClass() {
                this.$element = null;
                this.$wrapper = null;
                this.$image = null;
                this.properties = {
                    id: null,
                    alt: '',
                    title: '',
                    width: null,
                    height: null,
                    thumbnail: null,
                    path: null,
                    src: null,
                    align: 'none',
                    spacing: {
                        top: 'zero',
                        right: 'zero',
                        bottom: 'zero',
                        left: 'zero'
                    }
                };
            }

            /**
             * jQuery DOM element of widget CKEditor
             *
             * @type {jQuery}
             */
            ImageContentWidgetClass.prototype.$element = null;

            /**
             * jQuery DOM element of widget wrapper
             *
             * @type {jQuery}
             */
            ImageContentWidgetClass.prototype.$wrapper = null;

            /**
             * jQuery DOM element of image tag
             *
             * @type {jQuery}
             */
            ImageContentWidgetClass.prototype.$image = null;

            /**
             * Content widget properties
             *
             * @type {Object}
             */
            ImageContentWidgetClass.prototype.properties = {
                id: null,
                alt: '',
                title: '',
                width: null,
                height: null,
                // maybe use 'size'
                thumbnail: null,
                path: null,
                src: null,
                align: 'none',
                spacing: {
                    top: 'zero',
                    right: 'zero',
                    bottom: 'zero',
                    left: 'zero'
                }
            };

            /**
             * Set widget JQuery DOM element
             *
             * @param {jQuery} $element JQuery widget DOM element
             */
            ImageContentWidgetClass.prototype.setWidgetElement = function($element) {
                // @TODO : check element

                this.$element = $element;
                this.$wrapper = $(this.$element);
                this.$image = $('.moto-content-image', this.$element);
            };

            ImageContentWidgetClass.prototype.getAlignClass = function() {
                return alignClassesMap[this.properties.align];
            };

            ImageContentWidgetClass.prototype.getAlignValueFromClass = function($wrapper) {
                var alignValue = 'none';

                Object.keys(alignClassesMap).map(function(key) {
                    if ($wrapper.hasClass(alignClassesMap[key])) {
                        alignValue = key;
                    }
                }.bind(this));

                return alignValue;
            };

            /**
             * Load widget properties from widget DOM element
             *
             * @returns {Boolean} Return true if widget properties are loaded
             */
            ImageContentWidgetClass.prototype.loadFromWidgetElement = function() {
                if (!this.$image || !this.$element || !this.$wrapper) {
                    return false;
                }

                this.properties.id = this.$image.attr('data-id');
                this.properties.alt = this.$image.attr('alt');
                this.properties.title = this.$image.attr('title');
                this.properties.width = this.$image.attr('width');
                this.properties.height = this.$image.attr('height');
                this.properties.src = this.$image.attr('src');
                this.properties.path = this.$image.attr('data-path');
                this.properties.thumbnail = this.$image.attr('data-thumbnail');
                // TODO remove this shit when issue with name/src will be resolved
                this.properties.name = this.properties.src.split('/')[this.properties.src.split('/').length - 1];

                this.properties.align = this.getAlignValueFromClass(this.$wrapper);
                coreLibrarySpacingHelper.parseElement(this.$wrapper, this.properties.spacing);

                return true;
            };

            /**
             * Update widget DOM element
             *
             * @returns {Boolean} Return true if widget DOM element updated
             */
            ImageContentWidgetClass.prototype.updateWidgetElement = function() {
                if (!this.$image || !this.$element || !this.$wrapper) {
                    return false;
                }

                this.$image.attr('data-id', this.properties.id);
                this.$image.attr('alt', this.properties.alt);
                this.$image.attr('title', this.properties.title);
                this.$image.attr('width', this.properties.width);
                this.$image.attr('height', this.properties.height);
                this.$image.attr('src', this.properties.src);
                this.$image.attr('data-thumbnail', this.properties.thumbnail);

                return true;
            };

            /**
             * Set Image from MediaLibrary
             *
             * @param {Object} image Image from MediaLibrary
             * @param {String|null} size Image thumbnail size
             */
            ImageContentWidgetClass.prototype.setImage = function(image, size) {
                var imageThumbnail;

                this.properties.id = image.id;
                this.properties.alt = image.alt;
                this.properties.title = image.title;
                this.properties.name = image.name;

                if (angular.isObject(image.thumbnails)) {
                    imageThumbnail = image.thumbnails[size];
                }
                if (imageThumbnail) {
                    this.properties.path = image.getThumbnailPath(size);
                    this.properties.thumbnail = size;
                    this.properties.width = imageThumbnail.width;
                    this.properties.height = imageThumbnail.height;
                } else {
                    // image path is empty, maybe thumbnail is bad
                    this.properties.thumbnail = null;
                    this.properties.path = image.path;
                    this.properties.width = image.properties.video.resolution_x;
                    this.properties.height = image.properties.video.resolution_y;
                }

                this.properties.src = PathResolver.getUploadAbsoluteUrl(this.properties.path);
            };

            /**
             * Generate HTML code
             *
             * @returns {String|null} Return null when html can't be generated
             */
            ImageContentWidgetClass.prototype.generateHtmlCode = function() {
                var html;

                if (!this.properties.id) {
                    return null;
                }

                html =
                    '<' + ((this.properties.align === 'center') ? 'div' : 'span') + ' class="moto-content-image-plugin-wrapper ' + coreLibrarySpacingHelper.generateSpacings(this.properties.spacing) + ' ' + this.getAlignClass() + '">' +
                        '<span class="moto-content-image-container">' +
                            '<img' +
                                ' class="moto-content-image"' +
                                ' src="' + this.properties.src + '"' +
                                ' data-path="' + this.properties.path + '"' +
                                ' data-id="' + this.properties.id + '"' +
                                ' alt="' + this.properties.alt + '"' +
                                (this.properties.title ? ' title="' + this.properties.title + '"' : '') +
                                (this.properties.width ? ' width="' + this.properties.width + '"' : '') +
                                (this.properties.height ? ' height="' + this.properties.height + '"' : '') +
                                (this.properties.thumbnail ? ' data-thumbnail="' + this.properties.thumbnail + '"' : '') +
                            '/>' +
                        '</span>' +
                    '</' + ((this.properties.align === 'center') ? 'div' : 'span') + '>';

                return html;
            };

            editor.widgets.add('motoimagewidget', {
                allowedContent: 'span div(!moto-content-image-plugin-wrapper); span(!moto-content-image-container); img(!moto-content-image)',
                parts: {
                    image: '.moto-content-image',
                    wrapper: '.moto-content-image-plugin-wrapper'
                },
                upcast: function(element) {
                    return element.hasClass('moto-content-image-plugin-wrapper');
                },
                init: function() {
                    this.on('doubleclick', function() {
                        // we should use setTimeout because of strange widget selection bug in Edge
                        setTimeout(function() {
                            editor.execCommand('showEditImagePopup');
                        }, 10);
                    });

                    this.on('select', function() {
                        addAndSetupImageResizer(this);
                    });

                    this.on('deselect', function() {
                        removeImageResizer(this);
                    });
                }
            });

            editor.addCommand('showEditImagePopup', {
                canUndo: false,
                exec: function() {
                    var image;
                    var widget = editor.widgets.focused;

                    image = new ImageContentWidgetClass();
                    if (widget && widget.name === 'motoimagewidget') {
                        image.setWidgetElement(widget.element.$);
                        image.loadFromWidgetElement();
                    }
                    editor.config.preventBlur = true;
                    coreLibraryModal(
                        '@systemAssets/ckeditor/plugins/motoimage/popup.tpl.html',
                        function($modalScope, $modalInstance) {
                            var originalImageDimentions = {
                                width: (image.$image) ? image.$image[0].naturalWidth : 1,
                                height: (image.$image) ? image.$image[0].naturalHeight : 1
                            };

                            function updateImageDimentions() {
                                originalImageDimentions = {
                                    width: $modalScope.image.properties.width,
                                    height: $modalScope.image.properties.height
                                };
                            }

                            $modalScope.image = image;
                            $modalScope.changeImage = function() {
                                MediaLibraryService.open({
                                    id: image.properties.id,
                                    filters: ['image'],
                                    thumbnails: true,
                                    size: image.properties.thumbnail,
                                    onApply: function(imageFromML, size) {
                                        image.setImage(imageFromML, size);
                                        updateImageDimentions();
                                        setTimeout($modalScope.editImageForm.validate, 50);
                                    }
                                });
                            };

                            $modalScope.recalculateImageSize = function(type) {
                                if (type === 'width') {
                                    $modalScope.image.properties.height = coreLibraryUtils.proportionalResize(originalImageDimentions).setWidth($modalScope.image.properties.width).height;
                                } else {
                                    $modalScope.image.properties.width = coreLibraryUtils.proportionalResize(originalImageDimentions).setHeight($modalScope.image.properties.height).width;
                                }
                            };

                            $modalScope.ok = function() {
                                if (!$modalScope.editImageForm.validate()) {
                                    return;
                                }

                                $modalInstance.close($modalScope);
                                editor.insertHtml(image.generateHtmlCode());
                                editor.fire('saveSnapshot');
                                delete editor.config.preventBlur;
                            };

                            $modalScope.cancel = function() {
                                $modalInstance.close($modalScope);
                                delete editor.config.preventBlur;
                            };
                        },
                        null,
                        'widget-admin-edit'
                    );
                }
            });

            editor.ui.addButton('image', {
                label: $translate.instant('COMMON.TEXT.INSERT_IMAGE'),
                command: 'showEditImagePopup',
                toolbar: 'motocontentplugins,20'
            });

            // fix editor blur for widget 'text'
            function onMouseDown() {
                editor.config.preventBlur = true;
            }
            editor.on('instanceReady', function() {
                try {
                    $(editor.document.$).on('mousedown', '.cke_widget_drag_handler_container', onMouseDown);
                } catch (e) {
                    console.error(e);
                }
            });
            editor.on('contentDomUnload', function() {
                try {
                    $(editor.document.$).off('mousedown', '.cke_widget_drag_handler_container', onMouseDown);
                } catch (e) {
                    console.error(e);
                }
            });
        }
    });
})();
