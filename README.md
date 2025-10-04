# Mock SAML2 Identity Provider (IdP)

[![Build](https://github.com/pfrest/mock-saml2-idp/actions/workflows/build.yml/badge.svg)](https://github.com/pfrest/mock-saml2-idp/actions/workflows/build.yml)
[![Quality](https://github.com/pfrest/mock-saml2-idp/actions/workflows/quality.yml/badge.svg)](https://github.com/pfrest/mock-saml2-idp/actions/workflows/quality.yml)
[![Release](https://github.com/pfrest/mock-saml2-idp/actions/workflows/release.yml/badge.svg)](https://github.com/pfrest/mock-saml2-idp/actions/workflows/release.yml)

`mock-saml2-idp` is a radically simple SAML2 Identity Provider (IdP) for testing and developing SAML2 Service Providers
(SPs), all wrapped up in a single, easy-to-use Docker container. It supports highly configurable user attributes and
automatic logins to facilitate automated testing via CI/CD pipelines.

## Getting Started

To get started with `mock-saml2-idp`, you'll need to have [Docker](https://docs.docker.com/get-started/get-docker/)
installed on your machine. Once Docker is installed and running, you can pull the `mock-saml2-idp` image from the
GitHub Container Registry:

```bash
docker pull ghcr.io/pfrest/mock-saml2-idp:latest
```

### Running the Container

As a minimal example, you can run the container with the following command:

```bash
docker run \
-p 8080:8080 \
-p 8443:8443 \
-e SP_ACS_LOCATION=http://example.com/saml/acs/ \
-e SP_ENTITY_ID=http://example.com \
-d ghcr.io/pfrest/mock-saml2-idp:latest
```

> [!NOTE]
> You will need to set the environment variables to match your SAML2 Service Provider (SP) configuration. See the
> [Variables section](#variables) below for more information on available environment variables.

## Variables

The IdP can be configured using environment variables. While most variables can be left at their default values, but
you may need to configure the IdP to match your SP's specific requires or to emulate different scenarios. Below is a list
of all available environment variables:

| Variable                        | Description                                                                                    | Required | Default                                                |
| ------------------------------- | ---------------------------------------------------------------------------------------------- | -------- | ------------------------------------------------------ |
| `SP_ENTITY_ID`                  | The Entity ID of the SAML2 Service Provider (SP).                                              | Yes      |                                                        |
| `SP_ACS_LOCATION`               | The Assertion Consumer Service (ACS) URL of the SAML2 Service Provider (SP).                   | Yes      |                                                        |
| `SP_ACS_BINDING`                | The ACS binding type. Either `POST` or `REDIRECT`.                                             | No       | `urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST`       |
| `SP_SLO_LOCATION`               | The Single Logout (SLO) URL of the SAML2 Service Provider (SP).                                | No       | ` `                                                    |
| `SP_SLO_BINDING`                | The SLO binding type. Either `POST` or `REDIRECT`.                                             | No       | `urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect`   |
| `IDP_ENTITY_ID`                 | The Entity ID of the Identity Provider (IdP).                                                  | No       | `mock-saml2-idp`                                       |
| `IDP_CERT_FILE`                 | The path to the X.509 certificate file to use for signing/encrypting SAML assertions.          | No       | `/app/certs/idp.crt`                                   |
| `IDP_KEY_FILE`                  | The path to the private key file to use for signing/encrypting SAML assertions.                | No       | `/app/certs/idp.key`                                   |
| `IDP_AUTH_MODE`                 | The authentication mode. Either `basic` (username/password) or `auto` (automatic login)        | No       | `basic`                                                |
| `IDP_NAMEID_FORMAT`             | The NameID format to use. It is recommended to use the default.                                | No       | `urn:oasis:names:tc:SAML:2.0:nameid-format:persistent` |
| `IDP_NAMEID_ATTRIBUTE`          | The name of the user attribute to use as the NameID.                                           | No       | `uid`                                                  |
| `IDP_USER_NAME`                 | The username of the IdP user to create. By default this is also used as the `uid`              | No       | `mock-saml2-idp-user`                                  |
| `IDP_USER_NAME_ATTRIBUTE`       | The attribute name to use for the user's username.                                             | No       | `username`                                             |
| `IDP_USER_UID`                  | The UID of the IdP user to create. This sets the user's `uid` attribute.                       | No       | `${IDP_USER_NAME}`                                     |
| `IDP_USER_UID_ATTRIBUTE`        | The attribute name to use for the user's UID.                                                  | No       | `uid`                                                  |
| `IDP_USER_PASSWORD`             | The password of the IdP user to create. Note this only applies to the `basic` auth mode.       | No       | `mock-saml2-idp-password`                              |
| `IDP_USER_FIRST_NAME`           | The first name of the IdP user to create. This sets the user's `first_name` attribute.         | No       | `Mock`                                                 |
| `IDP_USER_FIRST_NAME_ATTRIBUTE` | The attribute name to use for the user's first name.                                           | No       | `first_name`                                           |
| `IDP_USER_LAST_NAME`            | The last name of the IdP user to create. This sets the user's `last_name` attribute.           | No       | `User`                                                 |
| `IDP_USER_LAST_NAME_ATTRIBUTE`  | The attribute name to use for the user's last name.                                            | No       | `last_name`                                            |
| `IDP_USER_EMAIL`                | The email address of the user to create. This sets the user's `email` attribute.               | No       | `mock-saml2-idp-user@example.com`                      |
| `IDP_USER_EMAIL_ATTRIBUTE`      | The attribute name to use for the user's email address.                                        | No       | `email`                                                |
| `IDP_USER_GROUPS`               | Comma-separated list of groups to assign to the user. This sets the user's `groups` attribute. | No       | `group1,group2`                                        |
| `IDP_USER_GROUPS_ATTRIBUTE`     | The attribute name to use for the user's group memberships                                     | No       | `groups`                                               |
| `IDP_USER_CUSTOM_ATTRIBUTES`    | A JSON string defining any additional custom user attributes to set.                           | No       | `{}`                                                   |

> [!NOTE]
>
> - Configuring multiple users is not supported. The container is designed to create a single user for testing purposes.
>   This IdP is not designed to act as a user directory.
> - If the `IDP_CERT_FILE` and `IDP_KEY_FILE` environment variables are not set or the specified files do not exist, a
>   self-signed certificate and key will be generated automatically during container startup.

## Configuring Your Service Provider (SP)

Once you have the container running, you will need to configure your SAML2 Service Provider (SP) to trust the
`mock-saml2-idp`.

### Automatic Configuration

If your SP supports automatic configuration via a metadata URL, you can use the following URL to retrieve the IdP:

```
/sso/saml2/idp/metadata.php
```

For example, if you are running the container locally on port 8443, the fully metadata URL to configure in your SP would be:

```
https://localhost:8443/sso/saml2/idp/metadata.php
```

> [!IMPORTANT]
> Your `mock-saml2-idp` instance must be reachable from your SP directly if you are using automatic configuration via
> the metadata URL. If your SP cannot reach the IdP at the specified URL (e.g., blocked by a firewall, no route to
> host, etc.), you will need to use manual configuration to proceed.

### Manual Configuration

If your SP does not support automatic configuration via a metadata URL, or you still need to test manual configuration,
you can easily and programmatically pull the IdP configuration using `mock-saml2-idp`'s /api/settings.php endpoint to
retrieve the necessary configuration details. This endpoint requires no authentication and returns a JSON response with
all the necessary information to configure your SP.

Below is an example request to the /api/settings.php endpoint:

```bash
curl -k https://localhost:8443/api/settings.php
```

```json
{
  "idp_entity_id": "mock-saml2-idp",
  "idp_cert_path": "/app/certs/idp.crt",
  "idp_key_path": "/app/certs/idp.key",
  "idp_cert": "-----BEGIN CERTIFICATE-----\nMIIFmTCCA4GgAwI...pVV2cckEMt0IGpu8lIR3\n-----END CERTIFICATE-----\n",
  "idp_metadata_url": "/sso/module.php/saml/idp/metadata.php",
  "idp_sso_url": "/sso/module.php/saml/idp/singleSignOnService",
  "idp_slo_url": "/sso/module.php/saml/idp/singleLogout",
  "idp_auth_mode": "basic",
  "idp_nameid_format": "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
  "idp_nameid_attribute": "uid",
  "idp_user_name": "mock-saml2-idp-user",
  "idp_user_name_attribute": "username",
  "idp_user_uid": "mock-saml2-idp-user",
  "idp_user_uid_attribute": "uid",
  "idp_user_password": "mock-saml2-idp-password",
  "idp_user_first_name": "Mock",
  "idp_user_first_name_attribute": "first_name",
  "idp_user_last_name": "User",
  "idp_user_last_name_attribute": "last_name",
  "idp_user_email": "mock-saml2-idp-user@example.com",
  "idp_user_email_attribute": "email",
  "idp_user_groups": ["group1", "group2"],
  "idp_user_groups_attribute": "groups",
  "idp_user_custom_attributes": [],
  "sp_entity_id": "https://localhost/sso/metadata/",
  "sp_acs_location": "https://localhost/sso/acs/",
  "sp_acs_binding": "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST",
  "sp_slo_location": "",
  "sp_slo_binding": "urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect"
}
```

> [!NOTE]
> You will need to piece together the full URLs for the metadata, SSO, and SLO endpoints using the base URL of your
> `mock-saml2-idp` instance when configuring your SP.
