/**
 * Created by a.jelen on 11.9.2015.
 */

function CookiesConfNo()
{
    ClearCookies();
    setCookie('sCookie', 'disabletrack', 186);
    jQuery(".cc-popup").hide();
}
function CookiesConfYes()
{
    setCookie('sCookie', 'enabletrack', 186);
    jQuery(".cc-popup").hide();
}
function OpenCookieConfDialog()
{
    //first
    var testcookie = getCookie('sCookie');
    if (!testcookie)
    {
        jQuery('.cc-popup').show();
    }
    else
    {
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

    for (var i = 0; i < cookies.length; i++)
    {
        var parts = cookies[i].split('=');
        var name = parts[0];

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

function OpenCookieOptions()
{
	jQuery('.cc-options').click(function()
	{
		jQuery('.cc-settings').toggle();
	});
}

jQuery(document).ready(function($)
{
    var popbtnClick = false;
    $(".cc-popup").hide();

    // if sCookie does not exist
    OpenCookieConfDialog();

	$(".cc-dismiss").click(function()
    {
        CookiesConfYes();
		window.location.reload(true);
        popbtnClick = true;
    });

    $(".cc-dismiss-no").click(function()
    {
        CookiesConfNo();
        window.location.reload(true);
        popbtnClick = true;
    });

    $('body').click(function()
    {
        if (!popbtnClick)
        {
			var testcookie = getCookie('sCookie');
			if (testcookie === null || testcookie !== "disabletrack")
			{
				CookiesConfYes();
			}
        }

		if ($('.cc-settings').is(':visible')) { $('.cc-settings').toggle();	}
    });

	$('.cc-options').click(function()
	{
		OpenCookieOptions();
		return false;
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
        while (c.charAt(0) === ' ') { c = c.substring(1, c.length); }
        if (c.indexOf(nameEQ) === 0) { return c.substring(nameEQ.length, c.length); }
    }
    return null;
}