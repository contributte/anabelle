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

\```
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

\```
{
	"jsonrpc": "2.0",
	"method": "user.logout",
	"params": {},
	"id": null
}
\```
```
