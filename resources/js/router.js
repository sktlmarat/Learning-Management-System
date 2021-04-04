import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from "./components/Dashboard";
import Course from "./components/Course";
import CourseRegistration from "./components/CourseRegistration";
import UserManager from "./components/UserManager";
import CourseManager from "./components/CourseManager";
import Assignment from "./components/Assignment";
import MyRequests from "./components/MyRequests";
import GradeAssignment from "./components/GradeAssignment";
import MyGrades from "./components/MyGrades";

import Classes from "./components/Classes";
import Sessions from "./components/Sessions";
import Attendance from "./components/Attendance";
import Calendar from "./components/Calendar";

Vue.use(VueRouter);


export default new VueRouter({
    routes: [
        {
            path: '/',
            component: Dashboard
        },
        {
            path: '/course/:id',
            component: Course
        },
        {
            path: '/course-registration',
            component: CourseRegistration
        },
        {
            path: '/admin/user-manager',
            component: UserManager
        },
        {
            path: '/admin/course-manager',
            component: CourseManager
        },
        {
            path: '/course/:course_id/assignment/:assignment_id',
            component: Assignment
        },
        {
            path: '/my-requests',
            component: MyRequests
        },
        {
            path: '/course/:course_id/assignment/:assignment_id/grade',
            component: GradeAssignment
        },
        {
            path: '/my-grades',
            component: MyGrades
        },
        {
            path: '/sessions/:course_id',
            component: Sessions
        },
        {
            path: '/session/:sessionId',
            component: Classes
        },
        {
            path: '/attendance',
            name: 'attendance',
            component: Attendance,
            props: true
        },
        {
            path: '/calendar',
            name: 'calendar',
            component: Calendar
        },
    ],
    mode: 'history',
    linkActiveClass: "",
    linkExactActiveClass: "active-page"
})
