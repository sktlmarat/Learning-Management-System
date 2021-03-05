<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" aria-current="page">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" v-if="assignment">{{ this.assignment.title }}
                    </li>
                </ol>
            </nav>
        </div>
        <div v-if="user.role == 'instructor'" class="modal fade" id="EditAssignmentModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-md-12">
                            <label>Title</label>
                            <input v-model="editAssignment.title" type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Description (optional)</label>
                            <input v-model="editAssignment.description" type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Deadline (optional)</label>
                            <input v-model="editAssignment.deadline" type="datetime-local" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_close" type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="edit_assignment()" class="btn btn-primary">Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="user.role == 'instructor'" class="modal fade" id="DeleteAssignmentModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You want to delete this assignment?</p>
                    </div>
                    <div class="modal-footer">
                        <button id="delete_assignment_close" type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="delete_assignment()" class="btn btn-danger">Delete</button>
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
                        <h5 class="card-title">{{ assignment.title }}</h5>
                        <p v-if="assignment.description">{{ assignment.description }}</p>
                        <div class="file-wrapper" v-if="assignment.file">
                            <p><strong>Attached file:</strong> <a
                                :href="'/storage/' + assignment.file">{{ assignment.title }}</a></p>
                        </div>
                        <div v-if="assignment.deadline"><strong>Assignment is due:</strong> <span
                            :class="parseDate(assignment.deadline) ? 'text-success' : 'text-danger'">{{
                                assignment.deadline | formatDate
                            }}</span>
                        </div>
                        <button data-toggle="modal"
                                v-if="user.role == 'instructor'"
                                @click="editAssignmentHelper(assignment.id, assignment.title, assignment.description, assignment.deadline)"
                                data-target="#EditAssignmentModal" class="btn btn-warning btn-md mt-3">Edit
                        </button>
                        <button
                            v-if="user.role == 'instructor'"
                            class="btn btn-danger btn-md mt-3" data-toggle="modal"
                            data-target="#DeleteAssignmentModal" @click="deleteAssignmentHelper(assignment.id)">Delete
                        </button>
                        <button class="btn btn-warning mt-3" data-toggle="modal"
                                data-target="#SetMaxModal">Set Maximum Grade
                        </button>
                    </div>
                </div>
                <div v-if="user.role == 'student'" class="modal fade" id="DeleteModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Confirmation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i class="material-icons">close</i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>You want to delete this submission?</p>
                            </div>
                            <div class="modal-footer">
                                <button id="delete_close" type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="button" @click="deleteSubmission()" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card"
                     v-if="all_submissions && user.role == 'student' && all_submissions.some(c => c.user_id === user.id)">
                    <div class="card-body w-50">
                        <h5 class="card-title">Your Submission</h5>
                        <div class="col-md-12 p-0">
                            <div v-for="submission in all_submissions" v-if="submission.user_id == user.id">
                                <h5 class="card-title">{{ submission.title }}</h5>
                                <p v-if="submission.text">{{ submission.text }}</p>
                                <p>Submitted file: <a
                                    :href="'/storage/' + submission.file_submission">{{ submission.title }}</a></p>
                                <button data-toggle="modal" data-target="#DeleteModal"
                                        @click="deleteHelper(submission.id)"
                                        class="btn btn-danger">Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" v-else-if="user.role == 'student'">
                    <div class="card-body w-50">
                        <h5 class="card-title">Submit assignment</h5>
                        <div class="form-group">
                            <label>Submission title</label>
                            <input type="text" v-model="submission.title" class="form-control"
                                   placeholder="Submission title">
                        </div>
                        <div class="form-group">
                            <label>Text (optional)</label>
                            <textarea v-model="submission.text" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>File</label>
                            <input ref="file" type="file" class="form-control" @change="handleFile">
                        </div>
                        <div class="col-md-12 p-0">
                            <button @click="submitAssignment()" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </div>
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
                                        <input v-model="assignment.max_grade" type="text" class="form-control" placeholder="100" readonly>
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
                <div class="card" v-if="user.role == 'instructor'">
                    <div class="card-body">
                        <h5 class="card-title">Student Submissions</h5>
                        <div class="accordion" id="accordionExample">
                            <div class="card" v-for="(submission, index) in all_submissions">
                                <div class="card-header" :id="'heading' + index" data-toggle="collapse"
                                     :data-target="'#collapse' + index" aria-expanded="true"
                                     aria-controls="collapseOne">
                                    {{ submission.user.name }} - {{ submission.title }} <span
                                    :class="parseSubmission(submission.created_at) ? 'text-success' : 'text-danger'">(Submitted at {{
                                        submission.created_at | formatDate
                                    }})</span>
                                    <i class="material-icons" id="down-i">keyboard_arrow_down</i>
                                </div>
                                <div :id="'collapse' + index" class="collapse" :aria-labelledby="'heading' + index"
                                     data-parent="#accordionExample">
                                    <div class="card-body">
                                        <p><strong>Submission title:</strong> {{ submission.text }}</p>
                                        <p><strong>Submitted file:</strong> <a
                                            :href="'/storage/' + submission.file_submission">{{ submission.title }}</a>
                                        </p>
                                        <button v-if="user.role == 'instructor'" class="btn btn-primary"
                                                data-toggle="modal"
                                                @click="gradeHelper(submission.user.id)"
                                                data-target="#GradeAssignmentModal">Grade
                                        </button>
                                    </div>
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
import moment from 'moment';

