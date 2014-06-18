Templates
=========

Templates are located in the *vendor/ekyna/user-bundle/Ekyna/Bundle/UserBundle/Resources/views* folder.
To override them, they may be copied in *app/Resources/EkynaUserBundle/views*.

| Template  | Description |
| --- | --- | 
| *layout.html.twig* | Customer area global layout (includes *menu.html.twig*). |
| *menu.html.twig* | Menu layout (KnpMenu). |

### Customer area pages

| Page | Route | Default Uri | Template  |
| --- | --- | --- | --- |
| Home | ekyna_user_account_home | /account/ | - |
| Login | fos_user_security_login | /account/login | *Security/login.html.twig* |
| Logout | fos_user_security_logout | /account/logout | - |
| Register | fos_user_registration_register | /account/register/ | *Registration/register.html.twig* |
| Register (confirmed) | fos_user_registration_confirmed | /account/register/confirmed | *Registration/confirmed.html.twig* |
| Reset password | fos_user_resetting_request | /account/resetting/ | Resetting/request.html.twig |
| My informations | fos_user_profile_show | /account/profile/ | *Profile/show.html.twig* |
| Edit my informations | fos_user_profile_edit | /account/profile/edit | *Profile/edit.html.twig* |
| Change my password | fos_user_change_password | /account/profile/change-password | *ChangePassword/changePassword.html.twig* |
| My addresses | ekyna_user_address_list | /account/address/ | *Address/list.html.twig* |
| Create a new address | ekyna_user_address_new | /account/address/new | *Address/new.html.twig* | 
| Edit the address | ekyna_user_address_edit | /account/address/{addressId}/edit | *Address/edit.html.twig* |
| Remove the address | ekyna_user_address_remove | /account/address/{addressId}/remove | *Address/remove.html.twig* |

### Twig extension

You can render an address with *Address/_render.html.twig* template by using the **render_adress** twig funtion.
```twig
{{ render_address(address) }}
```