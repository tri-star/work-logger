<template>
    <div>
        <h1 class="heading-1">{{ project.project_name }}</h1>
    </div>
</template>

<script>
import adapterFactory from "../../adapters/adapter-factory"

export default {
    props: {
        id: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            project: {}
        }
    },

    mounted() {
        const projectAdapter = adapterFactory.get("ProjectAdapter")
        projectAdapter.getProject(this.id).then(project => {
            this.project = project
            console.log(this.project)
        })
    }
}
</script>

<style lang="scss" scoped></style>
