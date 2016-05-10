
/**
 * @variable _0g used to hold the domain of website
 * 
 * For testing a perticular domain set this variable value to that domain
 * if you are at localhost that domain will be used to show the website
 */
var _0g = "";

_0g = "owebp.com";
_0g = "entechintl.com";
_0g = "et.owebp.com";
_0g = "farm.hybr.in";
_0g = "farm.hybr.owebp.com";
_0g = "pkmishra.owebp.com";
_0g = "kriya.owebp.com";
_0g = "apdds.owebp.com";
_0g = "syspro.owebp.com";
_0g = "ji.owebp.com";
_0g = "ji2.owebp.com";
_0g = "pis.owebp.com";

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