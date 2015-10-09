/**
 * Created by a.jelen on 11.9.2015.
 */

function CookiesConfNo()
{
    ClearCookies();
    setCookie('sCookie', 'disabletrack', 186);
    jQuery("#cookies-confirm").hide();
    //window.location.reload();
}
function CookiesConfYes()
{
    setCookie('sCookie', 'enabletrack', 186);
    jQuery("#cookies-confirm").hide();
    window.location.reload();
}
function OpenCookieConfDialog()
{
    //first
    var testcookie = getCookie('sCookie');
    if (!testcookie)
    {
        //setCookie('sCookie', 'disabletrack', 0);
        //jQuery("#cookies-confirm").show();
        jQuery('.cc-popup').show();
    }
    else
    {
        //jQuery("#cookies-confirm").hide();
        jQuery('.cc-popup').hide();
    }

    //jQuery('.cc-popup').toggle();
}

function ClearExistingCookie(key)
{
    date = new Date();
    date.setDate(date.getDate() - 1);
    document.cookie = escape(key) + '=; path=/; expires=' + date;
}

function ClearCookies()
{
    //clear google cookies
    var cookies = document.cookie.split('; ');
    //console.log(cookies);
    for (var i = 0; i < cookies.length; i++)
    {
        var parts = cookies[i].split('=');
        var name = parts[0];
        //console.log(name);

        Cookies.remove("__utma",
            {
                domain: '.' + location.host,
                path: '/'
            }
        );
        Cookies.remove("__utmb",
            {
                domain: '.' + location.host,
                path: '/'
            }
        );
        Cookies.remove("__utmc",
            {
                domain: '.' + location.host,
                path: '/'
            }
        );
        Cookies.remove("__utmz",
            {
                domain: '.' + location.host,
                path: '/'
            }
        );

        Cookies.remove("__utma",
            {
                domain: '.' + location.host.replace('www.', ''),
                path: '/'
            }
        );
        Cookies.remove("__utmb",
            {
                domain: '.' + location.host.replace('www.', ''),
                path: '/'
            }
        );
        Cookies.remove("__utmc",
            {
                domain: '.' + location.host.replace('www.', ''),
                path: '/'
            }
        );
        Cookies.remove("__utmz",
            {
                domain: '.' + location.host.replace('www.', ''),
                path: '/'
            }
        );
    }
}

jQuery(document).ready(function($)
{
    var popbtnClick = false;
    $("#cookies-confirm").hide();

    // if sCookie does not exist
    OpenCookieConfDialog();

    //-- handling mouse click event with cookie - confirm action

    //-- handle if clicked "Se strinjam"
    $("#cookies-confirm-yes").click(function()
    {
        CookiesConfYes();
        popbtnClick = true;
    });
    //-- handle if clicked "Se ne strinjam"
    $("#cookies-confirm-no").click(function()
    {
        CookiesConfNo();
        window.location.reload(true);
        popbtnClick = true;
    });

    //-- on page Piskotki da ne -----------------
    $("#cookies-confirm-page-yes").click(function()
    {
        CookiesConfYes();
        window.location.reload(true);
    });

    $("#cookies-confirm-page-no").click(function()
    {
        //ClearExistingCookie('aoCookie');
        //ClearCookies();
        CookiesConfNo();
        window.location.reload(true);
    });

    $('body').click(function()
    {
        if (!popbtnClick)
        {
            var testcookie = getCookie('sCookie');
            if (testcookie == null || testcookie != "disabletrack")
            {
                CookiesConfYes();
            }
        }
    });
});

function setCookie(name, value, days)
{
    if (days)
    {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toUTCString();
    }
    else { expires = ""; }
    document.cookie = name + "="+ value + expires +"; path=/";
}

function getCookie(name)
{
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++)
    {
        var c = ca[i];
        while (c.charAt(0) == ' ') { c = c.substring(1, c.length); }
        if (c.indexOf(nameEQ) == 0) { return c.substring(nameEQ.length, c.length); }
    }
    return null;
}