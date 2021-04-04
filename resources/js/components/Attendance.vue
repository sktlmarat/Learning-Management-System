<template>
    <div class="page-content">
        <div class="page-info">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link :to="'/course/'+this.session.course_id">{{ session.course.title }}</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link :to="'/sessions/'+this.session.course_id">Session</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link :to="'/session/'+this.session.id">{{ this.session.description }}</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" v-if="session">{{ this.class.date }}</li>
                </ol>
            </nav>
        </div>
        <div class="row container">
            <div class="col-12">
                <table id="table_id" class="display table">
                    <thead>
                    <tr>
                        <th scope="col">Student Name</th>
                        <th scope="col">Attendance Type</th>
                        <th scope="col">Comment</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(student,key) in students">
                        <!--                        <input type="hidden" v-model="add_attendance.student_id[key] = student.id">-->
                        <input type="hidden" v-model="save_attendance[key]['student_id'] = student.id">
                        <th scope="row">{{ student.name }}</th>
                        <td>
                            <!--                            <input type="radio" :id="'present'.key" v-model="add_attendance.attendance_type_id[key]" value="1">-->
                            <input type="radio" :id="'present'.key" v-model="save_attendance[key]['attendance_type_id']" value="1">
                            <label :for="'present'.key">Present</label>
                            <!--                            <input type="radio" :id="'late'.key" v-model="add_attendance.attendance_type_id[key]" value="2">-->
                            <input type="radio" :id="'late'.key" v-model="save_attendance[key]['attendance_type_id']" value="2">
                            <label :for="'late'.key">Late</label>
                            <!--                            <input type="radio" :id="'absent'.key" v-model="add_attendance.attendance_type_id[key]" value="3">-->
                            <input type="radio" :id="'absent'.key" v-model="save_attendance[key]['attendance_type_id']" value="3">
                            <label :for="'absent'.key">Absent</label>
                        </td>
                        <td>
                            <!--                            <input type="text" v-model="add_attendance.description[key]" value="">-->
                            <input type="text" v-model="save_attendance[key]['feedback']" value="">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="blocks">
                    <button class="btn btn-primary my-4" @click="saveAttendance()">Save Attendance</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Attendance",
    props: ['class','session'],
    data() {
        return {
            students: [],
            add_attendance: {
                student_id: [],
                attendance_type_id: [],
                description: []
            },
            save_attendance: []
        }
    },
    mounted() {
        this.renderPage();
    },
    methods: {
        renderPage() {
            //get students
            axios.get('/api/course/'+this.session.course_id+'/students')
                .then(response => {
                    this.students = response.data;
                    for(let i=0;i<this.students.length;i++)
                        this.save_attendance.push({
                            student_id: null,
                            feedback: '',
                            attendance_type_id: null,
                            session_id: this.session.id,
                            original_date: this.class.original_date,
                        });
                }).catch(e => {
                this.errors.push(e)
            });
        },
        saveAttendance(){
            let formData = new FormData();
            formData.append('attendance',this.save_attendance)
            axios.post('/api/attendance', this.save_attendance)
                .then(response => {
                    this.$toast.open({
                        message: 'You successfully taken attendance for the date',
                        type: 'success',
                        position: 'top-right'
                    });
                    router.go('/session/'+this.session.id);
                }).catch(e => {
                this.errors.push(e);
                this.$toast.open({
                    message: 'Error occurred during submission',
                    type: 'error',
                    position: 'top-right'
                });
            });
        }
    }
}
</script>

<style scoped>

</style>
