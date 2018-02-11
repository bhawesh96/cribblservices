(function() {
    //TODO-guru double-click on link (priority 0)
    CKEDITOR.plugins.add('motolinkto', {
        init: function(editor) {
            var selectedElement, selectedHTML, selectedParagraphClass, selectedParagraphStyle, isSelectedParagraph,
                coreLibraryUtils, LinkToService, $translate;

            coreLibraryUtils = editor.config.$injector.get('coreLibraryUtils');
            LinkToService = editor.config.$injector.get('motoUI.LinkToService');
            $translate = editor.config.$injector.get('$translate');

            /**
             * Workaround for MOTOCMS-3715 bug.
             * TODO: just use editor.getSelectedHtml(true) when ticket https://dev.ckeditor.com/ticket/16813 will be fixed
             */
            function _safeGetSelectedHtml() {
                var startNode, endNode, startOffset, endOffset, firstChildOfStartNode, range, fixedResultHTML, tempNode, tempNodeCopy;
                range = editor.getSelection().getRanges()[0];
                range.optimizeBookmark();
                startNode = range.startContainer;
                endNode = range.endContainer;
                startOffset = range.startOffset;
                endOffset = range.endOffset;
                if (startNode.type != CKEDITOR.NODE_TEXT && endNode.type == CKEDITOR.NODE_TEXT) {
                    firstChildOfStartNode = $(startNode.$).contents()[0];
                    if (firstChildOfStartNode) {
                        startNode = new CKEDITOR.dom.node(firstChildOfStartNode);
                        if (startNode.equals(endNode)) {
                            fixedResultHTML = startNode.substring(startOffset, endOffset);
                            // save styles
                            tempNode = $(range.startContainer.$);
                            while (!tempNode.is('p,h1,h2,h3,h4,li') && !tempNode.parent().hasClass('cke_editable')) {
                                tempNodeCopy = angular.copy(tempNode);
                                tempNodeCopy.empty();
                                tempNodeCopy.append(fixedResultHTML);
                                fixedResultHTML = tempNodeCopy[0].outerHTML;
                                tempNode = tempNode.parent();
                            }
                            return fixedResultHTML;
                        }
                    }
                }
                return editor.getSelectedHtml(true);
            }

            function reset() {
                selectedElement = null;
                selectedHTML = null;
                selectedParagraphClass = null;
                selectedParagraphStyle = null;
                isSelectedParagraph = null;
                delete editor.config.preventBlur;
            }

            function clearAttributes() {
                var i, attributes;

                attributes = coreLibraryUtils.getAttributes(selectedElement);
                for (i = 0; i < attributes.length; i++) {
                    selectedElement.removeAttr(attributes[i]);
                }
            }

            function processCompileData(action, properties, compileData) {
                var i, resultHTML;

                for (i in compileData) {
                    if (compileData.hasOwnProperty(i) && ($.inArray(i, ['text', 'class', 'data']) < 0)) {
                        selectedElement.attr(coreLibraryUtils.fromCamelCase(i), compileData[i]);
                    }
                }
                for (i in compileData.data) {
                    if (compileData.data.hasOwnProperty(i)) {
                        selectedElement.attr('data-' + coreLibraryUtils.fromCamelCase(i), compileData.data[i]);
                    }
                }
                resultHTML = compileData.text ? compileData.text : action.getDefaultText(properties);
                selectedElement.html(resultHTML);
                selectedElement.attr('class', compileData.class.join(' '));
                if (!selectedElement.is('[href]')) {
                    selectedElement.attr('href', '');
                }
            }

            function motoLinkToPopupOk(link) {
                var action, compileData, parentElement, range, idAttribute = 'cke_moto_link';

                if (selectedElement.is('a')) {
                    clearAttributes();
                } else {
                    // Carefully! There are a lot of strange code because of bugs in different browsers below.
                    editor.insertHtml('<a id="' + idAttribute + '" href="#">Link</a>');
                    selectedElement = $(editor.document.getById(idAttribute).$);
                    selectedElement.removeAttr('id');
                    selectedElement.removeAttr('href');
                    selectedElement.empty();
                    parentElement = selectedElement.parent();
                    if (parentElement.is('p,h1,h2,h3,h4,li')) {
                        if (selectedParagraphClass) {
                            parentElement.attr('class', selectedParagraphClass);
                            editor.fire('updateParagraphStylesSelect', {paragraphElement: parentElement});
                        }
                        if (selectedParagraphStyle) {
                            parentElement.attr('style', selectedParagraphStyle);
                        }
                    }
                }
                action = LinkToService.getAction(link.action);
                compileData = {
                    text: selectedHTML,
                    data: {
                        action: link.action
                    },
                    class: ['moto-link']
                };
                action.compileDomElement && action.compileDomElement(selectedElement, link.properties, compileData);
                processCompileData(action, link.properties, compileData);
                range = editor.createRange();
                range.moveToElementEditablePosition(new CKEDITOR.dom.element(selectedElement[0]), true);
                editor.getSelection().selectRanges([range]);
                selectedElement.removeAttr('data-cke-saved-href');
                editor.fire('saveSnapshot');
                editor.fire('change');
                reset();
            }

            function motoLinkToPopupCancel() {
                editor.focus();
                reset();
            }

            function getSelectedElement() {
                var $element = $(editor.getSelection().getStartElement().$),
                    closestSelectedLink = $element.closest('a');

                if (closestSelectedLink.length) {
                    $element = closestSelectedLink;
                }
                return $element;
            }

            function getSelectedHTML() {
                var $element, i, clearElement, clearElements, clearElementHTML, clearElementsSelector, resultHtml;

                if (selectedElement.is('a')) {
                    $element = angular.copy(selectedElement);
                } else {
                    $element = $('<div>' + _safeGetSelectedHtml() + '</div>');
                }
                clearElementsSelector = 'a,p,ul,ol,li';
                clearElements = $element.find(clearElementsSelector);
                while (clearElements.length > 0) {
                    for (i = 0; i < clearElements.length; i++) {
                        clearElement = $(clearElements[i]);
                        clearElementHTML = clearElement.html();
                        if (clearElement.is('p,h1,h2,h3,h4,li')) {
                            if (!isSelectedParagraph) {
                                isSelectedParagraph = true;
                                selectedParagraphClass = clearElement.attr('class');
                                selectedParagraphStyle = clearElement.attr('style');
                            }
                            if (!/<br[^<>]*>$/.test(clearElementHTML)) {
                                clearElementHTML = clearElementHTML + '<br>';
                            }
                        }
                        clearElement.after(clearElementHTML);
                        clearElement.remove();
                    }
                    clearElements = $element.find(clearElementsSelector);
                }
                resultHtml = $element.html();
                resultHtml = resultHtml.replace(/<br[^<>]*>$/, '');
                return resultHtml;
            }

            editor.addCommand('MotoLinkTo.OpenPopup', {
                canUndo: false,
                exec: function() {
                    var link = {}, action, parseData;

                    selectedElement = getSelectedElement();
                    selectedHTML = getSelectedHTML();
                    link.action = selectedElement.attr('data-action') || 'url';
                    action = LinkToService.getAction(link.action);
                    if (!action) {
                        link.action = 'url';
                        action = LinkToService.getAction(link.action);
                    }
                    link.properties = action.getDefaultProperties();
                    parseData = LinkToService.getParseData(selectedElement, selectedHTML);
                    action.parseDomElement && action.parseDomElement(selectedElement, link.properties, parseData);
                    editor.config.preventBlur = true;
                    LinkToService.openPopup({
                        link: link,
                        okHandler: motoLinkToPopupOk,
                        cancelHandler: motoLinkToPopupCancel
                    });
                }
            });

            editor.addCommand('MotoLinkTo.Unlink', {
                exec: function(editor) {
                    var style = new CKEDITOR.style({element: 'a', type: CKEDITOR.STYLE_INLINE, alwaysRemoveElement: 1});
                    editor.removeStyle(style);
                },
                refresh: function(editor, path) {
                    var element = path.lastElement && path.lastElement.getAscendant('a', true);
                    if (element && element.getName() == 'a' && element.getChildCount()) {
                        this.setState(CKEDITOR.TRISTATE_OFF);
                    }
                    else {
                        this.setState(CKEDITOR.TRISTATE_DISABLED);
                    }
                },
                contextSensitive: 1,
                startDisabled: 1,
                requiredContent: 'a'
            });

            editor.ui.addButton('MotoLinkTo.OpenPopup', {
                label: $translate.instant('UI.ELEMENT.LINK_TO.ADD_LINK_TEXT'),
                icon: CKEDITOR.plugins.getPath('motolinkto') + 'icons' + (CKEDITOR.env.hidpi ? '/hidpi' : '') + '/link.png',
                command: 'MotoLinkTo.OpenPopup',
                toolbar: 'links,1'
            });

            editor.ui.addButton('MotoLinkTo.Unlink', {
                label: $translate.instant('UI.ELEMENT.LINK_TO.UNLINK_TEXT'),
                icon: CKEDITOR.plugins.getPath('motolinkto') + 'icons' + (CKEDITOR.env.hidpi ? '/hidpi' : '') + '/unlink.png',
                command: 'MotoLinkTo.Unlink',
                toolbar: 'links,2'
            });

        }
    });
})();
