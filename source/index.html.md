---
title: Providi API Reference

language_tabs:
  - php: PHP
  - javascript: Javascript

includes:
  - authentication
  - user_profile
  - profile_subdomain
  - shop_access_codes
  - sponsors
  - events
  - marketing
  - voressundhed
  - recruiting
  - leaderboard
  - promotions
  - orders
  - errors
  - changelog

toc_footers:
  - <a href='https://providi-api-test.herokuapp.com'>Run API test</a>
---

# Introduction
The Providi API consists of the described endpoints/functions that return responses according to the [{json:api} specification](http://jsonapi.org). All responses are encoded using UTF-8.

Every request to the API must contain an authentication token (`token`) provided by an initial request to the [`authenticate`](#authentication) endpoint (of course this request will not include `token`).

Some requests will need to specify which country the request is concerning using the `country` parameter with a [ISO 3166-1 alpha-2](https://www.iso.org/obp/ui/#search) country code, e.g. `DK` for Denmark or `SI` for Slovenia. The code can be either upper case (`DK`) or lower case (`dk`).

Responses that include dates and/or times will always use the [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) format. Times will be shown with indication of offset from UTC.

| Type | Example |
| ---- | ------- |
| Date (September 28 2015) | 2015-09-28 |
| Time in CEST time zone (UTC + 2 hours) | 13:49:16+02:00 |
| Combined date and time | 2015-09-28T13:49:16+02:00 |

## Currency
Some requests will need to specify the currency of a specific sold or bought product or list of products and this will be using the [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) which is a combination of [ISO 3166-1 alpha-2](https://www.iso.org/obp/ui/#search) country code and the first letter of the currency itself.

| Type | Example |
| ---- | ------- |
| Danish krone | DKK |
| Japanese yen | JPY |

## User Roles
The user roles are described as follows:

| User role       | Description
| --------------- | -----------
|  member         | Normal user and default value for user role
|  superuser      | User that has access to the cockpit menu
|  administrator  | User with administrator rights with access to the management menu
