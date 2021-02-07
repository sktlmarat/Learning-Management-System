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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="course in filteredCourses">
                                        <th scope="row">{{ course.title }}</th>
                                        <td>{{ course.abbreviation }}</td>
                                        <td>{{ course.department.name }}</td>
                                        <td>{{ course.capacity }}</td>
                                        <td>
                                            <p v-if="user.courses.some(c => c.id === course.id)" class="text-success">
                                                You
                                                already enrolled</p>
                                            <button v-else @click="register(user.id, course.id, course.title)" class="btn btn-primary">Enroll</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
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
            search: ''
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
                    this.user = response.data;
                    this.$emit("updateStudent", this.user);
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
