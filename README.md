# SilverWare Publications Module

[![Latest Stable Version](https://poser.pugx.org/silverware/publications/v/stable)](https://packagist.org/packages/silverware/publications)
[![Latest Unstable Version](https://poser.pugx.org/silverware/publications/v/unstable)](https://packagist.org/packages/silverware/publications)
[![License](https://poser.pugx.org/silverware/publications/license)](https://packagist.org/packages/silverware/publications)

Provides a publications page for [SilverWare][silverware] apps, divided into a series of categories and publications.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Issues](#issues)
- [Contribution](#contribution)
- [Maintainers](#maintainers)
- [License](#license)

## Requirements

- [SilverWare][silverware]

## Installation

Installation is via [Composer][composer]:

```
$ composer require silverware/publications
```

## Usage

The module provides three pages ready for use within the CMS:

- `PublicationArchive`
- `PublicationCategory`
- `Publication`

Create a `PublicationArchive` as the top-level of your publications section. Under the `PublicationArchive` you
may add `PublicationCategory` pages as children to divide the page into a series
of categories. Then, as children of `PublicationCategory`, add your `Publication` pages for individual
publications.

Once you've added a `Publication`, you may add a series of downloadable files for the publication via the "Files" tab.

## Issues

Please use the [GitHub issue tracker][issues] for bug reports and feature requests.

## Contribution

Your contributions are gladly welcomed to help make this project better.
Please see [contributing](CONTRIBUTING.md) for more information.

## Maintainers

[![Colin Tucker](https://avatars3.githubusercontent.com/u/1853705?s=144)](https://github.com/colintucker) | [![Praxis Interactive](https://avatars2.githubusercontent.com/u/1782612?s=144)](https://www.praxis.net.au)
---|---
[Colin Tucker](https://github.com/colintucker) | [Praxis Interactive](https://www.praxis.net.au)

## License

[BSD-3-Clause](LICENSE.md) &copy; Praxis Interactive

[silverware]: https://github.com/praxisnetau/silverware
[composer]: https://getcomposer.org
[issues]: https://github.com/praxisnetau/silverware-publications/issues
