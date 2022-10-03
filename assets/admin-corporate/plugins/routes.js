/** @format */

import CompanyInfo from "../components/company/CompanyInfo";
import OrderHistory from "../components/order/OrderHistory";
import EmployeeList from "../components/employee/list/EmployeeList";

export const routes = [
    { path: `${process.env.MIX_APP_CORPORATE_URL}company`, name: "company_index", component: CompanyInfo },
    { path: `${process.env.MIX_APP_CORPORATE_URL}orders`, name: "company_orders", component: OrderHistory },
    { path: `${process.env.MIX_APP_CORPORATE_URL}employees`, name: "company_employees", component: EmployeeList },
];
