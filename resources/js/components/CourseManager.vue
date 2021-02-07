<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Course Manager</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">All Courses:</h5>
                    <button class="btn btn-primary" data-toggle="modal"
                            data-target="#AddCourseModal">Add New Course
                    </button>
                    <div class="modal fade" id="AddCourseModal" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Course</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i class="material-icons">close</i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Course Title</label>
                                            <input v-model="new_course.title" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label>Department</label>
                                            <select v-model="new_course.department" class="form-control custom-select">
                                                <option :value="department.id + '.' + department.abbreviation"
                                                        v-for="department in departments">
                                                    {{ department.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Abbreviation</label>
                                            <input v-model="newAbbreviation" type="email" class="form-control" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Course Number</label>
                                            <input v-model="new_course.number" type="number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label>Capacity</label>
                                            <input v-model="new_course.capacity" type="number" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="mod_close" type="button" class="btn btn-secondary"
                                            data-dismiss="modal">
                                        Close
                                    </button>
                                    <button @click="add_course" type="button" class="btn btn-primary">Save changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-0 my-4">
                        <input v-model="search" class="form-control" type="text" placeholder="Search course">
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
                        </tr>
                        </tbody>
                    </table>
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
            errors: [],
            search: '',
            departments: '',
            new_course: {
                title: '',
                department: '',
                capacity: '',
                number: ''
            }
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            axios.get('/api/all-courses')
                .then(response => {
                    this.courses = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
            axios.get('/api/all-departments/')
                .then(response => {
                    this.departments = response.data;
                }).catch(e => {
                this.errors.push(e)
            });
        },
        add_course() {
            axios.post('/api/add-course/', {
                title: this.new_course.title,
                abbreviation: this.newAbbreviation + this.new_course.number,
                department: this.new_course.department.split(".")[0],
                capacity: this.new_course.capacity
            })
                .then(response => {
                    console.log(response.data);
                    this.courses.push(response.data);
                    $("#mod_close").click();
                    this.$toast.open({
                        message: 'New course was added',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.errors.push(e);
                    this.$toast.open({
                        message: 'Can not add this course',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        }
    },
    computed: {
        filteredCourses() {
            if (this.courses) {
                return this.courses.filter(course => {
                    const res = course.title + course.abbreviation;
                    return res.toLowerCase().includes(this.search.toLowerCase());
                });
            } else {
                return this.courses;
            }
        },
        newAbbreviation() {
                return this.new_course.department.split(".")[1];
        }
    }
}
</script>
