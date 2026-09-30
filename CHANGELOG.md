# CHANGELOG
This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## **v2.2.0 (30th of September 2026)** - *Release*

* [feature] Krayin v2.2.6 compatibility: the package now runs on Laravel 12.

* [feature] Added Japanese (`ja`), Korean (`ko`), Brazilian Portuguese (`pt_BR`), Vietnamese (`vi`) and Chinese Simplified (`zh_CN`) translations, bringing the package in line with the locales shipped by Krayin.

* [feature] Added an importable API collection under `collections/`.

* [security] Hardened the user endpoints. `store` and `update` mass assigned the raw request, so any authenticated token could grant itself the administrator role and the `global` data scope. The payload is now whitelisted and the role, data scope, self-escalation and primary administrator guards from the admin panel are enforced.

* [security] Scoped the user and role listings by the acting user's data scope. A `group` or `individual` scoped token previously enumerated every user and role.

* [security] Recorded `created_by` when a user or role is created, so ownership scoping has something to filter on.

* [security] Validated the `sort`, `order` and column filter parameters of every listing endpoint against the queried table. Unknown names were passed to the query builder unchecked and surfaced as unhandled SQL errors.

* [fixed] Fixed the exception handler extending `App\Exceptions\Handler`, a class Laravel removed in 11. The package could not boot on Krayin 2.2.

* [fixed] Fixed the `sanctum.admin` middleware alias pointing at a namespace the class does not live in.

* [fixed] Fixed the Swagger docs failing to generate with "Required @OA\Info() not found" on l5-swagger 11, which reads PHP attributes only. The published config now registers the docblock annotation factory alongside it.

* [fixed] Fixed the stage resource omitting `lead_pipeline_id` and `sort_order`, so stages could not be matched to their pipeline.

* [fixed] Fixed deleting a record that does not exist returning a 500. The repository clones the model it finds, so a missing id raised a PHP error instead of a 404.

* [fixed] Fixed showing a record that does not exist returning a 500 across fifteen controllers.

* [fixed] Fixed the role delete guard checking a relation that does not exist on Krayin 2.2, which silently deleted roles still assigned to users.

* [fixed] Fixed updating a user without a `password` key raising an undefined index error.

* [fixed] Fixed `create-by-ai` returning a 500 when no files were uploaded.

* [fixed] Fixed the lead, quote and tag endpoints raising a 500 when given an id that matches no record, and added the missing `tag_id` validation.

* [fixed] Fixed updating a lead, a person or a pipeline raising an undefined index error when an optional field was omitted.

* [fixed] Fixed the person tag detach and mail delete responses returning a raw translation key instead of a message.

* [fixed] Fixed the Persian translation nesting `configuration` under `settings`, so the configuration message fell back to its key.

* [fixed] Fixed the attribute endpoints accepting any string as the attribute `type`. An unrecognised type has no value column, so a single bad attribute made every later write to that entity fail with an undefined index error. The type is now constrained to the types the value model supports.

* [fixed] Removed the `settings/attributes/mass-update` route, which had no controller method and returned a 500 on every call.

* [fixed] Fixed `settings/attributes/download` being shadowed by the `{id}` show route, which made the endpoint unreachable.

* [fixed] Fixed mass operations reporting "nothing matched" as a 500 rather than a 400.

## **v2.1.1 (1st of September 2025)** - *Release*

* Update Changelog

## **v2.1.0 (1st of September 2025)** - *Release*

* Krayin version v2.1.0 compatibility has been completed.
* Resolved route on lead creation
* Resolved case of class name
* Resolved Namespace in file AdminMiddleware.php

## **v2.0.0 (16th of September 2024)** - *Release*

* Krayin version 2 compatibility has been completed.
