import { store } from "../store";

export default async function guest({ next, router }) {
    if (await store.dispatch("isAuth")) {
        return router.push({ name: "dashboard" });
    }

    return next();
}