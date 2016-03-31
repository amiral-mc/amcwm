<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * HttpRequest encapsulates the $_SERVER variable and resolves its inconsistency among different Web servers.
 * HttpRequest also manages the cookies sent from and sent to the user.
 * @package AmcWm.core
 * @author Amiral Management Corporation
 * @version 1.0
 */
class HttpRequest extends CHttpRequest
{

    /**
     * Creates a cookie with a randomly generated CSRF token.
     * Initial values specified in {@link csrfCookie} will be applied
     * to the generated cookie.
     * @return CHttpCookie the generated cookie
     * @see enableCsrfValidation
     */
    protected function createCsrfCookie()
    {
        $cookie = parent::createCsrfCookie();
        $cookie->httpOnly = true;
        return $cookie;
    }

}
