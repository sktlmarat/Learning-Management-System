<template>
    <div class="blocks">
        <button v-if="user.role == 'instructor'" data-toggle="modal" data-target="#AddModal"
                class="btn btn-primary my-4">Add new block
        </button>
        <router-link v-if="user.role == 'instructor'" :to="'/sessions/'+course"
                class="btn btn-primary my-4">View Sessions
        </router-link>
        <div v-if="user.role == 'instructor'" class="modal fade" id="AddModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Add New Block</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label>Title of the block</label>
                        <input type="text" v-model="add_title" class="form-control" id="exampleInputEmail1"
                               placeholder="Enter title">
                    </div>
                    <div class="modal-footer">
                        <button id="mod_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="addBlock()" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="user.role == 'instructor'" class="modal fade" id="EditModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Block</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label>Title of the block</label>
                        <input type="text" v-model="edit.title" class="form-control" placeholder="Enter title">
                    </div>
                    <div class="modal-footer">
                        <button id="edit_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="editBlock()" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="user.role == 'instructor'" class="modal fade" id="DeleteModal" tabindex="-1" role="dialog"
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
                        <p>You want to delete {{ this.delete.title }} block?</p>
                    </div>
                    <div class="modal-footer">
                        <button id="delete_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="deleteBlock()" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="user.role == 'instructor'" class="modal fade" id="AddAssignmentModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Assignment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-12">
                            <label>Title</label>
                            <input v-model="add_assignment.title" type="text" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label>Description (optional)</label>
                            <textarea v-model="add_assignment.description" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-12">
                            <label>Set Deadline (optional)</label>
                            <input type="datetime-local" v-model="add_assignment.deadline" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label>Attach a file (optional)</label>
                            <input ref="assignment" type="file" class="form-control" @change="handleFileAssignment">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="assignment_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="addAssignment" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="user.role == 'instructor'" class="modal fade" id="AddMaterialModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Material</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-12">
                            <label>Title</label>
                            <input v-model="add_material.title" type="text" class="form-control">
                        </div>
                        <div class="form-group col-12">
                            <label>Attach a file</label>
                            <input ref="material" type="file" class="form-control" @change="handleFileMaterial">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="material_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close
                        </button>
                        <button type="button" @click="addMaterial" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" v-for="(block, i) in blocks" :key="i">
            <div class="card-body">
                <h3>{{ block.title }}</h3>
                <ul>
                    <li v-for="assignment in block.assignments">
                        <i class="material-icons">download_done</i><router-link :to="'/course/' + course + '/assignment/' + assignment.id"> {{ assignment.title }}
                        </router-link>
                    </li>
                    <li v-for="material in block.materials">
                        <i class="material-icons">school</i> <a :href="'/storage/' + material.file" target="_blank"> {{ material.title }}</a>
                    </li>
                </ul>
                <button v-if="user.role == 'instructor'" @click="editHelper(block.id, block.title, i)"
                        data-toggle="modal"
                        data-target="#EditModal" class="btn btn-warning btn-sm mr-2">Edit block
                </button>
                <button v-if="user.role == 'instructor'" data-toggle="modal"
                        data-target="#DeleteModal" class="btn btn-danger btn-sm mr-2"
                        @click="deleteHelper(block.id, block.title, i)">Delete block
                </button>
                <button v-if="user.role == 'instructor'" data-toggle="modal"
                        data-target="#AddMaterialModal" class="btn btn-primary btn-sm mr-2"
                        @click="materialHelper(block.id)">Add material
                </button>
                <button v-if="user.role == 'instructor'" data-toggle="modal"
                        data-target="#AddAssignmentModal" class="btn btn-primary btn-sm"
                        @click="assignmentHelper(block.id)">Add assignment
                </button>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    props: ['user', 'course'],
    data() {
        return {
            blocks: null,
            errors: null,
            add_title: '',
            edit: {
                id: '',
                title: ''
            },
            block_index: -1,
            delete: {
                id: '',
                title: ''
            },
            add_assignment: {
                title: '',
                description: '',
                file: '',
                block_id: '',
                deadline: ''
            },
            add_material: {
                title: '',
                file: '',
                block_id: ''
            }
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage(){
            axios.get('/api/course/block/' + this.course)
                .then(response => {
                    this.blocks = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
        },
        addBlock() {
            axios.post('/api/course-block', {title: this.add_title, course_id: this.course})
                .then(response => {
                    this.renderPage();
                    $("#mod_close").click();
                    this.$toast.open({
                        message: response.data.title + ' block was added',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not add this block',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        editHelper(id, title, index) {
            this.edit.title = title;
            this.edit.id = id;
            this.block_index = index;
        },
        deleteHelper(id, title, index) {
            this.delete.title = title;
            this.delete.id = id;
            this.block_index = index;
        },
        assignmentHelper(id){
            this.add_assignment.block_id = id;
        },
        materialHelper(id){
            this.add_material.block_id = id;
        },
        editBlock() {
            axios.post('/api/edit-course-block', {title: this.edit.title, block_id: this.edit.id})
                .then(response => {
                    this.renderPage();
                    $("#edit_close").click();
                    this.$toast.open({
                        message: response.data.title + ' block was edited',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not edit this block',
                        type: 'error',
                        position: 'top-right'
                    });
                });

        },
        deleteBlock() {
            axios.post('/api/delete-course-block', {block_id: this.delete.id})
                .then(response => {
                    this.renderPage();
                    $("#delete_close").click();
                    this.$toast.open({
                        message: 'Block successfully deleted',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not delete this block',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        handleFileAssignment() {
            this.add_assignment.file = this.$refs.assignment.files[0];
        },
        handleFileMaterial() {
            this.add_material.file = this.$refs.material.files[0];
        },
        addAssignment() {
            let formData = new FormData();
            formData.append('file', this.add_assignment.file);
            for (const [key, value] of Object.entries(this.add_assignment)) {
                formData.append(key, value);
            }
            axios.post('/api/add-assignment', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    $("#assignment_close").click();
                    this.$toast.open({
                        message: 'You successfully added new assignment!',
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
        addMaterial() {
            let formData = new FormData();
            formData.append('file', this.add_material.file);
            for (const [key, value] of Object.entries(this.add_material)) {
                formData.append(key, value);
            }
            axios.post('/api/add-material', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    $("#material_close").click();
                    this.$toast.open({
                        message: 'You successfully added new material!',
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
    }
}
</script>

<style scoped>
ul{
    padding-left: 10px;
    list-style: none;
}
</style>

