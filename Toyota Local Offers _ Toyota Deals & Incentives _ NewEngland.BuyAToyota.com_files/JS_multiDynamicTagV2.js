var ebScriptFileName = "JS_multiDynamicTagV2.js";

//HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?CN=DT&amp;TID=3097&amp;TVAL=' +ranTestTag_dynamicID+ '&amp;rnd=' + ebRand
//TID=3097&campaignID=1234&TVAL=1001

var MULTI = 'multi';
var TVAL;
var campaignID;
var TID;
var TIDArray;
var tagURL = 'HTTP://bs.serving-sys.com/BurstingPipe/ActivityServer.bs?CN=DT&TID=';
var stateURL = 'http://bs.serving-sys.com/BurstingPipe/adServer.bs?cn=SetState'; //campaignid=0&statevalue=1001
var arrSuffix = ["bat", "scion", "toyota-scion", "tcom", "tcuv"];
var mapEquivalent = {
    "aaa": "bbb",
    "1001": "ddd"
};
function init() {
    var gEbQueries = new ebScriptQuery(ebScriptFileName).flatten();
    //get TVAL value
    TVAL = gEbQueries.TVAL;
    campaignID = gEbQueries.campaignid;
    TID = gEbQueries.TID;
    TIDArray = TID.split(",");
   if ((TVAL != undefined) && (typeof (ebCreativeState) != "undefined")) {
        if (ebCreativeState != "") {
            //user state
            if (ebCreativeState != "multi") {
                // if (ebCreativeState == TVAL) //v1
                if (compareVals(ebCreativeState, TVAL)) {//v2 -todo compare by list 
                    //**state value equal to TVAL value **
                    //write tag with TVAL value
                    setRetargetingTags(TVAL)
                    //set state with TVAL value
                    setState(TVAL);

                } else {
                    //**staet value no equal to TVAL value**  
                    //write tag with multi value
                    if (TVAL.indexOf("nodealer")==0) {//V2
                        setRetargetingTags(ebCreativeState)
                        setState(ebCreativeState)

                    } //eof v2
                    else {
                        setRetargetingTags(MULTI)

                        //set state to multi
                        setState(MULTI)
                    }

                }
            } else {
                //**user status set to multi in the past**
                //write tag with multi value
                setRetargetingTags(MULTI)
                //set state to multi
                setState(MULTI);

            }
        } else {
            //**user state in empty**

            //write tag with TVAL value 
            setRetargetingTags(TVAL)
            if (TVAL.indexOf("nodealer")!=0) {//V2
                //set state with TVAL value 
                setState(TVAL);
            } //v2

        }

    } //end if (TVAL != undefined)




}

//write tag
function compareVals(ebCreativeState, TVAL) {
    if (ebCreativeState == TVAL) {
        return true;
    }
	var re = new RegExp(arrSuffix.join("$|")+"$");
	var ebCreativeState = ebCreativeState.replace(re,"");
	var TVAL = TVAL.replace(re,"");
        if (ebCreativeState == TVAL) {
            return true;
        }
    for (i in mapEquivalent) {
        if (((i == TVAL) && (mapEquivalent[i] == ebCreativeState)) || ((mapEquivalent[i] == TVAL) && (i == ebCreativeState))) {
            return true;
        }
    }
}
function writeTag(tag) {
    var ord = Math.floor(Math.random() * 1000000);
    var tagSrc = tag + '&ord=' + ord;
    document.write('<scr' + 'ipt src="' + tagSrc + '"></scr' + 'ipt>');
}

function setState(value) {
    var setState = stateURL + '&campaignid=' + campaignID + '&statevalue=' + value;
    document.write('<iframe src="' + setState + '" width=0" height="0" frameborder="0" >');
    document.write('</iframe>')
}



function setRetargetingTags(value) {
    for (var i = 0; i < TIDArray.length; i++) {
        var tag = tagURL + TIDArray[i] + '&TVAL=' + value;
        writeTag(tag);
    }

}

// *************** BEGIN DO NOT MODIFY *********************
var ebScriptQuery = function (scriptPath) {
    this.scriptPath = scriptPath;

};

ebScriptQuery.prototype = {
    get: function () {
        var lastQuery = '';
        var srcRegex = new RegExp(this.scriptPath.replace('.', '\\.') + '(\\?.*)?$', 'i');
        var scripts = document.getElementsByTagName("script");
        var i;
        for (i = 0; i < scripts.length; i++) {
            var script = scripts[i];
            if (script.src && script.src.match(srcRegex)) {
                var query = script.src.match(/\?([^#]*)(#.*)?/);
                lastQuery = !query ? '' : query[1];
            }
        }
        return lastQuery;
    },
    parse: function () {
        var result = {};
        var query = this.get();
        var components = query.split('&');
        var i;
        for (i = 0; i < components.length; i++) {
            var pair = components[i].split('=');
            var name = pair[0],
            value = pair[1];

            if (!result[name]) { result[name] = []; }
            // decode
            if (!value) {
                value = 'true';
            } else {
                try {
                    value = decodeURIComponent(value);
                } catch (e) {
                    value = unescape(value);
                }
            }

            // MacIE way
            var values = result[name];
            values[values.length] = value;
        }
        return result;
    },
    flatten: function () {
        var queries = this.parse();
        var name;
        for (name in queries) {
            queries[name] = queries[name][0];
        }
        return queries;
    },
    toString: function () {
        return 'ebScriptQuery [path=' + this.scriptPath + ']';
    }
};


// *************** END DO NOT MODIFY *********************

init();