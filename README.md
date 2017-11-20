ublaboo/anabelle
================

## JSON-RPC Api documentation generator


### Example:

There is an example documentation directory: `demo`, as you can see above.

### Extended Markdown syntax:

- `#include <file.md>` == content of `<file.md>` will be placed here
- `@ <sectionName>:<sectionFile.md>` == high-level section definition
- `@@ <sectionName>:<sectionFile.md>` == method section definition

### How to use it:

```bash
~ $ cd myApi
~/myApi $ composer require ublaboo/anabelle
~/myApi $ vendor/bin/anabelle docuDir docuOutputDir [-o, --overwriteOutputDir]
```

### Generator workflow:

1. Most important (and only required) file is `index.md`. In this file, you can use only (different Markdown markup is ignored in `index.md`):
	- `# <h1>`
	- `#include <file.md>`
	- `@ <sectionName>:<sectionFile.md>`
	- `@@ <sectionName>:<sectionFile.md>`)
1. `#include` macros are replaced
1. `<h1>` is used as documentation page title
1. `@` and `@@` sections are rendered in the sidebar nav
1. Content of `@` and `@@` sections is rendered into separate files and loaded into the main section detail after clicking particular section link in the nav
