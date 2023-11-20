<template>
    <div class="container">
        <div class="row" v-if="matchCount > 0">
            <div class="text-center">
                <h4>You can make changes by clicking on the score you want to change.</h4>
            </div>
            <div class="col-md-4" v-for="(match, index) in matches">
                <fixture_page_component
                    :matches=match :week=index
                ></fixture_page_component>
            </div>
            <div class="col-md-12 text-center ">
                <button type="button" :disabled=!playAllWeeksButton class="btn btn-info" @click="playAllWeeks()">
                    {{ playNextWeekButtonName }}
                </button>
            </div>

        </div>
        <div class="col-md-12 text-center" v-else>
            <h6>Fixture Not set Yet</h6>
        </div>
    </div>

</template>


<script setup>

import {onMounted, ref} from "vue";
import swal from "sweetalert2";

const matchCount = ref(0)
const matches = ref([])
const playNextWeekButtonName = ref("Play All Weeks")
const playAllWeeksButton = ref(true)
onMounted(async () => {
    getAllWeekMatches()
})

const getAllWeekMatches = async () => {
    const response = await axios.get('/api/all_weeks_matches')
    matches.value = await response.data.data
    matchCount.value = Object.keys(matches.value).length

}

const playAllWeeks = async () => {
    playAllWeeksButton.value = true;
    let response = await axios.get('/api/play_all_weeks')
    response = await response.data
    swal.fire(response.message);
    if (response.code === 200) {
        getAllWeekMatches()
    }
    playAllWeeksButton.value = false;
}
</script>

<script>
import Fixture_page_component from "@/component/league/fixture_page_component.vue";

export default {
    name: "fixtures",
    components: {Fixture_page_component}
}
</script>

<style scoped>

</style>
