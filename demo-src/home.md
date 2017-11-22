## Home

This is a documentation of our cookbook JSON-RPC API. If you are having any troubles baking your own API client, please contact our chef Pavel Janda.

### Specification of project {$projectName} (version {$projectVersion})

#### Endpoint

All api calls should be targeting uri `api.example.io`. According to [JSON-RPC specification](http://www.jsonrpc.org/specification), each and every request object has to contain following properties:

- `jsonrpc`: JSON-RPC version (`"2.0"`)
- `method`: Name of the method to be invoked
- `params`: Parameters of particular call (optional)
- `id`: A string identifying this call (may be `null` - in that case, `null` is also returned in the `id` property)

#### Example request payload:

```json
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
```
