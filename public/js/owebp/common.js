

/*
 * function to check the variable is not null
 */

function varNotNull(v) {
	if (v != null && v != '' && v) {
		return true;
	}
	return false;
}
/**
 * @variable _0g used to hold the domain of website
 *
 * For testing a perticular domain set this variable value to that domain
 * if you are at localhost that domain will be used to show the website
 * _lhdn is comming from org_details as local host domain name
 */

var _0g = 'owebp.com';
if (typeof(_lhdn) !== 'undefined' && varNotNull(_lhdn)) {
	_0g = _lhdn;
}

/**
 * @function _1g to get the domain name of from the url
 * @return {string} the domain name from url
 */
var _1g = function () {
    var _1l = window.location.hostname.replace(/www\./g, "");
    if (_1l == 'localhost') {_1l = _0g;}
    return _1l;
};

/**
 * @variable _2g is used to hold the person id from the person table of database
 *
 * this is used to perform some testing
 */
var _2g = ""; /* person id */
_2g = "540d7572e4b0b539bd017122"; /* guest */
_2g = "540d90cee4b0b539bd0171c1"; /* yogesh */

/**
 * @function isInit to check if a number is integer or not
 * @param n
 * @returns {Boolean} return true if number is integer
 */
function isInt(n){
    return typeof n == "number" && isFinite(n) && n % 1 === 0;
}

var viewType = '';
if (_1g() == 'ji.owebp.com') {
	/* bootstrap theme */
	viewType = 'bs_';
}

/* *******slider******* */

var tomar;
var imgnum = 1;
var marnum = 0;
var mainSliderContainer = document.getElementById("slider");
var slidenum = 0;
if (varNotNull(mainSliderContainer)) {
	mainSliderContainer.getElementsByTagName('img').length;
}
var startSlide = setInterval(sliderRight,3500);

function resetSlide()
{
	clearInterval(startSlide);
    startSlide = setInterval(sliderRight,3500);
}
function sliderRight()
{
	if (imgnum < slidenum) {
		marnum -= 100;
		imgnum += 1;
	} else {
		marnum = 0;
		imgnum = 1;
	}
	tomar =  marnum.toString() + "%";
	if (varNotNull(mainSliderContainer)) {
		mainSliderContainer.style.marginLeft = tomar;
	}
	resetSlide();
}
function sliderLeft()
{
	if (imgnum > 1) {
		marnum += 100;
		imgnum -= 1;
	} else {
		marnum = 0;
		imgnum = 1;
	}
	tomar =  marnum.toString() + "%";
	if (varNotNull(mainSliderContainer)) {
		mainSliderContainer.style.marginLeft = tomar;
	}
    resetSlide();
}
