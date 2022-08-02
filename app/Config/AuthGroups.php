<?php

namespace Config;

// ...
use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * The available authentication systems, listed
     * with alias and class name. These can be referenced
     * by alias in the auth helper:
     *      auth('api')->attempt($credentials);
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Complete control of the site.',
        ],
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Day to day administrators of the site.',
        ],
        'developer' => [
            'title'       => 'Developer',
            'description' => 'Site programmers.',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'General users of the site. Often customers.',
        ],
        'beta' => [
            'title'       => 'Beta User',
            'description' => 'Has access to beta-level features.',
        ],
        // otherUser
        // 'partner' => [
        //     'title' => 'User Partner',
        //     'description' => 'Groups for user partner',
        // ]
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system. Each system is defined
     * where the key is the
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'        => 'Can access the sites admin area',
        'admin.settings'      => 'Can access the main site settings',
        'users.manage-admins' => 'Can manage other admins',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'beta.access'         => 'Can access beta-level features',
        // otherRules
        'superadmin.createAdmin' => 'Can create admin',
        'superadmin.readAdmin' => 'Can read admin',
        'superadmin.updateAdmin' => 'Can update admin',
        'superadmin.deleteAdmin' => 'Can delete admin',
        'admin.createPartner' => 'Can create partner',
        'admin.readPartner' => 'Can read partner',
        'admin.updatePartner' => 'Can update partner',
        'admin.deletePartner' => 'Can delete partner',
        'partner.createProduct' => 'Can create product',
        'partner.readProduct' => 'Can read product',
        'partner.updateProduct' => 'Can update product',
        'partner.deleteProduct' => 'Can delete product',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     */
    public array $matrix = [
        'superadmin' => [
            'superadmin.*',
            'admin.*',
            'partner.*',
            // 'admin.*',
            // 'users.*',
            // 'beta.*',
            // 'user.*',
        ],
        'admin' => [
            'admin.*',
            'partner.*',
            // 'admin.access',
            // 'users.create',
            // 'users.edit',
            // 'users.delete',
            // 'beta.access',
        ],
        'developer' => [
            'superadmin.*',
            'admin.*',
            'partner.*',
            // 'admin.access',
            // 'admin.settings',
            // 'users.create',
            // 'users.edit',
            // 'beta.access',
            // 'user.*',
        ],
        // 'user' => [
        //     // otherRules
        //     'user.*',
        // ],
        // 'beta' => [
        //     'beta.access',
        // ],
    ];
}
