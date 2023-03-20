<?php

namespace VV\T3languageredirection\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\RedirectResponse;

class LanguageRedirectionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Get the website's configured languages
        $siteLanguages = $request->getAttribute('site')->getLanguages();

        // Do nothing, if the website has only one configured language
        if (count($siteLanguages) === 1) {
            return $handler->handle($request);
        }

        // Get the browser language from the HTTP request headers
        $browserLanguageIsoCode = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        // Retrieve the language attribute via the request object
        $requestedLanguageIsoCode = $request->getAttribute('language')->getTwoLetterIsoCode();

        // Check if the requested URL matches the browser language
        if ($requestedLanguageIsoCode === $browserLanguageIsoCode || $requestedLanguageIsoCode) {
            return $handler->handle($request);
        }

        foreach ($siteLanguages as $siteLanguage) {
            // Check if the browser language is supported
            if ($browserLanguageIsoCode === $siteLanguage->getTwoLetterIsoCode()) {
                // Redirect the user to the preferred language URL
                $redirectUrl = $request->getAttribute('site')->getBase()->getPath() . $browserLanguageIsoCode . $request->getUri()->getPath();
                return new RedirectResponse($redirectUrl);
            }
        }
    }
}
