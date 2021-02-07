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
                                        <input v-model="newEmail" type="email" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Password (randomly generated)</label>
                                        <input v-model="new_user.password" type="text" class="form-control">
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
                    <div class="col-md-6 p-0 my-4">
                        <input v-model="search" class="form-control" type="text" placeholder="Search user">
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Department</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="usr in filteredUsers">
                            <th scope="row">{{ usr.name }}</th>
                            <td>{{ usr.email }}</td>
                            <td>{{ usr.role }}</td>
                            <td>{{ usr.department.name }}</td>
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
            errors: [],
            search: '',
            new_user: {
                first_name: '',
                last_name: '',
                password: Math.random().toString(36).slice(-8),
                department: '',
                role: ''
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
        },
        add_user() {
            axios.post('/api/add-user/', {
                name: this.new_user.first_name + ' ' + this.new_user.last_name,
                email: this.newEmail,
                password: this.new_user.password,
                department: this.new_user.department,
                role: this.new_user.role
            })
                .then(response => {
                    this.users.push(response.data);
                    this.userInitial();
                    $("#mod_close").click();
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
        userInitial() {
            this.new_user.first_name = '';
            this.new_user.last_name = '';
            this.new_user.password = Math.random().toString(36).slice(-8);
            this.new_user.department = '';
            this.new_user.role = '';
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
        newEmail: {
            get() {
                return this.new_user.first_name.toLowerCase() + '.' + this.new_user.last_name.toLowerCase() + '@nu.edu.kz';
            },
            set(newValue) {
                const res = newValue.split("@")[0];
                this.new_user.first_name = res.split(".")[0];
                this.new_user.last_name = res.split(".")[1];
            }
        },
    }
}
</script>
