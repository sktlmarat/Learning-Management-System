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
                        <p>{{ assignment.description }}</p>
                        <p>Attached file:</p>
                        <a :href="'/storage/' + assignment.file">{{ assignment.title }}</a>
                    </div>
                </div>
                <div class="card" v-if="user.role == 'student'">
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
                                <div class="card-header" :id="'heading' + index" data-toggle="collapse" :data-target="'#collapse' + index" aria-expanded="true" aria-controls="collapseOne">
                                    {{ submission.user.name }} - {{ submission.title }}
                                </div>
                                <div :id="'collapse' + index" class="collapse" :aria-labelledby="'heading' + index" data-parent="#accordionExample">
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
export default {
    props: ['user'],
    data() {
        return {
            assignment: null,
            errors: [],
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
        axios.get('/api/assignment/' + this.$route.params.assignment_id)
            .then(response => {
                this.assignment = response.data;
            }).catch(e => {
            this.errors.push(e);
        });
        if(this.user.role == 'instructor'){
            axios.get('/api/assignment-submissions/' + this.$route.params.assignment_id)
                .then(response => {
                    this.all_submissions = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
        }
    },
    methods: {
        handleFile(event) {
            this.submission.file = this.$refs.file.files[0];
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
                    this.$toast.open({
                        message: 'Your file submitted successfully!',
                        type: 'success',
                        position: 'top-right'
                    });
                    this.initialSubmission();
                })
                .catch(e => {
                    this.errors.push(e);
                    this.$toast.open({
                        message: 'Error occured during submission',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        initialSubmission() {
            this.submission.title = '';
            this.submission.text = '';
            this.submission.file = '';
        }
    }
}
</script>
