/********************************************************************
* jQuery Array Utilities
* MIT license
* Kristian Marheim Abrahamsen, 2013
* https://github.com/KristianAbrahamsen/jquery.arrayUtilities


(The MIT License)

Copyright (c) 2013 Kristian Marheim Abrahamsen <kristian.abrahamsen@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************************************************/

(function ($) {
    var plugin = {};

    var checkIfAllArgumentsAreArrays = function (functionArguments) {
        for (var i = 0; i < functionArguments.length; i++) {
            if (!(functionArguments[i] instanceof Array)) {
                throw new Error('Every argument must be an array!');
            }
        }
    }

    plugin.distinct = function (array) {
        if (arguments.length != 1) throw new Error('There must be exactly 1 array argument!');
        checkIfAllArgumentsAreArrays(arguments);

        var result = [];

        for (var i = 0; i < array.length; i++) {
            var item = array[i];

            if ($.inArray(item, result) === -1) {
                result.push(item);
            }
        }

        return result;
    }

    plugin.union = function (/* minimum 2 arrays */) {
        if (arguments.length < 2) throw new Error('There must be minimum 2 array arguments!');
        checkIfAllArgumentsAreArrays(arguments);

        var result = this.distinct(arguments[0]);

        for (var i = 1; i < arguments.length; i++) {
            var arrayArgument = arguments[i];

            for (var j = 0; j < arrayArgument.length; j++) {
                var item = arrayArgument[j];

                if ($.inArray(item, result) === -1) {
                    result.push(item);
                }
            }
        }

        return result;
    }

    plugin.intersect = function (/* minimum 2 arrays */) {
        if (arguments.length < 2) throw new Error('There must be minimum 2 array arguments!');
        checkIfAllArgumentsAreArrays(arguments);

        var result = [];
        var distinctArray = this.distinct(arguments[0]);
        if (distinctArray.length === 0) return [];

        for (var i = 0; i < distinctArray.length; i++) {
            var item = distinctArray[i];

            var shouldAddToResult = true;

            for (var j = 1; j < arguments.length; j++) {
                var array2 = arguments[j];
                if (array2.length == 0) return [];

                if ($.inArray(item, array2) === -1) {
                    shouldAddToResult = false;
                    break;
                }
            }

            if (shouldAddToResult) {
                result.push(item);
            }
        }

        return result;
    }

    plugin.except = function (/* minimum 2 arrays */) {
        if (arguments.length < 2) throw new Error('There must be minimum 2 array arguments!');
        checkIfAllArgumentsAreArrays(arguments);

        var result = [];
        var distinctArray = this.distinct(arguments[0]);
        var otherArraysConcatenated = [];

        for (var i = 1; i < arguments.length; i++) {
            var otherArray = arguments[i];
            otherArraysConcatenated = otherArraysConcatenated.concat(otherArray);
        }

        for (var i = 0; i < distinctArray.length; i++) {
            var item = distinctArray[i];

            if ($.inArray(item, otherArraysConcatenated) === -1) {
                result.push(item);
            }
        }

        return result;
    }

    $.arrayUtilities = plugin;

    $.distinct = plugin.distinct;
    $.union = plugin.union;
    $.intersect = plugin.intersect;
    $.except = plugin.except;
} (jQuery));