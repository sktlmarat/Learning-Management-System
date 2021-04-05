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
                    <li class="breadcrumb-item active" aria-current="page" v-if="session">{{ this.classroom.date }}</li>
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
                    <tr v-for="(student,key) in this.save_attendance">
                        <input type="hidden" v-model="save_attendance[key]['student_id']">
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
                            <input type="text" v-model="save_attendance[key]['feedback']">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="blocks">
                    <button class="btn btn-primary my-4" @click="updateAttendance()">Update Attendance</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import router from "../router";

export default {
    name: "Attendance",
    props: ['classroom','session'],
    data() {
        return {
            students: [],
            attendances: [],
            add_attendance: {
                student_id: [],
                attendance_type_id: [],
                description: []
            },
            edit_attendance:[],
            save_attendance: [],
            errors:[]
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
                }).catch(e => {
                this.errors.push(e)
            });
            //get students' attendance
            axios.get('/api/attendance/'+this.session.id+'/edit?date='+this.classroom.original_date)
                .then(response => {
                    this.save_attendance = response.data;
                }).catch(e => {
                this.errors.push(e)
            });
        },
        updateAttendance(){
            axios.post('/api/update/attendance', {
                attendances : this.save_attendance,
                date : this.classroom.original_date,
                session_id : this.session.id
            }).then(response => {
                this.$toast.open({
                    message: 'You successfully updated the attendance!',
                    type: 'success',
                    position: 'top-right'
                });
                router.push({path:'/session/'+this.session.id});
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
