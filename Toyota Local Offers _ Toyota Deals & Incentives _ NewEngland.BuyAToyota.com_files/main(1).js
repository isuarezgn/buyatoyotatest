var logoImage,
    seriesName,
    seriesImage,
    badgeImage,
    modelYear,
    modelTrim,
    incentiveType,
    disclaimer,
    prTerm,
    termLength,
    leaseMonthlyPayment,
    leaseDAS,
    leaseTerm,
    cashback,
    custom;

var arr = ['noseries', 'notargetaudience', 'conversion', 'nomatch', ''];

var loadingList = [];

var timer;

function initAdkit(){
    adkit.onReady(startAd);
}
function resize(){
	windowWidth = window.innerWidth;
	newMargin = (windowWidth-1920)/2;
	//console.log(newMargin);
	document.getElementById("ad").style.marginLeft = newMargin + "px";
} 
        
function handleDynamicData(){
	resize();
	
    seriesName = adkit.getSVData("seriesName");
    seriesImage = adkit.getSVData("seriesImage");
    badgeImage = adkit.getSVData("badgeImage");
    modelYear = adkit.getSVData("modelYear");
    modelTrim = adkit.getSVData("modelTrim");
    incentiveType = adkit.getSVData("incentiveType");
    disclaimer = adkit.getSVData("disclaimer");
    aprTerm = adkit.getSVData("aprTerm");
    //termLength = adkit.getSVData("termLength");
    termLength = "60";
    leaseMonthlyPayment = adkit.getSVData("leaseMonthlyPayment");
    leaseDAS = adkit.getSVData("leaseDAS");
    leaseTerm = adkit.getSVData("leaseTerm");
    cashback = adkit.getSVData("cashback");
    custom = adkit.getSVData("custom");
	
	
}
        
function specialApr(){
	aprTermArr = aprTerm.split(",");
	aprTerm36=aprTermArr[0];
	aprTerm48=aprTermArr[1];
	aprTerm60=aprTermArr[2];
	 console.log("aprTerm36 " + aprTerm36);
	 console.log("aprTerm48 " + aprTerm48);
	 console.log("aprTerm60 " + aprTerm60);
	if(aprTerm36.indexOf("+") > -1){
		aprTerm = aprTerm36
		termLength="36";
	}else if(aprTerm48.indexOf("+") > -1){
		aprTerm = aprTerm48;
		termLength="48";
	}else if(aprTerm60.indexOf("+") > -1){
		aprTerm = aprTerm60;
		termLength="60";
	}
	aprTerm=aprTerm.replace('+','');
}

function startAd(){
    handleDynamicData();
	
	
	//figure out aprterm for 1.0,+1.9,2.0 pattern but only of it exists like that
	
	if(aprTerm.indexOf("+") > -1){
		specialApr();
	}
	
    timer = setInterval(checkLoading, 500);

    document.getElementById("CTA").onclick = handleClickthroughButtonClick;

    document.getElementById("CTA").onmouseover = function(){
        document.getElementById("CTA").src="images/cta_in.png";
    }
    document.getElementById("CTA").onmouseout = function(){
        document.getElementById("CTA").src="images/cta_out.png";
    }
	//remove the offer table if no series
	if(seriesName == "conversion" || seriesName == "noseries"){
		table.style.display = "none";
    }
	
    for (var i in arr) {
        if(seriesName == arr[i]){
            static();
            return;
        }
    }

    advanced();
}

function loader(url, obj) {
    loadingList.push(obj);
    imageSize(url, function(size) {
        obj.innerHTML = '<img src="' + url + '">';
    });
}

function imageSize(url, callback) {
    var response = {};
    var img = new Image();
    img.onload = function() {
        var x = img.width + 'px';
        var y = img.height + 'px';
        response = {width:x,height:y};
        if(callback) callback(response);
    }
    img.src = url;
}

function checkLoading(){
        
    for(var i in loadingList){
        if(loadingList[i].innerHTML == ""){
            return;
        }
    }

    clearInterval(timer);

    document.getElementById("loader").style.display = "none";
    document.getElementById("banner").style.display = "block";
}
  
function static(){
    loader(seriesImage, document.getElementById("seriesImage"));
}

