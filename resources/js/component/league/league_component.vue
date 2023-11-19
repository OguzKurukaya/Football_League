<template>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <p class="pt">Teams</p>
                <table class="table">
                    <thead>
                    <td>Pos.</td>
                    <td class="name">CLUBS</td>
                    <td>L</td>
                    <td>D</td>
                    <td>W</td>
                    <td>P</td>
                    <td>GF</td>
                    <td>GA</td>
                    <td>GD</td>
                    <td>PTS</td>
                    </thead>
                    <tbody>
                    <tr class="top" v-for="(team, index) in league" :key="team.id">
                        <td>{{ index + 1 }}</td>
                        <td class="name"><img class="logo" :src="'images/' + team.image_url" alt=" {{team.name}}">
                            {{ team.name }}
                        </td>
                        <td>{{ team.losses }}</td>
                        <td>{{ team.played - team.losses - team.wins }}</td>
                        <td>{{ team.wins }}</td>
                        <td>{{ team.played }}</td>
                        <td>{{ team.score_for }}</td>
                        <td>{{ team.score_against }}</td>
                        <td>{{ team.avarage }}</td>
                        <td>{{ team.points }}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <button type="button" :disabled=!buttonStatus id="generate_fixture" class="btn btn-info"
                            @click="generateFixture()">Generate Fixtures
                    </button>
                </div>
            </div>
            <div class="col-4">
                <fixture_page_component :matches=matches :week=week></fixture_page_component>
                <div class="col-md-12 text-center ">
                    <button type="button" :disabled=!fixturePlayButton class="btn btn-info" @click="playNextWeek()">
                        {{ playNextWeekButtonName }}
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <prediction_component :league=prediction>
                    </prediction_component>
                </div>
                <div class="col-md-4">
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import {onMounted, ref} from "vue";
import Fixture_page_component from "@/component/league/fixture_page_component.vue";
import swal from 'sweetalert2';
import Prediction_component from "@/component/league/prediction_component.vue";

const league = ref([])
const matches = ref([])
const week = ref(0)
const buttonStatus = ref(true)
const fixturePlayButton = ref(true)
const prediction = ref([])

const playNextWeekButtonName = ref('Play This Week')
onMounted(async () => {
    getLeague()
    nextWeekMatches()
    predictionLeague()
})
const predictionLeague = async () => {
    const response = await axios.get('/api/prediction')
    prediction.value = await response.data.data
}
const nextWeekMatches = async () => {
    const response = await axios.get('/api/next_week_matches')
    matches.value = await response.data.data
    if (response.data.type === 'last_week_matches') {
        playNextWeekButtonName.value = 'This was the Last Week of the League'
        changeNextWeekPlayButton();
    }else {
        playNextWeekButtonName.value = 'Play This Week';
        fixturePlayButton.value = true

    }
    if (matches.value.length !== 0) {
        week.value = matches.value[0].week
    }
    predictionLeague()
}
const getLeague = async () => {
    const response = await axios.get('/api/league')
    league.value = await response.data.data
}

const generateFixture = async () => {
    changeButtonStatus();
    let response = await axios.get('/api/create_fixture')
    response = await response.data
    swal.fire(response.message);
    if (response.code === 200) {
        nextWeekMatches()
    }
    changeButtonStatus();
}

const playNextWeek = async () => {
    changeNextWeekPlayButton();
    let response = await axios.get('/api/play_next_week')
    response = await response.data
    swal.fire(response.message);
    if (response.code === 200) {
        nextWeekMatches()
        getLeague()
    }
}

const changeButtonStatus = () => {
    buttonStatus.value = !buttonStatus.value
}

const changeNextWeekPlayButton = () => {
    fixturePlayButton.value = !fixturePlayButton.value
}

</script>

<script>
export default {
    name: "league_component"
}
</script>

<style scoped>

</style>
