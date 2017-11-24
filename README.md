[![Build Status](https://travis-ci.org/ublaboo/anabelle.svg?branch=master)](https://travis-ci.org/ublaboo/anabelle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ublaboo/anabelle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ublaboo/anabelle/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/ublaboo/anabelle/v/stable)](https://packagist.org/packages/ublaboo/anabelle)
[![License](https://poser.pugx.org/ublaboo/anabelle/license)](https://packagist.org/packages/ublaboo/anabelle)
[![Total Downloads](https://poser.pugx.org/ublaboo/anabelle/downloads)](https://packagist.org/packages/ublaboo/anabelle)
[![Gitter](https://img.shields.io/gitter/room/nwjs/nw.js.svg)](https://gitter.im/ublaboo/help)

ublaboo/anabelle
================

## Api documentation generator (JSON-RPC / REST)

(No matter whether you're using REST or JSON-RPC)

### Example

There is an example documentation directory: `demo`, as you can see above.

### Extended Markdown syntax

#### `#include <file.md>`

```md
#include variables.md
```

#### `$variable = <value>`

```md
$uuid = 123e4567-e89b-12d3-a456-426655440000
```

Inline variable usage

```md
User uuid is {$uuid}
```

#### `$$blockVariable ... $$`

```md
$$successEmptyResponse
{
	"jsonrpc": "2.0",
	"result": {},
	"id": null
}
$$
```

Block variable usage

```md
Server returns response:

{$$successEmptyResponse}
```

#### `@ <sectionName>:<sectionFile.md>`

High-level section definition. This macro available only in `index.md` file.

```md
@ Home:home.md
@ About project:about.md
```

#### `@@ <sectionName>:<sectionFile.md>`

Method section definition. This macro available only in `index.md` file.

```md
@@ user.login:methods/user.login.md
@@ user.logout:methods/user.logout.md
@@ user.register:methods/user.register.md
@@ user.confirm-registration:methods/user.confirm-registration.md
```


## How to use anabelle

```bash
~ $ cd myApi
~/myApi $ composer require ublaboo/anabelle
~/myApi $ vendor/bin/anabelle docu-src docu
```

### CLI options

#### Automatically overwrite output directory

```bash
vendor/bin/anabelle docu-src docu -o
// Or
vendor/bin/anabelle docu-src docu --overwriteOutputDir
```

#### Add http auth to generated files

**Beware! Anabelle generates by default .html files. If you are using http auth, it generates .php files due to the need of validating http auth headers.**

```bash
vendor/bin/anabelle docu-src docu -u user -p pass
// Or
vendor/bin/anabelle docu-src docu --httpAuthUser user -httpAuthPass pass
```

## Generator workflow

1. Most important (and only required) file is `index.md`. In this file, you can use only (different Markdown markup is ignored in `index.md`):
	- `# <h1>`
	- `#include <file.md>`
	- `$variable = <value>`
	- `$$blockVariable ... $$`
	- `@ <sectionName>:<sectionFile.md>`
	- `@@ <sectionName>:<sectionFile.md>`)
1. `#include` macros are replaced
1. `<h1>` is used as documentation page title
1. `@` and `@@` sections are rendered in the sidebar nav
1. Content of `@` and `@@` sections is rendered into separate files and loaded into the main section detail after clicking particular section link in the nav
