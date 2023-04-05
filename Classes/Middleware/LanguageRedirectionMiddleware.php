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
        // Do nothing, if a HTTP referer is set
        if (! empty($request->getServerParams()['HTTP_REFERER'])) {
            return $handler->handle($request);
        }

        // Get the website's configured site languages
        $siteLanguages = $request->getAttribute('site')->getLanguages();

        // Do nothing, if the website has only one configured site language
        if (count($siteLanguages) === 1) {
            return $handler->handle($request);
        }

        // Get base URL from site configuration
        $baseUrl = (string) array_filter($siteLanguages, function($siteLanguage) {
            return $siteLanguage->getLanguageId() === 0;
        })[0]->getBase();


        // Do nothing, if the requested URL is not the base URL
        if ($request->getAttribute('normalizedParams')->getRequestUrl() !== $baseUrl) {
            return $handler->handle($request);
        }

        // Do nothing, if request header is not set
        if (! isset($request->getServerParams()['HTTP_ACCEPT_LANGUAGE'])) {
            return $handler->handle($request);
        }

        // Get the ISO codes of the browser languages from HTTP request header
        $browserLanguageIsoCodes = $this->getBrowserLangugeIsoCodes($request->getServerParams()['HTTP_ACCEPT_LANGUAGE']);

        // Check which of the browser languages are supported by comparing two letter ISO codes
        foreach ($browserLanguageIsoCodes as $browserLanguageIsoCode) {
            foreach ($siteLanguages as $siteLanguage) {
                if ($browserLanguageIsoCode === $siteLanguage->getTwoLetterIsoCode()) {
                    // Do nothing, if the site language base URL is the currently requested URL
                    if ((string) $siteLanguage->getBase() === $request->getAttribute('normalizedParams')->getRequestUrl()) {
                        return $handler->handle($request);
                    }
                    // Redirect the user to the preferred language base URL
                    return new RedirectResponse($siteLanguage->getBase());
                }
            }
        }

        return $handler->handle($request);
    }

    protected function getBrowserLangugeIsoCodes(string $acceptLanguageHeader): array
    {
        $acceptedLanguages = preg_split("/\,/", $acceptLanguageHeader);
        foreach ($acceptedLanguages as $acceptedLanguage) {
            $acceptedLanguageIsoCodes[] = substr($acceptedLanguage, 0, 2);
        }
        return array_unique($acceptedLanguageIsoCodes);
    }
}
