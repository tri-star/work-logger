<template>
    <div>
        <table class="small-table project-list">
            <tr v-for="project in projects" :key="project.project_id">
                <td>
                    <router-link :to="`/project/${project.project_id}`">{{
                        project.project_name
                    }}</router-link>
                </td>
                <td class="col-number">
                    {{ project.count }}
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

<style lang="scss" scoped></style>