export default {
    props: ['user'],
    data() {
        return {
            assignment: null,
            errors: [],
            delete_id: '',
            submission: {
                title: '',
                text: '',
                file: '',
                assignment_id: this.$route.params.assignment_id,
                user_id: this.user.id
            },
            all_submissions: null,
            editAssignment: {
                id: '',
                title: '',
                description: '',
                deadline: ''
            },
            deleteAssignment: {
                id: ''
            },
            grade: {
                grade: '',
                student_id: '',
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
            axios.get('/api/assignment-submissions/' + this.$route.params.assignment_id)
                .then(response => {
                    this.all_submissions = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
        },
        handleFile(event) {
            this.submission.file = this.$refs.file.files[0];
        },
        deleteHelper(id) {
            this.delete_id = id;
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
        deleteSubmission() {
            axios.post('/api/delete-student-submission', {id: this.delete_id})
                .then(response => {
                    this.renderPage();
                    $("#delete_close").click();
                    this.$toast.open({
                        message: 'Submission deleted successfully',
                        type: 'success',
                        position: 'top-right'
                    });
                }).catch(e => {
                this.$toast.open({
                    message: 'Can not delete this submission',
                    type: 'error',
                    position: 'top-right'
                });
            });
        },
        parseDate(date) {
            return moment() < moment(date);
        },
        parseSubmission(date) {
            return moment(this.assignment.deadline) > moment(date);
        },
        deleteAssignmentHelper(id) {
            this.deleteAssignment.id = id;
        },
        editAssignmentHelper(id, title, description, deadline) {
            this.editAssignment.id = id;
            this.editAssignment.title = title;
            this.editAssignment.description = description;
            this.editAssignment.deadline = moment(deadline).format('YYYY-MM-DDThh:mm');
        },
        edit_assignment() {
            axios.post('/api/edit-assignment', {
                id: this.editAssignment.id,
                title: this.editAssignment.title,
                description: this.editAssignment.description,
                deadline: this.editAssignment.deadline
            })
                .then(response => {
                    this.renderPage();
                    $("#edit_close").click();
                    this.$toast.open({
                        message: 'Assignment was edited',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not edit this assignment',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        delete_assignment() {
            axios.post('/api/delete-assignment', {id: this.deleteAssignment.id})
                .then(response => {
                    this.renderPage();
                    $("#delete_assignment_close").click();
                    this.$toast.open({
                        message: 'Assignment was successfully deleted',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not delete this assignment',
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
        }
    }
}
</script>

<style scoped>
.accordion .card .card-header {
    background: white;
    text-transform: none;
    font-size: 14px;
}

.dark-theme .accordion .card .card-header {
    background: #323742;
}

#down-i {
    float: right;
}
</style>
