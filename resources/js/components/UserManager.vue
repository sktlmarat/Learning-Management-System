<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">User Manager</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">All Users:</h5>
                    <button class="btn btn-primary" data-toggle="modal"
                            data-target="#AddUserModal">Add New User
                    </button>
                    <div class="modal fade" id="AddUserModal" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i class="material-icons">close</i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>First Name</label>
                                            <input v-model="new_user.first_name" type="text" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Last Name</label>
                                            <input v-model="new_user.last_name" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input v-model="new_user.email" type="email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Password (randomly generated)</label>
                                        <input v-model="new_user.password" type="password" class="form-control">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Year of study</label>
                                            <select v-model="new_user.year_of_study" class="form-control custom-select">
                                                <option :value="reg.year" v-for="reg in registrations">
                                                    {{ reg.year }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Department</label>
                                            <select v-model="new_user.department" class="form-control custom-select">
                                                <option :value="department.id" v-for="department in departments">
                                                    {{ department.name }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Role</label>
                                            <select v-model="new_user.role" class="form-control custom-select">
                                                <option selected>student</option>
                                                <option>instructor</option>
                                                <option>admin</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>Choose instructor</label>
                                            <select v-model="new_user.adviser" class="form-control">
                                                <option v-for="instructor in instructors" :value="instructor.id">
                                                    {{ instructor.name }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-12">
                                            <label>Select Image</label>
                                            <input ref="avatar" type="file" class="form-control" @change="handleAvatar">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="mod_close" type="button" class="btn btn-secondary" data-dismiss="modal">
                                        Close
                                    </button>
                                    <button @click="add_user" type="button" class="btn btn-primary">Save changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="EditModal" tabindex="-1" role="dialog"
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
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>First Name</label>
                                            <input v-model="edit_user.first_name" type="text" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Last Name</label>
                                            <input v-model="edit_user.last_name" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input v-model="edit_user.email" type="email" class="form-control">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Department</label>
                                            <select v-model="edit_user.department" class="form-control custom-select">
                                                <option :value="department.id" v-for="department in departments">
                                                    {{ department.name }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Role</label>
                                            <select v-model="edit_user.role" class="form-control custom-select">
                                                <option>student</option>
                                                <option>instructor</option>
                                                <option>admin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="edit_close" type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close
                                    </button>
                                    <button type="button" @click="editUser()" class="btn btn-primary">Save changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog"
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
                                    <p>You want to delete {{ this.delete_user.fullName }} ?</p>
                                </div>
                                <div class="modal-footer">
                                    <button id="delete_close" type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close
                                    </button>
                                    <button type="button" @click="deleteUser()" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-0 my-4">
                        <input v-model="search" class="form-control" type="text" placeholder="Search user">
                    </div>
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Department</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(usr, i) in filteredUsers" :key="i">
                            <th scope="row">{{ usr.name }}</th>
                            <td>{{ usr.email }}</td>
                            <td>{{ usr.role }}</td>
                            <td>{{ usr.department.name }}</td>
                            <td>
                                <button data-toggle="modal"
                                        @click="editHelper(usr.id, usr.name, usr.department, usr.role, usr.email)"
                                        data-target="#EditModal" class="btn btn-warning btn-sm">Edit
                                </button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#DeleteModal" @click="deleteHelper(usr.id, usr.name)">Delete
                                </button>
                            </td>
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
            users: null,
            instructors: [],
            registrations: null,
            errors: [],
            search: '',
            new_user: {
                first_name: '',
                last_name: '',
                password: Math.random().toString(36).slice(-8),
                department: '',
                role: '',
                email: '@nu.edu.kz',
                avatar: '',
                year_of_study: '',
                adviser: '',
            },
            edit_user: {
                id: '',
                first_name: '',
                last_name: '',
                department: '',
                role: '',
                email: ''
            },
            delete_user: {
                id: '',
                fullName: ''
            },
            departments: null
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            axios.get('/api/all-users/')
                .then(response => {
                    this.users = response.data;
                }).catch(e => {
                this.errors.push(e)
            });
            axios.get('/api/all-departments/')
                .then(response => {
                    this.departments = response.data;
                }).catch(e => {
                this.errors.push(e)
            });
            axios.get('/api/all-registrations/')
                .then(response => {
                    this.registrations = response.data;
                }).catch(e => {
                this.errors.push(e)
            });
            axios.get('/api/instructors')
                .then(response => {
                    this.instructors = response.data;
                }).catch(e => {
                this.errors.push(e);
                this.$toast.open({
                    message: 'Error occurred during instructor fetch',
                    type: 'error',
                    position: 'top-right'
                });
            });
        },
        add_user() {
            let formData = new FormData();
            formData.append('file', this.new_user.avatar);
            for (const [key, value] of Object.entries(this.new_user)) {
                formData.append(key, value);
            }
            console.log(formData);
            axios.post('/api/add-user/', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    $("#mod_close").click();
                    this.renderPage();
                    this.$toast.open({
                        message: 'New user was added',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.errors.push(e);
                    this.$toast.open({
                        message: 'Can not add this user',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        handleAvatar() {
            this.new_user.file = this.$refs.avatar.files[0];
        },
        editHelper(id, name, department, role, email) {
            this.edit_user.id = id;
            this.edit_user.first_name = name.split(" ")[0];
            this.edit_user.last_name = name.split(" ")[1];
            this.edit_user.department = department.id;
            this.edit_user.role = role;
            this.edit_user.email = email;
        },
        deleteHelper(id, name) {
            this.delete_user.fullName = name;
            this.delete_user.id = id;
        },
        editUser() {
            axios.post('/api/edit-user', {
                name: this.edit_user.first_name + ' ' + this.edit_user.last_name,
                user_id: this.edit_user.id,
                email: this.edit_user.email,
                department: this.edit_user.department,
                role: this.edit_user.role
            })
                .then(response => {
                    this.renderPage();
                    $("#edit_close").click();
                    this.$toast.open({
                        message: response.data.name + ' was edited',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not edit this user',
                        type: 'error',
                        position: 'top-right'
                    });
                });

        },
        deleteUser() {
            axios.post('/api/delete-user', {user_id: this.delete_user.id})
                .then(response => {
                    this.renderPage();
                    $("#delete_close").click();
                    this.$toast.open({
                        message: this.delete_user.fullName + ' was successfully deleted',
                        type: 'success',
                        position: 'top-right'
                    });
                })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not delete this user',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        }
    },
    computed: {
        filteredUsers() {
            if (this.users) {
                return this.users.filter(u => {
                    const res = u.name + u.email + u.department.name + u.role;
                    return res.toLowerCase().includes(this.search.toLowerCase());
                });
            } else {
                return this.users;
            }
        },
    }
}
</script>
