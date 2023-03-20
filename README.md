# t3languageredirection
A simple middleware language redirect which automatically redirects users to the preferred language URL based on their browser's Accept-Language header.


## Requirements

`TYPO3` 9.5 and higher

`PHP` 7.2 and higher


## How to use

You can easily install this extension using Composer:

```bash
composer require visuellverstehen/t3languageredirection
```

That's it! No further action required after installation.


## How does it work
This extention will configure a new frontend middleware to your TYPO3 project. This is middleware called `LanguageRedirectionMiddleware` that is designed to handle language redirection for a website. It is implemented using the PSR-15 middleware interface.

When a request is made to the website, this middleware checks the configured languages for the website. If there is only one language, it does nothing and allows the request to proceed to the next middleware.

If there are multiple languages, the middleware gets the browser language from the HTTP request headers and checks if the browser language is supported by the website. If it is, the middleware redirects the user to the preferred language URL.

The middleware uses the `RedirectResponse` class from the TYPO3 CMS to perform the redirection. Overall, this middleware helps to ensure that users are directed to the appropriate language version of a website, based on their browser language and the website's configured languages.

