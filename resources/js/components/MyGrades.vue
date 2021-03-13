<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Grades</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-12" v-if="user_courses">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">My Grades:</h5>
                    <div class="accordion" id="accordionExample">
                        <div class="card" v-for="(course, index) in user_courses">
                            <div class="card-header" :id="'heading' + index" data-toggle="collapse"
                                 :data-target="'#collapse' + index" aria-expanded="true" aria-controls="collapseOne">
                                {{ course.title }} - {{ course.abbreviation }}
                                <i class="material-icons" id="down-i">keyboard_arrow_down</i>
                            </div>
                            <div :id="'collapse' + index" class="collapse" :aria-labelledby="'heading' + index"
                                 data-parent="#accordionExample">
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Assignment</th>
                                            <th scope="col">Grade</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(assignment, i) in course.assignments" :key="i">
                                            <td>{{ assignment.title }}</td>
                                            <td>{{ parseGrade(assignment.id) ? parseGrade(assignment.id).grade : 'Not graded' }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
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
            user_courses: '',
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            axios.get('/api/get-grade/' + this.user.id)
                .then(response => {
                    this.user_courses = response.data;
                }).catch(e => {
                this.errors.push(e);
            });
        },
        parseGrade(assignment_id) {
            let grade = this.user.grades.find(item => item.assignment_id == assignment_id);
            if (grade) {
                return grade;
            } else {
                return false;
            }
        }


    }
}
</script>

<style scoped>
.accordion .card .card-header {
    background: white;
    text-transform: none;
    font-size: 14px;
}

.dark-theme .accordion .card .card-header {
    background: #323742;
}

#down-i {
    float: right;
}
</style>
