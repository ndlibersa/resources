function getCookie(keyCo){
    var valCookie= ""; 
    var search= keyCo + "="; 
    if(document.cookie.length > 0) { 
        pos=document.cookie.indexOf(search); 
        if (pos != -1) {
            pos += search.length; 
            end= document.cookie.indexOf(";", pos); 
            if (end == -1) 
                end= document.cookie.length; 
            valCookie= unescape(document.cookie.substring(pos,end)) 
        }
    } 
    return valCookie; 
} 

function fLang() {
    var langBrowser = '';
    // Search the language according to the browser
    if(getCookie('lang')=='' || getCookie('lang')==undefined){
        if (navigator.languages==undefined) {
            if (navigator.language==undefined) {
                // Internet Explorer Compatibility
                langBrowser= navigator.userLanguage.slice(0,2);
            } else {
                // Old navigator compatibility
                langBrowser= navigator.language.slice(0,2);
            }
        } else { 
            // Recent navigators
            langBrowser= navigator.languages[0].slice(0,2);                                
        }
    }else{
        langBrowser = getCookie('lang');
    }
    return langBrowser;
}
var gt = new Gettext({ 'domain' : 'messages' });//gt = new Gettext({ 'domain' : 'messages' });
function _(msgid) {
    return gt.gettext(msgid);
}