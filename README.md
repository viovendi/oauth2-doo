# doo Provider for OAuth 2.0 Client

[![Build Status](https://img.shields.io/travis/jpbernius/oauth2-doo.svg)](https://travis-ci.org/jpbernius/oauth2-doo)
[![Build Status](https://scrutinizer-ci.com/g/jpbernius/oauth2-doo/badges/build.png?b=master)](https://scrutinizer-ci.com/g/jpbernius/oauth2-doo/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jpbernius/oauth2-doo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jpbernius/oauth2-doo/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jpbernius/oauth2-doo/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jpbernius/oauth2-doo/?branch=master)
[![License](https://img.shields.io/packagist/l/jpbernius/oauth2-doo.svg)](https://github.com/jpbernius/oauth2-doo/blob/master/LICENSE)
[![Total Downloads](https://poser.pugx.org/jpbernius/oauth2-doo/downloads)](https://packagist.org/packages/jpbernius/oauth2-doo)

This package provides [doo](https://github.com/viovendi) OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require jpbernius/oauth2-doo
```

## Usage

Usage is the same as The League's OAuth client, using `JPBernius\OAuth2\Client\Provider\DooProvider` as the provider.

### Authorization Code Flow

```php
$provider = new JPBernius\OAuth2\Client\Provider\DooProvider([
    'clientId' => 'YOUR_CLIENT_ID',
    'clientSecret' => 'YOUR_CLIENT_SECRET'
]);

$token = $provider->getAccessToken('client_credentials');
```
