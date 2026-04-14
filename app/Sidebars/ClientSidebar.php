<?php

namespace App\Sidebars;

use App\Repositories\RolePermissionRepository;

class ClientSidebar
{
    public function __construct(private RolePermissionRepository $rolePermissionRepository){

    }
    public function sidebar(){
        return [
            [
                "type" => "label",
                'label' => "Main",
                "id" => "main",
            ],
            [
                "name" => "Dashboard",
                "permission" => $this->rolePermissionRepository->getRoutePermission('dashboard.main'),
                "type" => "link",
                "active_link" => ['/'],
                "route" => route('dashboard.main'),
                "icon" => '<i class="link-icon" data-feather="box"></i>', // Preserved font-awesome icon
            ],
            [
                "type" => "label",
                'label' => "Sales & Purchase",
                "id" => "sale_purchase",
            ],
            [
                "name" => "Trade",
                "type" => "dropdown",
                "id" => "sale",
                // "permission" => $this->rolePermissionRepository->checkIfAnyRouteHasPermission(['sale.create', 'purchase.create']),
                "active_link" => [
                    request()->get('test') == 1 ? null : 'sale/*',
                    'sale/create',
                    'purchase/*',
                    'purchase/create'
                ],
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag link-icon"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>',
                "list" => [
                    [
                        'name' => 'Sales',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('sale.create'),
                        'route' => route('sale.create'),
                        'active_link' => [request()->get('test') == 1 ? null : 'sale/*', 'sale/create']
                    ],
                    [
                        'name' => 'Purchases',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('purchase.create'),
                        'route' => route('purchase.create'),
                        'active_link' => ['purchase/*', 'purchase/create']
                    ]
                ]
            ],

            [
                "type" => "label",
                'label' => "Ledgers Management",
                "id" => "ledgers",
            ],

            [
                "name" => "Ledgers",
                "type" => "dropdown",
                "id" => "custledger",
                // "permission" => $this->rolePermissionRepository->checkIfAnyRouteHasPermission(['custledger.index', 'supledger.index']),
                "active_link" => [
                    request()->get('test') == 1 ? null : 'custledger/*',
                    request()->get('test') == 1 ? null : 'custledger',
                    request()->get('test') == 1 ? null : 'supledger/*',
                    request()->get('test') == 1 ? null : 'supledger'
                ],
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign link-icon"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                "list" => [
                    [
                        'name' => 'Customer Ledger',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('custledger.index'),
                        'route' => route('custledger.index'),
                        'active_link' => [request()->get('test') == 1 ? null : 'custledger/*', request()->get('test') == 1 ? null : 'custledger']
                    ],
                    [
                        'name' => 'Supplier Ledger',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('supledger.index'),
                        'route' => route('supledger.index'),
                        'active_link' => [request()->get('test') == 1 ? null : 'supledger/*', request()->get('test') == 1 ? null : 'supledger']
                    ]
                ]
            ],

            [
                "type" => "label",
                'label' => "Payments Management",
                "id" => "payments",
            ],
            [
                "name" => "Payments",
                "type" => "dropdown",
                "id" => "tran",
                // "permission" => $this->rolePermissionRepository->checkIfAnyRouteHasPermission(['ctra.index', 'tra.index']),
                "active_link" => ['tra/*', 'tra', 'ctra/*', 'ctra'],
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card link-icon"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>',
                "list" => [
                    [
                        'name' => 'Customer Payments',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('ctra.index'),
                        'route' => route('ctra.index'),
                        'active_link' => ['ctra/*', 'ctra']
                    ],
                    [
                        'name' => 'Supplier Payments',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('tra.index'),
                        'route' => route('tra.index'),
                        'active_link' => ['tra/*', 'tra']
                    ]
                ]
            ],

            [
                "type" => "label",
                'label' => "Fuel Management",
                "id" => "fuel",
            ],
            [
                "name" => "Fuels",
                "permission" => $this->rolePermissionRepository->getRoutePermission('products.index'),
                "type" => "link",
                "active_link" => ['products/*', 'products'],
                "route" => route('products.index'),
                "icon" => '<i class="fas fa-gas-pump link-icon"></i>', // Preserved font-awesome icon
            ],
            [
                "name" => "Fuel Stocks",
                "permission" => $this->rolePermissionRepository->getRoutePermission('stock.index'),
                "type" => "link",
                "active_link" => ['stock/*', 'stock'],
                "route" => route('stock.index'),
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-battery link-icon"><rect x="1" y="6" width="18" height="12" rx="2" ry="2"></rect><line x1="23" y1="13" x2="23" y2="11"></line></svg>',
            ],
            [
                "name" => "Fuel Backup",
                "permission" => $this->rolePermissionRepository->getRoutePermission('backup.index'),
                "type" => "link",
                "active_link" => ['backup/*', 'backup'],
                "route" => route('backup.index'),
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-battery-charging link-icon"><path d="M5 18H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h3.19M15 6h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-3.19"></path><line x1="23" y1="13" x2="23" y2="11"></line><polyline points="11 6 7 12 13 12 9 18"></polyline></svg>',
            ],
            [
                "name" => "Fuel Dips",
                "permission" => $this->rolePermissionRepository->getRoutePermission('dip.index'),
                "type" => "link",
                "active_link" => ['dip/*', 'dip'],
                "route" => route('dip.index'),
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="link-icon feather feather-thermometer"><path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z"></path></svg>',
            ],

            [
                "type" => "label",
                'label' => "Settings",
                "id" => "settings",
            ],

            [
                "name" => "Users",
                "permission" => $this->rolePermissionRepository->getRoutePermission('user.index'),
                "type" => "link",
                "active_link" => ['user/*', 'user'],
                "route" => route('user.index'),
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users link-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
            ],
            [
                "name" => "Roles",
                "permission" => $this->rolePermissionRepository->getRoutePermission('roles.index'),
                "type" => "link",
                "active_link" => ['roles/*', 'roles'],
                "route" => route('roles.index'),
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users link-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
            ],

            [
                "name" => "Locations",
                "permission" => $this->rolePermissionRepository->getRoutePermission('hierarchy.index'),
                "type" => "link",
                "active_link" => ['hierarchy/*', 'hierarchy'],
                "route" => route('hierarchy.index'),
                "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin link-icon"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
            ],

            [
                "type" => "label",
                'label' => "Contacts",
                "id" => "contacts",
            ],

            [
                "name" => "Traders",
                "type" => "dropdown",
                "id" => "trader",
                // "permission" => $this->rolePermissionRepository->checkIfAnyRouteHasPermission(['customer.index', 'supplier.index']),
                "active_link" => ['supplier/*', 'supplier', 'customer/*', 'customer'],
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users link-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
                "list" => [
                    [
                        'name' => 'Customers',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('customer.index'),
                        'route' => route('customer.index'),
                        'active_link' => ['customer/*', 'customer']
                    ],
                    [
                        'name' => 'Suppliers',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('supplier.index'),
                        'route' => route('supplier.index'),
                        'active_link' => ['supplier/*', 'supplier']
                    ]
                ]
            ],

            [
                "type" => "label",
                'label' => "Expense Management",
                "id" => "expense",
            ],
            [
                "name" => "Expenses",
                "type" => "dropdown",
                "id" => "exp",
                // "permission" => $this->rolePermissionRepository->checkIfAnyRouteHasPermission(['exp.index', 'exptype.index']),
                "active_link" => ['exptype/*', 'exptype', 'exp/*', 'exp'],
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trello link-icon"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>',
                "list" => [
                    [
                        'name' => 'Expenses',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('exp.index'),
                        'route' => route('exp.index'),
                        'active_link' => ['exp/*', 'exp']
                    ],
                    [
                        'name' => 'Expense Types',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('exptype.index'),
                        'route' => route('exptype.index'),
                        'active_link' => ['exptype/*', 'exptype']
                    ]
                ]
            ],

            [
                "type" => "label",
                'label' => "Reports Management",
                "id" => "reports",
            ],
            [
                "name" => "Ledgers",
                "type" => "dropdown",
                "id" => "rep",
                // "permission" => $this->rolePermissionRepository->checkIfAnyRouteHasPermission(['report.credit']),
                "active_link" => ['report/credit/*', 'report/credit', request()->get('test') == 1 ? 'supledger' : null, request()->get('test') == 1 ? 'custledger' : null],
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather link-icon feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                "list" => [
                    [
                        'name' => 'Credit Report',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('report.credit'),
                        'route' => route('report.credit'),
                        'active_link' => ['report/credit', 'report/credit/*', request()->get('test') == 1 ? 'supledger' : null, request()->get('test') == 1 ? 'custledger' : null]
                    ]
                ]
            ],
            [
                "name" => "Sales",
                "type" => "dropdown",
                "id" => "dsa",
                // "permission" => $this->rolePermissionRepository->checkIfAnyRouteHasPermission(['report.sale.dailysale', 'report.sale.profit']),
                "active_link" => ['report/sale/dailysale', 'report/sale/dailysale/*', 'report/sale/profit', 'report/sale/profit/*', request()->get('test') != 1 ? null : 'sale/*', 'report/sale/dailysaleitem/*', 'report/sale/profitfilter', 'report/sale/profitfilter/*'],
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather link-icon feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>',
                "list" => [
                    [
                        'name' => 'Daily Sales',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('report.sale.dailysale'),
                        'route' => route('report.sale.dailysale'),
                        'active_link' => ['report/sale/dailysale', 'report/sale/dailysale/*', request()->get('test') != 1 ? null : 'sale/*', 'report/sale/dailysaleitem/*']
                    ],
                    [
                        'name' => 'Profit & Loss',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('report.sale.profit'),
                        'route' => route('report.sale.profit'),
                        'active_link' => ['report/sale/profit', 'report/sale/profit/*', 'report/sale/profitfilter', 'report/sale/profitfilter/*']
                    ]
                ]
            ],
            [
                "name" => "Expenses",
                "type" => "dropdown",
                "id" => "expe",
                // "permission" => $this->rolePermissionRepository->checkIfAnyRouteHasPermission(['report.expense']),
                "active_link" => ['report/expense', 'report/expense/*', 'report/expensefilter', 'report/expensefilter/*'],
                "icon" => '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trello link-icon"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>',
                "list" => [
                    [
                        'name' => 'Monthly Expense Report',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('report.expense'),
                        'route' => route('report.expense'),
                        'active_link' => ['report/expense', 'report/expense/*', 'report/expensefilter', 'report/expensefilter/*']
                    ]
                ]
            ],
            [
                "name" => "Fuel Prices",
                "type" => "dropdown",
                "id" => "price",
                // "permission" => $this->rolePermissionRepository->checkIfAnyRouteHasPermission(['report.price']),
                "active_link" => ['report/prices', 'report/prices/*'],
                "icon" => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather link-icon feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                "list" => [
                    [
                        'name' => 'Fuel Price Report',
                        'permission' => $this->rolePermissionRepository->getRoutePermission('report.price'),
                        'route' => route('report.price'),
                        'active_link' => ['report/prices', 'report/prices/*']
                    ]
                ]
            ]
        ];
    }
}
