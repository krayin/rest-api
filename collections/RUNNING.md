# Running the collection from the command line

The collection is a standard v2.1 file, so [Newman](https://github.com/postmanlabs/newman) runs it
headlessly and prints a pass/fail line per request.

## Install

```bash
npm install -g newman
```

Or without installing anything globally:

```bash
npx newman run ...
```

## Run everything

```bash
cd /home/users/vikas.vishwakarma/www/html/Krayin/rest-api

newman run collections/krayin-rest-api.collection.json \
  -e collections/krayin-local.environment.json
```

The Login request runs first and stores the bearer token, so the rest of the collection
authenticates on its own.

> Running everything also sends the DELETE and mass-destroy requests, which remove records the
> earlier requests created. To keep a database intact, use `--folder` (below) or the
> non-destructive recipe at the end.

## Run one folder

```bash
newman run collections/krayin-rest-api.collection.json \
  -e collections/krayin-local.environment.json \
  --folder "Authentication & Account" \
  --folder "Leads"
```

Folder names are exactly as they appear in the sidebar: `Activities`, `Configuration`, `Contacts`,
`Leads`, `Mails`, `Products`, `Quotes`, `Settings / Attributes`, `Settings / Data Transfer`,
`Settings / Email Templates`, `Settings / Groups`, `Settings / Locations`,
`Settings / Marketing`, `Settings / Pipelines`, `Settings / Roles`, `Settings / Sources`,
`Settings / Tags`, `Settings / Types`, `Settings / Users`, `Settings / Warehouses`,
`Settings / Web Forms`, `Settings / Webhooks`, `Settings / Workflows`, `Session teardown`.

Always include `Authentication & Account` (or pass `--env-var token=...`), or every request in the
folder returns 401.

## Run a single request

Newman has no "one request" flag, so filter the folder and read the line you care about:

```bash
newman run collections/krayin-rest-api.collection.json \
  -e collections/krayin-local.environment.json \
  --folder "Authentication & Account" --folder "Leads" \
  --reporter-cli-no-assertions
```

To test one endpoint in isolation, curl is quicker:

```bash
TOKEN=$(curl -s -X POST http://krayin-local.com/api/v1/login \
  -H 'Accept: application/json' -H 'Content-Type: application/json' \
  -d '{"email":"admin@example.com","password":"YOUR_PASSWORD","device_name":"cli"}' \
  | python3 -c 'import sys,json; print(json.load(sys.stdin)["token"])')

curl -s http://krayin-local.com/api/v1/leads \
  -H "Authorization: Bearer $TOKEN" -H 'Accept: application/json' | python3 -m json.tool
```

## See the failures only

The CLI reporter prints every request. To get a status code per request and nothing else, export
JSON and summarise it:

```bash
newman run collections/krayin-rest-api.collection.json \
  -e collections/krayin-local.environment.json \
  --reporters json --reporter-json-export /tmp/run.json \
  --suppress-exit-code

node -e '
const r = require("/tmp/run.json");
const codes = {};

r.run.executions.forEach(e => {
    const c = e.response ? e.response.code : 0;
    codes[c] = (codes[c] || 0) + 1;
});

console.log("status counts:", JSON.stringify(codes));
console.log();

r.run.executions.forEach(e => {
    const c = e.response ? e.response.code : 0;
    if (c >= 200 && c < 300) return;

    let message = "";
    try {
        message = JSON.parse(Buffer.from(e.response.stream.data).toString()).message || "";
    } catch (x) {}

    console.log(
        String(c).padEnd(4),
        e.item.name.padEnd(28),
        "/" + (e.request.url.path || []).join("/"),
        "  ",
        String(message).replace(/\s+/g, " ").slice(0, 70)
    );
});
'
```

`--suppress-exit-code` keeps a non-zero exit from stopping a shell script; drop it in CI when a
failure should fail the build.

## Useful flags

| Flag | Why |
| --- | --- |
| `--folder "<name>"` | Run one folder; repeatable |
| `--env-var "base_url=http://krayin.test"` | Override a variable without editing the environment |
| `--timeout-request 20000` | Raise the per-request timeout (ms) |
| `--delay-request 200` | Pause between requests |
| `-n 3` | Run the whole collection 3 times |
| `--bail` | Stop at the first failure |
| `--verbose` | Show request and response headers |
| `--reporters cli,json,html` | Add reporters (`npm i -g newman-reporter-html` for HTML) |

## Non-destructive run

To exercise the collection repeatedly without deleting anything, strip the DELETE and
mass-destroy requests first:

```bash
node -e '
const fs = require("fs");
const c = JSON.parse(fs.readFileSync("collections/krayin-rest-api.collection.json"));

const keep = r => r.request.method !== "DELETE" && !/mass-destroy|Logout/i.test(r.name);
const walk = items => items
    .map(r => r.item ? { ...r, item: walk(r.item) } : r)
    .filter(r => r.item ? r.item.length : keep(r));

c.item = walk(c.item).filter(f => !f.item || f.item.length);
fs.writeFileSync("/tmp/collection-readonly.json", JSON.stringify(c, null, 2));

let n = 0;
c.item.forEach(f => n += f.item.length);
console.log("kept " + n + " non-destructive requests");
'

newman run /tmp/collection-readonly.json -e collections/krayin-local.environment.json
```

## Expected failures

Some requests cannot pass without setup, and are not bugs:

| Request | Why |
| --- | --- |
| `Forgot password` | needs a reachable mail host (`mailhog:1025` by default) |
| `Download settings` | needs a `path` query parameter naming a stored file |
| `Download sample imports` | needs a valid `sample` type |
| `Create location` | needs a warehouse to exist |
| `Create email template`, `Update setting` | 1062 duplicate name if the record already exists from a previous run |

Anything else returning 500 is worth reporting.
