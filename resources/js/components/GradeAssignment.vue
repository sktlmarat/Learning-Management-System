<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link
                            :to="'/course/' + $route.params.course_id + '/assignment/' + $route.params.assignment_id">
                            Assignment
                        </router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Grading
                    </li>
                </ol>
            </nav>
        </div>
        <div class="modal fade" id="GradeAssignmentModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Grade Assignment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label>Max</label>
                                <input v-model="assignment.max_grade" type="text" class="form-control" placeholder="100"
                                       readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Grade</label>
                                <input v-model="grade.grade" type="number" class="form-control"
                                       :placeholder="'0 - ' +  this.assignment.max_grade">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Feedback</label>
                            <textarea v-model="grade.feedback" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="grade_close" type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="grade_assignment()" class="btn btn-primary">Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="EditGradeAssignmentModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Grade</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <label>Max</label>
                                <input v-model="assignment.max_grade" type="text" class="form-control" placeholder="100"
                                       readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Grade</label>
                                <input v-model="edit_grade.grade" type="number" class="form-control"
                                       :placeholder="'0 - ' +  this.assignment.max_grade">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Feedback</label>
                            <textarea v-model="edit_grade.feedback" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_grade_close" type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="editGrade" class="btn btn-primary">Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="user.role == 'instructor'" class="modal fade" id="SetMaxModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Set Maximum Grade</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <input type="number" v-model="assignment.max_grade" class="form-control" placeholder="100">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="set_max_close" type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="setMaxGrade()" class="btn btn-primary">Set Max</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="assignment">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Grade Assignment</h3>
                        <button class="btn btn-warning mt-1" v-if="user.role == 'instructor'" data-toggle="modal"
                                data-target="#SetMaxModal">Set Max Grade
                        </button>
                        <div class="col-md-6 p-0 my-4">
                            <input v-model="this.search" class="form-control" type="text" placeholder="Search student">
                        </div>
                        <table class="table" v-if="this.all_students">
                            <thead>
                            <tr>
                                <th scope="col">Student ID</th>
                                <th scope="col">Student Name</th>
                                <th scope="col">Grade</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(student, i) in filteredStudents" :key="i">
                                <th scope="row">{{ student.id }}</th>
                                <td>{{ student.name }}</td>
                                <td>{{ parseGrades(student) ? parseGrades(student).grade : 'Not Graded' }}</td>
                                <td>
                                    <button v-if="user.role == 'instructor' && !parseGrades(student)" class="btn btn-primary"
                                            data-toggle="modal"
                                            @click="gradeHelper(student.id)"
                                            data-target="#GradeAssignmentModal">Grade
                                    </button>
                                    <button v-else-if="user.role == 'instructor' && parseGrades(student)" class="btn btn-warning"
                                            data-toggle="modal"
                                            @click="editGradeHelper(parseGrades(student).id, parseGrades(student).grade, parseGrades(student).feedback)"
                                            data-target="#EditGradeAssignmentModal">Edit Grade
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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
            assignment: [],
            assignment_id:  this.$route.params.assignment_id,
            all_students: null,
            grade: {
                grade: '',
                student_id: '',
                feedback: '',
                search: ''
            },
            edit_grade: {
                id: '',
                grade: '',
                feedback: ''
            }
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            axios.get('/api/assignment/' + this.$route.params.assignment_id)
                .then(response => {
                    this.assignment = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
            axios.get('/api/course-student/' + this.$route.params.course_id)
                .then(response => {
                    this.all_students = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
        },
        submitAssignment() {
            let formData = new FormData();
            formData.append('file', this.submission.file);
            for (const [key, value] of Object.entries(this.submission)) {
                formData.append(key, value);
            }
            axios.post('/api/assignment-submit/', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    this.renderPage();
                    this.$toast.open({
                        message: 'Your file submitted successfully!',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.errors.push(e);
                    this.$toast.open({
                        message: 'Error occurred during submission',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        setMaxGrade() {
            axios.post('/api/set-max-grade', {
                max_grade: this.assignment.max_grade,
                id: this.assignment.id
            })
                .then(response => {
                    this.renderPage();
                    $("#set_max_close").click();
                    this.$toast.open({
                        message: 'Maximum grade is successfully set',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not set max grade',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        gradeHelper(id) {
            this.grade.student_id = id;
        },
        editGradeHelper(id, grade, feedback){
            this.edit_grade.id = id;
            this.edit_grade.feedback = feedback;
            this.edit_grade.grade = grade;
        },
        grade_assignment() {
            axios.post('/api/grade-assignment', {
                student_id: this.grade.student_id,
                assignment_id: this.assignment.id,
                grade: this.grade.grade,
                feedback: this.grade.feedback
            })
                .then(response => {
                    this.renderPage();
                    $("#grade_close").click();
                    this.$toast.open({
                        message: 'Assignment graded successfully',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not grade assignment',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        parseGrades(student) {
            let grade = student.grades.find(item => item.assignment_id == this.assignment_id);
            if(grade){
                return grade;
            }
            else{
                return false;
            }
        },
        editGrade() {
            axios.post('/api/edit-grade', {
                id: this.edit_grade.id,
                grade: this.edit_grade.grade,
                feedback: this.edit_grade.feedback,
            })
                .then(response => {
                    this.renderPage();
                    $("#edit_grade_close").click();
                    this.$toast.open({
                        message: 'Grade was edited',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not edit the grade',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        }
    },
    computed: {
        filteredStudents() {
            if (this.search) {
                return this.all_students.filter(student => {
                    const res = student.title + student.id;
                    return res.toLowerCase().includes(this.search.toLowerCase());
                });
            } else {
                return this.all_students;
            }
        }
    }
}
</script>

