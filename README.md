# t3languageredirection
A simple middleware language redirect which automatically redirects users to the preferred language URL based on their browser's Accept-Language header.

## How to use

You can easily install this extension using Composer:

```bash
composer require visuellverstehen/t3languageredirection
```

That's it! No further action required after installation.


## How does it work
This extention will configure a new frontend middleware to your TYPO3 project. This middleware is called `LanguageRedirectionMiddleware`. It is designed to handle simple language redirection for the base URL of a website. It is implemented using the PSR-15 middleware interface.

Overall, this middleware helps to ensure that users are directed to the appropriate language version of a website, based on their browser language and the website's configured languages.
