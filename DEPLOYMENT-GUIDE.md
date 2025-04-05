# GiggaDev Deployment Guide

This guide provides instructions for deploying the GiggaDev e-commerce platform to your Ubuntu 22.04 server running Apache2.

## Prerequisites

- An Ubuntu 22.04 server with Apache2 installed
- Root or sudo access to the server
- SSH access configured
- Domain name (giggadev.com) pointing to your server
- OpenSSH client installed on your local machine

## Deployment Process

### Step 1: Explore the Server Environment (Optional)

If you want to explore the Apache2 environment on your server first:

1. Connect to your server via SSH:
   ```
   ssh root@giggahost.com
   ```

2. Once connected, run commands to explore:
   - System information
   - Apache2 status and version
   - Apache2 configuration files
   - Virtual hosts configurations
   - Web content directories

### Step 2: Deploy the GiggaDev Platform

1. Create the Apache2 virtual host config file for giggadev.com
2. Create the directory structure for the website
3. Copy the website files to the production location
4. Set proper ownership and permissions
5. Enable the site in Apache2
6. Enable necessary Apache2 modules
7. Set up SSL with Let's Encrypt (optional)
8. Check Apache2 configuration for syntax errors
9. Reload Apache2 to apply changes

### Step 3: Verify the Deployment

After the deployment is complete:

1. Visit your website: `https://giggadev.com`
2. Check the Apache2 error logs if you encounter any issues

## Stripe Integration

After deployment, you need to:

1. Update your Stripe API keys in the configuration file
2. Set your Stripe webhook endpoint

## Troubleshooting

If you encounter issues during deployment:

1. **Apache2 Configuration Issues**:
   - Check Apache2 syntax: `apache2ctl configtest`
   - Check Apache2 error logs: `tail -f /var/log/apache2/error.log`

2. **Permission Issues**:
   - Ensure proper ownership
   - Check directory permissions
   - Check file permissions

3. **SSL/HTTPS Issues**:
   - Verify Let's Encrypt certificates
   - Renew certificates if needed

## Maintenance

Regular maintenance tasks:

1. Update the system
2. Check Apache2 security
3. Back up your site
4. Monitor logs
