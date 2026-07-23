<?php

namespace Webkul\RestApi\Tests\Integration;

/**
 * Real CRUD over the Contacts endpoints (persons + organizations) against a
 * live Krayin instance, plus the error contracts this fork guarantees: a
 * missing resource is a JSON 404 (not a 500/HTML), and invalid input is a JSON
 * 422 — driven through Krayin's real attribute validation and repositories.
 */
class ContactsIntegrationTest extends IntegrationTestCase
{
    // -- Organizations ------------------------------------------------------

    public function test_list_organizations_returns_paginated_json(): void
    {
        $response = $this->get('api/v1/contacts/organizations');

        $this->assertSame(200, $response['status']);
        $this->assertArrayHasKey('data', $response['json']);
        $this->assertArrayHasKey('meta', $response['json']);
    }

    public function test_create_show_and_delete_an_organization(): void
    {
        $name = $this->unique('Org ');

        $created = $this->post('api/v1/contacts/organizations', ['name' => $name]);

        $this->assertContains($created['status'], [200, 201], 'Create should succeed: '.json_encode($created));
        $id = $created['json']['data']['id'] ?? null;
        $this->assertNotNull($id, 'Created organization has no id: '.json_encode($created));
        $this->deleteOnTearDown("api/v1/contacts/organizations/{$id}");

        $this->assertSame($name, $created['json']['data']['name'] ?? null);

        // Show the freshly created record.
        $show = $this->get("api/v1/contacts/organizations/{$id}");
        $this->assertSame(200, $show['status']);
        $this->assertSame($id, $show['json']['data']['id'] ?? null);

        // Delete it and confirm it is gone.
        $deleted = $this->delete("api/v1/contacts/organizations/{$id}");
        $this->assertContains($deleted['status'], [200, 204]);

        $goneShow = $this->get("api/v1/contacts/organizations/{$id}");
        $this->assertSame(404, $goneShow['status']);
        $this->assertHumanMessage($goneShow['json']);
    }

    public function test_show_nonexistent_organization_returns_404_json(): void
    {
        $response = $this->get('api/v1/contacts/organizations/999999999');

        $this->assertSame(404, $response['status']);
        $this->assertHumanMessage($response['json']);
    }

    // -- Persons ------------------------------------------------------------

    public function test_list_persons_returns_paginated_json(): void
    {
        $response = $this->get('api/v1/contacts/persons');

        $this->assertSame(200, $response['status']);
        $this->assertArrayHasKey('data', $response['json']);
        $this->assertArrayHasKey('meta', $response['json']);
    }

    public function test_create_and_delete_a_person(): void
    {
        $name = $this->unique('Person ');
        $email = 'it_'.uniqid().'@example.test';

        $created = $this->post('api/v1/contacts/persons', [
            'name'   => $name,
            'emails' => [['value' => $email, 'label' => 'work']],
        ]);

        $this->assertContains($created['status'], [200, 201], 'Create should succeed: '.json_encode($created));
        $id = $created['json']['data']['id'] ?? null;
        $this->assertNotNull($id, 'Created person has no id: '.json_encode($created));
        $this->deleteOnTearDown("api/v1/contacts/persons/{$id}");

        $this->assertSame($name, $created['json']['data']['name'] ?? null);

        $show = $this->get("api/v1/contacts/persons/{$id}");
        $this->assertSame(200, $show['status']);
        $this->assertSame($id, $show['json']['data']['id'] ?? null);
    }

    public function test_create_organization_with_invalid_data_returns_422(): void
    {
        // `address.country` is validated against the countries table; an unknown
        // code must come back as a JSON 422 with field errors (not a 500).
        $response = $this->post('api/v1/contacts/organizations', [
            'name'    => $this->unique('Org '),
            'address' => ['country' => 'ZZ'],
        ]);

        $this->assertSame(422, $response['status']);
        $this->assertArrayHasKey('errors', $response['json']);
        $this->assertArrayHasKey('address.country', $response['json']['errors']);
        $this->assertHumanMessage($response['json']);
    }

    public function test_show_nonexistent_person_returns_404_json(): void
    {
        $response = $this->get('api/v1/contacts/persons/999999999');

        $this->assertSame(404, $response['status']);
        $this->assertHumanMessage($response['json']);
    }

    /**
     * Bug 2: a user-defined custom field must be returned by the API the way the
     * panel shows it — in the single GET and in the list (eager-loaded). This is
     * fully self-provisioning (it creates its own user-defined attribute and an
     * organization that sets it, then cleans both up), so it runs unattended in
     * the integration CI without any seed data. Organizations exercise the exact
     * same shared `InteractsWithCustomAttributes` mechanism used by leads/persons.
     */
    public function test_organization_user_defined_custom_field_round_trips(): void
    {
        // 1. Create a user-defined text attribute on `organizations`.
        $code = 'it_cf_'.uniqid();

        $attribute = $this->post('api/v1/settings/attributes', [
            'code'        => $code,
            'name'        => 'IT Custom Field',
            'type'        => 'text',
            'entity_type' => 'organizations',
            'is_required' => 0,
        ]);

        $this->assertContains($attribute['status'], [200, 201], 'Attribute create should succeed: '.json_encode($attribute));
        $attributeId = $attribute['json']['data']['id'] ?? null;
        $this->assertNotNull($attributeId, 'Created attribute has no id: '.json_encode($attribute));
        $this->deleteOnTearDown("api/v1/settings/attributes/{$attributeId}");

        // 2. Create an organization that sets the custom field.
        $value = $this->unique('cf_');
        $created = $this->post('api/v1/contacts/organizations', [
            'name' => $this->unique('Org '),
            $code  => $value,
        ]);

        $this->assertContains($created['status'], [200, 201], 'Org create should succeed: '.json_encode($created));
        $organizationId = $created['json']['data']['id'] ?? null;
        $this->assertNotNull($organizationId, 'Created organization has no id: '.json_encode($created));
        $this->deleteOnTearDown("api/v1/contacts/organizations/{$organizationId}");

        // 3. The single GET returns the custom field with its value.
        $show = $this->get("api/v1/contacts/organizations/{$organizationId}");
        $this->assertSame(200, $show['status']);
        $this->assertArrayHasKey($code, $show['json']['data'], 'Show did not expose custom field '.$code);
        $this->assertSame($value, $show['json']['data'][$code]);

        // 4. So does the list endpoint (proving the eager-load path).
        $list = $this->get('api/v1/contacts/organizations?limit=50');
        $match = null;
        foreach ($list['json']['data'] ?? [] as $row) {
            if ((int) ($row['id'] ?? 0) === (int) $organizationId) {
                $match = $row;
                break;
            }
        }

        $this->assertNotNull($match, 'Created organization not found in list.');
        $this->assertArrayHasKey($code, $match, 'List did not expose custom field '.$code);
        $this->assertSame($value, $match[$code]);
    }
}
