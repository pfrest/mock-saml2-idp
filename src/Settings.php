<?php

namespace MockSaml2Idp;

/**
 * Class to manage and provide configuration settings for the Mock SAML2 Identity Provider
 */
class Settings {
    const IDP_SSO_URL = '/sso/module.php/saml/idp/singleSignOnService';
    const IDP_SLO_URL = '/sso/module.php/saml/idp/singleLogout';
    const IDP_METADATA_URL = '/sso/saml2/idp/metadata.php';

    public string $idp_entity_id;
    public string $idp_cert_path;
    public string $idp_key_path;
    public string $idp_cert;
    public string $idp_auth_mode;
    public string $idp_nameid_format;
    public string $idp_nameid_attribute;
    public string $idp_user_name;
    public string $idp_user_name_attribute;
    public string $idp_user_uid;
    public string $idp_user_uid_attribute;
    public string $idp_user_password;
    public string $idp_user_first_name;
    public string $idp_user_first_name_attribute;
    public string $idp_user_last_name;
    public string $idp_user_last_name_attribute;
    public string $idp_user_email;
    public string $idp_user_email_attribute;
    public array $idp_user_groups;
    public string $idp_user_groups_attribute;
    public array $idp_user_custom_attributes;
    public string $sp_entity_id;
    public string $sp_acs_location;
    public string $sp_acs_binding;
    public string $sp_slo_location;
    public string $sp_slo_binding;

    /**
     * Constructor to initialize configuration from environment variables
     */
    public function __construct() {
        # Load values from environment variables and assign defaults where necessary
        $this->idp_entity_id = getenv('IDP_ENTITY_ID') ?: 'mock-saml2-idp';
        $this->idp_cert_path = getenv('IDP_CERT_PATH') ?: '/app/certs/idp.crt';
        $this->idp_key_path = getenv('IDP_KEY_PATH') ?: '/app/certs/idp.key';
        $this->idp_cert = file_get_contents($this->idp_cert_path) ?: '';
        $this->idp_auth_mode = getenv('IDP_AUTH_MODE') ?: 'basic';
        $this->idp_nameid_format =
            getenv('IDP_NAMEID_FORMAT') ?: 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent';
        $this->idp_nameid_attribute = getenv('IDP_NAMEID_ATTRIBUTE') ?: 'uid';
        $this->idp_user_name = getenv('IDP_USER_NAME') ?: 'mock-saml2-idp-user';
        $this->idp_user_name_attribute = getenv('IDP_USER_NAME_ATTRIBUTE') ?: 'username';
        $this->idp_user_uid = getenv('IDP_USER_UID') ?: $this->idp_user_name;
        $this->idp_user_uid_attribute = getenv('IDP_USER_UID_ATTRIBUTE') ?: 'uid';
        $this->idp_user_password = getenv('IDP_USER_PASSWORD') ?: 'mock-saml2-idp-password';
        $this->idp_user_first_name = getenv('IDP_USER_FIRST_NAME') ?: 'Mock';
        $this->idp_user_first_name_attribute = getenv('IDP_USER_FIRST_NAME_ATTRIBUTE') ?: 'first_name';
        $this->idp_user_last_name = getenv('IDP_USER_LAST_NAME') ?: 'User';
        $this->idp_user_last_name_attribute = getenv('IDP_USER_LAST_NAME_ATTRIBUTE') ?: 'last_name';
        $this->idp_user_email = getenv('IDP_USER_EMAIL') ?: 'mock-saml2-idp-user@example.com';
        $this->idp_user_email_attribute = getenv('IDP_USER_EMAIL_ATTRIBUTE') ?: 'email';
        $this->idp_user_groups = explode(',', getenv('IDP_USER_GROUPS')) ?: ['group1', 'group2'];
        $this->idp_user_groups_attribute = getenv('IDP_USER_GROUPS_ATTRIBUTE') ?: 'groups';
        $this->sp_entity_id = getenv('SP_ENTITY_ID');
        $this->sp_acs_location = getenv('SP_ACS_LOCATION');
        $this->sp_acs_binding = getenv('SP_ACS_BINDING') ?: 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST';
        $this->sp_slo_location = getenv('SP_SLO_LOCATION') ?: '';
        $this->sp_slo_binding = getenv('SP_SLO_BINDING') ?: 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect';

        # Custom attributes
        $custom_attributes_json = getenv('IDP_USER_CUSTOM_ATTRIBUTES') ?: '{}';
        $this->idp_user_custom_attributes = json_decode($custom_attributes_json, associative: true);
    }

    /**
     * Convert the settings to an associative array for serialization
     * @return array The settings in a serializable array format
     */
    public function to_array(): array {
        return [
            'idp_entity_id' => $this->idp_entity_id,
            'idp_cert_path' => $this->idp_cert_path,
            'idp_key_path' => $this->idp_key_path,
            'idp_cert' => $this->idp_cert,
            'idp_metadata_url' => $this::IDP_METADATA_URL,
            'idp_sso_url' => $this::IDP_SSO_URL,
            'idp_slo_url' => $this::IDP_SLO_URL,
            'idp_auth_mode' => $this->idp_auth_mode,
            'idp_nameid_format' => $this->idp_nameid_format,
            'idp_nameid_attribute' => $this->idp_nameid_attribute,
            'idp_user_name' => $this->idp_user_name,
            'idp_user_name_attribute' => $this->idp_user_name_attribute,
            'idp_user_uid' => $this->idp_user_uid,
            'idp_user_uid_attribute' => $this->idp_user_uid_attribute,
            'idp_user_password' => $this->idp_user_password,
            'idp_user_first_name' => $this->idp_user_first_name,
            'idp_user_first_name_attribute' => $this->idp_user_first_name_attribute,
            'idp_user_last_name' => $this->idp_user_last_name,
            'idp_user_last_name_attribute' => $this->idp_user_last_name_attribute,
            'idp_user_email' => $this->idp_user_email,
            'idp_user_email_attribute' => $this->idp_user_email_attribute,
            'idp_user_groups' => $this->idp_user_groups,
            'idp_user_groups_attribute' => $this->idp_user_groups_attribute,
            'idp_user_custom_attributes' => $this->idp_user_custom_attributes,
            'sp_entity_id' => $this->sp_entity_id,
            'sp_acs_location' => $this->sp_acs_location,
            'sp_acs_binding' => $this->sp_acs_binding,
            'sp_slo_location' => $this->sp_slo_location,
            'sp_slo_binding' => $this->sp_slo_binding,
        ];
    }
}
