// JavaScript Document


var updateOnLoad = function(func) {
	var current = window.onload;
	if(typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function () { 
			if(current) {
				current();
			}

			func();
		}

	}
	
}
// JavaScript Document
function findAndReplace(searchText, replacement) {
    fullExpression = regexEscape(searchText);
    input = new RegExp(fullExpression, "gi");
    output = replacement;
    domIterator(input, output);
}

function IsNumeric(sText) {
    var ValidChars = "0123456789.";
    var IsNumber = true;
    var Char;

    for (i = 0; i < sText.length && IsNumber == true; i++) {
        Char = sText.charAt(i);
        if (ValidChars.indexOf(Char) == -1) {
            IsNumber = false;
        }
    }
    return IsNumber;
}

function domIterator(regex, replacementNumber, node) {
    var childNodes = (node || document.body).childNodes;
    var cnLength = childNodes.length;
    var excludes = ['html', 'head', 'style', 'link', 'title', 'meta', 'script', 'object', 'iframe'];
    //for(var i=0; i<cnLength; i++) {
    while (cnLength--) {
        var currentNode = childNodes[cnLength];
        if (currentNode.nodeType === 1) {
            if (currentNode.nodeName == 'A' && currentNode.href.match(/^tel:/)) {
                currentNode.href = currentNode.href.replace(regex, replacementNumber);
            }
            if (excludes.indexOf(currentNode.nodeName.toLowerCase()) === -1) {
                arguments.callee(regex, replacementNumber, currentNode);
            }
        }
        if (currentNode == null || currentNode.nodeType !== 3 || currentNode.data === undefined) {
            continue;
        }
        // a match was found if still here
        // assign the replaced value
        var newText = currentNode.data.replace(regex, replacementNumber);
        // only replace if a change was made
        // and replace only the data of the current node.
        if (newText != currentNode.data) {
            currentNode.data = newText;
        }
    }
}

function replaceAll(numToInsert, numberFormat, numToExclude) {
    var fullExpression = "";
    var iteration = new Array();

    iteration[0] = addExclude("\\(\\s?\\d{3}\\s?\\)[\\-\\.\\s]?\\d{3}[\\-\\.\\s]?\\d{4}(?!\\d)(?=[<\\s\\.\\!\\?\\,]?)", numToExclude);
    iteration[1] = addExclude("\\b\\d{3}\\s?[\\-\\.\\s]?\\d{3}[\\-\\.\\s]?\\d{4}(?!\\d)\\b(?=[<\\s\\.\\!\\?\\,]?)", numToExclude);
    iteration[2] = addExclude("\\b(\\d{4,5})\\s\\d{3}\\s?(\\d{3,4})(?!\\d)\\b(?=[<\\s\\.\\!\\?\\,]?)", numToExclude);

    var iCount = iteration.length - 1;
    for (i = 0; i <= iCount; i++) {
        if (i == 0) {
            fullExpression = iteration[i];
        } else {
            fullExpression = fullExpression + "|" + iteration[i];
        }
    }

    input = new RegExp(fullExpression, "gi");
    if (numberFormat > -1) {
        output = formatnumber(numToInsert, numberFormat);
    } else {
        output = numToInsert;
    }
    if (mm_replace_ids) {
        idSplit = mm_replace_ids.split(",");
        var iCount = idSplit.length - 1;

        for (i = 0; i <= iCount; i++) {
            if (document.getElementById(idSplit[i])) {
                domIterator(input, output, document.getElementById(idSplit[i]));
                // document.getElementById(idSplit[i]).innerHTML = document.getElementById(idSplit[i]).innerHTML.replace(input,output);
            }
        }
    } else {
        domIterator(input, output);
    }
}

function replaceSingle(numToFind, numToInsert) {
    // This assumes that numToFind includes only digits.
    if (numToFind.length == 0) {
        return;
    }

    fullExpression = regexEscape(numToFind);
    input = new RegExp(fullExpression, "gi");
    output = numToInsert;
    domIterator(input, output);
}

function build_single_number_regex(numToFindS, numToInsertS, numberFormatS) {
    var fullExpressionS = "";
    var iterationS = new Array();
    if (numToFindS.substring(0, 1) == '0') {
        var areaCode = numToFindS.substring(0, 4);
        var exchange = numToFindS.substring(4, 7);
        var remain = numToFindS.substring(7, 11);
        var exchangeCombo = numToFindS + remain;
        var areaCode2 = numToFindS.substring(0, 5);
        var exchange2 = numToFindS.substring(5, 8);
        var remain2 = numToFindS.substring(8, 11);
        var exchangeCombo2 = numToFindS + remain2;
    } else {
        var areaCode = numToFindS.substring(0, 3);
        var exchange = numToFindS.substring(3, 6);
        var remain = numToFindS.substring(6, 10);
        var exchangeCombo = exchange + remain;
    }
    iterationS[0] = "\\(\\s?" + areaCode + "\\s?\\)[\\-\\.\\s]?" + exchange + "[\\-\\.\\s]?" + remain + "(?!\\d)(?=[<\\s\\.\\!\\?\\,]?)";
    iterationS[1] = areaCode + "\\s?[\\-\\.\\s]?" + exchange + "[\\-\\.\\s]?" + remain + "\\b(?!\\d)(?=[<\\s\\.\\!\\?\\,]?)";
    iterationS[2] = "(" + areaCode + "|" + areaCode2 + ")\\s(" + exchange + "|" + exchange2 + ")\\s(" + remain + "|" + remain2 + ")\\b(?!\\d)(?=[<\\s\\.\\!\\?\\,]?)";
    var iCountS = iterationS.length - 1;
    for (i = 0; i <= iCountS; i++) {
        if (i == 0) {
            fullExpressionS = iterationS[i];
        } else {
            fullExpressionS = fullExpressionS + "|" + iterationS[i];
        }
    }

    inputS = new RegExp(fullExpressionS, "gi");
    if (numberFormatS > -1) {
        outputS = formatnumber(numToInsertS, numberFormatS);
    } else {
        outputS = numToInsertS;
    }
    return {
        "input": inputS,
        "output": outputS
    };
}

function replaceSingleRegex(numToFindS, numToInsertS, numberFormatS) {
    // This assumes that numToFindS includes only digits.
    if (numToFindS.length == 0) {
        return;
    }
    var rregex = build_single_number_regex(numToFindS, numToInsertS, numberFormatS);
    domIterator(rregex.input, rregex.output);
}

function regexEscape(safesearch) {
    bs = String.fromCharCode(92);
    unsafe = bs + ".+*?[^]$(){}=!<>|:";
    for (i = 0; i < unsafe.length; ++i) {
        safesearch = safesearch.replace(new RegExp("\\" + unsafe.charAt(i), "g"), bs + unsafe.charAt(i));
    }
    return safesearch;
}

function getURLVariable(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if (results == null)
        return "";
    else
        return results[1];
}

function trim(stringToTrim) {
    return stringToTrim.replace(/^\s+|\s+$/g, "");
}

function addExclude(incomingRegex, numExclude) {
    //alert(incomingRegex);
    //alert(numExclude);
    var outgoingRegex = "";
    var preEx = "(?!";
    var postEx = "\\b)";

    if (numExclude) {
        excludeSplit = numExclude.split(",");

        var eCount = excludeSplit.length - 1;

        for (e = 0; e <= eCount; e++) {
            exNumber = trim(excludeSplit[e]);
            if (exNumber.substring(0, 1) == '0') {
                var areaCode = exNumber.substring(0, 4);
                var exchange = exNumber.substring(4, 7);
                var remain = exNumber.substring(7, 11);
                var exchangeCombo = exchange + remain;
                var areaCode2 = exNumber.substring(0, 5);
                var exchange2 = exNumber.substring(5, 8);
                var remain2 = exNumber.substring(8, 11);
                var exchangeCombo2 = exchange2 + remain2;

            } else {
                var areaCode = exNumber.substring(0, 3);
                var exchange = exNumber.substring(3, 6);
                var remain = exNumber.substring(6, 10);
                var exchangeCombo = exchange + remain;
            }
            switch (incomingRegex) {
                case "\\(\\s?\\d{3}\\s?\\)[\\-\\.\\s]?\\d{3}[\\-\\.\\s]?\\d{4}(?!\\d)(?=[<\\s\\.\\!\\?\\,]?)":
                    outgoingRegex = outgoingRegex + preEx + "\\(\\s?" + areaCode + "\\s?\\)[\\-\\.\\s]?" + exchange + "[\\-\\.\\s]?" + remain + postEx;
                    break;
                case "\\b\\d{3}\\s?[\\-\\.\\s]?\\d{3}[\\-\\.\\s]?\\d{4}(?!\\d)\\b(?=[<\\s\\.\\!\\?\\,]?)":
                    outgoingRegex = outgoingRegex + preEx + "" + areaCode + "\\s?[\\-\\.\\s]?" + exchange + "[\\-\\.\\s]?" + remain + postEx;
                    break;
                case "\\b(\\d{4,5})\\s\\d{3}\\s?(\\d{3,4})(?!\\d)\\b(?=[<\\s\\.\\!\\?\\,]?)":
                    outgoingRegex = outgoingRegex + preEx + "(" + areaCode + "|" + areaCode2 + ")\\s(" + exchange + "|" + exchange2 + ")\\s?(" + remain + "|" + remain2 + ")" + postEx;
                    break;
                default:
                    outgoingRegex = "";
            }
        }
    }
    return (outgoingRegex + incomingRegex);
}

function mm_action_replace() {
    if (tnarray[0].length == 10 || (tnarray[0].length == 11 && tnarray[0].charAt(0) == 0)) {
        if (tnarray[0] == default_number && overwrite_default_number == 'N') {} else {
            replaceAll(tnarray[0], customer_number_format, exclude_numbers);
        }
    }
}

function mm_action_single() {
    if (tnarray[0].length == 10 || (tnarray[0].length == 11 && tnarray[0].charAt(0) == 0)) {
        if (tnarray[0] == default_number && overwrite_default_number == 'N') {} else {
            if (filter_numbers) {
                filterSplit = filter_numbers.split(",");

                var eCount = filterSplit.length - 1;

                for (e = 0; e <= eCount; e++) {
                    filterNumber = trim(filterSplit[e]);
                    replaceSingleRegex(filterNumber, tnarray[0], customer_number_format);
                }
            }
        }
    }
}

