<template>
    <div class="col-12">
        <p class="pt">Championship Prediction</p>
        <table class="table">
            <thead>
            <td>Team</td>
            <td class="name">%</td>
            </thead>
            <tbody>
            <tr class="top" v-for="(team, index) in league" :key="team.id">
                <td class="name"><img class="logo" :src="'images/' + team.image_url" alt=" {{team.name}}">
                    {{ team.name }}
                </td>
                <td>
                    {{ team.prediction ?? 0}}
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
export default {
    name: "prediction_component",
    props: {
        league: Array
    }
}
</script>
<style scoped>
</style>
