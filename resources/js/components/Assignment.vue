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
        <div class="row" v-if="assignment">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ assignment.title }}</h5>
                        <p v-if="assignment.description">{{ assignment.description }}</p>
                        <div class="file-wrapper" v-if="assignment.file">
                        <p>Attached file:</p>
                        <a :href="'/storage/' + assignment.file">{{ assignment.title }}</a>
                        </div>
                        <div v-if="assignment.deadline">Assignment is due: <span :class="parseDate(assignment.deadline) ? 'text-success' : 'text-danger'">{{ assignment.deadline | formatDate}}</span></div>
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
                                <button id="delete_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                </button>
                                <button type="button" @click="deleteSubmission()" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" v-if="all_submissions && user.role == 'student' && all_submissions.some(c => c.user_id === user.id)">
                    <div class="card-body w-50">
                        <h5 class="card-title">Your Submission</h5>
                        <div class="col-md-12 p-0">
                            <div v-for="submission in all_submissions" v-if="submission.user_id == user.id">
                                <h5 class="card-title">{{ submission.title }}</h5>
                                <p v-if="submission.text">{{ submission.text }}</p>
                                <p>Submitted file:  <a :href="'/storage/' + submission.file_submission">{{ submission.title }}</a></p>
                                <button data-toggle="modal" data-target="#DeleteModal" @click="deleteHelper(submission.id)" class="btn btn-danger">Delete</button>
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
                <div class="card" v-if="user.role == 'instructor'">
                    <div class="card-body">
                        <h5 class="card-title">Student Submissions</h5>
                        <div class="accordion" id="accordionExample">
                            <div class="card" v-for="(submission, index) in all_submissions">
                                <div class="card-header" :id="'heading' + index" data-toggle="collapse"
                                     :data-target="'#collapse' + index" aria-expanded="true"
                                     aria-controls="collapseOne">
                                    {{ submission.user.name }} - {{ submission.title }}
                                </div>
                                <div :id="'collapse' + index" class="collapse" :aria-labelledby="'heading' + index"
                                     data-parent="#accordionExample">
                                    <div class="card-body">
                                        <p>{{ submission.text }}</p>
                                        <p>Submitted file:</p>
                                        <a :href="'/storage/' + submission.file_submission">{{ submission.title }}</a>
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
            all_submissions: null
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
        deleteHelper(id){
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
            return moment().format('MMMM Do, h:mm a') < moment(date).format('MMMM Do, h:mm a');
        }
    }
}
</script>
