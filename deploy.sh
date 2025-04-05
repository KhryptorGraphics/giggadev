#!/bin/bash
#
# GiggaDev Deployment Script
# ======================
# This script deploys the GiggaDev e-commerce platform to an Apache2 server.
#
# Usage: ./deploy.sh [domain_name] [admin_email]
#

set -e

# Check if parameters are provided
if [ $# -lt 2 ]; then
    echo "Usage: $0 [domain_name] [admin_email]"
    exit 1
fi

DOMAIN=$1
EMAIL=$2
WWW_DIR="/var/www/$DOMAIN"
APACHE_CONFIG="/etc/apache2/sites-available/$DOMAIN.conf"

echo "==== GiggaDev Deployment Script ===="
echo "Domain: $DOMAIN"
echo "Admin Email: $EMAIL"
echo "Web Directory: $WWW_DIR"
echo ""

# Create web directory if it doesn't exist
if [ ! -d "$WWW_DIR" ]; then
    echo "Creating web directory: $WWW_DIR"
    mkdir -p "$WWW_DIR"
fi

# Copy files to web directory
echo "Copying files to web directory..."
cp -r public_html "$WWW_DIR/"

# Set permissions
echo "Setting permissions..."
chown -R www-data:www-data "$WWW_DIR"
find "$WWW_DIR" -type d -exec chmod 755 {} \;
find "$WWW_DIR" -type f -exec chmod 644 {} \;

# Create Apache configuration
echo "Creating Apache configuration..."
cat > "$APACHE_CONFIG" << EOF
<VirtualHost *:80>
    ServerAdmin $EMAIL
    ServerName $DOMAIN
    ServerAlias www.$DOMAIN
    DocumentRoot $WWW_DIR/public_html
    
    <Directory $WWW_DIR/public_html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/$DOMAIN-error.log
    CustomLog \${APACHE_LOG_DIR}/$DOMAIN-access.log combined
    
    # Redirect HTTP to HTTPS
    RewriteEngine on
    RewriteCond %{SERVER_NAME} =$DOMAIN [OR]
    RewriteCond %{SERVER_NAME} =www.$DOMAIN
    RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
</VirtualHost>
EOF

# Enable Apache configuration
echo "Enabling Apache configuration..."
a2ensite "$DOMAIN.conf"

# Enable required modules
echo "Enabling required modules..."
a2enmod rewrite
a2enmod ssl

# Reload Apache
echo "Reloading Apache..."
systemctl reload apache2

echo ""
echo "==== Deployment Complete ===="
echo "The GiggaDev e-commerce platform has been deployed to $DOMAIN."
echo "You may now configure SSL with Let's Encrypt by running:"
echo "certbot --apache -d $DOMAIN -d www.$DOMAIN"
echo ""
echo "Don't forget to update your Stripe API keys in the configuration file!"
