<template>
    <div>
        <a class="command-button" @click="$emit('openNewProjectForm')">
            <i class="icon fas fa-plus-circle"></i>新規作成
        </a>
        <table class="small-table project-list">
            <tr v-for="project in projects" :key="project.id">
                <td>
                    <router-link :to="`/project/${project.id}`">{{
                        project.project_name
                    }}</router-link>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        projects: {
            type: Object,
            required: true
        }
    }
}
</script>

<style></style>