// add indexOf if not present
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(searchElement /*, fromIndex */ ) {
        "use strict";

        if (this === void 0 || this === null)
            throw new TypeError();

        var t = Object(this);
        var len = t.length >>> 0;
        if (len === 0)
            return -1;

        var n = 0;
        if (arguments.length > 0) {
            n = Number(arguments[1]);
            if (n !== n)
                n = 0;
            else if (n !== 0 && n !== (1 / 0) && n !== -(1 / 0))
                n = (n > 0 || -1) * Math.floor(Math.abs(n));
        }

        if (n >= len)
            return -1;

        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);

        for (; k < len; k++) {
            if (k in t && t[k] === searchElement)
                return k;
        }
        return -1;
    };
}

    if (typeof mm_debug == 'undefined'){
            mm_debug = 0;
    }
mm_logError ('[control] initializing...');var sdr_spanClass = 'mm-phone-number';mm_logError ('[control] is the visit organic: (false)');mm_logError ('[control] uuid already present: 35f35208-6d96-490b-ba00-00d019ff7e30');mm_logError ('[control] looking up campaign: f4d8196ce190ca74210f19b15109dcd6');mm_logError('[control] Campaign Type: AccuTrack SPC');var replace_type = '2';mm_logError ('[control] custom javascript enabled running');if (getURLVar('utm_content')){
	var custom1=getURLVar('utm_content');
}
if (getURLVar('ActivityID')){
                var custom2=getURLVar('ActivityID');
}
if (getURLVar('usercookie')){
                var custom3=getURLVar('usercookie');
}mm_logError ('[control] custom javascript complete');var mm_rmq = 0;var mm_rmq = 1;/*
    http://www.JSON.org/json2.js
    2010-11-17

    Public Domain.

    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

    See http://www.JSON.org/js.html


    This code should be minified before deployment.
    See http://javascript.crockford.com/jsmin.html

    USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
    NOT CONTROL.


    This file creates a global JSON object containing two methods: stringify
    and parse.

        JSON.stringify(value, replacer, space)
            value       any JavaScript value, usually an object or array.

            replacer    an optional parameter that determines how object
                        values are stringified for objects. It can be a
                        function or an array of strings.

            space       an optional parameter that specifies the indentation
                        of nested structures. If it is omitted, the text will
                        be packed without extra whitespace. If it is a number,
                        it will specify the number of spaces to indent at each
                        level. If it is a string (such as '\t' or '&nbsp;'),
                        it contains the characters used to indent at each level.

            This method produces a JSON text from a JavaScript value.

            When an object value is found, if the object contains a toJSON
            method, its toJSON method will be called and the result will be
            stringified. A toJSON method does not serialize: it returns the
            value represented by the name/value pair that should be serialized,
            or undefined if nothing should be serialized. The toJSON method
            will be passed the key associated with the value, and this will be
            bound to the value

            For example, this would serialize Dates as ISO strings.

                Date.prototype.toJSON = function (key) {
                    function f(n) {
                        // Format integers to have at least two digits.
                        return n < 10 ? '0' + n : n;
                    }

                    return this.getUTCFullYear()   + '-' +
                         f(this.getUTCMonth() + 1) + '-' +
                         f(this.getUTCDate())      + 'T' +
                         f(this.getUTCHours())     + ':' +
                         f(this.getUTCMinutes())   + ':' +
                         f(this.getUTCSeconds())   + 'Z';
                };

            You can provide an optional replacer method. It will be passed the
            key and value of each member, with this bound to the containing
            object. The value that is returned from your method will be
            serialized. If your method returns undefined, then the member will
            be excluded from the serialization.

            If the replacer parameter is an array of strings, then it will be
            used to select the members to be serialized. It filters the results
            such that only members with keys listed in the replacer array are
            stringified.

            Values that do not have JSON representations, such as undefined or
            functions, will not be serialized. Such values in objects will be
            dropped; in arrays they will be replaced with null. You can use
            a replacer function to replace those with JSON values.
            JSON.stringify(undefined) returns undefined.

            The optional space parameter produces a stringification of the
            value that is filled with line breaks and indentation to make it
            easier to read.

            If the space parameter is a non-empty string, then that string will
            be used for indentation. If the space parameter is a number, then
            the indentation will be that many spaces.

            Example:

            text = JSON.stringify(['e', {pluribus: 'unum'}]);
            // text is '["e",{"pluribus":"unum"}]'


            text = JSON.stringify(['e', {pluribus: 'unum'}], null, '\t');
            // text is '[\n\t"e",\n\t{\n\t\t"pluribus": "unum"\n\t}\n]'

            text = JSON.stringify([new Date()], function (key, value) {
                return this[key] instanceof Date ?
                    'Date(' + this[key] + ')' : value;
            });
            // text is '["Date(---current time---)"]'


        JSON.parse(text, reviver)
            This method parses a JSON text to produce an object or array.
            It can throw a SyntaxError exception.

            The optional reviver parameter is a function that can filter and
            transform the results. It receives each of the keys and values,
            and its return value is used instead of the original value.
            If it returns what it received, then the structure is not modified.
            If it returns undefined then the member is deleted.

            Example:

            // Parse the text. Values that look like ISO date strings will
            // be converted to Date objects.

            myData = JSON.parse(text, function (key, value) {
                var a;
                if (typeof value === 'string') {
                    a =
/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/.exec(value);
                    if (a) {
                        return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4],
                            +a[5], +a[6]));
                    }
                }
                return value;
            });

            myData = JSON.parse('["Date(09/09/2001)"]', function (key, value) {
                var d;
                if (typeof value === 'string' &&
                        value.slice(0, 5) === 'Date(' &&
                        value.slice(-1) === ')') {
                    d = new Date(value.slice(5, -1));
                    if (d) {
                        return d;
                    }
                }
                return value;
            });


    This is a reference implementation. You are free to copy, modify, or
    redistribute.
*/

/*jslint evil: true, strict: false, regexp: false */

/*members "", "\b", "\t", "\n", "\f", "\r", "\"", JSON, "\\", apply,
    call, charCodeAt, getUTCDate, getUTCFullYear, getUTCHours,
    getUTCMinutes, getUTCMonth, getUTCSeconds, hasOwnProperty, join,
    lastIndex, length, parse, prototype, push, replace, slice, stringify,
    test, toJSON, toString, valueOf
*/


// Create a JSON object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

if (!this.JSON) {
    this.JSON = {};
    JSON = this.JSON;
}

