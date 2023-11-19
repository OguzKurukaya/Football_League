<template>

    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #516BEB;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Premier League</a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link to="/">
                            <a class="nav-link active" aria-current="page">Teams</a></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/league">
                            <a class="nav-link active" aria-current="page" >League</a></router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/fixtures">
                            <a class="nav-link active" aria-current="page" >Fixtures</a></router-link>
                    </li>
                    <li class="nav-item bg-alert">
                            <a class="nav-link active" aria-current="page" @click="reset()" >Reset</a>
                    </li>


                </ul>
            </div>
        </div>
    </nav>

</template>

<script setup>
import swal from "sweetalert2";
const reset = async  () => {
    const response = await axios.get('/api/reset')
     let result =  response.data
    swal.fire({
        title: response.data.flag.toUpperCase(),
        text: response.data.message,
        icon: response.data.flag,
    })
    window.location.href = '/'
}
</script>

<script>
export default {
    name: "Navbar"
}
</script>

<style scoped>
.navbar-dark .navbar-nav a.nav-link {
    color: #ffffff;
    font-size: 1.1em;
}
.bg-alert{
    background-color: #ff0000;
}
li { cursor: pointer; }

</style>
