import { store } from "../store";

export default async function auth({ next, router }) {
    if (!await store.dispatch('isAuth')) {
        return router.push({ name: "login" });
    }

    return next();
}