(function () {
    "use strict";

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return isFinite(this.valueOf()) ?
                   this.getUTCFullYear()   + '-' +
                 f(this.getUTCMonth() + 1) + '-' +
                 f(this.getUTCDate())      + 'T' +
                 f(this.getUTCHours())     + ':' +
                 f(this.getUTCMinutes())   + ':' +
                 f(this.getUTCSeconds())   + 'Z' : null;
        };

        String.prototype.toJSON =
        Number.prototype.toJSON =
        Boolean.prototype.toJSON = function (key) {
            return this.valueOf();
        };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ?
            '"' + string.replace(escapable, function (a) {
                var c = meta[a];
                return typeof c === 'string' ? c :
                    '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
            }) + '"' :
            '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0 ? '[]' :
                    gap ? '[\n' + gap +
                            partial.join(',\n' + gap) + '\n' +
                                mind + ']' :
                          '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    k = rep[i];
                    if (typeof k === 'string') {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0 ? '{}' :
                gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' +
                        mind + '}' : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                     typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/
.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
.replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function' ?
                    walk({'': j}, '') : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());
/**
 * Multiple number replacement script.
 *
 * @param   object  The parent object scope. This is where we assign the JSON-P
 *                  callback function.
 * @return  void
 */
var source, keyword_ppc, campaign, adcampaign, gclid, type, phone_number, ad_type, display_format, mm_aid, mm_protocol_json;
var number_placeholderarray = new Array("numberassigned", "numberassigned_footer", "numberassigned_top", "numberassigned_left", "numberassigned_right", "numberassigned_support", "numberassigned_1", "numberassigned_2", "numberassigned_3", "numberassigned_4", "numberassigned_5", "numberassigned_6", "numberassigned_7", "numberassigned_8", "numberassigned_9", "numberassigned_10");
var promo_placeholderarray = new Array("promoassigned", "promoassigned_footer", "promoassigned_top", "promoassigned_left", "promoassigned_right", "promoassigned_support", "promoassigned_1", "promoassigned_2", "promoassigned_3", "promoassigned_4", "promoassigned_5", "promoassigned_6", "promoassigned_7", "promoassigned_8", "promoassigned_9", "promoassigned_10");
var tnarray = new Array("", "");
var CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
mm_protocol_json = (("https:" == document.location.protocol) ? "https://" : "http://");
var organic_hosts = [
    'www.google.com',
    'www.google.ca',
    'search.yahoo.com',
    'r.search.yahoo.com',
    'www.bing.com'
];

if (typeof mm_debug == 'undefined') {
    mm_debug = 0;
}

if (typeof mm_logError === 'undefined') {
    mm_logError = function(value) {
        if (!mm_debug) {
            return;
        }

        if (typeof console != 'undefined') {
            //	alert (value);
            //}else{
            console.log(value);
        }
    };
}

//name of the campaign to fetch in trackable numbers associated with campaign, leave blank to fetch with no campaign association
campaign = getVar("mm_campaign");
if (!campaign && typeof mm_c != 'undefined') {
    campaign = mm_c;
}
mm_logError("[spc-prod] campaign=" + campaign);

mm_logError("[spc-prod] GUID amd UUID creation");
if (campaign) {
    if (!cookieExistsName("MM_GUID")) {
        currentGUID = createGUID();
        setcookie("MM_GUID", currentGUID, 2628000);
    } else {
        currentGUID = get_cookie("MM_GUID", "")
    }
    if (!cookieExistsName("MM_UUID")) {
        currentUUID = createUUID();
        setcookie("MM_UUID", currentUUID, 1440);
    } else {
        currentUUID = get_cookie("MM_UUID", "")
    }

    // This variable is used for UUID if this visit is determined to be a new
    // session
    var spareUUID = createUUID();
} else {
    currentGUID = '';
    currentUUID = '';
    spareUUID = '';
}

if (typeof sdr_spanClass == 'undefined') {
    var sdr_spanClass = 'mm-phone-number'
}

mm_logError("[spc-prod] loading...");

Multi_Number = (function(parent_scope) {
    mm_logError("[spc-prod] loading multi-number....");
    var
    // Holds the HTML class name we expect to find wrapping destination
    // numbers
        DESTINATION_NUMBER_CLASS = sdr_spanClass,
        // Holds the desired cookie name for the multi-number replace cache
        CACHE_COOKIE_NAME = 'MM_MULTICACHE',
        // Holds the desired lifetime for the cache cookie in minutes
        CACHE_COOKIE_LIFETIME = 720, // 720 minutes = 12 hours
        // Holds the prefix we will use for JSON-P function names
        JSONP_RECEIVER_FUNCTION_PREFIX = 'mm_';

    /**
     * Defines a simple for-each function that accepts either an object or an
     * array to loop over, invoking the passed callback function for each item.
     *
     * @param   mixed     The object or array to loop over.
     * @param   callback  The callback function to run for each item.
     * @return  void
     */
    var for_each = function(subject, callback) {
        var
        // Holds the key/index value
            index = null,
            // Shortcut variable to the current value
            value = null,
            // Holds the result of the callback function
            result = null;

        // Loop over the subject object/array
        for (index in subject) {
            // added this to filter out IE class erroneous returns
            if (!subject.hasOwnProperty(index)) {
                continue;
            }
            // Grab a shortcut variable to the current value
            value = subject[index];

            // Invoke the callback function, passing the index and value
            result = callback(index, value);

            // If the result of the callback is anything other then undefined
            if (typeof result !== 'undefined' && result !== null) {
                // Return the result
                return result;
            }
        }
    };

    /**
     * Attempts to return a cookie string. If the cookie is unavailable, we
     * will return boolean false.
     *
     * @param   string  The name of the cookie to search for.
     * @return  mixed   If we find the cookie, we return the cookie string. If
     *                  we are unable to find the cookie, we return boolean
     *                  false.
     */
    var get_cookie_value = function(name) {
        var
        // Grab a shortcut value to the cookie string
            cookie = document.cookie,
            // Define a variable to hold the index of the cookie name
            name_index = null,
            equals_index = null,
            end_index = null;

        // If no cookie is defined
        if (!cookie) {
            return false; // Do nothing
        }

        // Attempt to find the string index for this cookie name
        name_index = cookie.indexOf(name);

        // If we are unable to find this cookie name in the cookie string
        if (name_index === -1) {
            return false; // Do nothing
        }

        // Attempt to find the index of the first equals sign after the cookie
        // name
        equals_index = cookie.indexOf('=', name_index) + 1;

        // If we were unable to find the equals sign
        if (equals_index === -1) {
            return false; // Do nothing
        }

        // Attempt to find the end of the cookie string
        end_index = cookie.indexOf(';', equals_index);

        // If we were unable to find the semicolon
        if (end_index === -1) {
            // Just use the end of the string as the last position
            end_index = cookie.length;
        }

        // Grab the substring of the cookie value and return it
        return cookie.substring(equals_index, end_index);
    };

    /**
     * Assigns or updates a cookie value.
     *
     * @param   string  The name of the cookie to update.
     * @param   string  The value to insert for the cookie.
     * @param   int     When to expire the cookie, in minutes.
     * @return  void
     */
    var set_cookie_value = function(name, value, expiration_in_minutes) {
        var
        // Holds the cookie string we will write
            cookie_string = name + '=' + value + '; ',
            // Create a new JavaScript date objct to get the current time
            today = new Date(),
            // Create a timestamp for when to expire the cookie
            cookie_expiration_timestamp = today.getTime() + (1000 * 60 *
                expiration_in_minutes),
            // Create a new date object to hold the expiration time
            cookie_expiration_object = new Date(cookie_expiration_timestamp),
            // Grab the actual cookie expiration date string
            cookie_expiration_gmt_string = cookie_expiration_object
            .toGMTString(),
            // Holds the subdomain
            subdomain = null;

        // Add on the expiration date and time
        cookie_string += 'expires=' + cookie_expiration_gmt_string + '; path=/; ';

        // Grab the subdomain from the hostname
        subdomain = getSubDomain(location.hostname);

        // Add the subdomain to the end of the cookie string
        cookie_string += 'domain=' + subdomain;

        // Assign the cookie
        document.cookie = cookie_string;
    };

    /**
     * Attempts to grab the serialized number cache from a cookie and return
     * the result fully deserialized. If we are unable to find the cookie, then
     * we just return boolean false.
     *
     * @return  object  If we find a cookie, we return the deserialized object.
     *                  If we are unable to find the cookie, we still return an
     *                  empty object.
     */
    var get_number_cache_object = function() {
        var
        // Attempt to grab the current value from the multi-number replace
        // cookie
            cookie_value = get_cookie_value(CACHE_COOKIE_NAME),
            // Define the cookie object variable
            cookie_object = null;

        // If we were unable to find the cookie
        if (cookie_value === false) {
            // Return an empty object
            return {};
        }

        try {
            // There should be a JSON-encoded object in the cookie value
            cookie_object = JSON.parse(cookie_value);
        } catch (exception) {
            // Return an empty object
            return {};
        }

        // If the deserialize does not work
        if (cookie_object === false) {
            // Return an empty object
            return {};
        }

        // Return the finished cookie object
        return cookie_object;
    };

    /**
     * Accepts an object of original-number => tracking-number pairs, and then
     * attempts to store them in the number cache cookie.
     *
     * @return  void
     */
    var set_number_cache_object = function(cache_object) {
        // Attempt to set the cookie value to the JSON-serialized string
        set_cookie_value(CACHE_COOKIE_NAME, JSON.stringify(cache_object),
            CACHE_COOKIE_LIFETIME);
    };

    /**
     * Generates a random string from an explicitly defined collection of
     * available characters.
     *
     * @param   int     The length of the string to return.
     * @return  string  The random alpha string.
     */
    var generate_random_alpha = function(desired_length) {
        var
        // The alpha characters to use. Note that there are no vowels. This
        // prevents most human-readable words from being accidentally
        // generated
            characters = 'BCDFGHJKLMNPQRSTVWXZbcdfghklmnpqrstvwxz',
            // Holds the completed result string
            result = '',
            // Define the variables to use in the for loop
            index = null,
            random_number = null;

        // Loop once for each character to generate
        for (index = 0; index < desired_length; index++) {
            // Generate a random number between 0 and the total number of
            // available characters
            random_number = Math.floor(Math.random() * characters.length);

            // Add this random character to the result string
            result += characters.substring(random_number,
                random_number + 1);
        }

        // return the random value we generated
        return result;
    };
    var _slice = function(arr, place) {
        try { // try using .slice()
            return Array.prototype.slice.call(arr, place);
        } catch (e) {
            // otherwise, manually create the array
            var result = [];
            for (var i = 0; i < arr.length; ++i) {
                result.push(arr[i]);
            }
            return result[place];
        }
    };
    /**
     * Returns all of the elements in the document that have the passed class
     * assigned to them.
     *
     * @param   string  The class name to search for.
     * @return  array   An array of matching elements.
     */
    var get_elements_by_class_name = function(class_name) {
        var results;
        // If "getElementsByClassName" is aleady defined, use it
        if (typeof document.getElementsByClassName !== 'undefined') {
            // Copy the result of "getElementsByClassName" into a simple array
            results = _slice(document.getElementsByClassName(class_name), 0);
            return results;
        }

        var
        // Pre-compile a regular expression for searching for the class
        // name in a string
            has_class_name = new RegExp('(?:^|\\s)' + class_name +
                '(?:$|\\s)'),
            // Grab all of the elements from the document into an array
            all_elements = document.getElementsByTagName('*'),
            // Define an array to hold the results we will return
            results = [],
            // Define the variables we use in the search loop
            index = null,
            element = null,
            element_class = null;

        // Loop over all of the elements in the document
        for (index = 0;
            (element = all_elements[index]) != null; index++) {
            // Grab a shortcut variable over to the class name
            element_class = element.className;

            // If we do not have the class name on this element
            if (!element_class || element_class.indexOf(class_name) === -1 || !has_class_name.test(element_class)) {
                // Move on to the next element
                continue;
            }

            // Add this element to the results we will return
            results.push(element);
        }

        // Return the results
        return results;
    };

    /**
     * Adds a single element to the request queue by its replace-number while
     * enforcing replace-number uniqueness.
     *
     * @param   array   A reference to the current request queue.
     * @param   string  A reference to the current replace number.
     * @param   object  A reference to the a DOM element.
     * @return  array   The updated request queue.
     */
    var add_to_request_queue = function(request_queue, replace_number,
        element) {
        var
        // Holds the matching queue item, if we find one
            found_item = null;

        // Search the request queue to determine if we already have this
        // replace number in it or not
        found_item = for_each(request_queue, function(index, current_item) {
            // If we do not have a matching number
            if (current_item.replace_number !== replace_number) {
                return; // Do nothing
            }

            // Found a match - return the current queue item
            return current_item;
        });

        // If we found a queue item
        if (typeof found_item !== 'undefined' && found_item !== null) {
            // Add this element to the existing queue item we found
            found_item.elements.push(element);

            // Return the updated request queue
            return request_queue;
        }

        if (replace_number == '' || replace_number == 'undefined') {
            return;
        }

        // Create a brand new request queue item
        request_queue.push({
            'replace_number': replace_number,
            'elements': [element]
        });

        // Return the updated request queue
        return request_queue;
    };

    /**
     * Adds a single element to the pixelfire queue by its tracking-number while
     * enforcing tracking-number uniqueness.
     *
     * @param   array   A reference to the current pixelfire queue.
     * @param   string  A reference to the current tracking number.
     * @return  array   The updated request queue.
     */
    var add_to_pixelfire_queue = function(pixelfire_queue, tracking_number) {
        var
        // Flag to tell us if we found a matching tracking number or not
            found_item = false;

        // Search the pixelfire queue to determine if we already have this
        // tracking number in it or not
        found_item = for_each(pixelfire_queue, function(index, current_item) {
            // If we do not have a matching number
            if (current_item.tracking_number !== tracking_number) {
                return; // Do nothing
            }

            // We found a match
            return true;
        });

        // If we did not find a an existing queue item
        if (found_item !== true) {
            // Add this element to the pixelfire queue
            pixelfire_queue.push({
                'tracking_number': tracking_number
            });
        }

        // Return the potentially updated pixelfire queue
        return pixelfire_queue;
    };

    /**
     * Finds all of the elements on the page that have the desired number
     * replacement class, grabs the phone numbers inside of the elements, then
     * attempts to find matches in the number replacement cache.
     *
     * @return  object  An object structure with a summary of the information.
     */
    var replace_number_search = function() {
        var
        // Attempt to find any elements that have the multi-number-
        // replacement class assigned to them
            elements = get_elements_by_class_name(DESTINATION_NUMBER_CLASS),
            // Grab a reference to the number cache
            number_cache = get_number_cache_object(),
            // Define a place to hold the phone numbers we were able to replace
            // using the number cache
            cache_hits = [],
            // Define a place to hold the phone numbers we need to request
            // tracking numbers for
            request_queue = [];

        // Loop over the replace number elements
        for_each(elements, function(index, element) {
            var
            // Grab the replace number from the element
                stripped = strip_element_tags(element.innerHTML),
                only_numeric = strip_non_numeric(stripped),
                replace_number = strip_country_code(only_numeric);
            if (replace_number !== '') {
                // If we have a cache hit
                if (typeof number_cache[replace_number] !== 'undefined') {
                    // Add this element to the cache hits collection
                    cache_hits.push({
                        'element': element,
                        'replace_number': replace_number,
                        'tracking_number': number_cache[replace_number]
                    });

                    // Move on to the next number on the page
                    return;
                }

                // Add this number to the request queue
                request_queue = add_to_request_queue(request_queue, replace_number,
                    element);
            }
        });

        // Return the cache hits and request queue collections
        return {
            'number_cache': number_cache,
            'cache_hits': cache_hits,
            'request_queue': request_queue
        };
    };

    /**
     * Removes country code from provided string
     *
     * @param   string  The string to strip country code from.
     * @return  string  The value string with country code stripped.
     */
    var strip_country_code = function(value) {
        // leaving this method to be used for international later
        // Return 10 digits from the right
        return value.substr(-10);
    };

    /**
     * Removes all element tags from a string.
     *
     * @param   string  The string to strip element tags from.
     * @return  string  The value string with all element tags stripped out.
     */
    var strip_element_tags = function(value) {
        // Return the value string with all element tags removed from it
        return value.replace(/(<([^>]+)>)/ig, '');
    };

    /**
     * Removes all non-numeric characters from the passed string.
     *
     * @param   string  The string to strip non-numeric characters from.
     * @return  string  The value string with all non-numeric characters
     *                  stripped out.
     */
    var strip_non_numeric = function(value) {
        // Return the value string with non-numeric characters removed from it
        return value.replace(/[^0-9]/g, '');
    };

    /**
     * Generates a URI query string using the passed array of key/value
     * objects.
     *
     * @param   array   An array of objects. Each sub-object needs to have both
     *                  name and value members.
     * @return  string  A string which can be concatenated to the URI.
     */
    var generate_query_string = function(pairs) {
        var
        // Define an array to hold the string representation of the pairs
            pair_strings = [],
            // Define the variables we use in the for loop
            index = null,
            pairs_length = pairs.length,
            pair;

        // Loop over all of the variable pairs
        for (index = 0; index < pairs_length; index++) {
            // Grab a reference to the current pair
            pair = pairs[index];

            // Add this string to the result array
            pair_strings.push(pair.name + '=' + pair.value);
        }

        // Return the finished query string partial
        return '?' + pair_strings.join('&');
    };

    /**
     * Injects a script tag into the DOM pointed at the passed URI.
     *
     * @param   string  The URI to point the injected script tag at.
     * @return  void
     */
    var inject_script_tag = function(uri) {
        // Inject a script tag with the passed URI string
        // document.write('<script src="' + uri + '"></script>');
        var head = document.getElementsByTagName("head").item(0);
        var script = document.createElement("script");
        script.setAttribute("src", uri);
        // all set, add the script
        head.appendChild(script);
    };

    /**
     * Converts the passed object into a set of URI pairs that can be parsed by
     * the generate_query_string function.
     *
     * @param   object  A collection of key: value pairs to convert.
     * @return  array   An array of pairs with name and value properties.
     */
    var object_to_pairs = function(source_object) {
        var
        // Define a variable to hold the finished pairs
            pairs = [],
            // Define variables for the loop below
            name = null,
            value = null;

        // Loop over the passed object parameters
        for (name in source_object) {
            // Grab a shortcut variable to the value of the current object
            // parameter in the loop
            value = source_object[name];

            // Add this pair
            pairs.push({
                'name': name,
                'value': value
            });
        }

        // Return the finished pairs
        return pairs;
    };

    /**
     * Makes a JSON-P request out to the specified URI using the passed
     * GET parameters object.
     *
     * @param   string    The base URI to make the request against.
     * @param   object    An object with key: value pairs to transform into get
     *                    parameters on the URI.
     * @param   function  The callback function to invoke when we get a
     *                    response back.
     * @return  void
     */
    var jsonp_request = function(base_uri, parameters, callback) {
        var
        // Define the JSON-P receiver function name as a random string of
        // 20 alpha characters prefixed with the passed prefix string
            jsonp_receiver_function_name = JSONP_RECEIVER_FUNCTION_PREFIX +
            generate_random_alpha(20),
            // Define a variable to hold the parameter pairs
            pairs = null;

        // Define the JSON-P receiver function on the parent scope
        parent_scope[jsonp_receiver_function_name] = function(data) {
            // Invoke the callback function passing the data
            callback(data);
        };

        // Convert the passed key: value object to a set of URI pairs
        pairs = object_to_pairs(parameters);

        // Add a jsonp parameter to hold the callback name
        pairs.push({
            'name': 'jsonp',
            'value': jsonp_receiver_function_name
        });

        inject_script_tag(base_uri + generate_query_string(pairs));
    };

    /**
     * Executes a data-image request out to the specified URI using the passed
     * GET parameters object.
     *
     * @param   string    The base URI to make the request against.
     * @param   object    An object with key: value pairs to transform into get
     *                    parameters on the URI.
     */
    var data_image_request = function(base_uri, parameters) {
        mm_logError("[data_image_request] init");
        var
        // Generate an array of name/value pairs from the passed parameters
        // object
            pairs = object_to_pairs(parameters),
            // Define the image object to do the request
            request_image = new Image();

        // Attempt to executed the data-image request
        mm_logError("[data_image_request] image src=" + base_uri + generate_query_string(pairs));
        request_image.src = base_uri + generate_query_string(pairs);
    };

    /**
     * Attempts to execute the pixelfire script using a data image request.
     *
     * @param   array  A queue of tracking numbers to send to the pixelfire
     *                 script.
     * @return  void
     */
    var execute_pixelfire = function(queue) {
        mm_logError("[execute_pixelfire] init");
        var
        // Define a place to hold the parameters for the pixelfire request
            parameters = {
                'u': probeURL(),
                'c1': escape(mm_c1),
                'c2': escape(mm_c2),
                'c3': escape(mm_c3),
                'aid': mm_aid
            },
            // Determine the protocol
            http_protocol = ("https:" == document.location.protocol) ? "https://" : "http://",
            // Define the URL to load
            base_uri = http_protocol + 'www.mongoosemetrics.com/correlation/pixelfire_correlate.php';

        // Loop over the queue
        for_each(queue, function(index, item) {
            // Add the parameter for this replace number
            mm_logError("[execute_pixelfire] adding n" + index.toString() + "=" + item.tracking_number);
            parameters['n' + index.toString()] = item.tracking_number;
        });

        // Attempt to execute the pixelfire data-image request
        mm_logError("[execute_pixelfire] base_uri: " + base_uri);
        data_image_request(base_uri, parameters);
    };

    /**
     * Return an object with the public functions we will expose to the
     * outside.
     */
    return {
        /**
         * @var  bool  Holds if this was a multi-number replacement or not.
         */
        'was_multi': null,
        /**
         * Makes the request out to JSON find correlation number using the
         * passed parameters. In the event that any elements are found in the
         * DOM that match the multi-number-replace class, the 'multi' flag will
         * be set to '1' and a series of n[0-9]* variables will be added to the
         * URIs GET string.
         *
         * @param   string    The base URI to make the request against.
         * @param   object    An object with key: value pairs to transform into get
         *                    parameters on the URI.
         * @param   function  The callback function to invoke when we get a
         *                    response back.
         */

        'find_correlation_number': function(uri, parameters, do_replacement, callback) {
            mm_logError("[find_correlation_number] init");

            if (!get_cookie_value('MM_DP') || get_cookie_value('MM_DP') === 'undefined') {
                var cookie_object = {
                    "bmi": parameters.mkwid,
                    "gclid": parameters.gclid
                };
                set_cookie_value('MM_DP', JSON.stringify(cookie_object), 720);
            } else {
                try {
                    var cookie_object = JSON.parse(get_cookie_value('MM_DP'));
                } catch (e) {
                    mm_logError('invalid JSON in MM_DP');
                }
            }
            if (typeof cookie_object != 'undefined') {
                if (mkwid === '' && typeof cookie_object.bmi != 'undefined') {
                    parameters.mkwid = cookie_object.bmi;
                }
                if (gclid === '' && typeof cookie_object.gclid != 'undefined') {
                    parameters.gclid = cookie_object.gclid;
                }
            }

            var
            // Search the DOM for elements with phone numbers to replace,
            // do some analysis and get the result
                search_result = do_replacement ? replace_number_search() : {},
                // Grab shortcut variables out to the returned cache hits,
                // request queue, and number cache collections, if those object
                // members exist
                cache_hits = typeof search_result.cache_hits !== 'undefined' ?
                search_result.cache_hits : [],
                request_queue = typeof search_result.request_queue !== 'undefined' ?
                search_result.request_queue : [],
                number_cache = typeof search_result.number_cache !== 'undefined' ?
                search_result.number_cache : [],
                // Determine what to send up to the pixelfire script
                pixelfire_queue = [],
                // Hold on to a reference to this object
                self = this;

            var has_custom_replace = typeof custom_replacement === 'function' ? true : false;

            mm_logError("[find_correlation_number] do_replacement: " + do_replacement);
            mm_logError("[find_correlation_number] search_result useless: " + search_result.length);
            mm_logError("[find_correlation_number] cache_hits: " + cache_hits.length);
            mm_logError("[find_correlation_number] request_queue: " + request_queue.length);
            mm_logError("[find_correlation_number] number_cache: " + number_cache.length);
            mm_logError("[find_correlation_number] custom replace: " + has_custom_replace);

            // Replace all of the elements where we had cache hits
            for_each(cache_hits, function(index, cache_hit) {
                if (has_custom_replace) {
                    custom_replacement(cache_hit);
                } else {
                    if (cache_hit.element.innerHTML.indexOf('tel:') !== -1) {
                        // Assign the replacement tracking number to the element
                        cache_hit.element.innerHTML = '<a href="tel:' + formatnumber(
                            cache_hit.tracking_number, 7) + '">' + formatnumber(cache_hit.tracking_number, display_format) + '</a>';
                    } else if (typeof cache_hit.element.href !== 'undefined' && cache_hit.element.href.indexOf('tel:') !== -1) {
                        cache_hit.element.innerHTML = formatnumber(cache_hit.tracking_number, display_format);
                        cache_hit.element.href = 'tel:' + formatnumber(cache_hit.tracking_number, 7);
                    } else {
                        // Assign the replacement tracking number to the element
                        cache_hit.element.innerHTML = formatnumber(
                            cache_hit.tracking_number, display_format);
                    }

                    // Add/Change other attributes if included
                    // If there are other items to add, add them, and do replacement there as well.
                    if (typeof attrArray !== 'undefined') {
                        for (var attrText in attrArray) {
                            // Check if the attribute exists in the tag
                            // mm_logError ("[find_correlation_number] Cache Testing " + attrText);
                            if (cache_hit.element.attributes[attrText] !== undefined) {
                                cache_hit.element.attributes[attrText].value = formatnumber(cache_hit.tracking_number, display_format);
                            }
                        }
                    }
                }

                // Add this cached tracking number to the pixelfire queue if it
                // isnt already there
                pixelfire_queue = add_to_pixelfire_queue(pixelfire_queue,
                    cache_hit.tracking_number);
            });

            mm_logError("[find_correlation_number] pixelfire_queue: " + pixelfire_queue.length);

            // If we have anything in the request queue
            if (request_queue.length > 0) {
                // Add the multi-number request GET variable
                parameters['multi'] = '1';
                this.was_multi = true;
            } else if (cache_hits.length > 0) {
                // This was a multi-number request, but we already handled
                // everything with the multi-number cache
                this.was_multi = true;
                mm_logError("[find_correlation_number] request_queue or cache_hits > 0, was_multi = true, firing pixelfire");
                // We have no new numbers to request so we can kick-off the
                // pixelfire script right now
                execute_pixelfire(pixelfire_queue);

                // Forward the response data to the callback function
                return callback({
                    'SUID': '1'
                });
            }

            // Loop over everything in the request queue
            for_each(request_queue, function(index, queue_item) {
                // Add this replace number to the URI
                mm_logError("[find_correlation_number] adding to json uri: n" + index.toString() + "=" + queue_item.replace_number);
                if (queue_item.replace_number != 'undefined' && queue_item.replace_number != '') {
                    parameters['n' + index.toString()] = queue_item.replace_number;
                }
            });

            // Make the JSON-P request out to the remote server
            mm_logError("[find_correlation_number] firing jsonp_request");
            jsonp_request(uri, parameters, function(response_data) {
                // If somehow the response data is undefined or null
                if (typeof response_data !== 'object' ||
                    response_data === null) {
                    // Do nothing
                    return;
                }

                var
                // Grab a shortcut variable to the numbers collection, if
                // one exists
                    numbers = typeof response_data.numbers !== 'undefined' ?
                    response_data.numbers : [],
                    // Gets set to boolean true if we update the cache object
                    updated_number_cache = false;

                // If I got a numbers member back
                if (numbers.length > 0) {
                    // This was a multi-number replacement
                    self.was_multi = true;
                } else {
                    // This was not a multi-number replacement
                    self.was_multi = false;
                }

                // Loop over the numbers that were sent back from the server
                for_each(numbers, function(index, number_object) {
                    var
                    // Grab a shortcut variable to the tracking number
                        tracking_number = number_object.tracking_number,
                        // Grab a shortcut variable to the corresponding
                        // request queue object
                        request_object = request_queue[index],
                        // Grab a shortcut variable to the request number
                        replace_number = request_object.replace_number,
                        // Grab a shortcut variable to the element
                        elements = request_object.elements;

                    // If the tracking number came back as anything other then
                    // a 10-digit phone number
                    if (tracking_number.length !== 10) {
                        // Move on to the next result
                        return;
                    }

                    // Loop over each of the elements
                    for_each(elements, function(index, element) {
                        if (has_custom_replace) {
                            var cache_obj = {
                                'element': element,
                                'replace_number': replace_number,
                                'tracking_number': tracking_number
                            };
                            custom_replacement(cache_obj);
                        } else {
                            if (element.innerHTML.indexOf('tel:') !== -1) {
                                // Assign the replacement tracking number to the element
                                element.innerHTML = '<a href="tel:' + formatnumber(
                                    tracking_number, display_format) + '">' + formatnumber(tracking_number, display_format) + '</a>';
                            } else if (typeof element.href !== 'undefined' && element.href.indexOf('tel:') !== -1) {
                                element.innerHTML = formatnumber(tracking_number, display_format);
                                element.href = 'tel:' + formatnumber(tracking_number, 7);
                            } else {
                                // Assign the replacement tracking number to the element
                                element.innerHTML = formatnumber(tracking_number,
                                    display_format);
                            }

                            // Add/Change other attributes if included
                            // If there are other items to add, add them, and do replacement there as well.
                            if (typeof attrArray !== 'undefined') {
                                for (var attrText in attrArray) {
                                    // Check if the attribute exists in the tag
                                    // mm_logError ("[find_correlation_number] Not-Cache Testing " + attrText);
                                    if (element.attributes[attrText] !== undefined) {
                                        element.attributes[attrText].value = formatnumber(tracking_number, display_format);
                                    }
                                }
                            }
                        }
                        // Modify the HTML on the page
                    });

                    // Add this number to the number cache if it isnt already
                    // there
                    number_cache[replace_number] = tracking_number;

                    // Add this tracking number to the pixelfire parameters
                    pixelfire_queue = add_to_pixelfire_queue(pixelfire_queue,
                        tracking_number);

                    // Indicate that we updated the number cache
                    updated_number_cache = true;
                });

                // If we had response data that we added to the number cache
                if (updated_number_cache) {
                    // Save the updated version of the number cache
                    set_number_cache_object(number_cache);
                }

                // Execute the pixelfire call
                execute_pixelfire(pixelfire_queue);

                // Forward the response data to the callback function
                callback(response_data);
            });
        }
    };

}(window));

/**

    Usage example:

    Multi_Number.find_correlation_number(
        'multi.php',
        {
            'crap': '1',
            'crap2': '2'
        },
        function (data) {
            showNumber(data);
        }
    );

*/

mm_logError("[spc-prod] checking UserAgent");
var mm_UA = function() {
    return navigator.userAgent;
}();

function checkBlockedUA() {
    var blockUAs = ['Googlebot', 'Web Preview'];

    if (typeof document.webkitVisibilityState != 'undefined') {
        if (document.webkitVisibilityState === 'prerender') {
            return true;
        } else {
            return false;
        }
    }
    for (var i = 0; i < blockUAs.length; i++) {
        if (mm_UA.match(blockUAs[i])) {
            mm_logError(blockUAs[i]);
            return true;
        }
    }
    return false;
}

var mm_blockedUA = checkBlockedUA();

// Getting URL variables
mm_logError("[spc-prod] getting URL variables");
var mm_url_vars = mm_extractVars(window.location.search.substring(1));

// Getting Referrer information
mm_logError("[spc-prod] getting referral URL");
var mm_referrer = mm_parseUri(mm_getReferrer());
mm_logError("[spc-prod] referrer=" + mm_referrer.source);

source = getVar("mm_utm_source");

if (!source) {
    source = getVar("utm_source");
}

gclid = getVar("gclid");
if (!source && gclid) {
    source = 'www.google.com';
}

// Capture Marin or Kenshoo variables
if (typeof mkwid == 'undefined') {
    var mkwid, mcreativeid;
    mkwid = getVar("mkwid");
    mcreativeid = getVar("mcreativeid");
}
if (!mkwid) {
    mkwid = getVar("kshid");
}
if (!mkwid) {
    mkwid = getVar("trkid");
}
if (!mkwid) {
    mkwid = getVar("jkId");
}
if (!mkwid) {
    mkwid = getVar("sfID");
}
if (!mkwid) {
    mkwid = getVar("cskid");
}
var mm_multi_req = 0;
if (typeof allow_multiple_ip_requests != 'undefined') {
    mm_multi_req = allow_multiple_ip_requests;
}

affiliate_id = unescape(getVar("affiliate_id"));
if (!affiliate_id) {
    affiliate_id = unescape(getVar("affiliate_id"));
}

adcampaign = unescape(getVar("mm_utm_campaign"));
if (!adcampaign) {
    adcampaign = unescape(getVar("utm_campaign"));
}

var isTagged = function() {
    var url = window.location;
    return url.href.search("[?&](keyword|mm_keyword)=") !== -1;
};

//name of the campaign to fetch in trackable numbers associated with campaign, leave blank to fetch with no campaign association
keyword_ppc = unescape(getVar("keyword"));

if (keyword_ppc.length < 2) {
    keyword_ppc = unescape(getVar("mm_keyword"));
    if (keyword_ppc.length < 2) {
        if (isTagged()) {
            keyword_ppc = 'keyword not present';
        }
    }
}

if (type == "D" && keyword_ppc.length < 2) {
    keyword_ppc = "direct traffic";
    type = "I";
} else {
    type = "P";
}

//name of the campaign to fetch in trackable numbers associated with campaign, leave blank to fetch with no campaign association
phone_number = getVar("phone_number");

//ad type differentiation inside campaign, leave blank to fetch with for no ad type association
ad_type = getVar("ad_type");

if (ad_type.length < 2) {
    if (typeof adtype != 'undefined') {
        ad_type = adtype;
    }
}

//set number display format 0 for XXX.XXX.XXXX 1 for (XXX) XXX-XXXX and 2 for xxx-xxx-xxxx
if (typeof customer_number_format != 'undefined')
    display_format = customer_number_format;
else
    display_format = 0;

//check  custom JS variable values. If they are there then pass to pixelfire and set them in cookie, else fetch from cooke and assign else null

if (typeof custom1 != 'undefined') {
    setcookie("MM_custom1", custom1, 720);
    mm_c1 = custom1;
} else {
    mm_c1 = get_cookie("MM_custom1", null);
}

if (typeof custom2 != 'undefined') {
    setcookie("MM_custom2", custom2, 720);
    mm_c2 = custom2;
} else {
    mm_c2 = get_cookie("MM_custom2", null);
}

if (typeof custom3 != 'undefined') {
    setcookie("MM_custom3", custom3, 720);
    mm_c3 = custom3;
} else {
    mm_c3 = get_cookie("MM_custom3", null);
}
if (typeof affiliate_id != 'undefined') {
    setcookie("MM_affiliate_id", affiliate_id, 720);
    mm_aid = affiliate_id;
} else
    mm_aid = get_cookie("MM_affiliate_id", null);

if (getVar("type") == "I") {
    type = "I";
    source = mm_referrer.source;
}

if (getVar("match") == "PPC") {
    type = "S";
    source = encodeURIComponent(document.URL);
}

if (typeof promocode != 'undefined') {
    if (typeof default_promo == 'undefined') {
        default_promo = 0;
    }
} else {
    promocode = '';
    default_promo = 0;
}

// If this variable is set to N, then we do not always replace numbers
// with the default number
if (typeof overwrite_default_number == 'undefined') {
    var overwrite_default_number = 'Y';
}
mm_logError("[spc-prod] overwrite_default_number=" + overwrite_default_number);

// These variables are used to override passed in variables
if (typeof override_keyword != 'undefined') {
    mm_logError('override_keyword = ' + override_keyword);
    keyword_ppc = override_keyword;
    mm_logError('keyword_ppc = ' + keyword_ppc);
}
if (typeof override_mmcampaign != 'undefined') {
    campaign = override_mmcampaign;
}
if (typeof override_source != 'undefined') {
    source = override_source;
}
if (typeof override_adcampaign != 'undefined') {
    adcampaign = override_adcampaign;
}
mm_logError('[spc-prod] checking override_type');
if (typeof override_type == 'undefined' || override_type == '') {
    override_type = '';
} else {
    type = override_type;
    mm_logError('[spc-prod] type overriden type=' + type);
}
if (typeof override_raw == 'undefined') {
    override_raw = '';
}
if (typeof override_referrer == 'undefined') {
    override_referrer = '';
}
if (typeof overflow_number == 'undefined') {
    overflow_number = '';
}

// This variable is for an organic 1:1 number
if (typeof organic_number == 'undefined') {
    organic_number = '';
}

// This variable is to define a 1:1 when session runs out of numbers
if (typeof failover_number == 'undefined') {
    failover_number = '';
}

if (typeof mm_rmq == 'undefined') {
    mm_rmq = 0;
}

// This variable is used to determine if web conversions are enabled
if (typeof mm_web_conversion != 'undefined') {
    if (mm_web_conversion == '1') {
        checkConversion();
    }
}

if (typeof mm_directories === 'undefined') {
    mm_directories = false;
}

mm_logError("[spc-prod] keyword_ppc = " + keyword_ppc);

/** Custom Number Replacement for Multiple Locations **/
/*
 * requires 2 variables set via client JS:
 *  mm_customLocationUrlMatch : regex with group to match the location in the url.
 *  mm_customLocationList : js object containing locations in the correct structure
 */
mm_logError("[spc-prod] delcaring multi number replacement function");
var mm_customLocationNumber = {
    run: function() {
        if (typeof(mm_customLocationUrlMatch) === 'undefined' ||
            typeof(mm_customLocationList) !== 'object') {
            return;
        }
        var currentLocationName = this.getLocation();
        if (currentLocationName !== null) {
            // loop over numbers in location
            var locationRef = mm_customLocationList.locations[currentLocationName];
            for (var i = 0; i < locationRef.length; i++) {
                if (typeof(locationRef[i] !== 'undefined')) {
                    domIterator(locationRef[i].origNumber, formatnumber(locationRef[i].replaceNumber, display_format));
                }
            }
        }
    },
    getLocation: function() {
        // get path, minus /our_schools and the trailing /, replacing dashes with underscores
        matchedLocation = location.pathname.match(mm_customLocationUrlMatch);
        if (matchedLocation === null) {
            return matchedLocation;
        }
        return matchedLocation[1].replace(/\-/g, '_');
    }

}.run();

mm_logError("[spc-prod] done loading");

// Functions to check if a conversion has happened
function checkConversion() {
    var mm_protocol_json = (("https:" == document.location.protocol) ? "https://" : "http://");

    var mm_conv_url = mm_protocol_json + "webconvert.mongoosemetrics.com/conversion/convert.php?mm_conversion_uuid=" + currentUUID;
    if (typeof mm_conversion_goal_id != 'undefined') mm_conv_url = mm_conv_url + "&mm_conversion_goal_id=" + mm_conversion_goal_id;
    if (typeof mm_conversion_fname != 'undefined') mm_conv_url = mm_conv_url + "&mm_conversion_fname=" + mm_conversion_fname;
    if (typeof mm_conversion_lname != 'undefined') mm_conv_url = mm_conv_url + "&mm_conversion_lname=" + mm_conversion_lname;
    if (typeof mm_conversion_score != 'undefined') mm_conv_url = mm_conv_url + "&mm_conversion_score=" + mm_conversion_score;
    if (typeof mm_conversion_email != 'undefined') mm_conv_url = mm_conv_url + "&mm_conversion_email=" + mm_conversion_email;
    if (typeof mm_conversion_phone != 'undefined') mm_conv_url = mm_conv_url + "&mm_conversion_phone=" + mm_conversion_phone;
    if (typeof mm_conversion_custom1 != 'undefined') mm_conv_url = mm_conv_url + "&mm_conversion_custom1=" + mm_conversion_custom1;
    if (typeof mm_conversion_custom2 != 'undefined') mm_conv_url = mm_conv_url + "&mm_conversion_custom2=" + mm_conversion_custom2;
    if (typeof mm_conversion_custom3 != 'undefined') mm_conv_url = mm_conv_url + "&mm_conversion_custom3=" + mm_conversion_custom3;

    myImage = new Image();
    myImage.src = mm_conv_url;

}

function getVisitor() {
    mm_logError("[getVisitor] init");
    mm_blockedUA = checkBlockedUA();
    if (mm_blockedUA) {
        mm_logError('[getVisitor] blocked UserAgent: ' + mm_UA);
        return false;
    }
    var sourceurl = mm_referrer.host;
    var raw_search = '';

    if (organic_hosts.indexOf(sourceurl) !== -1) {
        raw_search = mm_getParsedUrlVar(mm_referrer, 'q') || mm_getParsedUrlVar(mm_referrer, 'p');
    }

    var keyword;

    if (keyword_ppc.length < 2 || keyword_ppc == "direct traffic") {
        // organic Keyword
        if (mm_referrer.source) {
            if (type == "S") {
                keyword = "source";
            } else {
                keyword = raw_search;
                type = "O";
            }
        } else {
            if (keyword_ppc == "direct traffic" && type == "I") {
                keyword = keyword_ppc;
            }
        }
    } else {
        keyword = keyword_ppc;
    }
    var fullquery = encodeURIComponent(document.location.search);
    if (!source && mm_referrer.source) {
        source = sourceurl;
    }
    var ru = unescape(mm_referrer.source);
    if (override_referrer) {
        ru = override_referrer;
    }
    ru = encodeURIComponent(ru);
    if (override_raw) {
        raw_search = override_raw;
    }
    var
    // Determine if this site is HTTP or HTTPS then prefix our URI accordingly
        mm_protocol_json = (("https:" == document.location.protocol) ? "https://" : "http://"),
        // Determine the base URI for our JSON-P request
        mm_json_base_uri = mm_protocol_json + "www.mongoosemetrics.com/correlation/json_find_correlation_number.php";

    //    var head = document.getElementsByTagName("head").item(0);
    //    script = document.createElement("script");
    //    script.setAttribute("src", mm_json_base_uri);
    //    // all set, add the script
    //    head.appendChild(script);

    // Make the JSON-P request using the new multi-number replacement wrapper
    if (campaign !== '') {
        var do_the_replacement = false;
        if (cookieExistsName("MM_MULTICACHE")) {
            do_the_replacement = true;
            if (typeof get_cookie('MM_SDR') === 'undefined' && mm_directories) {
                do_the_replacement = false;
            }
        }
        mm_logError("[getVisitor] do_the_replacement=" + do_the_replacement);
        mm_logError("[getVisitor] mm_directories=" + mm_directories);
        mm_logError("[getVisitor] get_cookie('MM_SDR')=" + get_cookie('MM_SDR'));
        mm_logError("[getVisitor] firing json callback:visitorID");

        Multi_Number.find_correlation_number(
            mm_json_base_uri,
            // Forward all of the variables that we were forwarding before
            {
                'keyword': encodeURIComponent(keyword),
                'source': source,
                'adcampaign': adcampaign,
                'affiliate_id': affiliate_id,
                'callback': 'visitorID',
                'fullquery': fullquery,
                'GUID': currentGUID,
                'UUID': currentUUID,
                'SUID': spareUUID,
                'campaign': campaign,
                'gclid': gclid,
                'ref': ru,
                'q': raw_search,
                'type': type,
                'mkwid': mkwid,
                'or': override_raw,
                'post_to_rmq': mm_rmq,
                'num_request': false,
                'multi_req': mm_multi_req
            },

            //'keyword': encodeURIComponent(keyword),
            //'source': source,
            //'adcampaign': adcampaign,
            //'callback': 'visitorID',
            //'fullquery': fullquery,
            //'GUID': currentGUID,
            //'UUID': currentUUID,
            //'SUID': spareUUID,
            //'campaign': campaign,
            //'ref': ru,
            //'q': raw_search,
            //'type': type,
            //'mkwid': mkwid,
            //'mcreativeid': mcreativeid,
            //'gclid': gclid,
            //'ad_type': ad_type,
            //'or': override_raw
            // If the cookie does not exist then don't do the replacement'
            do_the_replacement,
            /**
             * This response handler gets called whenever we get JSON-P
             * data back from the remote server. This is where we forward
             * the data back to the old response handler called
             * "visitorID".
             *
             * @param   object  The JSON data from the remote server.
             * @return  void
             */
            function(response_data) {
                // Invoke the old callback function

                visitorID(response_data);
            }
        );
    }

    keyword = '';
}

function evaluateCustomVars() {
    //mm_logError("C1: " + mm_c1 + "C2: " + mm_c2 + "C3: " + mm_c3);
    if (mm_c1 === null && typeof custom1 != 'undefined') {
        setcookie("MM_custom1", custom1, 720);
        mm_c1 = custom1;
    } else {
        //mm_c1 = get_cookie("MM_custom1",null);
    }

    if (mm_c2 === null && typeof custom2 != 'undefined') {
        setcookie("MM_custom2", custom2, 720);
        mm_c2 = custom2;
    } else {
        //mm_c2 = get_cookie("MM_custom2",null);
    }

    if (mm_c3 === null && typeof custom3 != 'undefined') {
        setcookie("MM_custom3", custom3, 720);
        mm_c3 = custom3;
    } else {
        // mm_c3 = get_cookie("MM_custom3",null);
    }
}

function getNumber(campaign_alt) {
    mm_blockedUA = checkBlockedUA();

    mm_logError("[getNumber] init");
    if (mm_blockedUA) {
        mm_logError('blocked UserAgent: ' + mm_UA);
        return false;
    }

    var sourceurl = mm_referrer.host
    var raw_search = '';

    if (organic_hosts.indexOf(sourceurl) !== -1) {
        raw_search = mm_getParsedUrlVar(mm_referrer, 'q') || mm_getParsedUrlVar(mm_referrer, 'p');
    }

    var keyword;
    if (!cookieExists()) {
        mm_logError("[getNumber] cookie doesnt exist");
        mm_logError("[getNumber] keyword_ppc = " + keyword_ppc);

        if (campaign.length < 2)
            campaign = campaign_alt;

        if (keyword_ppc.length < 2 || keyword_ppc == "direct traffic" || override_type === 'O') {
            mm_logError("[getNumber] no keyword, direct traffic, or override type found");
            // organic Keyword
            if (override_referrer !== '') {
                mm_logError("[getNumber] override referrer found");
                mm_referrer.source = override_referrer;
            }
            mm_logError("[getNumber] refering URL = " + mm_referrer.source);
            if (mm_referrer.source) {
                if (type == "S") {
                    keyword = "source";
                } else {
                    mm_logError("[getNumber] source = " + sourceurl);
                    mm_logError("[getNumber] keyword_organic = " + raw_search);
                    //document.write("keyword=" + keyword);
                    //document.write("referrererer");
                    if (override_type === 'O') {
                        keyword = override_keyword;
                    } else {
                        if (typeof enable_organic_encrypted !== 'undefined' && enable_organic_encrypted === true && organic_hosts.indexOf(sourceurl) !== -1) {
                            if (!raw_search) {
                                keyword = 'Not Provided'
                            } else {
                                keyword = raw_search;
                            }
                        } else if (typeof enable_affiliate_tracking !== 'undefined' && enable_affiliate_tracking === true && sourceurl !== location.host) {
                            if (!raw_search) {
                                keyword = 'Not Provided';
                            } else {
                                keyword = raw_search;
                            }
                        } else {
                            keyword = raw_search;
                        }
                    }
                    mm_logError("[getNumber] keyword = " + keyword);
                    type = "O";
                    if (organic_number != '' && !mm_is_ppc && ((organic_hosts.indexOf(sourceurl) !== -1) && mm_is_organic)) {
                        showNumber({
                            "corelate_number": organic_number,
                            "interval": "43200",
                            "unique_cookie": "",
                            "promo_code": ""
                        });
                        mm_logError("[getNumber] found organic number");
                        keyword = '';
                    }
                }
            } else { //added on 06.04
                if (keyword_ppc == "direct traffic" && type == "I") {
                    keyword = keyword_ppc;
                }
                if (typeof enable_direct_tracking !== 'undefined' && enable_direct_tracking === true) {
                    keyword = 'Not Provided';
                    type = 'O';
                }
            }
        } else {
            keyword = keyword_ppc;
        }
        mm_logError("[getNumber] post logic keyword = " + keyword);
        if (typeof get_cookie('MM_SDR') === 'undefined' && mm_directories) {
            keyword = false;
        }
        if (keyword) //added on 06.04 if keyword exist with the request
        { //added on 06.04
            //Hard code the url of the request
            mm_logError("[getNumber] no number cookie, keyword present");
            var lu = probeURL();
            var ru = unescape(mm_referrer.source);
            if (override_referrer) {
                ru = override_referrer;
            }
            ru = encodeURIComponent(ru);
            mm_logError("ref = " + ru);
            var fullquery = encodeURIComponent(document.location.search);
            mm_logError("source = " + source);
            if (!source && mm_referrer.source) {
                source = sourceurl;
            }
            if (!source) {
                source = "directtraffic";
            }
            mm_logError("source = " + source);
            if (override_raw) {
                raw_search = override_raw;
            }
            var
            // Determine if this site is HTTP or HTTPS then prefix our URI accordingly
                mm_protocol_json = (("https:" == document.location.protocol) ? "https://" : "http://"),
                // Determine the base URI for our JSON-P request
                mm_json_base_uri = mm_protocol_json + "www.mongoosemetrics.com/correlation/json_find_correlation_number.php";

            // Make the JSON-P request using the new multi-number replacement wrapper
            Multi_Number.find_correlation_number(
                mm_json_base_uri,
                // Forward all of the variables that we were forwarding before
                {
                    'keyword': encodeURIComponent(keyword),
                    'campaign': campaign,
                    'phone_number': phone_number,
                    'type': type,
                    'source': source,
                    'mkwid': mkwid,
                    'mcreativeid': mcreativeid,
                    'gclid': gclid,
                    'adcampaign': adcampaign,
                    'affiliate_id': affiliate_id,
                    'ad_type': ad_type,
                    'callback': 'showNumber',
                    'ref': ru,
                    'q': raw_search,
                    'u': lu,
                    'pc': promocode,
                    'GUID': currentGUID,
                    'UUID': currentUUID,
                    'SUID': spareUUID,
                    'fullquery': fullquery,
                    'or': override_raw,
                    'post_to_rmq': mm_rmq,
                    'num_request': true,
                    'multi_req': mm_multi_req
                },
                // We are request a number and always want a replacement to happen
                // if possible.
                true,
                /**
                 * This response handler gets called whenever we get JSON-P
                 * data back from the remote server. This is where we forward
                 * the data back to the old response handler called
                 * "showNumber".
                 *
                 * @param   object  The JSON data from the remote server.
                 * @return  void
                 */
                function(response_data) {
                    // Invoke the old callback function
                    showNumber(response_data);
                }
            );

        } else {
            //if cookie doesnt exist and keyword is not part of the request, its a straight access.
            mm_logError("[getNumber] no number cookie, no keyword present");
            var sessCheck = get_cookie("MM_session_ID", '');
            getVisitor();
            if (sessCheck) {
                if (overflow_number != '') {
                    showNumber({
                        "corelate_number": overflow_number,
                        "interval": "43200",
                        "unique_cookie": "",
                        "promo_code": ""
                    });
                }
            }
            show_cookie("MM_correlation_Number");
            if (promocode)
                show_cookie("MM_promo_code");
        }
        var tn = get_cookie("MM_correlation_Number", default_number);
        if (tn != default_number)
            pixelfire(tn);
        return (tn);

    } else {
        //cookie exist
        mm_logError("[getNumber] cookie exists");
        if (typeof mm_redirect_url != 'undefined')
            window.location.href = mm_redirect_url;
        getVisitor();
        show_cookie("MM_correlation_Number");
        if (promocode)
            show_cookie("MM_promo_code");
        tn = get_cookie("MM_correlation_Number", default_number);
        if (tn != default_number)
            pixelfire(tn);
        return (tn);
    }
    keyword = undefined;
    keyword_ppc = undefined;
}

function pixelfire(tn) {
    var cb = new Date().getTime();
    var lu = probeURL();
    var mm_protocol_json = (("https:" == document.location.protocol) ? "https://" : "http://");
    evaluateCustomVars();

    //mm_logError("[Pixelfire] C1: " + mm_c1 + "C2: " + mm_c2 + "C3: " + mm_c3);
    var url = mm_protocol_json + "www.mongoosemetrics.com/correlation/pixelfire_correlate.php?phone_number=" + tn + "&u=" + lu + "&c1=" + escape(mm_c1) + "&c2=" + escape(mm_c2) + "&c3=" + escape(mm_c3) + "&aid=" + mm_aid + "&cb=" + cb;

    //mm_logError("url is "+ url);
    // alert(url);
    myImage = new Image();
    myImage.src = url;
}

/**
 * THis function is called when the JSON string is returned from json-encode.php
 */
function findurl(url) {
    var ref = document.createElement('a');
    ref.href = url;
    return ref.hostname;
}

function visitorID(number) {
    sessioncheck = number.SUID;
    //mm_logError("reply:"+sessioncheck);
    if (sessioncheck != 1)
        setcookie("MM_UUID", sessioncheck, 1440);
}

function showNumber(number) {
    //mm_logError(response);
    response = number.corelate_number;
    interval = number.interval;
    unique_cookie = number.unique_cookie;
    promo_code = number.promo_code;
    sessioncheck = number.SUID;

    not_found = 0;

    var is_multi = (typeof number.numbers !== 'undefined');

    if (sessioncheck != 1) {
        setcookie("MM_UUID", sessioncheck, 1440);
    }
    if ((response === -2 || response === 'jfcn_number_pool') && !is_multi) {
        if (failover_number != '') {
            response = failover_number;
            interval = 5;
        }
    }

    switch (response) {
        case ('jfcn_ip_blocked'):
        case ('jfcn_filtered'):
        case ('jfcn_no_keyword'):
        case ('jfcn_number_pool'):
        case ('jfcn_campaign_type'):
        case ('jfcn_campaign_issue'):
        case (-2):
        case (-1):
            not_found = 1;
            break;
    }

    if (not_found !== 1 && !is_multi) { //document.write(response);
        setcookie("MM_correlation_Number", response, interval);
        //setcookie("MM_keyword",keyword,720)
        //setcookie("MM_source",source,720)
        setcookie("MM_session_ID", unique_cookie, 43200);
        if (promo_code)
            setcookie("MM_promo_code", promo_code, interval);
    }
    if (!is_multi) {
        show_cookie("MM_correlation_Number");
        if (promo_code) {
            show_cookie("MM_promo_code");
        }
        pixelfire(response);
    }

    //Redirect page to a different URL after assignment, the variable defined on client web page
    if (typeof mm_redirect_url != 'undefined')
        window.location.href = mm_redirect_url;

    //reload the page after number assignment of customer has set var mm_reload=1;
    if (typeof mm_reload != 'undefined') {
        if (mm_reload == 1) {
            window.location.reload();
        }
    }

}

//FUNCTION TO FETCH THE URL VALUE FOR A NAME E.G: ?CAMPAIGN=INSULATED WATER HEATER -->getVAR("CAMPAIGN")
function getVar(name) {
    return mm_getUrlVar(name);
}

//FUNCTION TO SET PUBLISHED NUMBER TO COOKIE FOR INTERVAL SPECIFIED
function setcookie(cookie_name, val, interval) {
    //mm_logError(interval);
    if (val != "") {
        var today = new Date();
        today.setTime(today.getTime());
        vtime = today.getTime() + 1000 * 60 * interval;
        var cookie_expire_date = new Date(vtime);

        var cookie_string = cookie_name + "=" + val + ";expires=" + cookie_expire_date.toGMTString() + "; path=/; ";

        var sub_domain = getSubDomain(location.hostname);

        // do not set domain if dotless
        //if(sub_domain !== false) {
        cookie_string += "domain=" + sub_domain;
        //}

        document.cookie = cookie_string;
    }
}

function show_cookie(name) {
    var ret_one;
    var ret_two;
    //var default_number = "8002931174";
    if (document.cookie) {
        var cookie_index = document.cookie.indexOf(name);
        if (cookie_index != -1) {
            namestart = (document.cookie.indexOf("=", cookie_index) + 1);
            nameend = document.cookie.indexOf(";", cookie_index);
            if (nameend == -1) {
                nameend = document.cookie.length;
            }
            ret_one = document.cookie.substring(namestart, nameend);
            ret_two = document.cookie.substring(namestart, nameend);
        } else {
            ret_one = default_number;
            ret_two = default_promo;
        }

    } else {
        ret_one = default_number;
        ret_two = default_promo;
    }
    if (name == "MM_promo_code") {
        for (i = 0; i < promo_placeholderarray.length; i++) {
            if (document.getElementById(promo_placeholderarray[i])) {

                document.getElementById(promo_placeholderarray[i]).innerHTML = ret_two;
            }

        }
    } else {
        for (i = 0; i < number_placeholderarray.length; i++) {
            if (document.getElementById(number_placeholderarray[i])) {
                if (ret_one != default_number || overwrite_default_number != 'N') {

                    document.getElementById(number_placeholderarray[i]).innerHTML = formatnumber(ret_one, display_format);

                }
            }

        }
    }
    tnarray[0] = ret_one;
    tnarray[1] = formatnumber(ret_one, display_format);
    //mm_logError('shownumber');
    //mm_logError(tnarray[0]);

    if (ret_one != default_number || overwrite_default_number != 'N') {
        if (typeof our_function != 'undefined' && !Multi_Number.was_multi)
            eval(our_function + "();")
    }

    if (typeof callback_function != 'undefined') {
        if (mm_cookie_num === null) {
            mm_cookie_num = tnarray[0];
        }

        eval(callback_function + "();")
    }

}

function get_cookie(name, default_value) {
    var get_cookie_ret_one;
    //mm_logError(document.cookie);
    //var default_number = "8001234567";
    if (document.cookie) {
        //mm_logError(document.cookie);
        var cookie_index = document.cookie.indexOf(name);
        //mm_logError(cookie_index);
        if (cookie_index != -1) {
            namestart = (document.cookie.indexOf("=", cookie_index) + 1);
            nameend = document.cookie.indexOf(";", cookie_index);
            if (nameend == -1) {
                nameend = document.cookie.length;
            }
            get_cookie_ret_one = document.cookie.substring(namestart, nameend);
        } else
            get_cookie_ret_one = default_value;
    } else
        get_cookie_ret_one = default_value;
    return (get_cookie_ret_one);
}

//FUNCTION TO CHECK IF COOKIE WITH MM_CORRELATION_NUMBER EXISTS FOR THE VISITOR BROWSER
function cookieExists() {
    //mm_logError(document.cookie);
    if (document.cookie) {
        var cookie_index = document.cookie.indexOf("MM_correlation_Number");
        var multicache_index = document.cookie.indexOf('MM_MULTICACHE');
        if (cookie_index != -1 || multicache_index != -1)
            return true;
        else
            return false;
    } else
        return false;
}

function cookieExistsName(name) {
    //mm_logError(document.cookie);
    if (document.cookie) {
        var cookie_index = document.cookie.indexOf(name);
        if (cookie_index != -1)
            return true;
        else
            return false;
    } else
        return false;
}

function formatnumber(num, display_format) {
    var country_code, ini, st, end;
    if (typeof prefix_countrycode != 'undefined') {
        country_code = prefix_countrycode;
    } else {
        country_code = "";
    }

    //format 0 --  xxx.xxx.xxxx
    //format 1 --  (xxx) xxx-xxxx
    //format 2 --   xxx-xxx-xxxx
    //format 7 --  xxxxxxxxxx
    if (display_format == 0) {
        _return = "";

        if (country_code != "")
            _return += country_code + ".";

        ini = num.substring(0, 3);
        _return += ini + ".";
        st = num.substring(3, 6);
        _return += st + ".";
        end = num.substring(6, 10);
        _return += end;
        return _return;
    } else if (display_format == 1) {
        _return = "";

        if (country_code != "")
            _return += country_code;

        ini = "(" + num.substring(0, 3) + ")";
        _return += ini + " ";
        st = num.substring(3, 6);
        _return += st + "-";
        end = num.substring(6, 10);
        _return += end;
        return _return;
    } else if (display_format == 3) {
        _return = "";

        if (country_code != "")
            _return += country_code;

        ini = num.substring(0, 3);
        _return += ini + " ";
        st = num.substring(3, 6);
        _return += st + " ";
        end = num.substring(6, 10);
        _return += end;
        return _return;
    } else if (display_format == 4) {
        _return = "";
        if (num.substring(0, 2) == '44') {
            num = num.substring(2);
        }
        if (country_code != "")
            _return += country_code;

        ini = num.substring(0, 4);
        _return += ini + " ";
        st = num.substring(4, 7);
        _return += st + " ";
        end = num.substring(7, 11);
        _return += end;
        return _return;
    } else if (display_format == 5) {
        _return = "";
        if (num.substring(0, 2) == '44') {
            num = num.substring(2);
        }
        if (country_code != "")
            _return += country_code;

        ini = num.substring(0, 5);
        _return += ini + " ";
        st = num.substring(5, 8);
        _return += st + " ";
        end = num.substring(8, 11);
        _return += end;
        return _return;
    } else if (display_format == 6) {
        _return = "";
        if (num.substring(0, 2) == '44') {
            num = num.substring(2);
        }
        if (country_code != "")
            _return += country_code;

        ini = num.substring(0, 4);
        _return += ini + " ";
        end = num.substring(4, 11);
        _return += end;
        return _return;
    } else if (display_format == 7) {
        return num;
    } else {
        _return = "";

        if (country_code != "")
            _return += country_code + "-";

        ini = num.substring(0, 3);
        _return += ini + "-";
        st = num.substring(3, 6);
        _return += st + "-";
        end = num.substring(6, 10);
        _return += end;
        return _return;
    }
}

function probeURL() {
    var checkURL = 'NA';
    checkURL = document.URL;
    if (checkURL.length < 3) {
        checkURL = window.location.href;
    }
    if (checkURL.length < 3) {
        checkURL = document.location.href;
    }
    if (checkURL.length < 3) {
        checkURL = location.href;
    }
    checkURL = unescape(checkURL);
    var checkPOS = checkURL.indexOf('?');
    if (checkPOS != -1) {
        splitURL = checkURL.split('?');
        checkURL = splitURL[0];
    }
    return encodeURIComponent(checkURL);
}

function showExt() {
    if (tnarray[0].length == 10) {
        return 1;
    } else {
        return -1;
    }
}

function genS4() {
    return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
}

function createGUID() {
    return (genS4() + genS4() + "-" + genS4() + "-" + genS4() + "-" + genS4() + "-" + genS4() + genS4() + genS4());
}

function createUUID(len, radix) {
    var chars = CHARS,
        uuid = [];
    radix = radix || chars.length;

    if (len) {
        // Compact form
        for (var i = 0; i < len; i++) uuid[i] = chars[0 | Math.random() * radix];
    } else {
        // rfc4122, version 4 form
        var r;

        // rfc4122 requires these characters
        uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
        uuid[14] = '4';

        // Fill in random data.  At i==19 set the high bits of clock sequence as
        // per rfc4122, sec. 4.1.5
        for (var i = 0; i < 36; i++) {
            if (!uuid[i]) {
                r = 0 | Math.random() * 16;
                uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
            }
        }
    }

    return uuid.join('');
}

function Delete_Cookie(name, path, domain) {
    //mm_logError('del');
    if (Get_Cookie(name)) document.cookie = name + "=" +
        ((path) ? ";path=" + path : "") +
        ((domain) ? ";domain=" + domain : "") +
        ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}

function Get_Cookie(name) {

    var start = document.cookie.indexOf(name + "=");
    var len = start + name.length + 1;
    if ((!start) &&
        (name != document.cookie.substring(0, name.length))) {
        return null;
    }
    if (start == -1) return null;
    var end = document.cookie.indexOf(";", len);
    if (end == -1) end = document.cookie.length;
    return unescape(document.cookie.substring(len, end));
}

function getSubDomain(url) {
    if (!url.match(/\./)) {
        return false;
    }
    var dp = url.split(/\./);
    var lp = slp = tlp = '';
    pn = dp[dp.length - 1].split(/\:/)
    lp = pn[0];
    slp = dp[dp.length - 2];
    tlp = dp[dp.length - 3];
    if (lp.length === 2 && slp.length === 2) {
        lp = slp + '.' + lp;
        slp = tlp;
    }
    return '.' + slp + '.' + lp;
}

function mm_getUrlVar(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS, 'i');
    var results = regex.exec(window.location.href);
    if (results == null)
        return "";
    else
        return results[1];
}

