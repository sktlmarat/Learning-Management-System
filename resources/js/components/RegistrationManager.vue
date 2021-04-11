<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Registration Manager</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manage Course Registration:</h5>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Year of study</th>
                            <th scope="col">Status</th>
                            <th scope="col">Open</th>
                            <th scope="col">Close</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(reg, i) in registrations" :key="i">
                            <th scope="row" class="text-uppercase">{{ reg.year }}</th>
                            <td><p
                                :class="(reg.status == 'open') ? 'text-success text-uppercase' : 'text-danger text-uppercase'">
                                {{ reg.status }}</p></td>
                            <td>
                                <button v-if="reg.status == 'closed'" class="btn btn-primary"
                                        @click="openRegistration(reg.id)">Open
                                </button>
                            </td>
                            <td>
                                <button v-if="reg.status == 'open'" class="btn btn-danger"
                                        @click="closeRegistration(reg.id)">Close
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
            registrations: []
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            axios.get('/api/all-registrations')
                .then(response => {
                    this.registrations = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
        },
        openRegistration(id) {
            axios.post('/api/change-registration-status', {
                id: id,
                status: 'open'
            }).then(response => {
                this.renderPage();
                this.$toast.open({
                    message: 'Registration is open for ' + response.data.year + " year",
                    type: 'success',
                    position: 'top-right'
                });
            })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not open the registration',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        },
        closeRegistration(id) {
            axios.post('/api/change-registration-status', {
                id: id,
                status: 'closed'
            }).then(response => {
                this.renderPage();
                this.$toast.open({
                    message: 'Registration is closed for ' + response.data.year + " year",
                    type: 'success',
                    position: 'top-right'
                });
            })
                .catch(e => {
                    this.$toast.open({
                        message: 'Can not close the registration',
                        type: 'error',
                        position: 'top-right'
                    });
                });
        }
    },
}
</script>
