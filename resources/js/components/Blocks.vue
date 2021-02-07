<template>
    <div class="blocks">
        <button v-if="user.role == 'instructor'" data-toggle="modal" data-target="#AddModal"
                class="btn btn-primary my-4">Add new block
        </button>
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
        <div class="card" v-for="(block, i) in blocks" :key="i">
            <div class="card-body">
                <h3>{{ block.title }}</h3>
                <ul>
                    <li v-for="assignment in block.assignments">
                        {{ assignment.title }}
                    </li>
                    <li v-for="material in block.materials">
                        {{ material.title }}
                    </li>
                </ul>
                <button v-if="user.role == 'instructor'" @click="editHelper(block.id, block.title, i)"
                        data-toggle="modal"
                        data-target="#EditModal" class="btn btn-warning btn-sm mr-2">Edit block</button>
                <button v-if="user.role == 'instructor'" data-toggle="modal"
                        data-target="#DeleteModal" class="btn btn-danger btn-sm" @click="deleteHelper(block.id, block.title, i);">Delete block</button>
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
            }
        }
    },
    mounted() {
        axios.get('/api/course/block/' + this.course)
            .then(response => {
                this.blocks = response.data;
            }).catch(e => {
            this.errors.push(e);
        });

    },
    methods: {
        addBlock() {
            axios.post('/api/course-block', {title: this.add_title, course_id: this.course})
                .then(response => {
                    this.blocks.push(response.data);
                    this.add_title = '';
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
        editBlock() {
            axios.post('/api/edit-course-block', {title: this.edit.title, block_id: this.edit.id})
                .then(response => {
                    this.blocks[this.block_index].title = this.edit.title;
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
                    this.blocks.splice(this.block_index, 1);
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
        }
    }
}
</script>

