<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><router-link to="/">Dashboard</router-link></li>
                    <li class="breadcrumb-item active" aria-current="page" v-if="course">{{ this.course.title }}</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-8">
                <CourseBlock :user="user" :course="course.id" v-if="course"></CourseBlock>
            </div>
            <div class="col-4"></div>
        </div>
    </div>
</template>

<script>
import CourseBlock from './Blocks';

export default {
    props: ['user'],
    data () {
        return {
            course: null,
            errors: []
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
                }).catch(e => {
                this.errors.push(e)
            });
        }
    }
}
</script>
