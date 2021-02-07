<template>
            <div class="page-content">
                <div class="page-info">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-4" v-for="course in user.courses">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">{{ course.title }}</h5>
                            <router-link :to=" '/course/' + course.id "><button class="btn btn-info">Go Inside</button></router-link>
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
    }
}
</script>