function advanced(){

	
    document.getElementById("modelYear").innerHTML = modelYear;
    document.getElementById("modelTrim").innerHTML = modelTrim;

    loader(seriesImage, document.getElementById("seriesImage"));
    loader(badgeImage, document.getElementById("badgeImage"));

    var table = document.getElementById("table");
	var mYear = document.getElementById("modelYear");
	var mTrim = document.getElementById("modelTrim");
    var n = getName(seriesImage);
	
	var redColor="#d71921";
	var blackColor="#000000";


    if(n == "fjcruiser"){
        table.style.left = "560px";
        table.style.top = "-4%";
    
        document.getElementById("badge").style.marginBottom = "0px";
        document.getElementById("disclaimerLink").style.marginTop = "5px";
        document.getElementById("disclaimerLink").style.marginBottom = "5px";
    }else if(n == "rav4"){
		table.style.left = "1052px";
    }else if(n == "avalonhybrid"){
		table.style.left = "998px";
    }else if(n == "highlanderhybrid"){
		table.style.left = "1008px";
		//document.getElementById("disclaimerLink").style.marginTop = "5px";
    }else if(n == "camry"){
		table.style.left = "967px";
		mYear.style.color = redColor;
		mTrim.style.color = redColor;
    }else if(n == "priusv"){
		table.style.left = "540px";
		table.style.top = "0%";
        document.getElementById("badge").style.marginBottom = "0px";
        document.getElementById("disclaimerLink").style.marginTop = "5px";
        document.getElementById("disclaimerLink").style.marginBottom = "5px";
    }else if(n == "priusc"){
		table.style.left = "1016px";
    }else if(n == "4runner"){
		table.style.left = "998px";
    }else if(n == "corolla"){
		table.style.left = "1038px";
    }else if(n == "priusplugin"){
		table.style.left = "932px";
    }else if(n == "venza"){
		table.style.left = "985px";
    }else if(n == "sequoia"){
		table.style.left = "1058px";
    }else if(n == "landcruiser"){
		table.style.left = "1028px";
    }else if(n == "yaris"){
		table.style.left = "1001px";
		mYear.style.color = blackColor;
		mTrim.style.color = blackColor;
    }else if(n == "sienna"){
		table.style.left = "1052px";
    }else if(n == "tacoma"){
		table.style.left = "1023px";
    }else if(n == "highlander"){
		table.style.left = "1038px";
		//document.getElementById("disclaimerLink").style.marginTop = "5px";
    }else if(n == "avalon"){
		table.style.left = "998px";
		table.style.top = "-39px";
		mYear.style.color = blackColor;
    }else if(n == "prius"){
		table.style.left = "1008px";
    }else if(n == "tundra"){
		table.style.left = "958px";
    }else{
        table.style.left = "8px";
    }

    switch (incentiveType){
    
        case "Apr":
            document.getElementById("Apr").style.display = "table";
            document.getElementById("aprTerm").innerHTML = checkAPR(aprTerm);
            document.getElementById("termLength").innerHTML = termLength;
            document.getElementById("disclaimerLink").innerHTML = "MORE FINANCING TERMS";
        break;

        case "Lease":
            document.getElementById("Lease").style.display = "table";
            document.getElementById("leaseMonthlyPayment").innerHTML = leaseMonthlyPayment;
            document.getElementById("leaseDAS").innerHTML = checkComma(leaseDAS);
            document.getElementById("leaseTerm").innerHTML = leaseTerm;
            document.getElementById("disclaimerLink").innerHTML = "MORE LEASE TERMS";
        break;

        case "CashBack":
            document.getElementById("CashBack").style.display = "table";
            document.getElementById("cashback").innerHTML = checkComma(cashback);
            document.getElementById("disclaimerLink").innerHTML = "MORE CASH BACK TERMS";
        break;

        case "AprCash":
            document.getElementById("AprCash").style.display = "table";
            document.getElementById("aprTerm2").innerHTML = checkAPR(aprTerm);
            document.getElementById("termLength2").innerHTML = termLength;
            document.getElementById("cashback2").innerHTML = checkComma(cashback);
            document.getElementById("disclaimerLink").innerHTML = "MORE FINANCING TERMS";
        break;

        case "Custom":
            document.getElementById("Custom").style.display = "table";
            document.getElementById("custom").innerHTML = custom;
            /*if(custom.length > 25){
                document.getElementById("Custom").style.fontSize="25px";
            }*/
            document.getElementById("disclaimerLink").innerHTML = "";
        break;

    }

    if(disclaimer != ''){
        document.getElementById("disclaimerLink").style.display = "block";
        document.getElementById("disclaimer").innerHTML = "<p>"+disclaimer+"</p>";
        document.getElementById("disclaimerLink").onclick = toggleDisclaimer;
        document.getElementById("disclaimer").onclick = toggleDisclaimer;
    }else{
	document.getElementById("CTA").style.paddingTop = "10px";
	}

}

function getName(path){
    var n = path.replace(/^.*[\\\/]/, '');
    return n.replace(/\.[^/.]+$/, "");
}

function handleClickthroughButtonClick() {
    console.log("handleClickthroughButtonClick");
    EB.clickthrough();
}

function toggleDisclaimer(){
    var disclaimerClip = document.getElementById("disclaimer");
    var className = disclaimerClip.className;
    if(disclaimerClip.className == "disclaimerHide"){
        disclaimerClip.className = "disclaimerShow";
    }
    else{
        disclaimerClip.className = "disclaimerHide";
    }
}

function checkComma(num){
    if(num.length < 4) return num;
    return num.substr(0,1)+","+num.substr(1,3);
}

function checkAPR(num){
    num = num.slice(0, 3);
    var count = 0;
    for(i in num){
        if (num[i] == 0){
            count++; 
        } 
    }
    if(count > 1) {
        return '0';
    }
    else{
        return num;
    } 
}