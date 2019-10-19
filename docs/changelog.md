# Changelog

All notable changes to this package will be documented in this file. The format is based on [Keep a Changelog](http://keepachangelog.com/).

## [Unreleased](https://github.com/Astrotomic/stancy/compare/0.4.0...master)

## [v0.4.0](https://github.com/Astrotomic/stancy/releases/tag/0.4.0) - 2019-10-15

### Added

* ExportFactory which integrates spatie/laravel-export
  * `\Astrotomic\Stancy\Contracts\ExportFactory`
  * `\Astrotomic\Stancy\Facades\ExportFactory`
  * `\Astrotomic\Stancy\Factories\ExportFactory`
* `\Astrotomic\Stancy\Models\Page::getUrl()` method which depends on `\Astrotomic\Stancy\Contracts\Routable` interface

## [v0.3.0](https://github.com/Astrotomic/stancy/releases/tag/0.3.0) - 2019-10-14

### Added

* `\Astrotomic\Stancy\Models\Page` uses `\Illuminate\Support\Traits\Macroable` to allow extensions

### Changed

* `\Astrotomic\Stancy\StancyServiceProvider` is now deferred

### Removed

* unused package configuration

## [v0.2.0](https://github.com/Astrotomic/stancy/releases/tag/0.2.0) - 2019-10-11

### Added

* `\Astrotomic\Stancy\Factories\SitemapFactory::makeFromSheetList()` which allows to load multiple collections and/or single sheets

### Removed

* `\Astrotomic\Stancy\Factories\SitemapFactory::makeFromSheetCollection()`
* `\Astrotomic\Stancy\Factories\FeedFactory::makeFromSheetCollection()`

## [v0.1.0](https://github.com/Astrotomic/stancy/releases/tag/0.1.0) - 2019-10-07

### Added

* First release - until v1.0.0 kind of proof of concept

