<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    public array $groups = [
        'superadmin'      => ['title' => 'Super Admin',      'description' => 'Akses penuh ke seluruh sistem.'],
        'finance'         => ['title' => 'Finance',          'description' => 'Akses kelola keuangan dan invoice.'],
        'drafter'         => ['title' => 'Drafter',          'description' => 'Akses pembuatan gambar kerja.'],
        'surveyor'        => ['title' => 'Surveyor',         'description' => 'Akses data survei lapangan.'],
        'designer'        => ['title' => 'Designer',         'description' => 'Akses desain proyek.'],
        'design_interior' => ['title' => 'Design Interior',  'description' => 'Akses desain khusus interior.'],
        'arsitek'         => ['title' => 'Arsitek',          'description' => 'Akses rancangan arsitektur.'],
        'estimator'       => ['title' => 'Estimator',        'description' => 'Akses hitung RAB dan estimasi.'],
        'konten_kreator'  => ['title' => 'Konten Kreator',   'description' => 'Akses kelola media sosial & konten.'],
    ];

    public array $permissions = [
        'admin.access'        => 'Can access the sites admin area',
        'admin.settings'      => 'Can access the main site settings',
        'users.manage-admins' => 'Can manage other admins',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'beta.access'         => 'Can access beta-level features',

        'finance.access'      => 'Can access the finance section',
        'finance.view'        => 'Can view financial data',
        'finance.invoice'     => 'Can create/edit invoices',

        'drafter.access'      => 'Can access the drafter section',
        'drafter.create'      => 'Can create new drawings',
        'drafter.edit'        => 'Can edit existing drawings',

        'surveyor.access'     => 'Can access the surveyor section',
        'surveyor.collect'    => 'Can collect field survey data',

        'design.access'       => 'Can access the design section',
        'design.create'       => 'Can create new designs',
        'design.edit'         => 'Can edit existing designs',

        'design_interior.access' => 'Can access interior design',
        'design_interior.create' => 'Can create interior designs',
        'design_interior.edit'   => 'Can edit interior designs',

        'arsitek.access' => 'Can access the architectural section',
        'arsitek.design' => 'Can create architectural designs',

        'estimator.access' => 'Can access the estimation section',
        'estimator.rab'    => 'Can calculate BoQ/RAB',
        'estimator.price'  => 'Can set pricing',

        'content.create'   => 'Can create new content',
        'content.manage'   => 'Can manage/edit content',
        'content.social'   => 'Can post to social media',
        'content.draft'    => 'Can save drafts',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.access',
            'admin.settings',
            'users.manage',
            'finance.manage',
            'project.manage',
            'content.manage',
            'survey.manage',
        ],

        'finance' => [
            'finance.access',
            'finance.view',
            'finance.invoice',
        ],

        'drafter' => [
            'drafter.access',
            'drafter.create',
            'drafter.edit',
        ],

        'surveyor' => [
            'surveyor.access',
            'surveyor.collect',
        ],

        'designer' => [
            'design.access',
            'design.create',
            'design.edit',
        ],

        'design_interior' => [
            'design_interior.access',
            'design_interior.create',
            'design_interior.edit',
        ],

        'arsitek' => [
            'arsitek.access',
            'arsitek.design',
        ],

        'estimator' => [
            'estimator.access',
            'estimator.rab',
            'estimator.price',
        ],

        'konten_kreator' => [
            'content.create',
            'content.manage',
            'content.social',
            'content.draft',
        ],
    ];
}
