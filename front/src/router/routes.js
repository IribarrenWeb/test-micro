import DashboardLayout from "@/layout/DashboardLayout";
import AuthLayout from "@/layout/AuthLayout";

import Dashboard from "../views/Dashboard.vue";

import Login from "../views/Login.vue";
import Register from "../views/Register.vue";

import auth from "@/middleware/auth";
import guest from "@/middleware/guest";

const routes = [{
        path: "/",
        redirect: "/dashboard",
        component: DashboardLayout,
        children: [{
            path: "/dashboard",
            name: "dashboard",
            components: { default: Dashboard },
            meta: { middleware: auth },

        }, ]
    },
    {
        path: "/",
        redirect: "login",
        component: AuthLayout,
        children: [{
                path: "/login",
                name: "login",
                components: { default: Login },
                meta: { middleware: guest },
            },
            {
                path: "/register",
                name: "register",
                components: { default: Register },
                meta: { middleware: guest },
            },
        ],
    },
];

export default routes;