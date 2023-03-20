<?php

namespace VV\T3languageredirection\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\RedirectResponse;

class LanguageRedirectMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Get site's languages
        $siteLanguages = $request->getAttribute('site')->getLanguages();

        // Check if the site has only one language
        if (count($siteLanguages) === 1) {
            return $handler->handle($request);
        }

        // Get the browser language from the HTTP request headers
        $browserLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        // Retrieve the language attribute via the request object
        $requestedLanguage = $request->getAttribute('language')->getTwoLetterIsoCode();

        // Check if the requested URL match the browser language
        if ($requestedLanguage === $browserLanguage) {
            return $handler->handle($request);
        }

        foreach ($siteLanguages as $siteLanguage) {
            // Check if the browser language is supported
            if ($browserLanguage === $siteLanguage->getTwoLetterIsoCode()) {
                // Redirect the user to the preferred language URL
                $redirectUrl = $request->getAttribute('site')->getBase()->getPath() . $browserLanguage . $request->getUri()->getPath();
                return new RedirectResponse($redirectUrl);
            }
        }
    }
}
