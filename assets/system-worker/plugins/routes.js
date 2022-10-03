/** @format */

import Toolbar from "./../components/Static/Toolbar";
import Navigation from "./../components/Static/Navigation";
import Dashboard from "./../views/Dashboard";
import Login from "../views/Auth/Login";
import Profile from "../views/Profile/profile.component";

const URN = process.env.MIX_APP_WORKER_URL;

export const routes = [
    {
        props: true,
        name: "system-show-login-form",
        path: `${URN}login`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: Login,
        },
    },
    {
        props: true,
        name: "get_dashboard_page",
        path: `${URN}dashboard`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: Dashboard,
        },
    },
    {
        props: true,
        name: "profile_index",
        path: `${URN}profile`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: Profile,
        },
    },
    {
        props: true,
        name: "profile_view",
        path: `${URN}profile/:system_worker_id`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: Profile,
        },
    },
    {
        props: true,
        name: "get_driver_candidates",
        path: `${URN}driver-candidates`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/DriverCandidate/DriverCandidates"),
        },
    },
    {
        props: true,
        name: "get_candidates_create",
        path: `${URN}driver-candidates/create`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/DriverCandidate/Create"),
        },
    },
    {
        props: true,
        name: "get_driver_candidates_edit",
        path: `${URN}driver-candidates/edit/:candidate_id`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/DriverCandidate/Edit"),
        },
    },
    {
        props: true,
        name: "get_parks",
        path: `${URN}parks`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Park/Parks"),
        },
    },
    {
        props: true,
        name: "get_system_workers",
        path: `${URN}workers`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Worker/Index"),
        },
    },
    {
        props: true,
        name: "get_system_workers_create",
        path: `${URN}workers/create`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Worker/Create"),
        },
    },
    {
        name: "get_system_workers_edit",
        path: `${URN}workers/edit/:system_worker_id`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Worker/Edit"),
        },
    },
    {
        props: true,
        name: "traffic_safety_department",
        path: `${URN}traffic-safety`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/TrafficSafety/Index"),
        },
    },
    {
        props: true,
        name: "traffic_safety_department_create",
        path: `${URN}traffic-safety/create`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/TrafficSafety/Create"),
        },
    },
    {
        props: true,
        name: "traffic_safety_department_edit",
        path: `${URN}traffic-safety/edit/:car_id`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/TrafficSafety/Edit"),
        },
    },
    {
        props: true,
        name: "park_management",
        path: `${URN}park-management`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/ParkManagement/Index"),
        },
    },
    {
        props: true,
        name: "get_waybills",
        path: `${URN}waybills`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Waybills/Index"),
        },
    },
    {
        props: true,
        name: "park_manager_drivers",
        path: `${URN}drivers`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Driver/Index"),
        },
    },
    {
        props: true,
        name: "get_feedbacks_index",
        path: `${URN}feedbacks`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("../views/Feedback/Index"),
        },
    },
    {
        props: true,
        name: "get_company",
        path: `${URN}company`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Company/index"),
        },
    },
    {
        props: true,
        name: "legal_entity_index",
        path: `${URN}legal-entity`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/LegalEntity/Index"),
        },
    },
    {
        props: true,
        name: "company_create",
        path: `${URN}company/create`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Company/Create"),
        },
    },
    {
        props: true,
        name: "company_edit",
        path: `${URN}company/edit/:company_id`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Company/Edit"),
        },
    },
    {
        props: true,
        name: "legal_entity_create",
        path: `${URN}legal-entity/create`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("../views/LegalEntity/components/Create"),
        },
    },
    {
        props: true,
        name: "legal_entity_edit",
        path: `${URN}legal-entity/edit/:entity_id`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("../views/LegalEntity/components/Edit"),
        },
    },
    {
        props: true,
        name: "get_schedule_info",
        path: `${URN}schedule`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("../views/Schedule/Index.component.vue"),
        },
    },
    {
        props: true,
        name: "contract_signing",
        path: `${URN}contract-signing`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/ContractSigning/index"),
        },
    },
    {
        props: true,
        name: "show_company_index",
        path: `${URN}company`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Company/index"),
        },
    },
    {
        props: true,
        name: "driver_types",
        path: `${URN}driver-types`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/DriverTypes/Index"),
        },
    },
    {
        props: true,
        name: "show_complaint_index",
        path: `${URN}complaint`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Complaint/Index"),
        },
    },
    {
        props: true,
        name: "show_driver_contracts",
        path: `${URN}driver-contracts`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/DriverContracts/Index"),
        },
    },

    // Call Center
    {
        props: true,
        name: "call_center",
        path: `${URN}call-center`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/CallCenter/Operator/Index"),
        },
    },
    {
        props: true,
        name: "call_center_dispatcher",
        path: `${URN}call-center-dispatcher`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/CallCenter/Dispatcher/Index"),
        },
    },

    // AllTransaction
    {
        props: true,
        name: "bookkeeping_index",
        path: `${URN}bookkeeping`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Bookkeeping/Index"),
        },
    },
    {
        props: true,
        name: "bookkeeping_all_index",
        path: `${URN}bookkeeping/all`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Bookkeeping/all/Index"),
        },
    },

    {
        props: true,
        name: "bookkeeping_companies",
        path: `${URN}bookkeeping/companies`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Bookkeeping/companies/Index"),
        },
        children: [
            {
                props: true,
                name: "bookkeeping_company_orders_index",
                path: `${URN}bookkeeping/companies/company-orders`,
                components: {
                    toolbar: Toolbar,
                    navigation: Navigation,
                    default: () => import("../views/Bookkeeping/companies/orders/Index"),
                },
            },
            {
                props: true,
                name: "bookkeeping_companies_orders_price_report_index",
                path: `${URN}bookkeeping/companies/company-orders-report`,
                components: {
                    toolbar: Toolbar,
                    navigation: Navigation,
                    default: () => import("../views/Bookkeeping/companies/report/BookkeepingCompanyOrdersReport"),
                },
            },
        ],
    },
    {
        props: true,
        name: "bookkeeping_drivers",
        path: `${URN}bookkeeping/drivers`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Bookkeeping/drivers/Index"),
        },
    },
    {
        props: true,
        name: "clients_index",
        path: `${URN}clients`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Clients/Index"),
        },
    },
    {
        props: true,
        name: "penalties_index",
        path: `${URN}penalties`,
        components: {
            toolbar: Toolbar,
            navigation: Navigation,
            default: () => import("./../views/Penalty/Index"),
        },
    },
];
