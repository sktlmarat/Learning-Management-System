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
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-xl">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Registration for Spring 2021</h5>
                                <p>Registration is open for 2nd year students</p>
                                <p>{{ user.name }} - {{ user.department.name}}</p>
                                <p v-if="user.registration_status === 'pending'">Registration status: <span class="text-warning">Pending</span></p>
                                <p v-if="user.registration_status === 'approved'">Registration status: <span class="text-success">Approved</span></p>
                                <p v-if="user.registration_status === 'rejected'">Registration status: <span class="text-danger">Rejected</span></p>
                                <div class="col-md-6 p-0 my-4">
                                    <input v-model="search" class="form-control" id="exampleInputEmail1" placeholder="Search title">
                                </div>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Course Title</th>
                                        <th scope="col">Abbreviation</th>
                                        <th scope="col">Department</th>
                                        <th scope="col">Capacity</th>
                                        <th scope="col">Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="course in filteredCourses">
                                        <th scope="row">{{ course.title }}</th>
                                        <td>{{ course.abbreviation }}</td>
                                        <td>{{ course.department.name }}</td>
                                        <td>{{ course.capacity }}</td>
                                        <td>{{ course.time }}</td>
                                        <td>
                                            <p v-if="user.courses.some(c => c.id === course.id)" class="text-success">
                                                You already enrolled</p>
                                            <p v-else-if="user.schedule_request !== null && user.schedule_request.courses.some(c => c.id === course.id)" class="text-success">
                                                You already enrolled</p>
                                            <p v-else-if="checkOverlap(course)" class="text-success">
                                                Time Overlap</p>
                                            <button v-else @click="register(user.id, course.id, course.title)" class="btn btn-primary">Enroll</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <button v-if="user.schedule_request !== null && (user.registration_status === null || user.registration_status === 'rejected')" class="btn btn-primary btn-lg" @click="sendAdviser()">Send to Adviser</button>
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
            courses: null,
            errors: null,
            search: '',
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
            axios.post('/api/send-request-adviser', {request_id: this.user.schedule_request.id, student_id: this.user.id})
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
            let lineA = {
                start:time[0],
                end:time[1]
            }
            let overlap = false;
            this.courses.forEach(function(item){
                if(item.id === course.id || item.time == null) return;
                let time = item.time.toString().split('-');
                let lineB = {
                    start:time[0],
                    end:time[1]
                }
                if(lineA.start >= lineB.start && lineA.start <= lineB.end ||
                    lineA.end >= lineB.start && lineA.end <= lineB.end ||
                    lineB.start >= lineA.start && lineB.start <= lineA.end ||
                    lineB.end >= lineA.start && lineB.end <= lineA.end)
                    overlap = true;
            });
            return overlap;
        }
    },
    computed: {
        filteredCourses() {
            if(this.courses){
                return this.courses.filter(course => {
                    return course.title.toLowerCase().includes(this.search.toLowerCase());
                });
            }else{
                return this.courses;
            }
        }
    }
}
</script>
