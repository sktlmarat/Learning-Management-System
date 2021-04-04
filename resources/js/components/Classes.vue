<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link :to="'/course/'+this.session.course_id">{{ session.course.title }}</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link :to="'/sessions/'+this.session.course_id">Session</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" v-if="session">{{ this.session.description }}</li>
                </ol>
            </nav>
        </div>
        <div class="row container">
            <div class="col-12">
                <table id="table_id" class="display table">
                    <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in classes">
                        <th scope="row">{{ item.date }}</th>
                        <td v-if="time">{{ time }}</td>
                        <td v-if="session.description">{{ session.description }}</td>
                        <td>
                            <router-link :to="{ name:'attendance', params:{class:item,session:session} }"
                            v-if="!item.taken">Take Attendance</router-link>
                            <a href="#" class="taken" v-else>Already Taken</a>
<!--                            <a href="#" data-toggle="modal" data-target="#AddModal">Take Attendance Now</a>-->
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Classes",
    props: ['user'],
    data() {
        return {
            session: {},
            errors: [],
            classes: [],
            time: null,
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            //get session data
            axios.get('/api/session/' + this.$route.params.sessionId)
                .then(response => {
                    this.session = response.data;
                }).catch(e => {
                this.errors.push(e)
            });
            //get classes for sessions
            axios.get('/api/session/' +this.$route.params.sessionId+'/classes' )
                .then(response => {
                    this.classes = response.data.classes;
                    this.time = response.data.time;
                }).catch(e => {
                this.errors.push(e)
            });
        }
    }
}
</script>

<style scoped>
input[type="checkbox"]{
    margin-right: 5px;
}
.taken{
    color: red;
}
</style>
