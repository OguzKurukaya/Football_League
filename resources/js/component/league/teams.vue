<template>
    <div class="container">
        <div class="row">
            <div class="col-4 ">
                <p class="pt">Teams</p>
                <table class="table">
                    <thead>
                    <td>Pos.</td>
                    <td class="name">CLUBS</td>
                    </thead>
                    <tbody>

                    <tr class="top" v-for="(team, index) in teams" :key="team.id">
                        <td>{{ index + 1 }}</td>
                        <td class="name"><img class="logo" :src="'images/' + team.image_url" alt=" {{team.name}}">
                            {{ team.name }}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <router-link to="/league">Go For League</router-link>
                </div>
            </div>

        </div>
    </div>

</template>

<script setup>

import {onMounted, ref} from "vue";

let teams = ref([])

onMounted(async () => {
    getTeams()
})
const getTeams = async () => {
    const response = await axios.get('/api/teams')
    teams.value = await response.data.data
}

</script>

<script>
export default {
    name: "league_component"
}
</script>

<style scoped>

</style>
