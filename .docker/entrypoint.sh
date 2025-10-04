#/bin/bash

set -e
IDP_CERT_PATH="${IDP_CERT_PATH:-/app/certs/idp.crt}"
IDP_KEY_PATH="${IDP_KEY_PATH:-/app/certs/idp.key}"

# Generate certificates for the web server and SAML2 IdP
echo -n "Generating certificates... "
/bin/openssl req -newkey rsa:4096 -new -x509 -days 365 -nodes \
    -subj "/C=US/ST=Denial/L=Springfield/O=Dis/CN=apache.example.com" \
    -out /etc/ssl/certs/apache.crt \
    -keyout /etc/ssl/private/apache.key 2> /dev/null

if [ ! -f "$IDP_CERT_PATH" ] && [ ! -f "$IDP_KEY_PATH" ]; then
    /bin/mkdir -p $(/usr/bin/dirname $IDP_CERT_PATH)
    /bin/mkdir -p $(/usr/bin/dirname $IDP_KEY_PATH)
    /bin/openssl req -newkey rsa:4096 -new -x509 -days 365 -nodes \
        -subj "/C=US/ST=Denial/L=Springfield/O=Dis/CN=idp.example.com" \
        -out $IDP_CERT_PATH \
        -keyout $IDP_KEY_PATH 2> /dev/null
fi
echo "done."

# Ensure permissions are correct
/bin/chown -R www-data:www-data $IDP_CERT_PATH
/bin/chown -R www-data:www-data $IDP_KEY_PATH
/bin/chmod 644 $IDP_CERT_PATH
/bin/chmod 600 $IDP_KEY_PATH
/bin/chown -R www-data:www-data /etc/ssl/certs
/bin/chown -R www-data:www-data /etc/ssl/private/apache.key
/bin/chmod 644 /etc/ssl/certs/apache.crt
/bin/chmod 600 /etc/ssl/private/apache.key

# Start apache in the foreground
/usr/sbin/apache2ctl -D FOREGROUND
