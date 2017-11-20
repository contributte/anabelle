ublaboo/anabelle
================

## JSON-RPC Api documentation generator

Example docu directory:

`index.md`:

```md
# Awesome cookbook JSON-RPC API doc

@ Home:home.md
@ About project:about.md

@@ user.login:methods/user.login.md
@@ user.logout:methods/user.logout.md
```

(Everything except for `#`, `@` and `@@` sections will be removed)

`home.md`:

```md
## Home

This is a documentation of our cookbook JSON-RPC API. If you are having any troubles baking your own API client, please contact our chef Pavel Janda.

### Specification

#### Endpoint

All api calls should be targeting uri `api.example.io`. According to [JSON-RPC specification](http://www.jsonrpc.org/specification), each and every request object has to contain following properties:

- `jsonrpc`: JSON-RPC version (`"2.0"`)
- `method`: Name of the method to be invoked
- `params: Parameters of particular call (optional)
- `id`: An string identifying this call (may be `null`)

Example request payload:

\```json
{
	"jsonrpc": "2.0",
	"method": "Receipe.store",
	"params": {
		"name": "Bread with butter",
		"ingredients": [
			"bread",
			"butter"
		],
		"timeNeeded": 2
	},
	"id": null
}
\```
```

`about.md`:

```md
## About

Blah blah blah about

```

`methods/user.login.md`:

```md
## user.login

### Example request:

\```json
{
	"jsonrpc": "2.0",
	"method": "user.login",
	"params": {
		"username": "bob",
		"password": "the_creep"
	},
	"id": null
}
\```
```

`methods/user.logout.md`:

```md
## user.logout

### Example request:

\```json
{
	"jsonrpc": "2.0",
	"method": "user.logout",
	"params": {},
	"id": null
}
\```
```
