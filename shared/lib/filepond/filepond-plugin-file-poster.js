/*
 * FilePondPluginFilePoster 1.1.3
 * Licensed under MIT, https://opensource.org/licenses/MIT
 * Please visit https://pqina.nl/filepond for details.
 */

/* eslint-disable */
(function(global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined'
        ? (module.exports = factory())
        : typeof define === 'function' && define.amd
        ? define(factory)
        : (global.FilePondPluginFilePoster = factory());
})(this, function() {
    'use strict';

    var IMAGE_SCALE_SPRING_PROPS = {
        type: 'spring',
        stiffness: 0.5,
        damping: 0.45,
        mass: 10
    };

    var createPosterView = function createPosterView(_) {
        return _.utils.createView({
            name: 'file-poster',
            tag: 'div',
            ignoreRect: true,
            create: function create(_ref) {
                var root = _ref.root;

                root.ref.image = document.createElement('img');
                root.element.appendChild(root.ref.image);
            },
            write: _.utils.createRoute({
                DID_FILE_POSTER_LOAD: function DID_FILE_POSTER_LOAD(_ref2) {
                    var root = _ref2.root,
                        props = _ref2.props;
                    var id = props.id;

                    // get item

                    var item = root.query('GET_ITEM', { id: props.id });
                    if (!item) return;

                    // get poster
                    var poster = item.getMetadata('poster');
                    root.ref.image.src = poster;

                    // let others know of our fabulous achievement (so the image can be faded in)
                    root.dispatch('DID_FILE_POSTER_DRAW', { id: id });
                }
            }),
            mixins: {
                styles: ['scaleX', 'scaleY', 'opacity'],
                animations: {
                    scaleX: IMAGE_SCALE_SPRING_PROPS,
                    scaleY: IMAGE_SCALE_SPRING_PROPS,
                    opacity: { type: 'tween', duration: 750 }
                }
            }
        });
    };

    var applyTemplate = function applyTemplate(source, target) {
        // copy width and height
        target.width = source.width;
        target.height = source.height;

        // draw the template
        var ctx = target.getContext('2d');
        ctx.drawImage(source, 0, 0);
    };

    var createPosterOverlayView = function createPosterOverlayView(fpAPI) {
        return fpAPI.utils.createView({
            name: 'file-poster-overlay',
            tag: 'canvas',
            ignoreRect: true,
            create: function create(_ref) {
                var root = _ref.root,
                    props = _ref.props;

                applyTemplate(props.template, root.element);
            },
            mixins: {
                styles: ['opacity'],
                animations: {
                    opacity: { type: 'spring', mass: 25 }
                }
            }
        });
    };

    var getImageSize = function getImageSize(url, cb) {
        var image = new Image();
        image.onload = function() {
            var width = image.naturalWidth;
            var height = image.naturalHeight;
            image = null;
            cb(width, height);
        };
        image.src = url;
    };

    var easeInOutSine = function easeInOutSine(t) {
        return -0.5 * (Math.cos(Math.PI * t) - 1);
    };

    var addGradientSteps = function addGradientSteps(gradient, color) {
        var alpha =
            arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;
        var easeFn =
            arguments.length > 3 && arguments[3] !== undefined
                ? arguments[3]
                : easeInOutSine;
        var steps =
            arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : 10;
        var offset =
            arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : 0;

        var range = 1 - offset;
        var rgb = color.join(',');
        for (var i = 0; i <= steps; i++) {
            var p = i / steps;
            var stop = offset + range * p;
            gradient.addColorStop(
                stop,
                'rgba(' + rgb + ', ' + easeFn(p) * alpha + ')'
            );
        }
    };

    var MAX_WIDTH = 10;
    var MAX_HEIGHT = 10;

    var calculateAverageColor = function calculateAverageColor(image) {
        var scalar = Math.min(MAX_WIDTH / image.width, MAX_HEIGHT / image.height);

        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var width = (canvas.width = Math.ceil(image.width * scalar));
        var height = (canvas.height = Math.ceil(image.height * scalar));
        ctx.drawImage(image, 0, 0, width, height);
        var data = null;
        try {
            data = ctx.getImageData(0, 0, width, height).data;
        } catch (e) {
            return null;
        }
        var l = data.length;

        var r = 0;
        var g = 0;
        var b = 0;
        var i = 0;

        for (; i < l; i += 4) {
            r += data[i] * data[i];
            g += data[i + 1] * data[i + 1];
            b += data[i + 2] * data[i + 2];
        }

        r = averageColor(r, l);
        g = averageColor(g, l);
        b = averageColor(b, l);

        return { r: r, g: g, b: b };
    };

    var averageColor = function averageColor(c, l) {
        return Math.floor(Math.sqrt(c / (l / 4)));
    };

    var drawTemplate = function drawTemplate(
        canvas,
        width,
        height,
        color,
        alphaTarget
    ) {
        canvas.width = width;
        canvas.height = height;
        var ctx = canvas.getContext('2d');

        var horizontalCenter = width * 0.5;

        var grad = ctx.createRadialGradient(
            horizontalCenter,
            height + 110,
            height - 100,
            horizontalCenter,
            height + 110,
            height + 100
        );

        addGradientSteps(grad, color, alphaTarget, undefined, 8, 0.4);

        ctx.save();
        ctx.translate(-width * 0.5, 0);
        ctx.scale(2, 1);
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, width, height);
        ctx.restore();
    };

    var hasNavigator = typeof navigator !== 'undefined';

    var width = 500;
    var height = 200;

    var overlayTemplateShadow = hasNavigator && document.createElement('canvas');
    var overlayTemplateError = hasNavigator && document.createElement('canvas');
    var overlayTemplateSuccess = hasNavigator && document.createElement('canvas');

    if (hasNavigator) {
        drawTemplate(overlayTemplateShadow, width, height, [40, 40, 40], 0.85);
        drawTemplate(overlayTemplateError, width, height, [196, 78, 71], 1);
        drawTemplate(overlayTemplateSuccess, width, height, [54, 151, 99], 1);
    }

    var loadImage = function loadImage(url) {
        return new Promise(function(resolve, reject) {
            var img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = function() {
                resolve(img);
            };
            img.onerror = function(e) {
                reject(e);
            };
            img.src = url;
        });
    };

    var createPosterWrapperView = function createPosterWrapperView(_) {
        // create overlay view
        var overlay = createPosterOverlayView(_);

        /**
         * Write handler for when preview container has been created
         */
        var didCreatePreviewContainer = function didCreatePreviewContainer(_ref) {
            var root = _ref.root,
                props = _ref.props;
            var id = props.id;

            // we need to get the file data to determine the eventual image size

            var item = root.query('GET_ITEM', id);
            if (!item) return;

            // get url to file
            var fileURL = item.getMetadata('poster');

            // image is now ready
            var previewImageLoaded = function previewImageLoaded(data) {
                // calculate average image color, is in try catch to circumvent any cors errors
                var averageColor = root.query(
                    'GET_FILE_POSTER_CALCULATE_AVERAGE_IMAGE_COLOR'
                )
                    ? calculateAverageColor(data)
                    : null;
                item.setMetadata('color', averageColor, true);

                // the preview is now ready to be drawn
                root.dispatch('DID_FILE_POSTER_LOAD', {
                    id: id,
                    data: data
                });
            };

            // determine image size of this item
            getImageSize(fileURL, function(width, height) {
                // we can now scale the panel to the final size
                root.dispatch('DID_FILE_POSTER_CALCULATE_SIZE', {
                    id: id,
                    width: width,
                    height: height
                });

                // create fallback preview
                loadImage(fileURL).then(previewImageLoaded);
            });
        };

        /**
         * Write handler for when the preview has been loaded
         */
        var didLoadPreview = function didLoadPreview(_ref2) {
            var root = _ref2.root;

            root.ref.overlayShadow.opacity = 1;
        };

        /**
         * Write handler for when the preview image is ready to be animated
         */
        var didDrawPreview = function didDrawPreview(_ref3) {
            var root = _ref3.root;
            var image = root.ref.image;

            // reveal image

            image.scaleX = 1.0;
            image.scaleY = 1.0;
            image.opacity = 1;
        };

        /**
         * Write handler for when the preview has been loaded
         */
        var restoreOverlay = function restoreOverlay(_ref4) {
            var root = _ref4.root;

            root.ref.overlayShadow.opacity = 1;
            root.ref.overlayError.opacity = 0;
            root.ref.overlaySuccess.opacity = 0;
        };

        var didThrowError = function didThrowError(_ref5) {
            var root = _ref5.root;

            root.ref.overlayShadow.opacity = 0.25;
            root.ref.overlayError.opacity = 1;
        };

        var didCompleteProcessing = function didCompleteProcessing(_ref6) {
            var root = _ref6.root;

            root.ref.overlayShadow.opacity = 0.25;
            root.ref.overlaySuccess.opacity = 1;
        };

        /**
         * Constructor
         */
        var create = function create(_ref7) {
            var root = _ref7.root,
                props = _ref7.props;
            var image = createPosterView(_);

            // append image presenter
            root.ref.image = root.appendChildView(
                root.createChildView(image, {
                    id: props.id,
                    scaleX: 1.25,
                    scaleY: 1.25,
                    opacity: 0
                })
            );

            // image overlays
            root.ref.overlayShadow = root.appendChildView(
                root.createChildView(overlay, {
                    template: overlayTemplateShadow,
                    opacity: 0
                })
            );

            root.ref.overlaySuccess = root.appendChildView(
                root.createChildView(overlay, {
                    template: overlayTemplateSuccess,
                    opacity: 0
                })
            );

            root.ref.overlayError = root.appendChildView(
                root.createChildView(overlay, {
                    template: overlayTemplateError,
                    opacity: 0
                })
            );
        };

        return _.utils.createView({
            name: 'file-poster-wrapper',
            create: create,
            write: _.utils.createRoute({
                // image preview stated
                DID_FILE_POSTER_LOAD: didLoadPreview,
                DID_FILE_POSTER_DRAW: didDrawPreview,
                DID_FILE_POSTER_CONTAINER_CREATE: didCreatePreviewContainer,

                // file states
                DID_THROW_ITEM_LOAD_ERROR: didThrowError,
                DID_THROW_ITEM_PROCESSING_ERROR: didThrowError,
                DID_THROW_ITEM_INVALID: didThrowError,
                DID_COMPLETE_ITEM_PROCESSING: didCompleteProcessing,
                DID_START_ITEM_PROCESSING: restoreOverlay,
                DID_REVERT_ITEM_PROCESSING: restoreOverlay
            })
        });
    };

    /**
     * Image Preview Plugin
     */
    var plugin$1 = function(fpAPI) {
        var addFilter = fpAPI.addFilter,
            utils = fpAPI.utils;
        var Type = utils.Type,
            createRoute = utils.createRoute;

        // imagePreviewView

        var imagePreviewView = createPosterWrapperView(fpAPI);

        // called for each view that is created right after the 'create' method
        addFilter('CREATE_VIEW', function(viewAPI) {
            // get reference to created view
            var is = viewAPI.is,
                view = viewAPI.view,
                query = viewAPI.query;

            // only hook up to item view and only if is enabled for this cropper

            if (!is('file') || !query('GET_ALLOW_FILE_POSTER')) {
                return;
            }

            // create the image preview plugin, but only do so if the item is an image
            var didLoadItem = function didLoadItem(_ref) {
                var root = _ref.root,
                    props = _ref.props;
                var id = props.id;

                var item = query('GET_ITEM', id);

                // item could theoretically have been removed in the mean time
                if (!item || !item.getMetadata('poster') || item.archived) {
                    return;
                }

                // set preview view
                root.ref.imagePreview = view.appendChildView(
                    view.createChildView(imagePreviewView, { id: id })
                );

                // now ready
                root.dispatch('DID_FILE_POSTER_CONTAINER_CREATE', { id: id });
            };

            var didCalculatePreviewSize = function didCalculatePreviewSize(_ref2) {
                var root = _ref2.root,
                    props = _ref2.props,
                    action = _ref2.action;

                // set new height
                var height = root.rect.element.width * (action.height / action.width);

                // set height
                root.ref.imagePreview.element.style.cssText =
                    'height:' + Math.round(height) + 'px';
            };

            // start writing
            view.registerWriter(
                createRoute({
                    DID_LOAD_ITEM: didLoadItem,
                    DID_FILE_POSTER_CALCULATE_SIZE: didCalculatePreviewSize
                })
            );
        });

        // expose plugin
        return {
            options: {
                // Enable or disable file poster
                allowFilePoster: [true, Type.BOOLEAN],

                // Enables or disables reading average image color
                filePosterCalculateAverageImageColor: [false, Type.BOOLEAN]
            }
        };
    };

    var isBrowser =
        typeof window !== 'undefined' && typeof window.document !== 'undefined';

    if (isBrowser && document) {
        document.dispatchEvent(
            new CustomEvent('FilePond:pluginloaded', { detail: plugin$1 })
        );
    }

    return plugin$1;
});