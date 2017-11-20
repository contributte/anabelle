ublaboo/anabelle
================

## JSON-RPC Api documentation generator


### Example:

There is an example documentation directory: `demo`, as you can see above.

### Extended Markdown syntax:

#### `#include <file.md>`

```md
#include variables.md
```

#### `$variable = <value>`

```md
$uuid = 123e4567-e89b-12d3-a456-426655440000
```

Inline variable usage:

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

Block variable usage:

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


## How to use anabelle:

```bash
~ $ cd myApi
~/myApi $ composer require ublaboo/anabelle
~/myApi $ vendor/bin/anabelle docuDir docuOutputDir [-o, --overwriteOutputDir]
```

## Generator workflow:

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
