<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link :to="'/course/'+this.course.id">{{ this.course.title }}</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Sessions</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="blocks">
                    <button data-toggle="modal" data-target="#AddModal"
                            class="btn btn-primary my-4" v-if="course">Add new Session for {{ this.course.title }}
                    </button>
                </div>
                <div class="modal fade" id="AddModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle" v-if="course">Add New Session for {{ this.course.title }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="material-icons" id="thumbnail_close_add">close</i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <label>Session Date</label>
                                <input type="datetime-local" v-model="add_session.date" class="form-control" required>
                                <label>Duration</label>
                                <input type="number" v-model="add_session.duration" class="form-control"
                                       placeholder="Duration in minutes" required>
                                <label>Session End Date</label>
                                <input type="datetime-local" v-model="add_session.end_date" class="form-control" required>
                                <label>Session Days</label>
                                <div>
                                    <input type="checkbox" v-model="add_session.session_days" value="sunday">Sun
                                    <input type="checkbox" v-model="add_session.session_days" value="monday">Mon
                                    <input type="checkbox" v-model="add_session.session_days" value="tuesday">Tue
                                    <input type="checkbox" v-model="add_session.session_days" value="wednesday">Wed
                                    <input type="checkbox" v-model="add_session.session_days" value="thursday">Thu
                                    <input type="checkbox" v-model="add_session.session_days" value="friday">Fri
                                    <input type="checkbox" v-model="add_session.session_days" value="saturday">Sat
                                </div>
                                <label>Frequency</label>
                                <input type="number" v-model="add_session.frequency" class="form-control" required>
                                <label>Description</label>
                                <textarea v-model="add_session.description" class="form-control" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button id="mod_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                </button>
                                <button type="button" @click="addSession()" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row container">
            <div class="col-12">
                <table id="table_id" class="table">
                    <thead>
                    <tr>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Description</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="session in sessions">
                        <th scope="row">{{ session.date }}</th>
                        <td>{{ session.end_date }}</td>
                        <td>{{ session.duration }}</td>
                        <td>{{ session.description }}</td>
                        <td>
                            <a href="#" data-toggle="modal" data-target="#EditSession"
                               @click="editSession(session.id)">Edit</a> |
                            <a href="#" @click="deleteSession(session.id)">Delete</a> |
                            <router-link :to=" '/session/' + session.id ">Take Attendance</router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="EditSession" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle" v-if="edit_session.description">Edit {{ edit_session.description }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons" id="thumbnail_close_update">close</i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <label>Session Date</label>
                        <input type="datetime-local" v-model="edit_session.date" class="form-control" required>
                        <label>Duration</label>
                        <input type="number" v-model="edit_session.duration" class="form-control"
                               placeholder="Duration in minutes" required>
                        <label>Session End Date</label>
                        <input type="datetime-local" v-model="edit_session.end_date" class="form-control" required>
                        <label>Session Days</label>
                        <div>
                            <input type="checkbox" v-model="edit_session.session_days"
                                   value="sunday">Sun
                            <input type="checkbox" v-model="edit_session.session_days"
                                   value="monday">Mon
                            <input type="checkbox" v-model="edit_session.session_days"
                                   value="tuesday">Tue
                            <input type="checkbox" v-model="edit_session.session_days"
                                   value="wednesday">Wed
                            <input type="checkbox" v-model="edit_session.session_days"
                                   value="thursday">Thu
                            <input type="checkbox" v-model="edit_session.session_days"
                                   value="friday">Fri
                            <input type="checkbox" v-model="edit_session.session_days"
                                   value="saturday">Sat
                        </div>
                        <label>Frequency</label>
                        <input type="number" v-model="edit_session.frequency" class="form-control" required>
                        <label>Description</label>
                        <textarea v-model="edit_session.description" class="form-control" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button id="mod_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="updateSession(edit_session.id)" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    name: "Sessions",
    data() {
        return {
            course: null,
            sessions: [],
            errors: [],
            add_session:{
                date : null,
                duration : null,
                end_date : null,
                session_days : [],
                frequency : null,
                description : null,
                course_id : null,
            },
            edit_session:{
                id : null,
                date : null,
                duration : null,
                end_date : null,
                session_days : [],
                frequency : null,
                description : null,
                course_id : null,
            }
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            axios.get('/api/course/' + this.$route.params.course_id)
                .then(response => {
                    this.course = response.data;
                }).catch(e => {
                this.errors.push(e)
            });
            axios.get('/api/course/' + this.$route.params.course_id + '/session')
                .then(response => {
                    this.sessions = response.data;
                }).catch(e => {
                this.errors.push(e)
            });
        },
        addSession(){
            let formData = new FormData();
            this.add_session.course_id = this.course.id;
            for (const [key, value] of Object.entries(this.add_session)) {
                formData.append(key, value);
            }
            axios.post('/api/session', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                $("#thumbnail_close_add").click();
                this.$toast.open({
                    message: 'You successfully created session for the course '+this.course.title+'!',
                    type: 'success',
                    position: 'top-right'
                });
                this.renderPage();
            }).catch(e => {
                this.errors.push(e);
                this.$toast.open({
                    message: 'Error occurred during submission',
                    type: 'error',
                    position: 'top-right'
                });
            });
        },
        editSession(sessionId){
            axios.get('/api/session/'+sessionId+'/edit')
                .then(response => {
                    $("#thumbnail_close").click();
                    this.edit_session = response.data;

            }).catch(e => {
                this.errors.push(e);
                this.$toast.open({
                    message: 'Error occurred during data fetch!',
                    type: 'error',
                    position: 'top-right'
                });
            });
        },
        updateSession(sessionId){
            let formData = new FormData();
            for (const [key, value] of Object.entries(this.edit_session)) {
                formData.append(key, value);
            }
            formData.append("_method", 'PATCH');
            axios({
                method : "POST",
                url    : '/api/session/'+sessionId,
                data   : formData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            }).then(response => {
                $("#thumbnail_close_update").click();
                this.$toast.open({
                    message: 'You successfully updated the session'+this.edit_session.description+' for the course '+this.course.title+'!',
                    type: 'success',
                    position: 'top-right'
                });
                this.renderPage();
            }).catch(e => {
                this.errors.push(e);
                this.$toast.open({
                    message: 'Error occurred during submission',
                    type: 'error',
                    position: 'top-right'
                });
            });
        },
        deleteSession(sessionId){
            let formData = new FormData();
            formData.append("_method", 'DELETE');
            axios({
                method : "POST",
                url    : '/api/session/'+sessionId,
                data   : formData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            }).then(response => {
                this.$toast.open({
                    message: 'You successfully deleted a session from the course '+this.course.title+'!',
                    type: 'success',
                    position: 'top-right'
                });
                this.renderPage();
            }).catch(e => {
                this.errors.push(e);
                this.$toast.open({
                    message: 'Error occurred during deletion',
                    type: 'error',
                    position: 'top-right'
                });
            });
        }
    }
}
</script>

<style scoped>

</style>
