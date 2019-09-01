<template>
    <WlModal :showModal="showModal">
        <template v-slot:body>
            <ProjectForm
                ref="projectForm"
                @close="handleClose"
                @save="handleSave"
            />
        </template>
    </WlModal>
</template>

<script>
import AdapterFactory from "../../adapters/adapter-factory"
import ProjectForm from "./project-form"
import WlModal from "../../components/wl-modal"

export default {
    components: {
        ProjectForm,
        WlModal
    },

    data() {
        return {
            showModal: false,
            id: 0,
            project: {}
        }
    },

    methods: {
        open(id) {
            if (id) {
                //編集
            } else {
                this.id = 0
                this.project = {
                    id: 0,
                    project_name: "",
                    description: ""
                }
            }
            this.showModal = true
            this.$nextTick(() => {
                this.$refs.projectForm.init(this.id, this.project)
            })
        },

        async handleSave(project) {
            if (project.id) {
                //編集
            } else {
                await this.addProject(project)
            }
            this.showModal = false
            this.$emit("projectSaved")
        },

        handleClose() {
            this.showModal = false
        },

        async addProject(project) {
            const projectAdapter = AdapterFactory.get("ProjectAdapter")
            await projectAdapter.addProject(project)
        }
    }
}
</script>

<style lang="scss" scoped></style>
