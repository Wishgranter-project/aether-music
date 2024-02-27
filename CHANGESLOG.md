# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.0-alpha] - 2024-02-27
### Changed
- Renamed the package to `wishgranter-project/aether-music` and namespace to `WishgranterProject\AetherMusic`.

---

## [2.0.0-alpha] - 2023-12-10
### Changed
- Moved classes around and made tweaks to the sorting code.

---

## [1.0.0-alpha] - 2023-11-04
### Changed
- `Aether::search()` no longer returns search results straight away, instead it returns a `Search` object ( more below ).

### Added
- Now criteria to sort search results are modular, allowing for customization and extension.
