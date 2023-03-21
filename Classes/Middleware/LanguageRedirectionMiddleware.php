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
        // Do nothing, if the requested URL is not the root URL
        if ($request->getAttribute('normalizedParams')->getRequestUri() !== '/') {
            return $handler->handle($request);
        }

        // Do nothing, if a HTTP referer is set
        if ($request->getServerParams()['HTTP_REFERER'] !== '') {
            return $handler->handle($request);
        }

        // Get the website's configured site languages
        $siteLanguages = $request->getAttribute('site')->getLanguages();

        // Do nothing, if the website has only one configured site language
        if (count($siteLanguages) === 1) {
            return $handler->handle($request);
        }

        // Get the iso code of the browser language from HTTP request header
        $browserLanguageIsoCode = substr($request->getServerParams()['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        foreach ($siteLanguages as $siteLanguage) {
            // Check if the browser language is supported by comparing two letter iso codes
            if ($browserLanguageIsoCode === $siteLanguage->getTwoLetterIsoCode()) {
                // Do nothing, if the site language base URL is the currently requested URL
                if ((string) $siteLanguage->getBase() === $request->getAttribute('normalizedParams')->getRequestUrl()) {
                    return $handler->handle($request);
                }

                // Redirect the user to the preferred language base URL
                return new RedirectResponse($siteLanguage->getBase());
            }
        }

        return $handler->handle($request);
    }
}
