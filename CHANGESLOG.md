# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0-alpha] - 2023-11-04

### Changed
- `Aether::search()` no longer returns search results straight away, instead it returns a `Search` object ( more below ).

### Added
- Now criteria to sort search results are modular, allowing for customization and extension.
