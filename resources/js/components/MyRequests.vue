<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Requests</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Schedule Requests:</h5>
                    <div class="accordion" id="accordionExample">
                        <div class="card" v-for="(student, index) in students" v-if="student.schedule_request !== null">
                            <div class="card-header" :id="'heading' + index" data-toggle="collapse"
                                 :data-target="'#collapse' + index" aria-expanded="true" aria-controls="collapseOne">
                                {{ student.name }} - Schedule Request
                            </div>
                            <div :id="'collapse' + index" class="collapse" :aria-labelledby="'heading' + index"
                                 data-parent="#accordionExample">
                                <div class="card-body">
                                    <li v-if="student.schedule_request !== null"
                                        v-for="course in student.schedule_request.courses">
                                        {{ course.title }} - {{ course.abbreviation }}
                                    </li>
                                    <button class="btn btn-primary mt-3"
                                            @click="approveSchedule(student.schedule_request.id, student.id, student.schedule_request.courses)">
                                        Approve
                                    </button>
                                    <button class="btn btn-danger mt-3" @click="rejectSchedule(student.schedule_request.id, student.id)">Reject</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            students: null,
            request: {
                id: '',
                student_id: '',
                courses: []
            }
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            axios.get('/api/adviser-students/' + this.user.id)
                .then(response => {
                    this.students = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
        },
        approveSchedule(request_id, student_id, courses) {
            this.request.id = request_id;
            this.request.student_id = student_id;
            this.request.courses = courses;
            for (let i = 0; i < this.request.courses.length; i++) {
                this.request.courses[i] = this.request.courses[i].id;
            }
            axios.post('/api/approve-courses', {
                request_id: this.request.id,
                student_id: this.request.student_id,
                courses: this.request.courses
            })
                .then(response => {
                    this.renderPage();
                    this.$toast.open({
                        message: 'You successfully approved request!',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not approve this request',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        rejectSchedule(request_id, student_id) {
            this.request.id = request_id;
            this.request.student_id = student_id;
            axios.post('/api/reject-courses', {
                request_id: this.request.id,
                student_id: this.request.student_id,
            })
                .then(response => {
                    this.renderPage();
                    this.$toast.open({
                        message: 'You successfully rejected request!',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not reject this request',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        }

    }
}
</script>
