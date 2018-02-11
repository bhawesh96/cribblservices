'use strict';

(function() {
    CKEDITOR.plugins.add('mototexticon', {
        icons: 'icon',
        init: function(editor) {
            var PARENT_TAG_LIST = 'p,h1,h2,h3,h4,li';
            var PARENT_TAGS = PARENT_TAG_LIST.split(',');
            var selectIconService = editor.config.$injector.get('motoUI.SelectIconService');
            var $translate = editor.config.$injector.get('$translate');
            var DEBUG = false;

            function cleanZombieTags($node) {
                var siblings = [];
                var $element;
                var i;
                var len;

                $node = $($node);

                siblings = $node.siblings();
                for (i = 0, len = siblings.length; i < len; i ++) {
                    $element = $(siblings[i]);
                    if ($element.text().length === 0 && $element.children().length === 0) {
                        if (DEBUG) {
                            console.info('Remove zombie element', $element);
                        }
                        $element.remove();
                    }
                }
            }

            function getAllEditableIcons() {
                return $(editor.document.$).find('.cke_editable .fa');
            }

            function restoreContentEditableForIcons() {
                editor.undoManager.lock();
                getAllEditableIcons().attr('contenteditable', 'false');
                editor.undoManager.unlock();
            }

            function clearContentEditableForIcons() {
                editor.undoManager.lock();
                getAllEditableIcons().removeAttr('contenteditable');
                editor.undoManager.unlock();
            }

            //Fix for MOTOCMS-3595[bug]
            //Edge create two empty span in DOM after color/remove styles,
            //may be after some other actions.
            function clearEmptySpans() {
                editor.undoManager.lock();
                $(editor.document.$).find('.cke_editable .fa:empty').remove();
                editor.undoManager.unlock();
            }
            editor.on('change', clearEmptySpans);

            editor.on('instanceReady', restoreContentEditableForIcons);
            editor.on('getData', restoreContentEditableForIcons);
            editor.on('beforeGetData', clearContentEditableForIcons);
            editor.on('beforeDestroy', clearContentEditableForIcons);

            function reset() {
                delete editor.config.preventBlur;
            }

            function selectIconPopupOk(selectedIcon) {
                /**
                 * @type {CKEDITOR.dom.element}
                 */
                var icon;
                var $icon;
                var $parent;
                var range;

                if (selectedIcon && selectedIcon.unicode) {
                    icon = editor.document.createElement('span');
                    icon.addClass('fa');
                    if (selectedIcon.fixedWidth) {
                        icon.addClass('fa-fw');
                    }
                    icon.setAttribute('contenteditable', 'false');
                    icon.setHtml('&#x' + selectedIcon.unicode + ';');
                    editor.insertElement(icon);

                    $icon = $(icon.$);
                    try {

                        $parent = $($icon.parent());

                        if (DEBUG) {
                            window._icon = icon;
                            window._$icon = $icon;
                            window._$parent = $parent;
                            window._$editor = editor;
                        }

                        // Icon on double P => move icon before parent
                        // parent of parent can be one tag of the PARENT_TAGS
                        // sometimes in chrome element inserted already with P into other P
                        if ($parent.prop('tagName').toLowerCase() === 'p' && PARENT_TAGS.indexOf($parent.parent().prop('tagName').toLowerCase()) !== -1) {
                            if (DEBUG) {
                                console.warn('Icon on double P => move icon before parent');
                            }
                            $parent.before($icon);
                            $parent.remove();
                        }

                        // IE, FF after insert element already has tag P
                        // patcher for chrome
                        if ($icon.parents(PARENT_TAG_LIST).length === 0) {
                            if (DEBUG) {
                                console.warn('Need wrap by P');
                            }
                            $icon.wrap('<p>');
                        }

                        $parent = $icon.parent();
                        // check is parent icon P
                        if ($parent.prop('tagName').toLowerCase() === 'p') {
                            // add to parent class 'moto-text_normal' if parent P not has any class
                            if (angular.isUndefined($parent.attr('class')) || !$parent.attr('class')) {
                                if (DEBUG) {
                                    console.warn('Add class moto-text_normal to parent icon');
                                }
                                $parent.addClass('moto-text_normal');
                            }
                        }

                        cleanZombieTags($parent);

                        // force moving cursor after icon
                        range = editor.createRange();
                        range.moveToElementEditablePosition(new CKEDITOR.dom.element($icon[0]), true);
                        editor.getSelection().selectRanges([range]);

                    } catch (e) {
                        console.error(e);
                    }

                    editor.fire('saveSnapshot');
                    editor.fire('change');
                }

                reset();
            }

            function selectIconPopupCancel() {
                editor.focus();
                reset();
            }

            editor.addCommand('showIconSelectDialog', {
                canUndo: false,
                exec: function() {
                    editor.config.preventBlur = true;
                    selectIconService.openPopup({
                        features: 'fixedWidth',
                        okHandler: selectIconPopupOk,
                        cancelHandler: selectIconPopupCancel
                    });
                }
            });

            editor.ui.addButton('icon', {
                label: $translate.instant('UI.ELEMENT.SELECT_ICON.BUTTON_TOOLTIP'),
                icon: CKEDITOR.plugins.getPath('mototexticon') + 'icons' + (CKEDITOR.env.hidpi ? '/hidpi' : '') + '/icon.png',
                command: 'showIconSelectDialog',
                toolbar: 'motocontentplugins,10'
            });
        }
    });
})();
