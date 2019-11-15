# Changelog

All notable changes to this package will be documented in this file. The format is based on [Keep a Changelog](http://keepachangelog.com/).

## [Unreleased](https://github.com/Astrotomic/stancy/compare/0.6.0...master)

## [v0.6.0](https://github.com/Astrotomic/stancy/releases/tag/0.6.0) - 2019-11-15

## Added

* `\Astrotomic\Stancy\Models\Page::__get()` method to access `\Astrotomic\Stancy\Models\PageData` properties

### Changed

* [spatie/laravel-feed:^2.6](https://github.com/spatie/laravel-feed/releases/tag/2.6.0) is now used which changed the `\Spatie\Feed\Feed` class signature
* `\Astrotomic\Stancy\Contracts\FeedFactory::makeFromSheetCollectionName()` return type changed from `array` to `\Illuminate\Support\Collection` 
* `\Astrotomic\Stancy\Traits\PageHasOrder::$order` allows `float` now

## [v0.5.1](https://github.com/Astrotomic/stancy/releases/tag/0.5.1) - 2019-10-29

### Changed

* [spatie/laravel-export:^0.2.2](https://github.com/spatie/laravel-export/releases/tag/0.2.2) is now used which fixes the export path of static files with an extension - like `sitemap.xml`

## [v0.5.0](https://github.com/Astrotomic/stancy/releases/tag/0.5.0) - 2019-10-22

### Added

* `\Astrotomic\Stancy\Commands\MakePageCommand`

### Changed

* `\Astrotomic\Stancy\StancyServiceProvider` is not deferred anymore

## [v0.4.0](https://github.com/Astrotomic/stancy/releases/tag/0.4.0) - 2019-10-15

### Added

* ExportFactory which integrates [spatie/laravel-export](https://github.com/spatie/laravel-export)
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

