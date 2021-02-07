<template>
        <router-view @updateStudent="student_register($event)" :key="$route.fullPath" :user="user"></router-view>
</template>

<script>
export default {
    name: "App",
    props: ['user'],
    methods: {
        student_register(updated) {
            this.user = updated;
        }
    }
}
</script>
