<template>
    <div class="page-sidebar">
        <div class="logo-box"><a href="#" class="logo-text">Senior - LMS</a><a href="#" id="sidebar-close"><i
            class="material-icons">close</i></a> <a href="#" id="sidebar-state"><i class="material-icons">adjust</i><i
            class="material-icons compact-sidebar-icon">panorama_fish_eye</i></a></div>
        <div class="page-sidebar-inner slimscroll">
            <ul class="accordion-menu">
                <li class="sidebar-title">
                    Apps
                </li>
                <router-link tag="li" to="/"><a><i class="material-icons-outlined">dashboard</i>Dashboard</a>
                </router-link>
                <router-link v-if="user.role == 'student'" tag="li" to="/course-registration"><a><i class="material-icons-outlined">library_add</i>Course Registration</a>
                </router-link>
                <router-link v-if="user.role == 'admin'" tag="li" to="/admin/user-manager"><a><i class="material-icons-outlined">supervisor_account</i>User Manager</a>
                </router-link>
                <router-link v-if="user.role == 'admin'" tag="li" to="/admin/course-manager"><a><i class="material-icons-outlined">class</i>Course Manager</a>
                </router-link>
                <li v-if="user.role == 'student' || user.role == 'instructor'">
                    <a href="#"><i class="material-icons">menu_book</i>My Courses<i
                        class="material-icons has-sub-menu">add</i></a>
                    <ul class="sub-menu">
                        <li v-for="course in user.courses">
                            <router-link :to=" '/course/' + course.id ">{{ course.title }}</router-link>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    name: "Navbar"
}
</script>

