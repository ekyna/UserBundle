UserBundle
===========

FOSUser management with [EkynaAdminBundle](https://github.com/ekyna/AdminBundle).

# Installation

 [TODO]
            

## Configuration

- Configure third party bundles

    ```yaml
    # app/config/config.yml
    hwi_oauth:
        firewall_names: ["front"]
        connect: ~
        fosub:
            username_iterations: 30
            properties:
                fake: fake
        resource_owners:
            google:
                type:           google
                client_id:      # <your value>
                client_secret:  # <your value>
                scope:          "email profile"
            # ...
    
    fos_user:
        from_email:
            address:     # <your value>
            sender_name: # <your value>
    ```

- Configure the security

    ```yaml
    # app/config/security.yml
    security:
        providers:
            fos_userbundle:
                id: fos_user.user_provider.username_email
        encoders:
            FOS\UserBundle\Model\UserInterface:
                algorithm:            pbkdf2
                hash_algorithm:       sha512
                encode_as_base64:     true
                iterations:           1000
        firewalls:
            dev:
                pattern:  ^/(_(profiler|wdt)|css|images|js)/
                security: false
            main:
                switch_user: true
                context:     user
                form_login:
                    success_handler: ekyna_user.security.authentication_success_handler
                    failure_handler: ekyna_user.security.authentication_failure_handler
                    provider: fos_userbundle
                    login_path: fos_user_security_login
                    check_path: fos_user_security_check
                    always_use_default_target_path: false
                    target_path_parameter: _target_path
                    default_target_path: /
                    use_referer: true
                remember_me:
                    secret: "%secret%"
                    name: APP_REMEMBER_ME
                    lifetime: 31536000
                    remember_me_parameter: _remember_me
                logout:
                    path: fos_user_security_logout
                    target: fos_user_security_login
                    invalidate_session: false
                oauth:
                    success_handler: ekyna_commerce.security.oauth_authentication_success_handler
                    resource_owners:
                        facebook: "/oauth/login/check-facebook"
                        google: "/oauth/login/check-google"
                    login_path:     fos_user_security_login
                    failure_path:   fos_user_security_login
                    oauth_user_provider:
                        service: ekyna_user.oauth.fos_provider
                anonymous: true
    ```

- Create a base template for emails
    ```twig
    {# app/Resources/view/email.html.twig #}
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body>
        <h1>
            {% block subject %}{% endblock %}
        </h1>
        <div>
            {% block body %}{% endblock %}
        </div>
    </body>
    </html>
    ```