function mm_getParsedUrlVar(referrerObj, variableName) {
    return typeof referrerObj.queryKey[variableName] === 'undefined' ? '' : referrerObj.queryKey[variableName];
}

function mm_extractVars(obj) {
    if (typeof obj === 'undefined') {
        return {};
    }
    var urlvars = {};
    var s = obj.split('&');
    for (var i = 0; i < s.length; i++) {
        var uv = s[i].split("=");
        urlvars[uv[0].toLowerCase()] = uv[1];
    }
    return urlvars;
}

function mm_getReferrer() {
    if (typeof document.referrer != 'undefined') {
        r = document.referrer;
    } else {
        r = document.location.href;
    }
    return r;
}

function mm_parseUri(str) {
    var o = {
        strictMode: true,
        key: ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"],
        q: {
            name: "queryKey",
            parser: /(?:^|&)([^&=]*)=?([^&]*)/g
        },
        parser: {
            strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
            loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
        }
    };
    m = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
        uri = {},
        i = 14;

    while (i--) uri[o.key[i]] = m[i] || "";

    uri[o.q.name] = {};
    uri[o.key[12]].replace(o.q.parser, function($0, $1, $2) {
        if ($1) uri[o.q.name][$1] = $2;
    });

    return uri;
}
var tracking_enable = 1;if (typeof mm_replace_ids == 'undefined')	mm_replace_ids='';if (typeof customer_number_format == 'undefined')	customer_number_format = '2';if (typeof mm_replace == 'undefined' || mm_replace == 'TRUE'){mm_logError('[declare] mm_replace is undefined');	mm_replace='FALSE';}mm_logError('[declare] mm_replace='+mm_replace);if (typeof filter_numbers == 'undefined')	filter_numbers='';if (typeof exclude_numbers == 'undefined')	exclude_numbers='';if (typeof mm_onload == 'undefined')  mm_onload='0';if (typeof overwrite_default_number == 'undefined')  overwrite_default_number = 'Y';if (typeof organic_number == 'undefined')  organic_number = '';if (typeof overflow_number == 'undefined')  overflow_number = '';if (typeof mm_replace_ids == 'undefined')  mm_replace_ids = '';if (typeof default_number == 'undefined')  default_number = '';run_replacement = 'FALSE';mm_logError('[control] checking for multi-market or directories');mm_logError ('[control] checking for sdr');mm_logError ('[control] url param mm_sdr=0');mm_logError ('[control] not a multi-market campaign');var proxy_enable = 0;
        mm_logError('[control] session: mm_replace = '+mm_replace);
        if (mm_onload=='1'){
            updateOnLoad(function(){
                if (mm_replace.toUpperCase()=='TRUE'){
                    our_function='mm_action_replace';
                }
                if (mm_replace.toUpperCase()=='SINGLE'){
                    our_function='mm_action_single';
                }
                getNumber('f4d8196ce190ca74210f19b15109dcd6');
            });
        }else{
            if (mm_replace.toUpperCase()=='TRUE'){
                our_function='mm_action_replace';
            }
            if (mm_replace.toUpperCase()=='SINGLE'){
                our_function='mm_action_single';
            }

            getNumber('f4d8196ce190ca74210f19b15109dcd6');
        }
    