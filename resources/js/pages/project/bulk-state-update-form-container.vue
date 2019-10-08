<template>
    <WlModal :showModal="showModal">
        <template v-slot:body>
            <section>
                <div class="form">
                    <div class="row">
                        <div class="col-header">状態</div>
                        <div class="col">
                            <WlDropDown
                                :menues="statusList"
                                name="state"
                                v-model="state"
                            />
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="form">
                    <div class="row">
                        <div class="">
                            <button class="button" @click="handleSubmit">
                                更新
                            </button>
                            <button class="button" @click="handleClose">
                                キャンセル
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </WlModal>
</template>

<script>
import Task from "../../domain/task"
import WlDropDown from "../../components/form/wl-dropdown"
import WlModal from "../../components/wl-modal"
import adapterFactory from "../../adapters/adapter-factory"

export default {
    props: {},
    components: {
        WlDropDown,
        WlModal
    },
    data() {
        return {
            showModal: false,
            taskIds: [],
            state: Task.STATE_NONE,
            statusList: Task.getStatusNames()
        }
    },

    methods: {
        open(ids) {
            this.showModal = true
            this.taskIds = ids
        },

        handleSubmit() {
            this.showModal = false

            const TaskAdapter = adapterFactory.get("TaskAdapter")
            TaskAdapter.bulkStateUpdate(this.taskIds, this.state)
        },
        handleClose() {
            this.showModal = false
        }
    },

    mounted() {}
}
</script>

<style lang="scss" scoped>
@import "../../../sass/imports";

section {
    display: block;
    margin-bottom: 20px;
}

label {
    display: block;
    background-color: $light-primary-color;
    padding: 5px;
}

.form {
    width: 300px;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;

    .row {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        padding: 10px;

        .col-header {
            width: 80px;
        }
        .col {
            width: 200px;
        }
    }
    .button {
        margin-right: 20px;
    }

    .button-area {
        margin-top: 10px;
        justify-self: center;
    }
}

.errors {
    margin-top: 5px;
    background-color: rgba(255, 100, 100, 0.2);
    list-style: none;
    padding: 0;
    li {
        padding: 5px 10px;
        color: #e00;
    }
}
</style>
