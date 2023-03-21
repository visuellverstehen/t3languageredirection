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
        if ($request->getServerParams()['REQUEST_URI'] !== '/') {
            return $handler->handle($request);
        }

        // Get the website's configured languages
        $siteLanguages = $request->getAttribute('site')->getLanguages();

        // Do nothing, if the website has only one configured language
        if (count($siteLanguages) === 1) {
            return $handler->handle($request);
        }

        // Get the browser language from the HTTP request headers
        $browserLanguageIsoCode = substr($request->getHeaderLine('Accept-Language'), 0, 2);

        // Get site root URL
        $siteRootUrl = $request->getAttribute('site')->getBase()->getPath();

        foreach ($siteLanguages as $siteLanguage) {
            // Check if the browser language is supported
            if ($browserLanguageIsoCode === $siteLanguage->getTwoLetterIsoCode()) {
                // Redirect the user to the preferred language URL
                $redirectUrl = $siteRootUrl . $browserLanguageIsoCode;
                return new RedirectResponse($redirectUrl);
            }
        }

        return $handler->handle($request);
    }
}
