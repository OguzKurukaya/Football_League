import {createRouter, createWebHistory} from "vue-router";


import notFound from "@/component/NotFound.vue";
import teams from "@/component/league/teams.vue";
import league_component from "@/component/league/league_component.vue";
import fixtures_component from "@/component/league/fixtures_component.vue";

const routes = [
    {
        path: "/",
        name: "teams",
        component: teams,
    },
    {
        path: "/:pathMatch(.*)*",
        name: "notFound",
        component: notFound,
    },
    {
        path: "/league",
        name: "league",
        component: league_component,
    },
    {
        path: "/fixtures",
        name: "fixtures",
        component: fixtures_component
    }

];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
