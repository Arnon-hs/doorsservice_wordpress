/**
 *  Ajax Autocomplete for jQuery, version 1.2.27
 *  (c) 2015 Tomas Kirda
 *
 *  Ajax Autocomplete for jQuery is freely distributable under the terms of an MIT-style license.
 *  For details, see the web site: https://github.com/devbridge/jQuery-Autocomplete
 *
 *  Modified by Damian Góra: http://damiangora.com
 */

/*jslint  browser: true, white: true, single: true, this: true, multivar: true */
/*global define, window, document, jQuery, exports, require */

// Expose plugin as an AMD module if AMD loader is present:
(function (factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object' && typeof require === 'function') {
        // Browserify
        factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    'use strict';

    var utils = (function () {
            return {
                escapeRegExChars: function (value) {
                    return value.replace(/[|\\{}()[\]^$+*?.]/g, "\\$&");
                },
                createNode: function (containerClass) {
                    var div = document.createElement('div');
                    div.className = containerClass;
                    div.style.position = 'absolute';
                    div.style.display = 'none';
                    div.setAttribute('unselectable', 'on');
                    return div;
                },
                highlight: function (suggestionValue, phrase) {

                    if (dgwt_wcas.is_premium) {
                        var i;
                        var tokens = phrase.split(/ /);

                        if (tokens) {
                            tokens = tokens.sort(function (a, b) {
                                return b.length - a.length;
                            });
                            for (i = 0; i < tokens.length; i++) {
                                if (tokens[i] && tokens[i].length > 1) {
                                    var pattern = '(' + utils.escapeRegExChars(tokens[i].trim()) + ')';
                                    suggestionValue = suggestionValue.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>');
                                }
                            }
                        }
                    } else {
                        var pattern = '(' + utils.escapeRegExChars(phrase) + ')';
                        suggestionValue = suggestionValue.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>');
                    }

                    return suggestionValue;
                },
                debounce: function (func, wait) {
                    var timeout,
                        debounceID = new Date().getUTCMilliseconds();

                    // First query in the chain
                    if (ajaxDebounceState.id.length === 0) {
                        ajaxDebounceState.id = debounceID;
                        func();
                        return;
                    }

                    ajaxDebounceState.id = debounceID;

                    timeout = setTimeout(function () {

                        if (debounceID !== ajaxDebounceState.id) {
                            clearTimeout(timeout);
                            return;
                        }

                        // Last query in the chain
                        func();
                        ajaxDebounceState.id = '';

                    }, wait);
                },
                mouseHoverDebounce: function (func, selector, wait) {
                    var timeout;

                    timeout = setTimeout(function () {

                        if ($(selector + ':hover').length > 0) {
                            func();
                        } else {
                            clearTimeout(timeout);
                            return;
                        }


                    }, wait);
                }
            };
        }()),
        ajaxDebounceState = {
            id: '',
            callback: null,
            ajaxSettings: null,
            object: null,
        },
        keys = {
            ESC: 27,
            TAB: 9,
            RETURN: 13,
            LEFT: 37,
            UP: 38,
            RIGHT: 39,
            DOWN: 40
        },
        noop = $.noop;

    function Autocomplete(el, options) {
        var that = this;

        // Shared variables:
        that.element = el;
        that.el = $(el);
        that.suggestions = [];
        that.badQueries = [];
        that.selectedIndex = -1;
        that.currentValue = that.element.value;
        that.timeoutId = null;
        that.cachedResponse = {};
        that.cachedDetails = {};
        that.detailsRequestsSent = [];
        that.onChangeTimeout = null;
        that.onChange = null;
        that.isLocal = false;
        that.suggestionsContainer = null;
        that.detailsContainer = null;
        that.noSuggestionsContainer = null;
        that.options = $.extend(true, {}, Autocomplete.defaults, options);
        that.classes = {
            selected: 'dgwt-wcas-suggestion-selected',
            suggestion: 'dgwt-wcas-suggestion',
            suggestionsContainerOrientTop: 'dgwt-wcas-suggestions-wrapp--top'
        };
        that.hint = null;
        that.hintValue = '';
        that.selection = null;
        that.overlayMobileState = 'off';

        // Initialize and set options:
        that.initialize();
        that.setOptions(options);

    }

    Autocomplete.utils = utils;

    $.Autocomplete = Autocomplete;

    Autocomplete.defaults = {
        ajaxSettings: {},
        autoSelectFirst: false,
        appendTo: 'body',
        serviceUrl: null,
        lookup: null,
        onSelect: null,
        width: 'auto',
        containerDetailsWidth: 'auto',
        showDetailsPanel: false,
        showImage: false,
        showPrice: false,
        showSKU: false,
        showDescription: false,
        showSaleBadge: false,
        showFeaturedBadge: false,
        saleBadgeText: 'sale',
        featuredBadgeText: 'featured',
        minChars: 3,
        maxHeight: 600,
        deferRequestBy: 0,
        params: {},
        formatResult: _formatResult,
        delimiter: null,
        zIndex: 999999999,
        type: 'GET',
        noCache: false,
        isRtl: false,
        onSearchStart: noop,
        onSearchComplete: noop,
        onSearchError: noop,
        preserveInput: false,
        searchFormClass: 'dgwt-wcas-search-wrapp',
        containerClass: 'dgwt-wcas-suggestions-wrapp',
        containerDetailsClass: 'dgwt-wcas-details-wrapp',
        searchInputClass: 'dgwt-wcas-search-input',
        preloaderClass: 'dgwt-wcas-preloader',
        closeTrigger: 'dgwt-wcas-close',
        tabDisabled: false,
        dataType: 'text',
        currentRequest: null,
        triggerSelectOnValidInput: true,
        isPremium: false,
        overlayMobile: false,
        preventBadQueries: true,
        lookupFilter: _lookupFilter,
        paramName: 'query',
        transformResult: _transformResult,
        showNoSuggestionNotice: false,
        noSuggestionNotice: 'No results',
        orientation: 'bottom',
        forceFixPosition: false,
        positionFixed: false,
        debounceWaitMs: 400,
        sendGAEvents: true,
    };

    function _lookupFilter(suggestion, originalQuery, queryLowerCase) {
        return suggestion.value.toLowerCase().indexOf(queryLowerCase) !== -1;
    };

    function _transformResult(response) {
        return typeof response === 'string' ? $.parseJSON(response) : response;
    };

    function _formatResult(suggestionValue, currentValue, highlight) {
        // Do not replace anything if there current value is empty
        if (!currentValue) {
            return suggestionValue;
        }

        if (highlight) {
            suggestionValue = utils.highlight(suggestionValue, currentValue);
        }

        return suggestionValue.replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/&lt;sup/g, '<sup')
            .replace(/&lt;\/sup/g, '</sup')
            .replace(/sup&gt;/g, 'sup>')
            .replace(/&lt;(\/?strong)&gt;/g, '<$1>');
    };

    Autocomplete.prototype = {
        initialize: function () {
            var that = this,
                suggestionSelector = '.' + that.classes.suggestion,
                selected = that.classes.selected,
                options = that.options,
                container,
                containerDetails,
                closeTrigger = '.' + options.closeTrigger,
                formWrapper = that.getFormWrapper();

            // Remove autocomplete attribute to prevent native suggestions:
            that.element.setAttribute('autocomplete', 'off');

            var context = that.el.closest('.' + options.searchFormClass).data('wcas-context');

            // html() deals with many types: htmlString or Element or Array or jQuery
            that.noSuggestionsContainer = $('<div class="dgwt-wcas-no-suggestion"></div>')
                .html(this.options.noSuggestionNotice).get(0);

            // Create sugestions container
            that.suggestionsContainer = Autocomplete.utils.createNode(options.containerClass);

            container = $(that.suggestionsContainer);
            container.attr('data-wcas-context', context);
            container.addClass('woocommerce');

            container.appendTo(options.appendTo || 'body');

            // Add conditiona classes
            if (options.showImage === true) {
                container.addClass('dgwt-wcas-has-img');
            }

            // Price
            if (options.showPrice === true) {
                container.addClass('dgwt-wcas-has-price');
            }

            // Description
            if (options.showDescription === true) {
                container.addClass('dgwt-wcas-has-desc');
            }

            if (options.showSKU === true) {
                container.addClass('dgwt-wcas-has-sku');
            }

            if (options.showHeadings === true) {
                container.addClass('dgwt-wcas-has-headings');
            }

            // Only set width if it was provided:
            if (options.width !== 'auto') {
                container.width(options.width);
            }

            // Create suggestions details container
            if (options.showDetailsPanel === true) {

                that.detailsContainer = Autocomplete.utils.createNode(options.containerDetailsClass);

                containerDetails = $(that.detailsContainer);
                containerDetails.attr('data-wcas-context', context);
                containerDetails.addClass('woocommerce');

                containerDetails.appendTo('body');
            }

            // Listen for mouse over event on suggestions list:
            container.on('mouseenter.autocomplete', suggestionSelector, function () {

                var currentIndex = $(this).data('index');
                var selector = '.dgwt-wcas-suggestions-wrapp[data-wcas-context="' + that.getContext() + '"] .dgwt-wcas-suggestion[data-index="' + currentIndex + '"]';

                if (that.selectedIndex != currentIndex) {
                    utils.mouseHoverDebounce(function () {
                        if (that.selectedIndex !== currentIndex) {
                            that.getDetails(that.suggestions[currentIndex]);
                            that.activate(currentIndex);
                        }
                    }, selector,100);

                }
            });

            // Deselect active element when mouse leaves suggestions container:
            container.on('mouseout.autocomplete', function () {
                //that.selectedIndex = -1;
                // container.children( '.' + selected ).removeClass( selected );
            });

            // Listen for click event on suggestions list:
            $(document).on('click.autocomplete', closeTrigger, function (e) {
                that.hide();
                that.clear(false);
                $(this).removeClass(options.closeTrigger);
                $(this).closest('.' + options.searchFormClass).find('.' + options.searchInputClass).val('').focus();
            });

            // Mobile mode
            if (
                that.options.overlayMobile
                && that.isMobileMode()
            ) {

                if (formWrapper.hasClass('js-dgwt-wcas-icon-mobile')) {
                    formWrapper.prepend('<div class="dgwt-wcas-icon-handler">' + dgwt_wcas.magnifier_icon + '</div>');
                }

                formWrapper.prepend('<div class="js-dgwt-wcas-enable-mobile-form dgwt-wcas-enable-mobile-form"></div>');

                var $el = formWrapper.find('.js-dgwt-wcas-enable-mobile-form');
                $el.on('click', function (e) {
                    that.enableOverlayMobile();
                });

            }

            container.on('click.autocomplete', suggestionSelector, function () {
                that.select($(this).data('index'));
            });

            container.on('click.autocomplete', function () {
                clearTimeout(that.blurTimeoutId);
            })

            if (that.canShowDetailsBox()) {
                containerDetails.on('click.autocomplete', function () {
                    clearTimeout(that.blurTimeoutId);
                })
            }

            that.hideAfterClickOutsideListener();


            $(document).on('change', '[name="js-dgwt-wcas-quantity"]', function (e) {
                var $input = $(this).closest('.js-dgwt-wcas-pd-addtc').find('[data-quantity]');
                $input.attr('data-quantity', $(this).val());
            });


            that.fixPositionCapture = function () {
                that.adjustContainerWidth();
                if (that.visible) {
                    that.fixPosition();
                }
            };

            $(window).on('resize.autocomplete', that.fixPositionCapture);

            $(window).on('scroll', function () {

                if (that.suggestions.length > 0 && that.elementOrParentIsFixed(that.getFormWrapper())) {
                    if ($(window).scrollTop() === 0) {
                        var timeSteps = [1, 10, 20, 30, 40, 50, 60, 70, 80, 90, 120, 140, 170, 200, 250, 400, 700, 1000, 2000];

                        timeSteps.forEach(function (offset) {
                            setTimeout(function () {
                                that.fixHeight();
                                that.fixPositionCapture();
                            }, offset);
                        });
                    } else {
                        that.fixHeight();
                        that.fixPositionCapture();
                    }
                }
            });

            that.el.on('keydown.autocomplete', function (e) {
                that.onKeyPress(e);
            });
            that.el.on('keyup.autocomplete', function (e) {
                that.onKeyUp(e);
            });
            that.el.on('blur.autocomplete', function () {
                that.onBlur();
            });
            that.el.on('focus.autocomplete', function () {
                that.onFocus();
            });
            that.el.on('change.autocomplete', function (e) {
                that.onKeyUp(e);
            });
            that.el.on('input.autocomplete', function (e) {
                that.onKeyUp(e);
            });
        },
        onFocus: function () {
            var that = this;

            that.fixPositionCapture();
            if (that.el.val().length >= that.options.minChars) {
                that.onValueChange();
            }

        },
        onBlur: function () {
            var that = this,
                options = that.options,
                value = that.el.val(),
                query = that.getQuery(value);

            // If user clicked on a suggestion, hide() will
            // be canceled, otherwise close suggestions
            that.blurTimeoutId = setTimeout(function () {
                that.hide();

                if (that.selection && that.currentValue !== query) {
                    (options.onInvalidateSelection || $.noop).call(that.element);
                }
            }, 200);
        },
        abortAjax: function () {
            var that = this;
            if (that.currentRequest) {
                that.currentRequest.abort();
                that.currentRequest = null;
            }
        },
        setOptions: function (suppliedOptions) {
            var that = this,
                $suggestionsContainer = that.getSuggestionsContainer(),

                options = $.extend({}, that.options, suppliedOptions);

            that.isLocal = Array.isArray(options.lookup);

            if (that.isLocal) {
                options.lookup = that.verifySuggestionsFormat(options.lookup);
            }

            options.orientation = that.validateOrientation(options.orientation, 'bottom');

            // Adjust height, width and z-index:
            //@TODO nie działa obliczanie prawej krawędzi ekranu
            $suggestionsContainer.css({
                'max-height': !that.canShowDetailsBox() ? options.maxHeight + 'px' : 'none',
                'width': options.width + 'px',
                'z-index': options.zIndex
            });

            // Add classes
            if (options.showDetailsPanel === true) {
                var $detailsContainer = that.getDetailsContainer();

                $detailsContainer.css({
                    'z-index': (options.zIndex - 1)
                });
                $('body').addClass('dgwt-wcas-is-details');
            }

            options.onSearchComplete = function () {
                var searchForm = that.getFormWrapper();
                searchForm.removeClass('dgwt-wcas-processing');
                that.preloader('hide', 'form', 'dgwt-wcas-inner-preloader');
                that.preloader('show', 'form', options.closeTrigger);
            };

            this.options = options;
        },
        clearCache: function () {
            this.cachedResponse = {};
            this.cachedDetails = {};
            this.badQueries = [];
        },
        clear: function (cache) {
            if (cache) {
                this.clearCache();
            }
            this.currentValue = '';
            this.suggestions = [];
        },
        disable: function () {
            var that = this;
            that.disabled = true;
            clearTimeout(that.onChangeTimeout);
            that.abortAjax();
        },
        enable: function () {
            this.disabled = false;
        },
        fixPosition: function () {
            var that = this,
                $containerSuggestions = $('.' + that.options.containerClass + '[data-wcas-context="' + that.getContext() + '"]'),
                offset = that.getFormOffset();

            $containerSuggestions.css(offset);

            if (that.canShowDetailsBox()) {
                that.fixPositionDetailsBox();
            }

        },
        fixPositionDetailsBox: function () {

            var that = this,
                $form = that.el.closest('[data-wcas-context]'),
                $containerDetails = $('.' + that.options.containerDetailsClass + '[data-wcas-context="' + that.getContext() + '"]'),
                $containerSuggestions = $('.' + that.options.containerClass + '[data-wcas-context="' + that.getContext() + '"]'),
                offset = that.getFormOffset(),
                nativeOffsetLeft = offset.left,
                maxWidth = 550;


            if ($containerDetails.length == 0) {
                return false;
            }

            var borderCorrection = that.options.isRtl === true ? 1 : 2;
            var leftOffset = Math.round(offset.left);
            offset.left = leftOffset + Math.round($containerSuggestions.width() + borderCorrection);

            $containerDetails.css(offset);


            // Not set position on the bigger search form
            if ($form.width() >= maxWidth) {

                $('body').removeClass('dgwt-wcas-details-outside');
                $('body').removeClass('dgwt-wcas-details-right');
                $('body').removeClass('dgwt-wcas-details-left');

                if (that.options.isRtl === true) {
                    $containerSuggestions.css('left', leftOffset + Math.round($containerDetails.width() + borderCorrection) + 'px');
                    $containerDetails.css('left', (nativeOffsetLeft) + 'px');
                }

                return;
            }

            var windowWidth = $(window).width(),
                cDWidth = $containerDetails.width(),
                cOffset = $containerDetails.offset();

            $('body').addClass('dgwt-wcas-details-outside');


            if (that.options.isRtl === true) {
                offset.left = offset.left + 1;
            }

            var rightBorderCrossed = false;
            var leftBorderCrossed = false;

            // Right border crossed
            if (windowWidth < (cOffset.left + cDWidth)) {
                rightBorderCrossed = true;

                $('body').removeClass('dgwt-wcas-details-right');
                $('body').addClass('dgwt-wcas-details-left');

                $containerDetails.css('left', (Math.round(parseFloat($containerSuggestions.css('left').replace('px', ''))) - $containerDetails.outerWidth()) + 'px');

            }

            cOffset = $containerDetails.offset();

            // Left border crossed
            if (cOffset.left < 1) {
                leftBorderCrossed = true;

                $('body').removeClass('dgwt-wcas-details-left');
                $('body').addClass('dgwt-wcas-details-right');

            }

            if (leftBorderCrossed && rightBorderCrossed) {
                $('body').removeClass('dgwt-wcas-details-left');
                $('body').removeClass('dgwt-wcas-details-right');
                $('body').addClass('dgwt-wcas-details-notfit');
            } else {
                $('body').removeClass('dgwt-wcas-details-notfit');
            }


        },
        fixHeight: function () {

            var that = this,
                options = that.options;

            if (options.showDetailsPanel != true) {
                return false;
            }

            var $suggestionsWrapp = that.getSuggestionsContainer(),
                $detailsWrapp = that.getDetailsContainer();

            $suggestionsWrapp.css('height', 'auto');
            $detailsWrapp.css('height', 'auto');

            var sH = $suggestionsWrapp.outerHeight(),
                dH = $detailsWrapp.outerHeight(),
                minHeight = 340;

            $suggestionsWrapp.find('.dgwt-wcas-suggestion:last-child').removeClass('dgwt-wcas-suggestion-no-border-bottom');

            if (sH <= minHeight && dH <= minHeight) {
                return false;
            }

            $suggestionsWrapp.find('.dgwt-wcas-suggestion:last-child').addClass('dgwt-wcas-suggestion-no-border-bottom');

            if (dH < sH) {
                $detailsWrapp.css('height', (sH) + 'px');
            }

            if (sH < dH) {
                $suggestionsWrapp.css('height', dH + 'px');
            }

            return false;
        },
        getFormOffset: function () {
            var that = this,
                $form = that.getFormWrapper(),
                $suggestionsContainer = that.getSuggestionsContainer();

            // Choose orientation
            var orientation = that.options.orientation,
                containerHeight = $form.outerHeight(),
                height = that.el.outerHeight(),
                offset = that.el.offset(),
                styles = {'top': offset.top, 'left': offset.left};

            if (orientation === 'auto') {
                var viewPortHeight = $(window).height(),
                    scrollTop = $(window).scrollTop(),
                    topOverflow = -scrollTop + offset.top - containerHeight,
                    bottomOverflow = scrollTop + viewPortHeight - (offset.top + height + containerHeight);

                orientation = (Math.max(topOverflow, bottomOverflow) === topOverflow) ? 'top' : 'bottom';
            }

            if (orientation === 'top') {
                var sh = $suggestionsContainer[0].getBoundingClientRect().top,
                    ft = $form[0].getBoundingClientRect().top;

                $suggestionsContainer.css('height', 'auto');
                if (ft < $suggestionsContainer.height()) {
                    $suggestionsContainer.height(ft - 10);
                }
                styles.top += -$suggestionsContainer.outerHeight();
            } else {
                styles.top += height;
            }

            return styles;

        },
        getContext: function () {
            var that = this,
                $container = that.el.closest('[data-wcas-context]'),
                context = '';
            if ($container.length > 0) {
                context = $container.data('wcas-context');
            }
            return context;
        },
        getFormWrapper: function () {
            var that = this;

            return that.el.closest('[data-wcas-context]');
        },
        getSuggestionsContainer: function () {
            var that = this;
            return $('.' + that.options.containerClass + '[data-wcas-context="' + that.getContext() + '"]');
        },
        getDetailsContainer: function () {
            var that = this;
            return $('.' + that.options.containerDetailsClass + '[data-wcas-context="' + that.getContext() + '"]');
        },
        scrollDownSuggestions: function () {
            var that = this,
                $el = that.getSuggestionsContainer();
            $el[0].scrollTop = $el[0].scrollHeight;
        },
        isCursorAtEnd: function () {
            var that = this,
                valLength = that.el.val().length,
                selectionStart = that.element.selectionStart,
                range;

            if (typeof selectionStart === 'number') {
                return selectionStart === valLength;
            }
            if (document.selection) {
                range = document.selection.createRange();
                range.moveStart('character', -valLength);
                return valLength === range.text.length;
            }
            return true;
        },
        onKeyPress: function (e) {
            var that = this;

            // If suggestions are hidden and user presses arrow down, display suggestions:
            if (!that.disabled && !that.visible && e.which === keys.DOWN && that.currentValue) {
                that.suggest();
                return;
            }

            if (that.disabled || !that.visible) {
                return;
            }

            switch (e.which) {
                case keys.ESC:
                    that.el.val(that.currentValue);
                    that.hide();
                    break;
                case keys.RIGHT:
                    if (that.hint && that.options.onHint && that.isCursorAtEnd()) {
                        that.selectHint();
                        break;
                    }
                    return;
                case keys.TAB:
                    if (that.hint && that.options.onHint) {
                        that.selectHint();
                        return;
                    }
                    if (that.selectedIndex === -1) {
                        that.hide();
                        return;
                    }
                    that.select(that.selectedIndex);
                    if (that.options.tabDisabled === false) {
                        return;
                    }
                    break;
                case keys.RETURN:
                    that.hide();
                    return;
                    /*if (that.selectedIndex === -1) {
                        that.hide();
                        return;
                    }
                    that.select(that.selectedIndex);*/
                    break;
                case keys.UP:
                    that.moveUp();
                    break;
                case keys.DOWN:
                    that.moveDown();
                    break;
                default:
                    return;
            }

            // Cancel event if function did not return:
            e.stopImmediatePropagation();
            e.preventDefault();
        },
        onKeyUp: function (e) {
            var that = this;

            if (that.disabled) {
                return;
            }

            switch (e.which) {
                case keys.UP:
                case keys.DOWN:
                    return;
            }

            clearTimeout(that.onChangeTimeout);

            if (that.currentValue !== that.el.val()) {
                that.findBestHint();
                if (that.options.deferRequestBy > 0) {
                    // Defer lookup in case when value changes very quickly:
                    that.onChangeTimeout = setTimeout(function () {
                        that.onValueChange();
                    }, that.options.deferRequestBy);
                } else {
                    that.onValueChange();
                }
            }
        },
        onValueChange: function () {
            if (this.ignoreValueChange) {
                this.ignoreValueChange = false;
                return;
            }

            var that = this,
                options = that.options,
                value = that.el.val(),
                query = that.getQuery(value);

            if (that.selection && that.currentValue !== query) {
                that.selection = null;
                (options.onInvalidateSelection || $.noop).call(that.element);
            }

            clearTimeout(that.onChangeTimeout);
            that.currentValue = value;
            that.selectedIndex = -1;

            // Check existing suggestion for the match before proceeding:
            if (options.triggerSelectOnValidInput && that.isExactMatch(query)) {
                that.select(0);
                return;
            }

            if (query.length < options.minChars) {
                $('.' + that.options.closeTrigger).removeClass(that.options.closeTrigger);
                that.hide();
            } else {
                that.getSuggestions(query);
            }
        },
        isExactMatch: function (query) {
            var suggestions = this.suggestions;

            return (suggestions.length === 1 && suggestions[0].value.toLowerCase() === query.toLowerCase());
        },
        canShowDetailsBox: function () {
            var that = this;

            return that.options.showDetailsPanel == true && !that.isMobileMode();
        },
        isMobileMode: function () {
            var that = this;
            return $(window).width() < that.options.mobileBreakpoint
        },
        getQuery: function (value) {
            var delimiter = this.options.delimiter,
                parts;

            if (!delimiter) {
                return value;
            }
            parts = value.split(delimiter);
            return $.trim(parts[parts.length - 1]);
        },
        getSuggestionsLocal: function (query) {
            var that = this,
                options = that.options,
                queryLowerCase = query.toLowerCase(),
                filter = options.lookupFilter,
                limit = parseInt(options.lookupLimit, 10),
                data;

            data = {
                suggestions: $.grep(options.lookup, function (suggestion) {
                    return filter(suggestion, query, queryLowerCase);
                })
            };

            if (limit && data.suggestions.length > limit) {
                data.suggestions = data.suggestions.slice(0, limit);
            }

            return data;
        },
        getSuggestions: function (q) {
            var response,
                that = this,
                options = that.options,
                serviceUrl = options.serviceUrl,
                searchForm = that.getFormWrapper(),
                params,
                cacheKey,
                ajaxSettings;

            options.params[options.paramName] = q;

            if (typeof dgwt_wcas.current_lang != 'undefined') {
                options.params['l'] = dgwt_wcas.current_lang;
            }

            that.preloader('show', 'form', 'dgwt-wcas-inner-preloader');
            searchForm.addClass('dgwt-wcas-processing');

            if (options.onSearchStart.call(that.element, options.params) === false) {
                return;
            }

            params = options.ignoreParams ? null : options.params;

            if ($.isFunction(options.lookup)) {
                options.lookup(q, function (data) {
                    that.suggestions = data.suggestions;
                    that.suggest();
                    that.selectFirstSuggestion(data.suggestions);
                    options.onSearchComplete.call(that.element, q, data.suggestions);
                });
                return;
            }

            if (that.isLocal) {
                response = that.getSuggestionsLocal(q);
            } else {
                if ($.isFunction(serviceUrl)) {
                    serviceUrl = serviceUrl.call(that.element, q);
                }
                cacheKey = serviceUrl + '?' + $.param(params || {});
                response = that.cachedResponse[cacheKey];
            }

            if (response && Array.isArray(response.suggestions)) {
                that.suggestions = response.suggestions;
                that.suggest();
                that.selectFirstSuggestion(response.suggestions);
                options.onSearchComplete.call(that.element, q, response.suggestions);
            } else if (!that.isBadQuery(q)) {
                that.abortAjax();

                ajaxSettings = {
                    url: serviceUrl,
                    data: params,
                    type: options.type,
                    dataType: options.dataType
                };

                $.extend(ajaxSettings, options.ajaxSettings);

                ajaxDebounceState.object = that;
                ajaxDebounceState.ajaxSettings = ajaxSettings;

                utils.debounce(function () {

                    var that = ajaxDebounceState.object,
                        ajaxSettings = ajaxDebounceState.ajaxSettings;

                    that.currentRequest = $.ajax(ajaxSettings).done(function (data) {
                        var result;
                        that.currentRequest = null;
                        result = that.options.transformResult(data, q);

                        if (typeof result.suggestions !== 'undefined') {
                            that.processResponse(result, q, cacheKey);
                            that.selectFirstSuggestion(result.suggestions);

                            if (
                                result.suggestions.length === 1
                                && typeof result.suggestions[0].type !== 'undefined'
                                && result.suggestions[0].type === 'no-results'
                            ) {
                                that.gaEvent(q, 'Autocomplete Search without results');
                            } else {
                                that.gaEvent(q, 'Autocomplete Search with results');
                            }

                        }

                        that.fixPositionCapture();

                        that.options.onSearchComplete.call(that.element, q, result.suggestions);


                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        that.options.onSearchError.call(that.element, q, jqXHR, textStatus, errorThrown);
                    });

                }, options.debounceWaitMs);


            } else {
                options.onSearchComplete.call(that.element, q, []);
            }

        },
        getDetails: function (suggestion) {

            var that = this,
                options = that.options;

            // Disable details panel
            if (!that.canShowDetailsBox()) {
                return false;
            }

            // Brake if there are no suggestions
            if (suggestion == null || suggestion.type == 'undefined') {
                return;
            }

            // Disable on more product suggestion
            if (typeof suggestion.more_products == 'string' && suggestion.more_products === 'more_products') {
                return;
            }

            that.fixHeight();

            var cacheKey,
                $containerDetails = that.getDetailsContainer(),
                currentObjectID = that.prepareSuggestionObjectID(suggestion),
                result;

            // Check cache
            result = that.cachedDetails[currentObjectID];

            if (result != null) {

                // Load response from cache
                $containerDetails.html(result.html);
                that.fixHeight();
                that.fixPositionCapture();

            } else {

                var data = {
                    action: dgwt_wcas.action_result_details,
                    items: []
                };

                $.each(that.suggestions, function (i, suggestion) {

                    if(
                        typeof suggestion.type != 'undefined'
                        && suggestion.type != 'more_products'
                        && suggestion.type != 'headline'
                    ){
                        var itemData = {
                            objectID: that.prepareSuggestionObjectID(suggestion),
                            value: suggestion.value != null ? suggestion.value : ''
                        };
                        data.items.push(itemData);
                    }
                });


                $containerDetails.html('');
                that.preloader('show', 'details', '', true);

                // Prevent duplicate ajax requests
                if ($.inArray(currentObjectID, that.detailsRequestsSent) != -1) {
                    return;
                } else {
                    that.detailsRequestsSent.push(currentObjectID);
                }

                $.ajax({
                    data: data,
                    type: 'post',
                    url: dgwt_wcas.ajax_details_endpoint,
                    success: function (response) {

                        var result = typeof response === 'string' ? jQuery.parseJSON(response) : response;

                        if(typeof result.items != 'undefined'){
                            for(var i = 0 ; i < result.items.length ; i++){
                                var cacheKey = result.items[i]['objectID'];
                                that.cachedDetails[cacheKey] = {html: result.items[i]['html']};
                            }
                        }

                        that.preloader('hide', 'details', '', true);

                        var currentObjectID = that.prepareSuggestionObjectID(that.suggestions[that.selectedIndex]);

                        if (that.cachedDetails[currentObjectID] != null) {
                            $containerDetails.html(that.cachedDetails[currentObjectID]['html']);
                        } else {
                            // @TODO Co wyświetlić w Details panel gdy napotkamy błąd?
                            $containerDetails.html('');
                        }
                        that.fixPositionCapture();
                        that.fixHeight();
                    },
                    error: function (jqXHR, exception) {

                        that.preloader('hide', 'details', '', true);

                        $containerDetails.html(jqXHR);
                        that.fixPositionCapture();
                        that.fixHeight();
                    },
                });
            }

            $(document).trigger('dgwtWcasDetailsPanelLoaded', that);
        },
        prepareSuggestionObjectID(suggestion) {
            var objectID = '';

            if (typeof suggestion != 'undefined' && typeof suggestion.type != 'undefined') {

                if (suggestion.post_id != null) {
                    objectID = suggestion.type + '__' + suggestion.post_id;

                    if(suggestion.type === 'product_variation'){
                        objectID += '__' + suggestion.variation_id;
                    }
                }


                if (suggestion.term_id != null && suggestion.taxonomy != null) {
                    objectID = suggestion.type + '__' + suggestion.term_id + '__' + suggestion.taxonomy;
                }
            }

            return objectID;
        },
        selectFirstSuggestion(suggestions) {
            var that = this,
                index = 0,
                noResults = false;

            if (!that.canShowDetailsBox()) {
                return;
            }

            if (suggestions != 'undefined' && suggestions.length > 0) {
                $.each(that.suggestions, function (i, suggestion) {

                    if (
                        typeof suggestion.type != 'undefined'
                        && suggestion.type != 'more_products'
                        && suggestion.type != 'headline'
                        && suggestion.type != 'no-results'
                    ) {
                        index = i;
                        return false;
                    }

                    if(typeof suggestion.type === 'undefined' || suggestion.type === 'no-results'){
                        noResults = true;
                    }

                });
            }

            if(noResults){
                return;
            }

            that.getDetails(suggestions[index]);
            that.activate(index);
        },
        isBadQuery: function (q) {
            if (!this.options.preventBadQueries) {
                return false;
            }

            var badQueries = this.badQueries,
                i = badQueries.length;

            while (i--) {
                if (q.indexOf(badQueries[i]) === 0) {
                    return true;
                }
            }

            return false;
        },
        hide: function () {
            var that = this,
                $container = that.getSuggestionsContainer(),
                $containerDetails = that.getDetailsContainer();

            if ($.isFunction(that.options.onHide) && that.visible) {
                that.options.onHide.call(that.element, container);
            }

            that.visible = false;
            that.selectedIndex = -1;
            clearTimeout(that.onChangeTimeout);
            $container.hide();
            $container.removeClass(that.classes.suggestionsContainerOrientTop);
            $containerDetails.hide();

            $('body').removeClass('dgwt-wcas-open');
            $('body').removeClass('dgwt-wcas-block-scroll');

            that.signalHint(null);
        },
        hideAfterClickOutsideListener: function () {
            var that = this;
            if (!that.isMobileMode()) {

                $(document).mouseup(function (e) {
                    if (!that.visible) {
                        return;
                    }

                    var $container = that.getSuggestionsContainer(),
                        $containerDetails = that.getDetailsContainer(),
                        outsideForm = !($(e.target).closest('.' + that.options.searchFormClass).length > 0 || $(e.target).hasClass(that.options.searchFormClass)),
                        outsideContainer = !($(e.target).closest('.' + that.options.containerClass).length > 0 || $(e.target).hasClass(that.options.containerClass));


                    if (!that.canShowDetailsBox()) {

                        if (outsideForm && outsideContainer) {
                            that.hide();
                        }

                    } else {

                        var outsidecontainerDetails = !($(e.target).closest('.' + that.options.containerDetailsClass).length > 0 || $(e.target).hasClass(that.options.containerDetailsClass));

                        if (outsideForm && outsideContainer && outsidecontainerDetails) {
                            that.hide();
                        }

                    }

                });

            }
        },
        suggest: function () {
            if (!this.suggestions.length) {
                if (this.options.showNoSuggestionNotice) {
                    this.noSuggestions();
                } else {
                    this.hide();
                }
                return;
            }

            var that = this,
                options = that.options,
                groupBy = options.groupBy,
                formatResult = options.formatResult,
                value = that.getQuery(that.currentValue),
                className = that.classes.suggestion,
                classSelected = that.classes.selected,
                container = that.getSuggestionsContainer(),
                containerDetails = that.getDetailsContainer(),
                noSuggestionsContainer = $(that.noSuggestionsContainer),
                beforeRender = options.beforeRender,
                html = '',
                category,
                formatGroup = function (suggestion, index) {
                    var currentCategory = suggestion.data[groupBy];

                    if (category === currentCategory) {
                        return '';
                    }

                    category = currentCategory;

                    return '<div class="autocomplete-group"><strong>' + category + '</strong></div>';
                };

            if (options.triggerSelectOnValidInput && that.isExactMatch(value)) {
                that.select(0);
                return;
            }

            $('body').removeClass('dgwt-wcas-nores');

            // Build suggestions inner HTML:
            $.each(that.suggestions, function (i, suggestion) {

                var parent = '',
                    dataAttrs = '',
                    is_img = false;

                if (groupBy) {
                    html += formatGroup(suggestion, value, i);
                }

                if (typeof suggestion.type == 'undefined' || (suggestion.type != 'product') && suggestion.type != 'product_variation') {

                    var dataUrl = '',
                        classes = className,
                        innerClass = 'dgwt-wcas-st',
                        prepend = '',
                        append = '',
                        title = '',
                        highlight = true;

                    if (suggestion.taxonomy === 'product_cat') {
                        classes += ' dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-cat';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.t.category + '</span>';
                        }
                        if (typeof suggestion.breadcrumbs != 'undefined' && suggestion.breadcrumbs) {
                            title = suggestion.breadcrumbs + ' &gt; ' + suggestion.value;
                            append += '<span class="dgwt-wcas-st-breadcrumbs">' + dgwt_wcas.copy_in_category + ' ' + suggestion.breadcrumbs + '</span>';
                            //@TODO RTL support
                        }

                    } else if (suggestion.taxonomy === 'product_tag') {
                        classes += ' dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-tag';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.t.tag + '</span>';
                        }
                    } else if (options.isPremium && suggestion.taxonomy === options.taxonomyBrands) {
                        classes += ' dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-brand';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.t.brand + '</span>';
                        }
                    } else if (options.isPremium && suggestion.type === 'post') {
                        classes += ' dgwt-wcas-suggestion-pt dgwt-wcas-suggestion-tp-post';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.t.post + '</span>';
                        }
                    } else if (options.isPremium && suggestion.type === 'page') {
                        classes += ' dgwt-wcas-suggestion-pt dgwt-wcas-suggestion-pt-page';
                        if (!options.showHeadings) {
                            prepend += '<span class="dgwt-wcas-st--direct-headline">' + dgwt_wcas.t.page + '</span>';
                        }
                    } else if (suggestion.type === 'more_products') {
                        classes += ' js-dgwt-wcas-suggestion-more dgwt-wcas-suggestion-more';
                        innerClass = 'dgwt-wcas-st-more';
                        suggestion.value = dgwt_wcas.copy_show_more + ' (' + suggestion.total + ')';
                        highlight = false;
                    } else if (options.showHeadings && suggestion.type === 'headline') {
                        classes += ' js-dgwt-wcas-suggestion-headline dgwt-wcas-suggestion-headline';
                        if (typeof dgwt_wcas.t[suggestion.value + '_plu'] != 'undefined') {
                            suggestion.value = dgwt_wcas.t[suggestion.value + '_plu'];
                        }
                        highlight = false;
                    } else {
                        classes += ' dgwt-wcas-suggestion-nores';
                        suggestion.value = dgwt_wcas.copy_no_result;
                        highlight = false;
                        $('body').addClass('dgwt-wcas-nores');
                    }

                    title = title.length > 0 ? ' title="' + title + '"' : '';

                    html += '<div class="' + classes + '" data-index="' + i + '">';
                    html += '<span' + title + ' class="' + innerClass + '">' + prepend + formatResult(suggestion.value, value, highlight) + append + '</span>';
                    html += '</div>';
                } else {

                    // Image
                    if (options.showImage === true && typeof suggestion.thumb_html != 'undefined') {
                        is_img = true;
                    }

                    // One suggestion HTML
                    dataAttrs += typeof suggestion.post_id != 'undefined' ? 'data-post-id="' + suggestion.post_id + '" ' : '';
                    dataAttrs += typeof suggestion.taxonomy != 'undefined' ? 'data-taxonomy="' + suggestion.taxonomy + '" ' : '';
                    dataAttrs += typeof suggestion.term_id != 'undefined' ? 'data-term-id="' + suggestion.term_id + '" ' : '';
                    html += '<div class="' + className + ' dgwt-wcas-suggestion-product" data-index="' + i + '" ' + dataAttrs + '>';

                    // Image
                    if (is_img) {
                        html += '<span class="dgwt-wcas-si">' + suggestion.thumb_html + '</span>';
                    }


                    html += is_img ? '<div class="dgwt-wcas-content-wrapp">' : '';


                    // Title
                    html += '<span class="dgwt-wcas-st">';
                    html += '<span class="dgwt-wcas-st-title">' + formatResult(suggestion.value, value, true) + parent + '</span>';

                    // SKU
                    if (options.showSKU === true && typeof suggestion.sku != 'undefined' && suggestion.sku.length > 0) {
                        html += '<span class="dgwt-wcas-sku">(' + dgwt_wcas.t.sku_label + ' ' + formatResult(suggestion.sku, value, true) + ')</span>';
                    }

                    // Description
                    if (options.showDescription === true && typeof suggestion.desc != 'undefined' && suggestion.desc) {
                        html += '<span class="dgwt-wcas-sd">' + formatResult(suggestion.desc, value, true) + '</span>';
                    }

                    html += '</span>';

                    // Price
                    if (options.showPrice === true && typeof suggestion.price != 'undefined') {
                        html += '<span class="dgwt-wcas-sp">' + suggestion.price + '</span>';
                    }

                    // On sale product badge
                    if (options.showFeaturedBadge === true && suggestion.on_sale === true) {
                        html += '<span class="dgwt-wcas-badge dgwt-wcas-badge-os">' + options.saleBadgeText + '</span>';
                    }

                    // Featured product badge
                    if (options.showFeaturedBadge === true && suggestion.featured === true) {
                        html += '<span class="dgwt-wcas-badge dgwt-wcas-badge-f">' + options.featuredBadgeText + '</span>';
                    }


                    html += is_img ? '</div>' : '';
                    html += '</div>';

                }
            });

            this.adjustContainerWidth();

            noSuggestionsContainer.detach();
            container.html(html);

            if ($.isFunction(beforeRender)) {
                beforeRender.call(that.element, container, that.suggestions);
            }

            that.fixPositionCapture();
            container.show();

            // Add class on show
            $('body').addClass('dgwt-wcas-open');

            if (options.showDetailsPanel === true) {
                containerDetails.show();
                that.fixHeight();
            }

            // Select first value by default:
            if (options.autoSelectFirst) {
                that.selectedIndex = 0;
                container.scrollTop(0);
                container.children('.' + className).first().addClass(classSelected);
            }

            that.visible = true;

            if (that.options.orientation === 'top') {
                that.getSuggestionsContainer().addClass(that.classes.suggestionsContainerOrientTop);
                $('body').addClass('dgwt-wcas-block-scroll');
                setTimeout(function () {
                    that.scrollDownSuggestions();
                }, 300);
            }

            that.findBestHint();
        },
        noSuggestions: function () {
            var that = this,
                beforeRender = that.options.beforeRender,
                container = that.getSuggestionsContainer(),
                noSuggestionsContainer = $(that.noSuggestionsContainer);

            // Some explicit steps. Be careful here as it easy to get
            // noSuggestionsContainer removed from DOM if not detached properly.
            noSuggestionsContainer.detach();

            // clean suggestions if any
            container.empty();
            container.append(noSuggestionsContainer);

            if ($.isFunction(beforeRender)) {
                beforeRender.call(that.element, container, that.suggestions);
            }

            that.fixPositionCapture();

            container.show();
            that.visible = true;
        },
        adjustContainerWidth: function () {
            var that = this,
                options = that.options,
                width,
                container = $('body'),
                searchForm = that.getFormWrapper(),
                containerSugg = that.getSuggestionsContainer(),
                containerDetails = that.getDetailsContainer(),
                maxWidth = 550,
                offset = that.getFormOffset(),
                realWidth = getComputedStyle(searchForm[0]).width;

            realWidth = Math.round(parseFloat(realWidth.replace('px', '')));

            // If width is auto, adjust width before displaying suggestions,
            if (options.width === 'auto') {
                width = that.el.outerWidth();
                containerSugg.css('width', width + 'px');
            }

            if (options.showDetailsPanel === true) {

                // Set specific style on the bigger search form
                if (searchForm.width() >= maxWidth) {

                    container.addClass('dgwt-wcas-full-width');


                    if (realWidth % 2 == 0) {
                        containerSugg.css('width', Math.round(realWidth / 2));
                        containerDetails.css('width', Math.round(realWidth / 2));
                    } else {
                        containerSugg.css('width', Math.floor(realWidth / 2));
                        containerDetails.css('width', Math.ceil(realWidth / 2));
                    }


                    container.removeClass('dgwt-wcas-details-left');
                    container.removeClass('dgwt-wcas-details-right');

                    if (options.isRtl === true) {
                        containerDetails.css('left', '0');
                    } else {
                        containerSugg.css('left', (realWidth / 2 + offset.left) + 'px');
                    }

                    return;
                }

                container.addClass('dgwt-wcas-details-right');

            }

        },
        findBestHint: function () {
            var that = this,
                value = that.el.val().toLowerCase(),
                bestMatch = null;

            if (!value) {
                return;
            }

            $.each(that.suggestions, function (i, suggestion) {
                var foundMatch = suggestion.value.toLowerCase().indexOf(value) === 0;
                if (foundMatch) {
                    bestMatch = suggestion;
                }
                return !foundMatch;
            });

            that.signalHint(bestMatch);
        },
        signalHint: function (suggestion) {
            var hintValue = '',
                that = this;
            if (suggestion) {
                hintValue = that.currentValue + suggestion.value.substr(that.currentValue.length);
            }
            if (that.hintValue !== hintValue) {
                that.hintValue = hintValue;
                that.hint = suggestion;
                (this.options.onHint || $.noop)(hintValue);
            }
        },
        /*
         * Manages preloader
         *
         * @param action (show or hide)
         * @param container (parent selector)
         * @param cssClass
         * @param detailsBox bool
         */
        preloader: function (action, place, cssClass, detailsBox) {

            var that = this,
                html,
                container,
                defaultClass = 'dgwt-wcas-preloader-wrapp',
                cssClasses = cssClass == null ? defaultClass : defaultClass + ' ' + cssClass;


            if (place === 'form') {
                container = that.getFormWrapper().find('.dgwt-wcas-preloader');
            } else if (place === 'details') {
                container = $(that.detailsContainer);
            }

            // Disable preloader and check if container exist  

            if (dgwt_wcas.show_preloader != 1 || container.length == 0) {
                return;
            }

            if (detailsBox !== true) {
                if (action === 'hide') {
                    container.removeClass(cssClass);
                } else {
                    container.addClass(cssClass);
                }
                return;
            }

            // Action hide
            if (action === 'hide') {
                $(defaultClass).remove();
                return
            }

            // Action show
            if (action === 'show') {
                // html = '<div class="' + cssClasses + '"><div class="dgwt-wcas-default-preloadera"></div></div>';
                var rtlSuffix = that.options.isRtl ? '-rtl' : '';
                html = '<div class="' + cssClasses + '"><img class="dgwt-wcas-placeholder-preloader" src="' + dgwt_wcas.img_url + 'placeholder' + rtlSuffix + '.png" /></div>';
                container.html(html);
            }
        },
        verifySuggestionsFormat: function (suggestions) {

            // If suggestions is string array, convert them to supported format:
            if (suggestions.length && typeof suggestions[0] === 'string') {
                return $.map(suggestions, function (value) {
                    return {value: value, data: null};
                });
            }

            return suggestions;
        },
        validateOrientation: function (orientation, fallback) {
            orientation = $.trim(orientation || '').toLowerCase();

            if ($.inArray(orientation, ['auto', 'bottom', 'top']) === -1) {
                orientation = fallback;
            }

            return orientation;
        },
        processResponse: function (result, originalQuery, cacheKey) {
            var that = this,
                options = that.options;

            result.suggestions = that.verifySuggestionsFormat(result.suggestions);

            // Cache results if cache is not disabled:
            if (!options.noCache) {
                that.cachedResponse[cacheKey] = result;
                if (options.preventBadQueries && !result.suggestions.length) {
                    that.badQueries.push(originalQuery);
                }
            }

            // Return if originalQuery is not matching current query:
            if (originalQuery !== that.getQuery(that.currentValue)) {
                return;
            }

            if (that.options.orientation === 'top') {
                result.suggestions.reverse();
            }

            that.suggestions = result.suggestions;
            that.suggest();
        },
        activate: function (index) {
            var that = this,
                activeItem,
                selected = that.classes.selected,
                container = that.getSuggestionsContainer(),
                children = container.find('.' + that.classes.suggestion);

            container.find('.' + selected).removeClass(selected);

            that.selectedIndex = index;

            if (that.selectedIndex !== -1 && children.length > that.selectedIndex) {
                activeItem = children.get(that.selectedIndex);
                $(activeItem).addClass(selected);
                return activeItem;
            }

            return null;
        },
        selectHint: function () {
            var that = this,
                i = $.inArray(that.hint, that.suggestions);

            that.select(i);
        },
        select: function (i) {
            var that = this;
            that.hide();
            that.onSelect(i);
        },
        moveUp: function () {
            var that = this;

            if (that.selectedIndex === -1) {
                return;
            }

            if (that.selectedIndex === 0) {
                that.getSuggestionsContainer().children('.' + that.classes.suggestion).first().removeClass(that.classes.selected);
                that.selectedIndex = -1;
                that.ignoreValueChange = false;
                that.el.val(that.currentValue);
                that.findBestHint();
                return;
            }

            that.adjustScroll(that.selectedIndex - 1);
        },
        moveDown: function () {
            var that = this;

            if (that.selectedIndex === (that.suggestions.length - 1)) {
                return;
            }

            that.adjustScroll(that.selectedIndex + 1);
        },
        adjustScroll: function (index) {
            var that = this,
                activeItem = that.activate(index);

            that.getDetails(that.suggestions[index]);


            if (!activeItem || that.canShowDetailsBox()) {
                return;
            }

            var offsetTop,
                upperBound,
                lowerBound,
                $suggestionContainer = that.getSuggestionsContainer(),
                heightDelta = $(activeItem).outerHeight();

            offsetTop = activeItem.offsetTop;
            upperBound = $suggestionContainer.scrollTop();
            lowerBound = upperBound + that.options.maxHeight - heightDelta;

            if (offsetTop < upperBound) {
                $suggestionContainer.scrollTop(offsetTop);
            } else if (offsetTop > lowerBound) {
                $suggestionContainer.scrollTop(offsetTop - that.options.maxHeight + heightDelta);
            }

            if (!that.options.preserveInput) {
                // During onBlur event, browser will trigger "change" event,
                // because value has changed, to avoid side effect ignore,
                // that event, so that correct suggestion can be selected
                // when clicking on suggestion with a mouse
                that.ignoreValueChange = true;
                that.el.val(that.getValue(that.suggestions[index].value));
            }
            that.signalHint(null);
        },
        onSelect: function (index) {
            var that = this,
                onSelectCallback = that.options.onSelect,
                suggestion = that.suggestions[index];

            if (typeof suggestion.type != 'undefined' && suggestion.type === 'more_products') {
                that.el.closest('form').trigger('submit');
                return;
            }

            that.currentValue = that.getValue(suggestion.value);

            if (that.currentValue !== that.el.val() && !that.options.preserveInput) {
                that.el.val(that.currentValue);
            }

            if (suggestion.url.length > 0) {
                window.location.href = suggestion.url;
            }

            that.signalHint(null);
            that.suggestions = [];
            that.selection = suggestion;
            if ($.isFunction(onSelectCallback)) {
                onSelectCallback.call(that.element, suggestion);
            }
        },
        getValue: function (value) {
            var that = this,
                delimiter = that.options.delimiter,
                currentValue,
                parts;

            if (!delimiter) {
                return value;
            }

            currentValue = that.currentValue;
            parts = currentValue.split(delimiter);

            if (parts.length === 1) {
                return value;
            }

            return currentValue.substr(0, currentValue.length - parts[parts.length - 1].length) + value;
        },
        dispose: function () {
            var that = this;
            that.el.off('.autocomplete').removeData('autocomplete');
            $(window).off('resize.autocomplete', that.fixPositionCapture);
            $('.' + that.options.containerClass).remove();
            $('.' + that.options.containerDetailsClass).remove();
        },
        enableOverlayMobile: function () {
            var that = this;

            if (that.overlayMobileState === 'on') {
                return;
            }

            that.overlayMobileState = 'on';

            var zIndex = 999999,
                $wrapper = that.getFormWrapper(),
                $suggestionsWrapp = that.getSuggestionsContainer(),
                $overlayWrap,
                html = '';

            $('html').addClass('dgwt-wcas-overlay-mobile-on');
            html += '<div class="js-dgwt-wcas-overlay-mobile dgwt-wcas-overlay-mobile">';
            html += '<div class="dgwt-wcas-om-bar js-dgwt-wcas-om-bar">';
            html += '<span class="dgwt-wcas-om-return js-dgwt-wcas-om-return">';
            html += '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" color="#FFF">';
            html += '<path fill="#FFF" d="M14 6.125H3.351l4.891-4.891L7 0 0 7l7 7 1.234-1.234L3.35 7.875H14z" fill-rule="evenodd"></path>';
            html += '</svg>';
            html += '</span>';
            html += '</div>';
            html += '</div>';

            // Create overlay
            $(that.options.mobileOverlayWrapper).append(html);
            $overlayWrap = $('.js-dgwt-wcas-overlay-mobile');
            $overlayWrap.css('zIndex', zIndex);

            $wrapper.after('<span class="js-dgwt-wcas-om-hook"></span>');
            $wrapper.appendTo('.js-dgwt-wcas-om-bar');
            $suggestionsWrapp.appendTo('.js-dgwt-wcas-om-bar');
            $wrapper.addClass('dgwt-wcas-search-wrapp-mobile');

            if ($wrapper.hasClass('dgwt-wcas-has-submit')) {
                $wrapper.addClass('dgwt-wcas-has-submit-off');
                $wrapper.removeClass('dgwt-wcas-has-submit');
            }

            $wrapper.find('.' + that.options.searchInputClass).focus();

            $(document).on('click', '.js-dgwt-wcas-om-return', function (e) {
                that.disableOverlayMobile($overlayWrap);
            });
        },
        disableOverlayMobile: function ($overlayWrap) {
            var that = this;

            var $suggestionsWrapp = that.getSuggestionsContainer();

            var $clonedForm = $('.js-dgwt-wcas-om-bar').find('.' + that.options.searchFormClass);

            if ($clonedForm.hasClass('dgwt-wcas-has-submit-off')) {
                $clonedForm.removeClass('dgwt-wcas-has-submit-off');
                $clonedForm.addClass('dgwt-wcas-has-submit');
            }

            $clonedForm.removeClass('dgwt-wcas-search-wrapp-mobile');
            $('html').removeClass('dgwt-wcas-overlay-mobile-on');
            $suggestionsWrapp.appendTo('body');
            $suggestionsWrapp.removeAttr('body-scroll-lock-ignore');
            $('.js-dgwt-wcas-om-hook').after($clonedForm);
            $('.js-dgwt-wcas-overlay-mobile').remove();
            $('.js-dgwt-wcas-om-hook').remove();

            setTimeout(function () {
                $clonedForm.find('.' + that.options.searchInputClass).val('');
                var $closeBtn = $clonedForm.find('.dgwt-wcas-close');
                if ($clonedForm.length > 0) {
                    $closeBtn.removeClass('dgwt-wcas-close');
                }
            }, 150);


            that.overlayMobileState = 'off';
        },
        elementOrParentIsFixed: function ($element) {

            var $checkElements = $element.add($element.parents());
            var isFixed = false;

            $checkElements.each(function () {

                if ($(this).css("position") === "fixed") {
                    isFixed = true;
                    return false;
                }
            });
            return isFixed;
        },
        gaEvent: function (label, category) {
            var that = this;
            if (that.options.sendGAEvents) {
                try {

                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'autocomplete_search', {
                            'event_label': label,
                            'event_category': category
                        });
                        return;
                    }

                    if (typeof ga !== 'undefined') {
                        ga('send', {
                            hitType: 'event',
                            eventCategory: category,
                            eventAction: 'autocomplete_search',
                            eventLabel: label
                        });
                    }

                } catch (error) {
                }
            }
        }
    };


    // Create chainable jQuery plugin:
    $.fn.dgwtWcasAutocomplete = function (options, args) {
        var dataKey = 'autocomplete';
        // If function invoked without argument return
        // instance of the first matched element:
        if (!arguments.length) {
            return this.first().data(dataKey);
        }

        return this.each(function () {
            var inputElement = $(this),
                instance = inputElement.data(dataKey);

            if (typeof options === 'string') {
                if (instance && typeof instance[options] === 'function') {
                    instance[options](args);
                }
            } else {
                // If instance already exists, destroy it:
                if (instance && instance.dispose) {
                    instance.dispose();
                }
                instance = new Autocomplete(this, options);
                inputElement.data(dataKey, instance);
            }
        });
    };

    // Don't overwrite if it already exists
    if (!$.fn.autocomplete) {
        $.fn.autocomplete = $.fn.dgwtWcasAutocomplete;
    }

    (function () {

        /*-----------------------------------------------------------------
        /* Positioning search preloader
        /*------------------------------------------------------------------*/
        $(window).on('load', function () {
            if ($('.dgwt-wcas-search-submit').length > 0) {
                $('.dgwt-wcas-search-submit').each(function () {

                    var $preloader = $(this).closest('.dgwt-wcas-search-wrapp').find('.dgwt-wcas-preloader');

                    if (dgwt_wcas.is_rtl == 1) {
                        $preloader.css('left', (6 + $(this).outerWidth()) + 'px');
                    } else {
                        $preloader.css('right', $(this).outerWidth() + 'px');
                    }
                });
            }
        });

        // RUN
        $(document).ready(function () {
            "use strict";

            /*-----------------------------------------------------------------
            /* Fix the wrong context returned from PHP
            /* because some themes or builders do it wrong
            /*------------------------------------------------------------------*/
            makeContextUnique(true);

            /*-----------------------------------------------------------------
             /* Fire autocomplete
             /*------------ -----------------------------------------------------*/
            var showDetailsPanel = dgwt_wcas.show_details_box == 1 ? true : false;
            var mobileBreakpoint = dgwt_wcas.mobile_breakpoint;

            // Disable details panel on small screens
            if (jQuery(window).width() < mobileBreakpoint || ('ontouchend' in document)) {
                showDetailsPanel = false;
            }

            $('.dgwt-wcas-search-input').dgwtWcasAutocomplete({
                minChars: dgwt_wcas.min_chars,
                width: dgwt_wcas.sug_width,
                autoSelectFirst: false,
                triggerSelectOnValidInput: false,
                serviceUrl: dgwt_wcas.ajax_search_endpoint,
                paramName: 's',
                showDetailsPanel: showDetailsPanel,
                showImage: dgwt_wcas.show_images == 1 ? true : false,
                showPrice: dgwt_wcas.show_price == 1 ? true : false,
                showDescription: dgwt_wcas.show_desc == 1 ? true : false,
                showSKU: dgwt_wcas.show_sku == 1 ? true : false,
                showSaleBadge: dgwt_wcas.show_sale_badge == 1 ? true : false,
                showFeaturedBadge: dgwt_wcas.show_featured_badge == 1 ? true : false,
                saleBadgeText: dgwt_wcas.t.sale_badge,
                featuredBadgeText: dgwt_wcas.t.featured_badge,
                isRtl: dgwt_wcas.is_rtl == 1 ? true : false,
                showHeadings: dgwt_wcas.show_headings == 1 ? true : false,
                isPremium: dgwt_wcas.is_premium == 1 ? true : false,
                taxonomyBrands: dgwt_wcas.taxonomy_brands,
                overlayMobile: dgwt_wcas.overlay_mobile == 1 ? true : false,
                mobileBreakpoint: mobileBreakpoint,
                mobileOverlayWrapper: dgwt_wcas.mobile_overlay_wrapper,
                debounceWaitMs: dgwt_wcas.debounce_wait_ms,
                sendGAEvents: dgwt_wcas.send_ga_events,
            });

        });

        /*-----------------------------------------------------------------
        /* Fix the wrong context again
        /* Some page builders can copy instances of serach bar
        /* for eg. mobile usage
        /*------------ -----------------------------------------------------*/
        $(window).on('load', function () {
            makeContextUnique(false);
        });

        /**
         * Fix wrong context served by some themes or buildiers
         */
        function makeContextUnique(onlySearchBar) {

            var $searchWrapps = $('.dgwt-wcas-search-wrapp[data-wcas-context]');

            var uniqueContext = [];

            if ($searchWrapps.length > 0) {
                $searchWrapps.each(function () {
                    var context = $(this).attr('data-wcas-context');

                    if ($.inArray(context, uniqueContext) == -1) {
                        uniqueContext.push(context);

                    } else {
                        var newContext = Math.random().toString(36).substring(2, 6);

                        $(this).attr('data-wcas-context', newContext);

                        if (!onlySearchBar) {
                            var suggestionsContainer = $('.dgwt-wcas-suggestions-wrapp[data-wcas-context="' + context + '"]');
                            var detailsContainer = $('.dgwt-wcas-details-wrapp[data-wcas-context="' + context + '"]');

                            if (suggestionsContainer.length > 0) {
                                $(suggestionsContainer[suggestionsContainer.length - 1]).attr('data-wcas-context', newContext);
                            }

                            if (detailsContainer.length > 0) {
                                $(detailsContainer[detailsContainer.length - 1]).attr('data-wcas-context', newContext);
                            }
                        }
                    }
                });
            }

        }

    }());

}));
