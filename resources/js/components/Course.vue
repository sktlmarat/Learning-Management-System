<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" v-if="course">{{ this.course.title }}</li>
                </ol>
            </nav>
        </div>
        <div class="modal fade" id="UpdateThumbnailModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Thumbnail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label>Select Image</label>
                                <input ref="thumbnail" type="file" class="form-control" @change="handleImage">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="thumbnail_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="updateThumbnail()" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="DropModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Drop Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You want to drop this course?</p>
                    </div>
                    <div class="modal-footer">
                        <button id="drop_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="dropCourse()" class="btn btn-danger">Drop</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <CourseBlock :user="user" :course="course.id" v-if="course"></CourseBlock>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <img class="card-img mb-4" :src="'/storage/' + course.thumbnail" alt="" v-if="course">
                        <ul class="list-unstyled profile-about-list" v-if="course">
                            <li><i class="material-icons">school</i><span>{{ course.title }}</span></li>
                            <li><i class="material-icons">work</i><span>{{ course.abbreviation }}</span></li>
                            <li>
                                <button data-toggle="modal"
                                        data-target="#UpdateThumbnailModal" class="btn btn-block btn-primary m-t-lg"
                                        v-if="this.user.role == 'instructor'">
                                    Update course thumbnail
                                </button>
                                <button data-toggle="modal"
                                        data-target="#DropModal" v-if="this.user.role == 'student'" class="btn btn-block btn-danger">Drop the course</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CourseBlock from './Blocks';

export default {
    props: ['user'],
    data() {
        return {
            course: null,
            errors: [],
            thumbnail: {
                file: '',
                course_id: ''
            },
            drop: {
                uid: this.user.id,
                cid: ''
            }
        }
    },
    components: {
        CourseBlock
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            axios.get('/api/course/' + this.$route.params.id)
                .then(response => {
                    this.course = response.data;
                    this.drop.cid = response.data.id;
                }).catch(e => {
                this.errors.push(e)
            });
        },
        handleImage() {
            this.thumbnail.file = this.$refs.thumbnail.files[0];
        },
        updateThumbnail() {
            let formData = new FormData();
            this.thumbnail.course_id = this.course.id;
            formData.append('file', this.thumbnail.file);
            for (const [key, value] of Object.entries(this.thumbnail)) {
                formData.append(key, value);
            }
            axios.post('/api/update-course-thumbnail/', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    $("#thumbnail_close").click();
                    this.$toast.open({
                        message: 'You successfully updated thumbnail!',
                        type: 'success',
                        position: 'top-right'
                    });
                    this.renderPage();
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
        dropCourse() {
            axios.post('/api/drop-course', {uid: this.drop.uid, cid: this.drop.cid})
                .then(response => {
                    this.renderPage();
                    $("#drop_close").click();
                    this.$toast.open({
                        message: 'Course was successfully dropped',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not drop this course',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        }
    }
}
</script>

