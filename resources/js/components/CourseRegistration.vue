<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Course Registration</li>
                </ol>
            </nav>
        </div>
        <div v-if="registration[0].status === 'closed'" class="alert alert-danger">
            Registration is closed
        </div>
        <div v-else class="row">
            <div class="col-12">
                <div class="row">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Registration for Spring 2021</h5>
                                <p class="text-success">Registration is open</p>
                                <p>{{ user.name }} - {{ user.department.name }}</p>
                                <p v-if="user.registration_status === 'pending'">Registration status: <span
                                    class="text-warning">Pending</span></p>
                                <p v-if="user.registration_status === 'approved'">Registration status: <span
                                    class="text-success">Approved</span></p>
                                <p v-if="user.registration_status === 'rejected'">Registration status: <span
                                    class="text-danger">Rejected</span></p>
                                <div class="col-md-6 p-0 my-4">
                                    <input v-model="search" class="form-control" id="exampleInputEmail1"
                                           placeholder="Search title">
                                </div>
                                <table class="table table-responsive">
                                    <thead>
                                    <tr>
                                        <th scope="col">Course Title</th>
                                        <th scope="col">Abbreviation</th>
                                        <th scope="col">Department</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Days</th>
                                        <th scope="col">Register</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="course in filteredCourses">
                                        <th scope="row">{{ course.title }}</th>
                                        <td>{{ course.abbreviation }}</td>
                                        <td>{{ course.department.name }}</td>
                                        <td>{{ course.date }}</td>
                                        <td>{{ course.time }}</td>
                                        <td>{{ course.day }}</td>
                                        <td>
                                            <p v-if="user.courses.some(c => c.id === course.id)" class="text-success">
                                                You already enrolled</p>
                                            <p v-else-if="user.schedule_request !== null && user.schedule_request.courses.some(c => c.id === course.id)"
                                               class="text-success">
                                                You already enrolled</p>
                                            <p v-else-if="checkOverlap(course)" class="text-danger">
                                                Time Overlap</p>
                                            <button v-else @click="register(user.id, course.id, course.title)"
                                                    class="btn btn-primary">Enroll
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <button
                                    v-if="user.schedule_request !== null && (user.registration_status === null || user.registration_status === 'rejected')"
                                    class="btn btn-primary btn-lg" @click="sendAdviser()">Send to Adviser
                                </button>
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
            registration: '',
            courses: null,
            errors: null,
            search: '',
            user_courses: ''
        }
    },
    components: {},
    mounted() {
        axios.get('/api/all-courses')
            .then(response => {
                this.courses = response.data;
            }).catch(e => {
            this.errors.push(e);
        });
        axios.get('/api/user-course/' + this.user.id)
        .then(response => {
            this.user_courses = response.data;
        }).catch(e => {
            this.errors.push(e)
        });
        if (this.user.year_of_study) {
            axios.get('/api/registration/' + this.user.year_of_study)
                .then(response => {
                    this.registration = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
        }
    },
    methods: {
        register(user_id, course_id, course_title) {
            axios.post('/api/register-course', {course_id: course_id, user_id: user_id})
                .then(response => {
                    let newUser = response.data;
                    this.$emit("updateStudent", newUser);
                    this.$toast.open({
                        message: 'You successfully registered to ' + course_title,
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not register to this course',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        sendAdviser() {
            axios.post('/api/send-request-adviser', {
                request_id: this.user.schedule_request.id,
                student_id: this.user.id
            })
                .then(response => {
                    let User = response.data;
                    this.$emit("updateStudent", User);
                    this.$toast.open({
                        message: 'You successfully sent request!',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not send request',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        checkOverlap(course) {
            let time = course.time.toString().split('-');
            let dateB = course.date.toString().split(' to ');
            let daysA = course.day.toString().split(',');
            let lineA = {
                start: time[0],
                end: time[1]
            }
            let overlapTime = false;
            let overlapDate = false;
            let overlapDay = false;
            this.user_courses.forEach(function (item) {
                if (item.id === course.id || item.time == null) return;
                //check time overlap below
                let time = item.time.toString().split('-');
                let lineB = {
                    start: time[0],
                    end: time[1]
                }
                if (lineA.start > lineB.start && lineA.start < lineB.end ||
                    lineA.end > lineB.start && lineA.end < lineB.end ||
                    lineB.start > lineA.start && lineB.start < lineA.end ||
                    lineB.end > lineA.start && lineB.end < lineA.end)
                    overlapTime = true;
                //check date overlap below
                let dateA = item.date.toString().split(' to ');
                let a_start = dateA[0];
                let a_end = dateA[1];
                let b_start = dateB[0];
                let b_end = dateB[1];
                if (a_start <= b_start && b_start <= a_end) overlapDate = true; // b starts in a
                if (a_start <= b_end && b_end <= a_end) overlapDate = true; // b ends in a
                if (b_start < a_start && a_end < b_end) overlapDate = true; // a in b
                //check day overlap below
                let daysB = item.day.toString().split(',');
                overlapDay = daysA.some(r => daysB.indexOf(r) >= 0);
            });
            return (overlapTime && overlapDate && overlapDay);
        }
    },
    computed: {
        filteredCourses() {
            if (this.courses) {
                return this.courses.filter(course => {
                    return course.title.toLowerCase().includes(this.search.toLowerCase());
                });
            } else {
                return this.courses;
            }
        }
    }
}
</script>
