<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row" v-for="chunk in courseChunks">
                    <div class="col-md-6" v-for="course in chunk">
                        <router-link :to=" '/course/' + course.id ">
                            <div class="card text-center">
                                <div class="card-body">
                                    <img class="card-img mb-4" :src="'/storage/' + course.thumbnail" alt="">
                                    <h5 class="card-title">{{ course.title }}</h5>
                                    <button class="btn btn-info">Go Inside</button>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">About</h5>
                        <p>Quisque vel tellus sit amet quam efficitur sagittis. Fusce aliquam pulvinar suscipit.</p>
                        <ul class="list-unstyled profile-about-list">
                            <li><i class="material-icons">school</i><span>Studied Mechanical Engineering at <a href="#">Carnegie Mellon University</a></span>
                            </li>
                            <li><i class="material-icons">work</i><span>Former manager at <a href="#">Stacks</a></span>
                            </li>
                            <li><i class="material-icons">my_location</i><span>From <a
                                href="#">Boston, Massachusetts</a></span></li>
                            <li><i class="material-icons">rss_feed</i><span>Followed by 716 people</span></li>
                            <li>
                                <button class="btn btn-block btn-primary m-t-lg">Follow</button>
                                <button class="btn btn-block btn-secondary m-t-lg">Message</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Contact Info</h5>
                        <ul class="list-unstyled profile-about-list">
                            <li><i class="material-icons">contact_page</i><span>{{ user.role.toUpperCase() }} - {{ user.name }}</span></li>
                            <li><i class="material-icons">mail_outline</i><span>{{ user.email }}</span></li>
                            <li><i class="material-icons">account_balance</i><span>Department of {{ user.department.name }}</span></li>
                        </ul>
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
            message: 'Msg'
        }
    },
    computed: {
        courseChunks() {
            return _.chunk(Object.values(this.user.courses), 2)
        }
    }
}
</script>

<style scoped>
.card-img {
    border-radius: 50%;
    width: 140px;
    height: 140px;
    object-fit: cover;
}
</style>
