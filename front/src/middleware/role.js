import { store } from "../store";

export default function auth({ next, router }) {

    if (!store.getters['auth/isAuthenticated']) {
        return router.push({ name: "login" });
    }

    return next();
}