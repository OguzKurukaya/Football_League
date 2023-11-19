<template>

    <div class="container">
        <div class="row align-items-right">
            <div class="col-md-12 text-center ">
                <p>Next Matches</p>
            </div>
            <div class="col-md-12" v-if="matches.length > 0">
                <div class="col-md-12 text-center ">
                    <p>Week {{week.toString()}}</p>
                </div>
                <div class="row" v-for="(match, index) in matches">
                    <div class="col-md-4 h-25 p-3">
                        <td class="name ">
                            <div class="align-items-center d-flex justify-content-center flex-column">
                                <img class="logo" :src="'images/' + match.home_team_image_url"
                                     alt=" {{match.home_team_name}}">
                                <p>{{ match.home_team_name }}</p>
                            </div>
                        </td>
                    </div>
                    <div class="col-md-4  align-items-center d-flex justify-content-center flex-column">
                        <div v-if="!match.is_played">
                            -
                        </div>
                        <div v-else>
                            <div class="row">
                                <div class=" form-group col-md-5">
                                    <input :id=index
                                           name="home_team_score"
                                           class="col-md-12 input no-border"
                                           type="text"
                                           readonly
                                           @change="activateButton"
                                           @click="clicked"
                                           :value=match.home_team_score>
                                </div>
                                -
                                <div class="form-group col-md-5">
                                    <input :id=index
                                           name="away_team_score"
                                           class="col-md-12 input no-border"
                                           readonly
                                           @change="activateButton"
                                           @click="clicked"
                                           :value=match.away_team_score>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4  h-25 p-3 ">
                        <td class="name">
                            <div class="align-items-center d-flex justify-content-center flex-column">
                                <img class="logo" :src="'images/' + match.away_team_image_url"
                                     alt=" {{match.away_team_name}}">
                                <p>{{ match.away_team_name }}</p>
                            </div>
                        </td>
                    </div>
                    <div class="col-md-12 text-center" v-if="match.seen">
                        <button type="button" :id=index class="btn btn-info" @click="updateMatch">Update Match</button>
                    </div>

                    <hr>
                </div>
            </div>
            <div class="col-md-12 text-center" v-else>
                <h6>Fixture Not set Yet</h6>
            </div>
        </div>
    </div>


</template>
<script setup>
import swal from "sweetalert2";

</script>

<script>
export default {
    methods: {
        clicked(e) {
            e.preventDefault();
            e.target.readOnly = false
        },
        activateButton(e) {
            let id = e.target.id;
            let value = e.target.value;
            let name = e.target.name;
            this.$props.matches[e.target.id].seen = true
            this.$props.matches[e.target.id][name] = parseInt(value)
        },
        async updateMatch(e) {
            const value = this.$props.matches[e.target.id]
            let data =
                {
                    match_id: value.match_id,
                    home_score: value.home_team_score,
                    away_score: value.away_team_score
                }
            let response = await axios.post('/api/edit_match', data)
            swal.fire({
                title: response.data.flag.toUpperCase(),
                text: response.data.message,
                icon: response.data.flag,
            })
            if (response.data.flag === 'success') {
                this.$props.matches[e.target.id].seen = false
            }
        }

    },
    name: "fixture_page_component",
    props: {
        matches: Array,
        week: Number
    }
}
</script>

<style scoped>
.no-border {
    border: 0;
    box-shadow: none;
}

.input {
    text-align: center;
}

p {
    white-space: nowrap;
}
</style>
